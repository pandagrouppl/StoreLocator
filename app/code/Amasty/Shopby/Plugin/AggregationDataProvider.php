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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Search\Request\BucketInterface;


class AggregationDataProvider
{
    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var ScopeResolverInterface
     */
    protected $scopeResolver;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  */
    protected $productCollectionFactory;

    /** @var \Magento\Catalog\Model\Product\Visibility  */
    protected $catalogProductVisibility;

    /** @var \Amasty\Shopby\Model\Layer\Filter\IsNew\Helper  */
    protected $isNewHelper;

    /** @var \Amasty\Shopby\Model\Layer\Filter\OnSale\Helper  */
    protected $onSaleHelper;

    /** @var ScopeConfigInterface  */
    protected $scopeConfig;

    /**
     * @param ResourceConnection $resource
     * @param ScopeResolverInterface $scopeResolver
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Amasty\Shopby\Model\Layer\Filter\IsNew\Helper $isNewHelper
     * @param \Amasty\Shopby\Model\Layer\Filter\OnSale\Helper $onSaleHelper
     */
    public function __construct(
        ResourceConnection $resource,
        ScopeResolverInterface $scopeResolver,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Amasty\Shopby\Model\Layer\Filter\IsNew\Helper $isNewHelper,
        \Amasty\Shopby\Model\Layer\Filter\OnSale\Helper $onSaleHelper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->resource = $resource;
        $this->scopeResolver = $scopeResolver;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->isNewHelper = $isNewHelper;
        $this->onSaleHelper = $onSaleHelper;
        $this->scopeConfig = $scopeConfig;
    }

    public function aroundGetDataSet(
        \Magento\CatalogSearch\Model\Adapter\Mysql\Aggregation\DataProvider $subject,
        \Closure $proceed,
        BucketInterface $bucket,
        array $dimensions,
        Table $entityIdsTable
    ) {
        if ($bucket->getField() == 'stock_status') {
            $isStockEnabled = $this->scopeConfig->isSetFlag('amshopby/stock_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            if ($isStockEnabled) {
                return $this->addStockAggregation($entityIdsTable);
            }
        }

        if ($bucket->getField() == 'rating_summary') {
            $isRatingEnabled = $this->scopeConfig->isSetFlag('amshopby/rating_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            if ($isRatingEnabled) {
                return $this->addRatingAggregation($entityIdsTable, $dimensions);
            }
        }

        if ($bucket->getField() == 'is_new') {
            $isNewEnabled = $this->scopeConfig->isSetFlag('amshopby/is_new_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            if ($isNewEnabled) {
                return $this->addIsNewAggregation($entityIdsTable, $dimensions);
            }
        }

        if ($bucket->getField() == 'on_sale') {
            $isOnSaleEnabled = $this->scopeConfig->isSetFlag('amshopby/on_sale_filter/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            if ($isOnSaleEnabled) {
                return $this->addOnSaleAggregation($entityIdsTable, $dimensions);
            }
        }

        return $proceed($bucket, $dimensions, $entityIdsTable);
    }

    protected function addStockAggregation(Table $entityIdsTable)
    {
        $derivedTable = $this->resource->getConnection()->select();
        $derivedTable->from(
            ['main_table' => $this->resource->getTableName('cataloginventory_stock_status')],
            [
                'value' => 'stock_status',
            ]
        );
//        $derivedTable->where('main_table.stock_id = ?', 1);    //filter by stock id = 1 (don't need probable)

        $derivedTable->joinInner(
            ['entities' => $entityIdsTable->getName()],
            'main_table.product_id  = entities.entity_id',
            []
        );

        $select = $this->resource->getConnection()->select();
        $select->from(['main_table' => $derivedTable]);

        return $select;
    }

    protected function addRatingAggregation(Table $entityIdsTable, $dimensions)
    {
        $currentScope = $dimensions['scope']->getValue();
        $currentScopeId = $this->scopeResolver->getScope($currentScope)->getId();
        $derivedTable = $this->resource->getConnection()->select();
        $derivedTable->from(
            ['entities' => $entityIdsTable->getName()],
            []
        );

        $columnRating = new \Zend_Db_Expr("
                IF(main_table.rating_summary >=100,
                    5,
                    IF(
                        main_table.rating_summary >=80,
                        4,
                        IF(main_table.rating_summary >=60,
                            3,
                            IF(main_table.rating_summary >=40,
                                2,
                                IF(main_table.rating_summary >=20,
                                    1,
                                    0
                                )
                            )
                        )
                    )
                )
            ");

        $derivedTable->joinLeft(
            ['main_table' => $this->resource->getTableName('review_entity_summary')],
            sprintf('`main_table`.`entity_pk_value`=`entities`.entity_id
                AND `main_table`.entity_type = 1
                AND `main_table`.store_id  =  %d',
                $currentScopeId),
            [
                //'entity_id' => 'entity_pk_value',
                'value' => $columnRating,
            ]
        );
        $select = $this->resource->getConnection()->select();
        $select->from(['main_table' => $derivedTable]);
        return $select;
    }

    protected function addIsNewAggregation(Table $entityIdsTable, $dimensions)
    {
        $currentScope = $dimensions['scope']->getValue();
        $currentScopeId = $this->scopeResolver->getScope($currentScope)->getId();

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection->addStoreFilter($currentScopeId);
        $this->isNewHelper->addNewFilter($collection);

        $collection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
        $collection->getSelect()->columns('e.entity_id');

        $derivedTable = $this->resource->getConnection()->select();
        $derivedTable->from(
            ['entities' => $entityIdsTable->getName()],
            []
        );

        $derivedTable->joinLeft(
            ['is_new' => $collection->getSelect()],
            'is_new.entity_id  = entities.entity_id',
            [
                'value' => new \Zend_Db_Expr("if(is_new.entity_id is null, 0, 1)")
            ]
        );

        $select = $this->resource->getConnection()->select();
        $select->from(['main_table' => $derivedTable]);

        return $select;
    }

    protected function addOnSaleAggregation(Table $entityIdsTable, $dimensions)
    {
        $currentScope = $dimensions['scope']->getValue();
        $currentScopeId = $this->scopeResolver->getScope($currentScope)->getId();

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection->addStoreFilter($currentScopeId);
        $this->onSaleHelper->addOnSaleFilter($collection);

        $collection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
        $collection->getSelect()->columns('e.entity_id');

        $derivedTable = $this->resource->getConnection()->select();
        $derivedTable->from(
            ['entities' => $entityIdsTable->getName()],
            []
        );

        $derivedTable->joinLeft(
            ['on_sale' => $collection->getSelect()],
            'on_sale.entity_id  = entities.entity_id',
            [
                'value' => new \Zend_Db_Expr("if(on_sale.entity_id is null, 0, 1)")
            ]
        );

        $select = $this->resource->getConnection()->select();
        $select->from(['main_table' => $derivedTable]);

        return $select;
    }

}
