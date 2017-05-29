<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Plugin;

use Magento\Framework\App\ResourceConnection;

class SearchIndexBuilder
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /** @var \Amasty\Shopby\Model\Request  */
    protected $shopbyRequest;

    /** @var ResourceConnection */
    protected $resource;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  */
    protected $productCollectionFactory;

    /** @var \Magento\Catalog\Model\Product\Visibility  */
    protected $catalogProductVisibility;

    /** @var \Amasty\Shopby\Model\Layer\Filter\IsNew\Helper  */
    protected $isNewHelper;

    /** @var \Amasty\Shopby\Model\Layer\Filter\OnSale\Helper  */
    protected $onSaleHelper;

    /** @var \Amasty\Shopby\Model\Layer\Cms\Manager  */
    protected $cmsManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Amasty\Shopby\Model\Layer\Filter\IsNew\Helper $isNewHelper,
        \Amasty\Shopby\Model\Layer\Filter\OnSale\Helper $onSaleHelper,
        \Amasty\Shopby\Model\Layer\Cms\Manager $cmsManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->shopbyRequest = $shopbyRequest;
        $this->resource = $resource;

        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->isNewHelper = $isNewHelper;
        $this->onSaleHelper = $onSaleHelper;
        $this->cmsManager = $cmsManager;
    }


    public function afterBuild($subject, $result)
    {
        if($this->isEnabledShowOutOfStock() && $this->isEnabledStockFilter()) {
            if ($this->shopbyRequest->getParam('stock')) {
                $this->addStockDataToSelect($result);
            }
        }

        if($this->isEnabledRatingFilter()) {
            $this->addRatingDataToSelect($result);
        }

        if ($this->isEnabledIsNewFilter()) {
            $this->addIsNewDataToSelect($result);
        }

        if ($this->isEnabledOnSaleFilter()){
            $this->addOnSaleDataToSelect($result);
        }

        if ($this->cmsManager->isCmsPageNavigation()) {
            $this->cmsManager->addCmsPageDataToSelect($result);
        }

        return $result;
    }

    protected function addStockDataToSelect($select)
    {
        $connection = $select->getConnection();

        $select->joinLeft(
            ['stock_index' => $this->resource->getTableName('cataloginventory_stock_status')],
            'search_index.entity_id = stock_index.product_id'
            . $connection->quoteInto(
                ' AND stock_index.website_id IN (?, 0)',
                $this->storeManager->getWebsite()->getId()
            ),
            []
        );
    }

    protected function addRatingDataToSelect($select)
    {
        $select->joinLeft(
            ['rating' => $this->resource->getTableName('review_entity_summary')],
            sprintf('`rating`.`entity_pk_value`=`search_index`.entity_id
                AND `rating`.entity_type = 1
                AND `rating`.store_id  =  %d',
                $this->storeManager->getStore()->getId()),
            []
        );
    }

    protected function addIsNewDataToSelect($select)
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection->addStoreFilter();
        $this->isNewHelper->addNewFilter($collection);

        $collection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
        $collection->getSelect()->columns(['e.entity_id', new \Zend_Db_Expr('1 as is_new')]);

        $select->joinLeft(
            ['is_new' => $collection->getSelect()],
            '`is_new`.`entity_id`=`search_index`.entity_id',
            []
        );
    }

    protected function addOnSaleDataToSelect($select)
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->addStoreFilter();
        $this->onSaleHelper->addOnSaleFilter($collection);

        $collection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
        $collection->getSelect()->columns(['e.entity_id', 'relation.parent_id', new \Zend_Db_Expr('1 as on_sale')]);

        $select->joinLeft(
            ['on_sale' => $collection->getSelect()],
            '`search_index`.entity_id in (`on_sale`.`entity_id`, `on_sale`.`parent_id`)',
            []
        );
    }

    protected function isEnabledShowOutOfStock()
    {
        return $this->scopeConfig->isSetFlag(
            'cataloginventory/options/show_out_of_stock',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    protected function isEnabledStockFilter()
    {
        return $this->scopeConfig->isSetFlag('amshopby/stock_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function isEnabledRatingFilter()
    {
        return $this->scopeConfig->isSetFlag('amshopby/rating_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function isEnabledIsNewFilter()
    {
        return $this->scopeConfig->isSetFlag('amshopby/is_new_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function isEnabledOnSaleFilter()
    {
        return $this->scopeConfig->isSetFlag('amshopby/on_sale_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
