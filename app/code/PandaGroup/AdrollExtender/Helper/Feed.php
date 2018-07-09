<?php

namespace PandaGroup\AdrollExtender\Helper;


class Feed extends \Adroll\Pixel\Helper\Feed
{
    /** @var \Magento\Store\Api\StoreRepositoryInterface  */
    protected $_storeRepository;

    /** @var \Magento\Directory\Model\CurrencyFactory  */
    protected $_currencyFactory;

    /** @var \Magento\Catalog\Helper\Image  */
    protected $_imageHelper;

    /** @var \Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProvider  */
    protected $_lowestPriceOptionsProvider;


    /**
     * Feed constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\CatalogInventory\Helper\Stock $stockFilter
     * @param \Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProvider $lowestPriceOptionsProvider
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        \Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProvider $lowestPriceOptionsProvider
    ) {
        $this->_storeRepository = $storeRepository;
        $this->_currencyFactory = $currencyFactory;
        $this->_imageHelper = $imageHelper;
        $this->_lowestPriceOptionsProvider = $lowestPriceOptionsProvider;
        parent::__construct($context, $productStatus, $productVisibility, $productCollectionFactory, $storeRepository, $currencyFactory, $imageHelper, $stockFilter, $lowestPriceOptionsProvider);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return null
     */
    private function getBrand($product)
    {
        $brand = $product->getCustomAttribute('brand');

        if (!is_null($brand)) {
            return $brand->getValue();
        }

        $manufacturer = $product->getCustomAttribute('manufacturer');

        if (!is_null($manufacturer)) {
            return $manufacturer->getValue();
        }

        return null;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Directory\Model\Currency $baseCurrency
     * @param \Magento\Directory\Model\Currency $destCurrency
     * @return array
     * @throws \Exception
     */
    private function getPriceInfo($product, $baseCurrency, $destCurrency)
    {
        switch ($product->getTypeId()){
            case 'configurable':
                $price = 0;
                $salePrice = 0;
                foreach ($this->_lowestPriceOptionsProvider->getProducts($product) as $subProduct) {
                    $subProductPrice = $subProduct->getPrice();
                    $subProductSalePrice = $subProduct->getFinalPrice(1);
                    $price = $price ? min($price, $subProductPrice) : $subProductPrice;
                    $salePrice = $salePrice ? min($salePrice, $subProductSalePrice) : $subProductSalePrice;
                }
                break;
            case 'bundle':
                $finalPriceObj = $product->getPriceInfo()->getPrice('final_price');
                $price = $finalPriceObj->getMinimalPrice()->getValue();
                $salePrice = $price;
                break;
            case 'grouped':
                $prices = array();
                $salePrices = array();
                $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
                foreach($associatedProducts as $associatedProduct) {
                    $prices[] = $associatedProduct->getPrice();
                    $salePrices[] = $associatedProduct->getFinalPrice(1);
                }
                sort($prices);
                sort($salePrices);
                $price = $prices[0];
                $salePrice = $salePrices[0];
                break;
            default:
                $price = $product->getPrice();
                $salePrice = $product->getFinalPrice(1);
                break;
        }

        return [
            'price' => $baseCurrency->convert($price, $destCurrency),
            'sale_price' => $baseCurrency->convert($salePrice, $destCurrency)
        ];
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Directory\Model\Currency $baseCurrency
     * @param \Magento\Directory\Model\Currency $destCurrency
     * @return array
     * @throws \Exception
     */
    private function serializeProduct($product, $baseCurrency, $destCurrency)
    {
        $productImageAttr = $product->getCustomAttribute('adroll_img');

        $productImage = $this->_imageHelper->init($product, 'product_base_image');

        if (null !== $productImageAttr && $productImageAttr->getValue() !== 'no_selection') {
            $productImage->setImageFile($productImageAttr->getValue());
        }

        $generalInfo = [
            'id' => $product->getId(),
            'title' => $product->getName(),
            'description' => $product->getDescription(),
            'url' => $product->getProductUrl(),
            'brand' => $this->getBrand($product),
            'image_url' => $productImage
                ->constrainOnly(FALSE)
                ->keepAspectRatio(TRUE)
                ->keepFrame(FALSE)
                ->getUrl()
        ];

        $priceInfo = $this->getPriceInfo($product, $baseCurrency, $destCurrency);

        return array_merge($generalInfo, $priceInfo);
    }

    /**
     * @param $destCurrencyCode
     * @param $storeCode
     * @param $page
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function generateProductFeed($destCurrencyCode, $storeCode, $page)
    {
        $productFeed = array('products' => array());
        $store = $this->_storeRepository->get($storeCode);
        $baseCurrency = $store->getBaseCurrency();
        $destCurrency = $this->_currencyFactory->create()->load($destCurrencyCode);
        $products = $this->getFeedableProducts($store);

        $lastPage = ceil($products->getSize() / self::FEED_PAGE_SIZE);

        if ($page <= $lastPage) {
            $products->setPageSize(self::FEED_PAGE_SIZE)->setCurPage($page);
            foreach ($products as $product) {
                $productFeed['products'][] = $this->serializeProduct($product, $baseCurrency, $destCurrency);
            }
        }

        return $productFeed;
    }
}
