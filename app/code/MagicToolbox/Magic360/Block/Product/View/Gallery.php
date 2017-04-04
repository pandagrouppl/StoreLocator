<?php

/**
 * Magic 360 view block
 *
 */
namespace MagicToolbox\Magic360\Block\Product\View;

use Magento\Framework\Data\Collection;
use MagicToolbox\Magic360\Helper\Data;
use Magento\Framework\Data\CollectionFactory;
use MagicToolbox\Magic360\Model\GalleryFactory;
use MagicToolbox\Magic360\Model\ColumnsFactory;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{
    /**
     * Helper
     *
     * @var \MagicToolbox\Magic360\Helper\Data
     */
    public $magicToolboxHelper = null;

    /**
     * Magic360 module core class
     *
     * @var \MagicToolbox\Magic360\Classes\Magic360ModuleCoreClass
     *
     */
    public $toolObj = null;

    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    protected $collectionFactory;

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
    public $magic360ImageHelper;

    /**
     * Rendered gallery HTML
     * @var array
     */
    protected $renderedGalleryHtml = [];

    /**
     * ID of the current product
     * @var integer
     */
    protected $currentProductId = null;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper
     * @param \Magento\Framework\Data\CollectionFactory $collectionFactory
     * @param \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory
     * @param \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
     * @param \MagicToolbox\Magic360\Helper\Image $magic360ImageHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory,
        \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory,
        \MagicToolbox\Magic360\Helper\Image $magic360ImageHelper,
        array $data = []
    ) {
        $this->magicToolboxHelper = $magicToolboxHelper;
        $this->toolObj = $this->magicToolboxHelper->getToolObj();
        $this->collectionFactory = $collectionFactory;
        $this->modelGalleryFactory = $modelGalleryFactory;
        $this->modelColumnsFactory = $modelColumnsFactory;
        $this->magic360ImageHelper = $magic360ImageHelper;
        parent::__construct($context, $arrayUtils, $jsonEncoder, $data);
    }

    /**
     * Get escaper
     *
     * @return \Magento\Framework\Escaper
     */
    public function getEscaper()
    {
        return $this->_escaper;
    }

    /**
     * Retrieve collection of Magic360 gallery images
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return Magento\Framework\Data\Collection
     */
    public function getGalleryImagesCollection($product = null)
    {
        static $images = [];
        if (is_null($product)) {
            $product = $this->getProduct();
        }
        $id = $product->getId();
        if (!isset($images[$id])) {
            $images[$id] = $this->collectionFactory->create();
            $galleryModel = $this->modelGalleryFactory->create();
            $collection = $galleryModel->getCollection();
            $collection->addFieldToFilter('product_id', $id);
            if ($collection->count()) {
                $_images = $collection->getData();
                $compare = create_function('$a,$b', 'if ($a["position"] == $b["position"]) return 0; return (int)$a["position"] > (int)$b["position"] ? 1 : -1;');
                usort($_images, $compare);
                foreach ($_images as &$image) {
                    if (!$this->magic360ImageHelper->fileExists($image['file'])) {
                        continue;
                    }
                    unset($image['product_id']);
                    $image['large_image_url'] = $this->magic360ImageHelper
                        ->init($product, 'product_page_image_large', ['width' => null, 'height' => null])
                        ->setImageFile($image['file'])
                        ->getUrl();

                    $originalSizeArray = $this->magic360ImageHelper->getOriginalSizeArray();

                    if ($this->toolObj->params->checkValue('square-images', 'Yes')) {
                        $bigImageSize = ($originalSizeArray[0] > $originalSizeArray[1]) ? $originalSizeArray[0] : $originalSizeArray[1];
                        $image['large_image_url'] = $this->magic360ImageHelper
                            ->init($product, 'product_page_image_large')
                            ->setImageFile($image['file'])
                            ->resize($bigImageSize)
                            ->getUrl();
                    }

                    list($w, $h) = $this->magicToolboxHelper->magicToolboxGetSizes('thumb', $originalSizeArray);
                    $image['medium_image_url'] = $this->magic360ImageHelper
                        ->init($product, 'product_page_image_medium', ['width' => $w, 'height' => $h])
                        ->setImageFile($image['file'])
                        ->getUrl();

                    $images[$id]->addItem(new \Magento\Framework\DataObject($image));
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
    public function getColumns($id = null)
    {
        static $columns = [];
        if (is_null($id)) {
            $id = $this->getProduct()->getId();
        }
        if (!isset($columns[$id])) {
            $columnsModel = $this->modelColumnsFactory->create();
            $columnsModel->load($id);
            $_columns = $columnsModel->getData('columns');
            $columns[$id] = $_columns ? $_columns : 0;
        }
        return $columns[$id];
    }

    /**
     * Retrieve additional gallery block
     *
     * @return mixed
     */
    public function getAdditionalBlock()
    {
        $data = $this->_coreRegistry->registry('magictoolbox');
        if ($data && !empty($data['additional-block-name'])) {
            return $data['blocks'][$data['additional-block-name']];
        }
        return null;
    }

    /**
     * Retrieve original gallery block
     *
     * @return mixed
     */
    public function getOriginalBlock()
    {
        $data = $this->_coreRegistry->registry('magictoolbox');
        return is_null($data) ? null : $data['blocks']['product.info.media.image'];
    }

    /**
     * Retrieve another gallery block
     *
     * @return mixed
     */
    public function getAnotherBlock()
    {
        $data = $this->_coreRegistry->registry('magictoolbox');
        if ($data) {
            if (!empty($data['additional-block-name'])) {
                return $data['blocks'][$data['additional-block-name']];
            }
            $skip = true;
            foreach ($data['blocks'] as $name => $block) {
                if ($name == 'product.info.media.magic360') {
                    $skip = false;
                    continue;
                }
                if ($skip) {
                    continue;
                }
                if ($block) {
                    return $block;
                }
            }
        }
        return null;
    }

    /**
     * Check for installed modules, which can operate in cooperative mode
     *
     * @return bool
     */
    public function isCooperativeModeAllowed()
    {
        $data = $this->_coreRegistry->registry('magictoolbox');
        return is_null($data) ? false : $data['cooperative-mode'];
    }

    /**
     * Get thumb switcher initialization attribute
     *
     * @param integer $id
     * @return string
     */
    public function getThumbSwitcherInitAttribute($id = null)
    {
        static $html = null;
        if ($html === null) {
            if (is_null($id)) {
                $id = $this->currentProductId;
            }
            $data = $this->_coreRegistry->registry('magictoolbox');
            $block = $data['blocks'][$data['additional-block-name']];
            $html = $block->getThumbSwitcherInitAttribute($id);
        }
        return $html;
    }

    /**
     * Before rendering html, but after trying to load cache
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->renderGalleryHtml();
        return parent::_beforeToHtml();
    }

    /**
     * Get rendered HTML
     *
     * @param integer $id
     * @return string
     */
    public function getRenderedHtml($id = null)
    {
        if (is_null($id)) {
            $id = $this->getProduct()->getId();
        }
        return isset($this->renderedGalleryHtml[$id]) ? $this->renderedGalleryHtml[$id] : '';
    }

    /**
     * Render gallery block HTML
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $isAssociatedProduct
     * @param array $data
     * @return $this
     */
    public function renderGalleryHtml($product = null, $isAssociatedProduct = false, $data = [])
    {
        if (is_null($product)) {
            $product = $this->getProduct();
        }
        $this->currentProductId = $id = $product->getId();
        if (!isset($this->renderedGalleryHtml[$id])) {
            $this->toolObj->params->setProfile('product');
            $magic360Data = [];

            $images = $this->getGalleryImagesCollection($product);
            $columns = $this->getColumns($id);
            if ($columns > $images->count()) {
                $columns = $images->count();
            }
            $this->toolObj->params->setValue('columns', $columns);

            $originalBlock = $this->getAnotherBlock();

            if (!$images->count()) {
                if ($originalBlock) {
                    if (strpos($originalBlock->getModuleName(), 'MagicToolbox_') === 0) {
                        $this->renderedGalleryHtml[$id] = $originalBlock->renderGalleryHtml($product, $isAssociatedProduct)->getRenderedHtml($id);
                    } else {
                        //NOTE: Magento_Catalog
                        $this->renderedGalleryHtml[$id] = $isAssociatedProduct ? '' : $originalBlock->toHtml();
                    }
                }
                return $this;
            }

            foreach ($images as $image) {

                $magic360Data[] = [
                    'medium' => $image->getData('medium_image_url'),
                    'img' => $image->getData('large_image_url'),
                ];
            }

            $this->renderedGalleryHtml[$id] = $this->toolObj->getMainTemplate($magic360Data, ['id' => "Magic360-product-{$id}"]);

            if ($this->isCooperativeModeAllowed()) {
                $additionalBlock = $this->getAdditionalBlock();
                $_images = $additionalBlock->getGalleryImagesCollection($product);
                if ($_images->count()) {
                    $magic360Icon = $this->getMagic360IconPath();
                    if ($magic360Icon) {
                        $magic360IconUrl = $this->magic360ImageHelper
                            ->init(null, 'product_page_image_small', ['width' => null, 'height' => null])
                            ->setImageFile($magic360Icon)
                            ->getUrl();
                        $originalSizeArray = $this->magic360ImageHelper->getOriginalSizeArray();

                        list($w, $h) = $additionalBlock->magicToolboxHelper->magicToolboxGetSizes('selector', $originalSizeArray);
                        $magic360Icon = $this->magic360ImageHelper
                            ->init(null, 'product_page_image_small', ['width' => $w, 'height' => $h])
                            ->setImageFile($magic360Icon)
                            ->getUrl();
                    } else {
                        $magic360Icon = $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail');
                    }

                    $this->renderedGalleryHtml[$id] = $additionalBlock->renderGalleryHtml(
                        $product,
                        $isAssociatedProduct,
                        ['magic360-icon' => $magic360Icon, 'magic360-html' => $this->renderedGalleryHtml[$id]]
                    )->getRenderedHtml($id);
                } else {
                    $this->renderedGalleryHtml[$id] = '<div class="MagicToolboxContainer"'.$this->getThumbSwitcherInitAttribute().'>'.$this->renderedGalleryHtml[$id].'</div>';
                }
                return $this;
            }

            $this->renderedGalleryHtml[$id] = '<div class="MagicToolboxContainer">'.$this->renderedGalleryHtml[$id].'</div>';

            //NOTE: use original gallery (content that was generated before will be used there)
            if (!$isAssociatedProduct && strpos($originalBlock->getModuleName(), 'MagicToolbox_') === false) {
                $this->renderedGalleryHtml[$id] = $this->getDefaultGalleryHtml();
            }
        }
        return $this;
    }

    /**
     * Get default gallery HTML
     *
     * @param integer $id
     * @return string
     */
    public function getDefaultGalleryHtml()
    {
        static $html = null;
        if (is_null($html)) {
            $moduleName = $this->getModuleName();
            $template = $this->getTemplate();

            $this->setData('module_name', 'Magento_Catalog');
            $this->setTemplate('Magento_Catalog::product/view/gallery.phtml');

            $html = $this->toHtml();

            $this->setData('module_name', $moduleName);
            $this->setTemplate($template);
        }
        return $html;
    }

    /**
     * Retrieve product images in JSON format
     *
     * @return string
     */
    public function getGalleryImagesJson()
    {
        $imagesItems = [];
        $product = $this->getProduct();
        $images = $this->getGalleryImagesCollection($product);

        $magic360Icon = $this->getMagic360IconPath();
        if ($magic360Icon) {
            $magic360Icon = $this->magic360ImageHelper
                ->init(null, 'product_page_image_small', ['width' => null, 'height' => null])
                ->setImageFile($magic360Icon)
                ->getUrl();
        } else {
            $magic360Icon = $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail');
        }

        if ($images->count()) {
            $id = $product->getId();
            $imagesItems[] = [
                'magic360' => 'Magic360-product-'.$id,
                'thumb' => $magic360Icon,
                'html' => '<div class="fotorama__select">'.$this->renderedGalleryHtml[$id].'</div>',
                'caption' => '',
                'position' => 0,
                'isMain' => true,
                'fit' => 'none',
            ];
        }

        foreach ($this->getGalleryImages() as $image) {
            $imagesItems[] = [
                'thumb' => $image->getData('small_image_url'),
                'img' => $image->getData('medium_image_url'),
                'full' => $image->getData('large_image_url'),
                'caption' => $image->getLabel(),
                'position' => (int)$image->getPosition()+1,
                'isMain' => false,
            ];
        }

        return json_encode($imagesItems);
    }

    /**
     * Get Magic360 icon path
     *
     * @return string
     */
    public function getMagic360IconPath()
    {
        static $path = null;
        if (is_null($path)) {
            $this->toolObj->params->setProfile('product');
            $icon = $this->toolObj->params->getValue('icon');
            $hash = md5($icon);
            $model = $this->magic360ImageHelper->getModel();
            $mediaDirectory = $model->getMediaDirectory();
            if ($mediaDirectory->isFile('magic360/icon/'.$hash.'/360icon.jpg')) {
                $path = 'icon/'.$hash.'/360icon.jpg';
            } else {
                $rootDirectory = $model->getRootDirectory();
                if ($rootDirectory->isFile($icon)) {
                    $rootDirectory->copyFile($icon, 'magic360/icon/'.$hash.'/360icon.jpg', $mediaDirectory);
                    $path = 'icon/'.$hash.'/360icon.jpg';
                } else {
                    $path = '';
                }
            }
        }
        return $path;
    }
}
