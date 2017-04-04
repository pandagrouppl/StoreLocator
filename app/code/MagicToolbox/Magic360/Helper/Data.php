<?php

namespace MagicToolbox\Magic360\Helper;

/**
 * Data helper
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\ConfigFactory
     */
    protected $_modelConfigFactory = null;

    /**
     * Magic360 module core class
     *
     * @var \MagicToolbox\Magic360\Classes\Magic360ModuleCoreClass
     *
     */
    protected $magic360 = null;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\GalleryFactory
     */
    protected $modelGalleryFactory = null;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\ColumnsFactory
     */
    protected $modelColumnsFactory = null;

    /**
     * Magic360 image helper
     * @var \MagicToolbox\Magic360\Helper\Image
     */
    protected $magic360ImageHelper;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Product list block
     *
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $listProductBlock = null;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \MagicToolbox\Magic360\Model\ConfigFactory $modelConfigFactory
     * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory
     * @param \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
     * @param \MagicToolbox\Magic360\Helper\Image $magic360ImageHelper
     * @param \MagicToolbox\Magic360\Classes\Magic360ModuleCoreClass $magic360
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MagicToolbox\Magic360\Model\ConfigFactory $modelConfigFactory,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory,
        \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory,
        \MagicToolbox\Magic360\Helper\Image $magic360ImageHelper,
        \MagicToolbox\Magic360\Classes\Magic360ModuleCoreClass $magic360,
        \Magento\Framework\Registry $registry
    ) {
        $this->_modelConfigFactory = $modelConfigFactory;
        $this->magic360 = $magic360;
        $this->_imageHelper = $imageHelperFactory->create();
        $this->modelGalleryFactory = $modelGalleryFactory;
        $this->modelColumnsFactory = $modelColumnsFactory;
        $this->magic360ImageHelper = $magic360ImageHelper;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    public function getToolObj()
    {
        static $doInit = true;
        if ($doInit) {
            $model = $this->_modelConfigFactory->create();
            $collection = $model->getCollection();
            $collection->addFieldToFilter('platform', 0);
            $collection->addFieldToFilter('status', ['neq' => 0]);
            $data = $collection->getData();
            foreach ($data as $key => $param) {
                $this->magic360->params->setValue($param['name'], $param['value'], $param['profile']);
            }
            $doInit = false;
        }
        return $this->magic360;
    }

    /**
     * Public method to get image sizes
     *
     * @return array
     */
    public function magicToolboxGetSizes($sizeType, $originalSizes = [])
    {
        $w = $this->magic360->params->getValue($sizeType.'-max-width');
        $h = $this->magic360->params->getValue($sizeType.'-max-height');
        if (empty($w)) {
            $w = 0;
        }
        if (empty($h)) {
            $h = 0;
        }
        if ($this->magic360->params->checkValue('square-images', 'No')) {
            //NOTE: fix for bad images
            if (empty($originalSizes[0]) || empty($originalSizes[1])) {
                return [$w, $h];
            }
            list($w, $h) = $this->calculateSize($originalSizes[0], $originalSizes[1], $w, $h);
        } else {
            $h = $w = $h ? ($w ? min($w, $h) : $h) : $w;
        }
        return [$w, $h];
    }

    /**
     * Public method to calculate sizes
     *
     * @return array
     */
    private function calculateSize($originalW, $originalH, $maxW = 0, $maxH = 0)
    {
        if (!$maxW && !$maxH) {
            return [$originalW, $originalH];
        } elseif (!$maxW) {
            $maxW = ($maxH * $originalW) / $originalH;
        } elseif (!$maxH) {
            $maxH = ($maxW * $originalH) / $originalW;
        }
        $sizeDepends = $originalW/$originalH;
        $placeHolderDepends = $maxW/$maxH;
        if ($sizeDepends > $placeHolderDepends) {
            $newW = $maxW;
            $newH = $originalH * ($maxW / $originalW);
        } else {
            $newW = $originalW * ($maxH / $originalH);
            $newH = $maxH;
        }
        return [round($newW), round($newH)];
    }

    /**
     * Get HTML data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $isAssociatedProduct
     * @param array $mediaAttributeCodes
     * @return string
     */
    public function getHtmlData($product, $isAssociatedProduct = false, $mediaAttributeCodes = ['small_image'])
    {
        static $_html = [];
        $id = $product->getId();
        $key = implode('_', $mediaAttributeCodes);
        if (!isset($_html[$key])) {
            $_html[$key] = [];
        }
        $html = & $_html[$key];
        if (!isset($html[$id])) {
            $this->magic360->params->setProfile('category');

            /** @var $listProductBlock \Magento\Catalog\Block\Product\ListProduct */
            $listProductBlock = $this->getListProductBlock();
            if ($listProductBlock) {
                $productImage = $listProductBlock->getImage($product, $listProductBlock->getMode() == 'grid' ? 'category_page_grid' : 'category_page_list');
                $productImageWidth = $productImage->getWidth();
            } else {
                list($productImageWidth, ) = $this->magicToolboxGetSizes('thumb');
            }


            $images = $this->getGalleryData($product);
            if (!count($images)) {
                $anotherRenderer = $this->getAnotherRenderer();
                if ($anotherRenderer) {
                    if (strpos($anotherRenderer->getModuleName(), 'MagicToolbox_') === 0) {
                        $html[$id] = $anotherRenderer->getMagicToolboxHelper()->getHtmlData($product, $isAssociatedProduct, $mediaAttributeCodes);
                    } else {
                        $image = 'no_selection';
                        foreach ($mediaAttributeCodes as $mediaAttributeCode) {
                            $image = $product->getData($mediaAttributeCode);
                            if ($image && $image != 'no_selection') {
                                break;
                            }
                        }
                        if (!$image || $image == 'no_selection') {
                            $html[$id] = $isAssociatedProduct ? '' : $this->getPlaceholderHtml();
                            return $html[$id];
                        }

                        $img = $this->_imageHelper->init($product, 'product_page_image_large', ['width' => null, 'height' => null])
                                 ->setImageFile($image)
                                 ->getUrl();
                        $originalSizeArray = $this->_imageHelper->getOriginalSizeArray();
                        list($w, $h) = $this->magicToolboxGetSizes('thumb', $originalSizeArray);
                        $medium = $this->_imageHelper->init($product, 'product_page_image_medium', ['width' => $w, 'height' => $h])
                                ->setImageFile($image)
                                ->getUrl();

                        $html[$id] =
                            '<a href="'.$product->getProductUrl().'" class="product photo" tabindex="-1">'.//product-item-photo
                                '<img src="'.$medium.'"/>'.
                            '</a>';
                        $html[$id] = '<div class="MagicToolboxContainer" style="width: '.$productImageWidth.'px;">'.$html[$id].'</div>';
                    }
                    return $html[$id];
                }
                $html[$id] = $isAssociatedProduct ? '' : $this->getPlaceholderHtml();
                return $html[$id];
            }

            $columns = $this->getColumns($id);
            if ($columns > count($images)) {
                $columns = count($images);
            }
            $this->magic360->params->setValue('columns', $columns);

            $html[$id] = $this->magic360->getMainTemplate($images, ['id' => "Magic360-category-{$id}"]);
            $html[$id] = '<div class="MagicToolboxContainer" style="width: '.$productImageWidth.'px;">'.$html[$id].'</div>';
        }

        return $html[$id];
    }

    /**
     * Retrieve another renderer
     *
     * @return mixed
     */
    public function getAnotherRenderer()
    {
        $data = $this->coreRegistry->registry('magictoolbox_category');
        if ($data) {
            $skip = true;
            foreach ($data['renderers'] as $name => $renderer) {
                if ($name == 'configurable.magic360') {
                    $skip = false;
                    continue;
                }
                if ($skip) {
                    continue;
                }
                if ($renderer) {
                    return $renderer;
                }
            }
        }
        return null;
    }

    /**
     * Get placeholder HTML
     *
     * @return string
     */
    public function getPlaceholderHtml()
    {
        static $html = null;
        if ($html === null) {
            $placeholderUrl = $this->magic360ImageHelper->getDefaultPlaceholderUrl('small_image');
            list($width, $height) = $this->magicToolboxGetSizes('thumb');
            $html = '<div class="MagicToolboxContainer placeholder" style="width: '.$width.'px;height: '.$height.'px">'.
                    '<span class="align-helper"></span>'.
                    '<img src="'.$placeholderUrl.'"/>'.
                    '</div>';
        }
        return $html;
    }

    /**
     * Set product list block
     *
     */
    public function setListProductBlock(\Magento\Catalog\Block\Product\ListProduct $block)
    {
        $this->listProductBlock = $block;
    }

    /**
     * Get product list block
     *
     * @return \Magento\Catalog\Block\Product\ListProduct
     */
    public function getListProductBlock()
    {
        return $this->listProductBlock;
    }

    /**
     * Retrieve collection of Magic360 gallery images
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getGalleryData($product)
    {
        static $images = [];
        $id = $product->getId();
        if (!isset($images[$id])) {
            $images[$id] = [];
            $galleryModel = $this->modelGalleryFactory->create();
            $collection = $galleryModel->getCollection();
            $collection->addFieldToFilter('product_id', $id);
            $collection->addFieldToSelect('position');
            $collection->addFieldToSelect('file');
            if ($collection->count()) {
                $_images = $collection->getData();
                $compare = create_function('$a,$b', 'if ($a["position"] == $b["position"]) return 0; return (int)$a["position"] > (int)$b["position"] ? 1 : -1;');
                usort($_images, $compare);
                foreach ($_images as &$image) {

                    if (!$this->magic360ImageHelper->fileExists($image['file'])) {
                        continue;
                    }

                    $image['img'] = $this->magic360ImageHelper
                        ->init($product, 'product_page_image_large', ['width' => null, 'height' => null])
                        ->setImageFile($image['file'])
                        ->getUrl();

                    $originalSizeArray = $this->magic360ImageHelper->getOriginalSizeArray();

                    if ($this->magic360->params->checkValue('square-images', 'Yes')) {
                        $bigImageSize = ($originalSizeArray[0] > $originalSizeArray[1]) ? $originalSizeArray[0] : $originalSizeArray[1];
                        $image['img'] = $this->magic360ImageHelper
                            ->init($product, 'product_page_image_large')
                            ->setImageFile($image['file'])
                            ->resize($bigImageSize)
                            ->getUrl();
                    }

                    list($w, $h) = $this->magicToolboxGetSizes('thumb', $originalSizeArray);
                    $image['medium'] = $this->magic360ImageHelper
                        ->init($product, 'product_page_image_medium', ['width' => $w, 'height' => $h])
                        ->setImageFile($image['file'])
                        ->getUrl();

                    $images[$id][] = $image;
                }
            }
        }
        return $images[$id];
    }

    /**
     * Retrieve columns param
     *
     * @param integer $id
     * @return integer
     */
    public function getColumns($id)
    {
        static $columns = [];
        if (!isset($columns[$id])) {
            $columnsModel = $this->modelColumnsFactory->create();
            $columnsModel->load($id);
            $_columns = $columnsModel->getData('columns');
            $columns[$id] = $_columns ? $_columns : 0;
        }
        return $columns[$id];
    }

    /**
     * Public method for retrieve statuses
     *
     * @return array
     */
    public function getStatuses($profile = false, $force = false)
    {
        static $result = null;
        if (is_null($result) || $force) {
            $result = [];
            $model = $this->_modelConfigFactory->create();
            $collection = $model->getCollection();
            $collection->addFieldToFilter('platform', 0);
            $data = $collection->getData();
            foreach ($data as $key => $param) {
                if (!isset($result[$param['profile']])) {
                    $result[$param['profile']] = [];
                }
                $result[$param['profile']][$param['name']] = $param['status'];
            }
        }
        return isset($result[$profile]) ? $result[$profile] : $result;
    }

    /**
     * Public method for retrieve config map
     *
     * @return array
     */
    public function getConfigMap()
    {
        return unserialize('a:3:{s:7:"default";a:4:{s:7:"General";a:1:{i:0;s:28:"include-headers-on-all-pages";}s:9:"Magic 360";a:23:{i:0;s:7:"magnify";i:1;s:15:"magnifier-width";i:2;s:15:"magnifier-shape";i:3;s:10:"fullscreen";i:4;s:4:"spin";i:5;s:18:"autospin-direction";i:6;s:12:"sensitivityX";i:7;s:12:"sensitivityY";i:8;s:15:"mousewheel-step";i:9;s:14:"autospin-speed";i:10;s:9:"smoothing";i:11;s:8:"autospin";i:12;s:14:"autospin-start";i:13;s:13:"autospin-stop";i:14;s:13:"initialize-on";i:15;s:12:"start-column";i:16;s:9:"start-row";i:17;s:11:"loop-column";i:18;s:8:"loop-row";i:19;s:14:"reverse-column";i:20;s:11:"reverse-row";i:21;s:16:"column-increment";i:22;s:13:"row-increment";}s:24:"Positioning and Geometry";a:3:{i:0;s:15:"thumb-max-width";i:1;s:16:"thumb-max-height";i:2;s:13:"square-images";}s:13:"Miscellaneous";a:8:{i:0;s:4:"icon";i:1;s:12:"show-message";i:2;s:7:"message";i:3;s:12:"loading-text";i:4;s:23:"fullscreen-loading-text";i:5;s:4:"hint";i:6;s:9:"hint-text";i:7;s:16:"mobile-hint-text";}}s:7:"product";a:4:{s:7:"General";a:1:{i:0;s:13:"enable-effect";}s:9:"Magic 360";a:23:{i:0;s:7:"magnify";i:1;s:15:"magnifier-width";i:2;s:15:"magnifier-shape";i:3;s:10:"fullscreen";i:4;s:4:"spin";i:5;s:18:"autospin-direction";i:6;s:12:"sensitivityX";i:7;s:12:"sensitivityY";i:8;s:15:"mousewheel-step";i:9;s:14:"autospin-speed";i:10;s:9:"smoothing";i:11;s:8:"autospin";i:12;s:14:"autospin-start";i:13;s:13:"autospin-stop";i:14;s:13:"initialize-on";i:15;s:12:"start-column";i:16;s:9:"start-row";i:17;s:11:"loop-column";i:18;s:8:"loop-row";i:19;s:14:"reverse-column";i:20;s:11:"reverse-row";i:21;s:16:"column-increment";i:22;s:13:"row-increment";}s:24:"Positioning and Geometry";a:3:{i:0;s:15:"thumb-max-width";i:1;s:16:"thumb-max-height";i:2;s:13:"square-images";}s:13:"Miscellaneous";a:4:{i:0;s:4:"icon";i:1;s:12:"show-message";i:2;s:7:"message";i:3;s:4:"hint";}}s:8:"category";a:4:{s:7:"General";a:1:{i:0;s:13:"enable-effect";}s:9:"Magic 360";a:23:{i:0;s:7:"magnify";i:1;s:15:"magnifier-width";i:2;s:15:"magnifier-shape";i:3;s:10:"fullscreen";i:4;s:4:"spin";i:5;s:18:"autospin-direction";i:6;s:12:"sensitivityX";i:7;s:12:"sensitivityY";i:8;s:15:"mousewheel-step";i:9;s:14:"autospin-speed";i:10;s:9:"smoothing";i:11;s:8:"autospin";i:12;s:14:"autospin-start";i:13;s:13:"autospin-stop";i:14;s:13:"initialize-on";i:15;s:12:"start-column";i:16;s:9:"start-row";i:17;s:11:"loop-column";i:18;s:8:"loop-row";i:19;s:14:"reverse-column";i:20;s:11:"reverse-row";i:21;s:16:"column-increment";i:22;s:13:"row-increment";}s:24:"Positioning and Geometry";a:3:{i:0;s:15:"thumb-max-width";i:1;s:16:"thumb-max-height";i:2;s:13:"square-images";}s:13:"Miscellaneous";a:4:{i:0;s:4:"icon";i:1;s:12:"show-message";i:2;s:7:"message";i:3;s:4:"hint";}}}');
    }
}
