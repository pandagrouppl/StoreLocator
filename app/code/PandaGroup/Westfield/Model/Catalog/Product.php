<?php
namespace PandaGroup\Westfield\Model\Catalog;

class Product extends \Magento\Framework\Model\AbstractModel
{
    protected $objectManager;
    protected $resourceConnection;

    public function __construct()
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resourceConnection = $this->objectManager->create('Magento\Framework\App\ResourceConnection');
    }

    public function getAssignedProductsToCategories()
    {
        $catalogCategoryProductTable = $this->resourceConnection->getTableName('catalog_category_product');
        $productModel = $this->objectManager->create('Magento\Catalog\Model\Product');
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $productModel->getCollection();

        $catalogConfigModel = $this->objectManager->create('Magento\Catalog\Model\Config');
        $productAttributesConfig = $catalogConfigModel->getProductAttributes();

        $collection->addAttributeToSelect($productAttributesConfig)
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();

        /** @var /Magento\Catalog\Model\Product\Visibility $productVisibilityModel */
        $productVisibilityModel = $this->objectManager->create('Magento\Catalog\Model\Product\Visibility');
        $visibleInSiteIds = $productVisibilityModel->getVisibleInSiteIds();

        $collection->addAttributeToFilter('visibility', ['in' => $visibleInSiteIds]);

        $productStatusModel = $this->objectManager->create('Magento\Catalog\Model\Product\Attribute\Source\Status');
        $visibleStatusIds = $productStatusModel->getVisibleStatusIds();

        $collection->addAttributeToFilter('status', ['in' => $visibleStatusIds]);
        $collection->addAttributeToFilter('type_id', ['neq' => 'giftcards']);
        $collection->addAttributeToFilter('type_id', ['neq' => 'amgiftcard']);

        /** Fix lack of simple products **/
        //$collection->addAttributeToFilter('type_id', ['in' => ['configurable']]);

        $collection->getSelect()
            ->joinRight( ['catalog_category_product' => $catalogCategoryProductTable],
             'e.entity_id = catalog_category_product.product_id', '*')
            ->group('e.entity_id');

        return $collection;
    }

    public function getCountForAssignedProductsToCategories()
    {
        $connection = $this->resourceConnection->getConnection('core_read');
        $catalogCategoryProductTable = $this->resourceConnection->getTableName('catalog_category_product');

        $select = $connection->select()
            ->from(['cp' => $this->resourceConnection->getTableName('catalog_product_entity')], ['COUNT(DISTINCT cp.entity_id) AS count'])
            ->joinRight( ['catalog_category_product' => $catalogCategoryProductTable],
             'cp.entity_id = catalog_category_product.product_id', '*');

        return (int) $connection->fetchOne($select);
    }

}
