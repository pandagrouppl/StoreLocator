<?php

namespace PandaGroup\StoreLocator\Model\Resource\StoreLocator;

//use Magento\Framework\Model\Resource\Db\Collection\AbstractCollection
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\StoreLocator\Model\StoreLocator',
            'PandaGroup\StoreLocator\Model\Resource\StoreLocator'
        );
    }
}