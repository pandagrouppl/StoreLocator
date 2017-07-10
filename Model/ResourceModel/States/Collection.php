<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel\States;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\StoreLocator\Model\States',
            'PandaGroup\StoreLocator\Model\ResourceModel\States'
        );
    }
}