<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\StoreLocator\Model\StoreLocator',
            'PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator'
        );
    }
}