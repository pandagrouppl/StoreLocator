<?php

namespace MagicToolbox\Magic360\Helper;

use Magento\Catalog\Model\Product;

/**
 * Class ConfigurableData
 * Helper class for getting options
 *
 */
class ConfigurableData extends \Magento\ConfigurableProduct\Helper\Data
{
    /**
     * Helper
     *
     * @var \MagicToolbox\Magic360\Helper\Data
     */
    public $magicToolboxHelper = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Enable effect
     *
     * @var bool
     */
    protected $isEffectEnable = false;

    /**
     * Use original gallery
     *
     * @var bool
     */
    protected $useOriginalGallery = true;

    /**
     * Gallery data
     *
     * @var array
     */
    protected $galleryData = [];

    /**
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($imageHelper);
        $this->magicToolboxHelper = $magicToolboxHelper;
        $this->coreRegistry = $registry;
    }

    /**
     * Retrieve collection of gallery images
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Model\Product\Image[]|null
     */
    public function getGalleryImages(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        return ($this->isEffectEnable && !$this->useOriginalGallery) ? null : parent::getGalleryImages($product);
    }

    /**
     * Get Options for Configurable Product Options
     *
     * @param \Magento\Catalog\Model\Product $currentProduct
     * @param array $allowedProducts
     * @return array
     */
    public function getOptions($currentProduct, $allowedProducts)
    {
        $isEnabled = false;

        $data = $this->coreRegistry->registry('magictoolbox');
        if ($data && $data['current'] != 'product.info.media.image') {

            foreach ($data['blocks'] as $key => $block) {
                if (!in_array($key, ['product.info.media.image', 'product.info.media.magic360']) && $block) {
                    $this->useOriginalGallery = false;
                    break;
                }
            }

            $galleryBlock = $data['blocks'][$data['current']];
            $toolObj = $galleryBlock->magicToolboxHelper->getToolObj();
            $isEnabled = !$toolObj->params->checkValue('enable-effect', 'No', 'product');
            if ($isEnabled) {
                if (!$this->useOriginalGallery) {
                    $productId = $currentProduct->getId();
                    $this->galleryData[$productId] = $galleryBlock->renderGalleryHtml($currentProduct)->getRenderedHtml($productId);
                }
                $allProducts = $currentProduct->getTypeInstance()->getUsedProducts($currentProduct, null);
                foreach ($allProducts as $product) {
                    $productId = $product->getId();
                    $this->galleryData[$productId] = $galleryBlock->renderGalleryHtml($product, true)->getRenderedHtml($productId);
                }
                $this->isEffectEnable = true;
            }
        }

        $data = $this->coreRegistry->registry('magictoolbox_category');
        if ($data && $data['current-renderer'] == 'configurable.magic360') {
            $this->useOriginalGallery = false;
            $productId = $currentProduct->getId();
            $this->galleryData[$productId] = $this->magicToolboxHelper->getHtmlData($currentProduct, false, ['image', 'small_image', 'thumbnail']);

            $allProducts = $currentProduct->getTypeInstance()->getUsedProducts($currentProduct, null);
            foreach ($allProducts as $product) {
                $productId = $product->getId();
                $this->galleryData[$productId] = $this->magicToolboxHelper->getHtmlData($product, true, ['image']);
            }
            $this->isEffectEnable = true;
        }

        $options = parent::getOptions($currentProduct, $allowedProducts);

        if ($isEnabled && $this->useOriginalGallery) {

            $magic360Icon = $galleryBlock->getMagic360IconPath();

            if ($magic360Icon) {
                $magic360Icon = $galleryBlock->magic360ImageHelper
                    ->init(null, 'product_page_image_small', ['width' => null, 'height' => null])
                    ->setImageFile($magic360Icon)
                    ->getUrl();
            } else {
                $magic360Icon = $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
            }

            $productImages = isset($options['images']) ? $options['images'] : [];
            foreach ($productImages as $productId => &$images) {
                if (!empty($this->galleryData[$productId])) {
                    foreach ($images as &$image) {
                        $image['position'] = (int)$image['position']+1;
                        $image['isMain'] = false;
                    }
                    array_unshift($images, [
                        'magic360' => 'Magic360-product-'.$productId,
                        'thumb' => $magic360Icon,
                        'html' => '<div class="fotorama__select">'.$this->galleryData[$productId].'</div>',
                        'caption' => '',
                        'position' => 0,
                        'isMain' => true,
                        'fit' => 'none',
                    ]);
                }
                if (isset($this->galleryData[$productId])) {
                    unset($this->galleryData[$productId]);
                }
            }

            //NOTE: product that has no images but has 360 images
            foreach ($this->galleryData as $productId => $html) {
                if (!empty($html)) {
                    $productImages[$productId][] = [
                        'magic360' => 'Magic360-product-'.$productId,
                        'thumb' => $magic360Icon,
                        'html' => '<div class="fotorama__select">'.$this->galleryData[$productId].'</div>',
                        'caption' => '',
                        'position' => 0,
                        'isMain' => true,
                        'fit' => 'none',
                    ];
                }
            }

            $options['images'] = $productImages;
            $this->galleryData = [];
        }

        return $options;
    }

    /**
     * Get gallery data
     *
     * @return array
     */
    public function getGalleryData()
    {
        return $this->galleryData;
    }

    /**
     * Use original gallery flag
     *
     * @return bool
     */
    public function useOriginalGallery()
    {
        return $this->useOriginalGallery;
    }

    /**
     * Get registry
     *
     * @return \Magento\Framework\Registry
     */
    public function getRegistry()
    {
        return $this->coreRegistry;
    }
}
