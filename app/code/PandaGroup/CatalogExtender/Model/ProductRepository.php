<?php

namespace PandaGroup\CatalogExtender\Model;

class ProductRepository extends \Magento\Catalog\Model\ProductRepository
{
    /**
     * {@inheritdoc}
     */
    public function getById($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        $cacheKey = $this->getCacheKey([$editMode, $storeId]);
        if (!isset($this->instancesById[$productId][$cacheKey]) || $forceReload) {
            $product = $this->productFactory->create();
            if ($editMode) {
                $product->setData('_edit_mode', true);
            }
            if ($storeId !== null) {
                $product->setData('store_id', $storeId);
            }
            $product->load($productId);
            if (!$product->getId()) {
                //throw new NoSuchEntityException(__('Requested product doesn\'t exist'));
            }
            $this->instancesById[$productId][$cacheKey] = $product;
            $this->instances[$product->getSku()][$cacheKey] = $product;
        }
        return $this->instancesById[$productId][$cacheKey];

    }
}
