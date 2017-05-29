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
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\DB\Select;
use Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory;
use Magento\Framework\Search\Request\Aggregation\DynamicBucket;

class DynamicAggregation
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @var ScopeResolverInterface
     */
    protected $scopeResolver;

    protected $eavConfig;
    protected $filterSettingHelper;
    protected $dataProvider;
    protected $entityStorageFactory;

    protected $scopeConfig;

    /**
     * @param ResourceConnection $resource
     * @param ScopeResolverInterface $scopeResolver
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\ScopeResolverInterface $scopeResolver,
        \Magento\Eav\Model\Config $eavConfig,
        \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper,
        \Magento\Framework\Search\Dynamic\DataProviderInterface $priceDataProvider,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Search\Dynamic\EntityStorageFactory $entityStorageFactory
    ) {
        $this->resource = $resource;
        $this->scopeResolver = $scopeResolver;
        $this->eavConfig = $eavConfig;
        $this->filterSettingHelper = $filterSettingHelper;
        $this->priceDataProvider = $priceDataProvider;
        $this->entityStorageFactory = $entityStorageFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function aroundBuild(
        \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Dynamic $subject,
        \Closure $closure,
        \Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderInterface $dataProvider,
        array $dimensions,
        \Magento\Framework\Search\Request\BucketInterface $bucket,
        \Magento\Framework\DB\Ddl\Table $entityIdsTable
    ) {
        $attribute = $this->eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $bucket->getField());

        if($attribute->getBackendType() == 'decimal') {
            $filterSetting = $this->filterSettingHelper->getSettingByAttribute($attribute);
            if ($filterSetting->getDisplayMode() == \Amasty\Shopby\Model\Source\DisplayMode::MODE_SLIDER ||
                $filterSetting->getDisplayMode() == \Amasty\Shopby\Model\Source\DisplayMode::MODE_FROM_TO_ONLY ||
                $filterSetting->getAddFromToWidget() === '1') {

                if($attribute->getAttributeCode() == 'price') {
                    if ($this->scopeConfig
                            ->getValue(AlgorithmFactory::XML_PATH_RANGE_CALCULATION) == AlgorithmFactory::RANGE_CALCULATION_IMPROVED) {
                        $newBucket = new DynamicBucket($bucket->getName(), $bucket->getField(), 'auto');
                        $bucket = $newBucket;
                    }
                    $minMaxData['data'] = $this->priceDataProvider->getAggregations($this->entityStorageFactory->create($entityIdsTable));
                    $minMaxData['data']['value'] = 'data';
                } else {

                    $currentScope = $dimensions['scope']->getValue();
                    $currentScopeId = $this->scopeResolver->getScope($currentScope)->getId();
                    $select = $this->resource->getConnection()->select();
                    $table = $this->resource->getTableName(
                        'catalog_product_index_eav_decimal'
                    );
                    $select->from(['main_table' => $table], ['value'])
                        ->where('main_table.attribute_id = ?', $attribute->getAttributeId())
                        ->where('main_table.store_id = ? ', $currentScopeId);
                    $select->joinInner(
                        ['entities' => $entityIdsTable->getName()],
                        'main_table.entity_id  = entities.entity_id',
                        []
                    );
                    /** @var Select $fullQuery */
                    $fullQuery = $this->resource->getConnection()
                        ->select();

                    $fullQuery->from(['main_table' => $select], ['value'=> new \Zend_Db_Expr("'data'")]);
                    $fullQuery->columns(
                        ['min' => 'min(main_table.value)',
                         'max' => 'max(main_table.value)',
                         'count' => 'count(*)'
                        ]
                    );

                    $minMaxData = $dataProvider->execute($fullQuery);
                }

                $defaultData = $closure($dataProvider, $dimensions, $bucket, $entityIdsTable);

                return array_replace($minMaxData, $defaultData);
            }
        }

        return $closure($dataProvider, $dimensions, $bucket, $entityIdsTable);
    }
}
