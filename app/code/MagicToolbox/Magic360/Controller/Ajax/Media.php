<?php

namespace MagicToolbox\Magic360\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\Product;

/**
 * Ajax media controller
 *
 */
class Media extends \Magento\Swatches\Controller\Ajax\Media
{
    /**
     * Variant product identifier
     *
     * @var string
     */
    protected $variantProductId = '';

    /**
     * Get product media by fallback:
     * 1stly by default attribute values
     * 2ndly by getting base image from configurable product
     *
     * @return string
     */
    public function execute()
    {
        $product = null;
        $productMedia = [];
        $this->variantProductId = '';

        if ($productId = (int)$this->getRequest()->getParam('product_id')) {
            $currentConfigurable = $this->productModelFactory->create()->load($productId);
            $attributes = (array)$this->getRequest()->getParam('attributes');
            if (!empty($attributes)) {
                $product = $this->getProductVariationWithMedia($currentConfigurable, $attributes);
            }
            if ((empty($product) || (!$product->getImage() || $product->getImage() == 'no_selection'))
                && isset($currentConfigurable)
            ) {
                $product = $currentConfigurable;
            }
            // $productMedia = $this->swatchHelper->getProductMediaGallery($product);
        }

        if ($this->variantProductId) {
            $productMedia['variantProductId'] = $this->variantProductId;
        } elseif ($product) {
            $productMedia['variantProductId'] = $product->getId();
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($productMedia);
        return $resultJson;
    }

    /**
     * @param  Product $currentConfigurable
     * @param  array $attributes
     * @return Product|bool|null
     */
    protected function getProductVariationWithMedia(Product $currentConfigurable, array $attributes)
    {
        $product = null;
        $layeredAttributes = [];
        $configurableAttributes = $this->swatchHelper->getAttributesFromConfigurable($currentConfigurable);
        if ($configurableAttributes) {
            $layeredAttributes = $this->getLayeredAttributesIfExists($configurableAttributes);
        }
        $resultAttributes = array_merge($layeredAttributes, $attributes);

        $product = $this->swatchHelper->loadVariationByFallback($currentConfigurable, $resultAttributes);

        if (!$product || (!$product->getImage() || $product->getImage() == 'no_selection')) {

            if ($product) {
                $this->variantProductId = $product->getId();
            }

            $product = $this->swatchHelper->loadFirstVariationWithImage(
                $currentConfigurable,
                $resultAttributes
            );
        }
        return $product;
    }
}
