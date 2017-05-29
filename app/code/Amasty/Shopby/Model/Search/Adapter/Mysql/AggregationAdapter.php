<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Search\Adapter\Mysql;


/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
class AggregationAdapter
{
    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\Mapper
     */
    protected $mapper;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory
     */
    protected $temporaryStorageFactory;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Container
     */
    protected $aggregationContainer;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderContainer
     */
    protected $dataProviderContainer;

    /**
     * AggregationAdapter constructor.
     *
     * @param \Magento\Framework\Search\Adapter\Mysql\Mapper                        $mapper
     * @param \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory       $temporaryStorageFactory
     * @param \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Container $aggregationContainer
     */
    public function __construct(
        \Magento\Framework\Search\Adapter\Mysql\Mapper $mapper,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Container $aggregationContainer,
        \Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderContainer $dataProviderContainer
    ) {
        $this->mapper = $mapper;
        $this->temporaryStorageFactory = $temporaryStorageFactory;
        $this->aggregationContainer = $aggregationContainer;
        $this->dataProviderContainer = $dataProviderContainer;
    }

    public function getBucketByRequest(\Magento\Framework\Search\RequestInterface $request, $attributeCode)
    {
        $query = $this->mapper->buildQuery($request);
        $temporaryStorage = $this->temporaryStorageFactory->create();
        $documentsTable = $temporaryStorage->storeDocumentsFromSelect($query);
        $dataProvider = $this->dataProviderContainer->get($request->getIndex());
        $buckets = $request->getAggregation();

        $attributeCode = $attributeCode . "_bucket";
        $currentBucket = null;
        foreach($buckets as $bucket) {
            if($bucket->getName() == $attributeCode) {
                $currentBucket = $bucket;
                break;
            }
        }

        if(is_null($currentBucket)) {
            return [];
        }


        $aggregationBuilder = $this->aggregationContainer->get($currentBucket->getType());
        $aggregation = $aggregationBuilder->build(
            $dataProvider,
            $request->getDimensions(),
            $currentBucket,
            $documentsTable
        );
        return $aggregation;
    }
}
