<?php
namespace PandaGroup\Westfield\Model;

use PandaGroup\Westfield\Logger\Logger;
use SimpleXMLElement;

class Api extends \PandaGroup\Westfield\Model\Api\AbstractApi
{
    protected $westfieldCategories = [];

    protected $brand = 'Peter Jackson';
    protected $countryOfOrigin = 'Australia';
    protected $currency = 'AUD';

    protected $_stockItemRepository;

    public function sendCatalog() {
        // todo: ogarnąć wysyłąnie tego katalogu
        if (!$this->isEnabled()) {
            return false;
        }

        try {
            $this->getLog()->logToFile('Start generating XML file...', Logger::INFO);
            if ($this->createFullXml()) {
                $this->getLog()->logToFile('File generated', Logger::INFO);

                $xmlFile = fopen($this->getWestfieldFile('full'), "r");
                $xmlData = fread($xmlFile, filesize($this->getWestfieldFile('full')));

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_ENCODING ,"");
                //curl_setopt($curl, CURLOPT_UPLOAD, 1);
                //curl_setopt($curl, CURLOPT_INFILE, $xmlFile);
                //curl_setopt($curl, CURLOPT_INFILESIZE, filesize($xmlFile));
                curl_setopt($curl, CURLOPT_URL, $this->getGatewayUrlFull());
                curl_setopt($curl, CURLOPT_USERPWD, "{$this->getUsername()}:{$this->getPassword()}");
                //curl_setopt($curl, CURLOPT_PUT, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                curl_setopt($curl, CURLOPT_MAXREDIRS, 30);
                curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlData);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $this->getLog()->logToFile('Start connection to url: ' . $this->getGatewayUrlFull(), Logger::INFO);

                $result = curl_exec($curl);
                $error = curl_error($curl);
                $info = curl_getInfo($curl);

                $this->getLog()->logToFile($result, Logger::INFO);

                $response = $this->parseResponse($result);
                $this->getStatus()->insertResponseDataFromXml($response, $this->isTestMode());
                $this->getLog()->logToFile('Catalog has been sent successfully', Logger::INFO);

                return true;
            }
        } catch (\Exception $e) {
            $this->getLog()->logToFile($e->getMessage(), Logger::ERROR);
        }
        return false;
    }

    public function createFullXml($type = 'full') {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><product_update_job/>');

        $xml->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('xmlns:xsi:noNamespaceSchemaLocation', 'product-request-schema.xsd');
        $xml->addAttribute('xmlns:xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');

        $xml->addChild('source_platform', 'Retailer-Integration-Platfrom_v1.0');
        $this->addProductToXml($xml);

        return $xml->saveXML($this->getWestfieldFile($type));
    }

    protected function addProductToXml(SimpleXMLElement $xml) {
        $xmlProducts = $xml->addChild('products');
        /** @var \PandaGroup\Westfield\Model\Catalog\Product $catalogProductModel */
        $catalogProductModel = $this->objectManager->create('PandaGroup\Westfield\Model\Catalog\Product');
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $catalogProductModel->getAssignedProductsToCategories();

        $productRepository = $this->objectManager->create('Magento\Catalog\Model\ProductRepository');

        foreach ($productCollection as $i => $product) {
            $xmlProduct = $xmlProducts->addChild('product');

            $product = $productRepository->get($product->getSku());

            if ($this->getProductHelper()->isNew($product)) {
                $xmlProduct->addAttribute('new_product', 'true');
            }

            if ($this->getProductHelper()->isSale($product)) {
                $xmlProduct->addAttribute('sale', 'true');
            }

            // fix nbsp not defined
            $encodeSku = htmlspecialchars($product->getSku(), ENT_QUOTES);
            $xmlProduct->addChild('sku', $encodeSku);
            $xmlProduct->addChild('brand', $this->getBrand());
            //Ampersand fix
            $productName = $product->getName();
            $encodeProductName = htmlspecialchars($productName, ENT_QUOTES);

            $xmlProduct->addChild('product_name', $encodeProductName);
            $xmlProduct->addChild('short_description', htmlspecialchars(strip_tags($product->getShortDescription())));
            $productDescription = $product->getDescription();
            if (empty($productDescription)) {
                $productDescription = htmlspecialchars(strip_tags($product->getShortDescription()));
            }

            /** Fix Ampersand **/
            $productDescription = htmlspecialchars($productDescription, ENT_QUOTES);

            $xmlProduct->addChild('detailed_description', $productDescription);
            $this->addCategoriesToXml($xmlProduct, $product);

            $xmlProduct->addChild('country_of_origin', $this->getCountryOfOrigin());

            $this->addProductDetailsToXml($xmlProduct, $product);
        }
    }

    /**
     * @param SimpleXMLElement $xmlProduct
     * @param \PandaGroup\Westfield\Model\Catalog\Product $product
     */
    protected function addCategoriesToXml(SimpleXMLElement $xmlProduct, $product) {

        /** Fix to get category from ProductRepository **/
        $productRepository = $this->objectManager->create('Magento\Catalog\Model\ProductRepository');
        $product = $productRepository->get($product->getData('sku'));
        /** Fix to get category from ProductRepository **/

        $westfieldCategories = $this->getProductWestfieldCategories($product);

        $xmlCategories = $xmlProduct->addChild('categories');

        $count = 0;

        if (empty($westfieldCategories)) {
            $this->getLog()->logToFile('Empty categories for product ID: ' . $product->getId(), Logger::INFO);
        }

        foreach ($westfieldCategories as $westfieldCategory) {

            /** Fix **/
            if (false === empty($westfieldCategory)) {
            /** Fix **/
                $xmlCategory = $xmlCategories->addChild('category', $westfieldCategory);
                if (0 === $count) {
                    $xmlCategory->addAttribute('primary', "true");
                }

                $count++;
            }

        }
    }

    /**
     * @param \PandaGroup\Westfield\Model\Catalog\Product $product
     * @return array
     */
    public function getProductWestfieldCategories($product) {
        $productCategories = [];
        if ($product instanceof \Magento\Catalog\Model\Product) {

            /** @var \PandaGroup\Westfield\Model\Catalog\Category $productCategoriesModel */
            $productCategoriesModel = $this->objectManager->get('PandaGroup\Westfield\Model\Catalog\Category');
            $productCategories = $productCategoriesModel->getWestfieldCategoriesFromCategoryByIds($product->getCategoryIds());
        }

        return $productCategories;
    }

    protected function addProductDetailsToXml(SimpleXMLElement $xmlProduct, $product) {
        $xmlDetails = $xmlProduct->addChild('details');
        $productsToXml = [];

        switch ($product->getTypeId()) {
            case \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE:
                $productsToXml = [$product];
                break;

            case \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE:

                /** Fix bad condition **/
                if (null !== $product ) {

                    /** Fix lack of details **/
                    $productRepository = $this->objectManager->create('Magento\Catalog\Model\ProductRepository');
                    $confProduct = $productRepository->get($product->getData('sku'));
                    $productsToXml = $confProduct->getTypeInstance()->getUsedProducts($confProduct);

//                    /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProducts */
//                    $configurableProducts = $this->objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
//                    $productsToXml = $configurableProducts->getUsedProducts($product);
                    /** Fix lack of details **/
                }
                break;
        }
        $this->addProductDetailToXml($xmlDetails, $productsToXml, $product);

    }

    /**
     * @param SimpleXMLElement $xmlDetails
     * @param $products
     * @param \Magento\Catalog\Model\Product $baseProduct
     */
    protected function addProductDetailToXml(SimpleXMLElement $xmlDetails, $products, $baseProduct) {
        $counter = 0;
        $westfieldColor = $this->objectManager->get('PandaGroup\Westfield\Model\Color');
        $westfieldCatlogProductAttribute = $this->objectManager->get('PandaGroup\Westfield\Model\Catalog\Product\Attribute');
        foreach ($products as $product) {
            $xmlDetail = $xmlDetails->addChild('detail');

            /** Fix Ampersand **/
            $productSku = htmlspecialchars($product->getSku(), ENT_QUOTES);

            $xmlDetail->addChild('retailer_ref', $productSku);
            $xmlDetail->addChild('retailer_product_url', $baseProduct->getProductUrl(false));

            $xmlPrices = $xmlDetail->addChild('prices');
            $xmlPrice = $xmlPrices->addChild('price');
            $xmlPrice->addAttribute('currency', $this->getCurrency());
            $xmlPrice->addChild('amount', (int) ($baseProduct->getFinalPrice() * 100));
            $xmlPrice->addChild('tax', 0);
            $xmlPrice->addChild('rrp', (int) ($baseProduct->getPrice() * 100));

            //PJ-390 Hotfix: configurable product cause error
            //Old code:
            //$xmlDetail->addChild('quantity', (int)$product->getStockItem()->getQty());
            $prod = null;
            //todo: poprawić, żeby szarpał te produkty :/
            try{
                $prod = $this->getStockItem($product->getId());
            }catch (\Exception $e){
                $this->getLog()->logToFile($e->getMessage(), Logger::INFO);
            }

            $xmlDetail->addChild('quantity', (int)($prod ? $prod->getQty() : '0'));
            $xmlDetail->addChild('ean', '');

            $xmlAttributes = $xmlDetail->addChild('attributes');
            $color = $this->getColor($westfieldColor, $product);
            $xmlAttributes->addChild('colour', $color);
            $xmlAttributes->addChild('colour_description', $color);
            $size = $this->getSize($westfieldCatlogProductAttribute, $product);
            $xmlAttributes->addChild('size', $size);
            $xmlAttributes->addChild('size_description', $size);
            $xmlAttributes->addChild('construction', '');
            $xmlAttributes->addChild('material', '');
            $xmlAttributes->addChild('style', '');

            $this->addProductMedia($xmlDetail, $product, $baseProduct);

            $counter++;
        }
    }

    private function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }

    protected function addProductMedia(SimpleXMLElement $xmlDetail, $product, $baseProduct) {
        $xmlMedia = $xmlDetail->addChild('media');

        /** Fix image collection to get they from ProductRepository **/
        //$product->load('media_gallery');
        $productRepository = $this->objectManager->create('Magento\Catalog\Model\ProductRepository');
        /** @var \Magento\Catalog\Model\Product $repositoryProduct */
        $repositoryProduct = $productRepository->get($product->getData('sku'));

        //$images = $product->getMediaGalleryImages();
        $images = $repositoryProduct->getMediaGalleryImages();
        /** Fix image collection to get they from ProductRepository **/


        $imagesFromBaseProduct = false;

        if (0 === $images->count()) {

            /** Fix image collection to get they from ProductRepository **/
            //$baseProduct->load('media_gallery');
            //$images = $baseProduct->getMediaGalleryImages();
            $repositoryBaseProduct = $productRepository->get($baseProduct->getData('sku'));
            $images = $repositoryBaseProduct->getMediaGalleryImages();
            $imagesFromBaseProduct = true;
            /** Fix image collection to get they from ProductRepository **/
        }

        $imagesCounter = 0;
        foreach ($images as $image) {
            $imageUrl = $xmlMedia->addChild('image_url', $image->getUrl());
            if (0 === $imagesCounter) {
                $imageUrl->addAttribute('default', 'true');
            }

            if ($imagesFromBaseProduct) {
                break;
            }

            $imagesCounter++;
        }
    }

    public function parseResponse($response) {
        if (empty($response)) {
            return false;
        }

        if (strpos($response, '<?xml') === 0) {
            $xmlResponse = simplexml_load_string($response);

            if (is_object($xmlResponse)) {
                return $xmlResponse;
            } else {
                throw new \Exception('Cannot convert response to object', Logger::ERROR);
            }
        } else {
            $this->getLog()->logToFile((string)$response);
            throw new \Exception('Response is not saved as XML', Logger::ERROR);
        }
    }

    protected function getBrand() {
        return $this->brand;
    }

    protected function getCountryOfOrigin() {
        return $this->countryOfOrigin;
    }

    protected function getCurrency() {
        return $this->currency;
    }

    protected function getColor($westfieldColor, $product) {
        return $westfieldColor->getColorByValue($product->getData($this->getColorAttributeCode()));
    }

    protected function getSize($westfieldCatlogProductAttribute, $product) {
        return $westfieldCatlogProductAttribute->getProductSize($product);
    }

    protected function getWestfieldCategories() {
        if (empty($this->westfieldCategories)) {
            $categoryModel = $this->objectManager->create('PandaGroup\Westfield\Model\Catalog\Category');
            $this->westfieldCategories = $categoryModel->getCategoriesAsArray();
        }

        return $this->westfieldCategories;
    }

    protected function getProductHelper() {
        return $this->objectManager->create('PandaGroup\Westfield\Helper\Product');
    }
}
