<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel\CountriesData;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\StoreLocator\Model\CountriesData',
            'PandaGroup\StoreLocator\Model\ResourceModel\CountriesData'
        );
    }
}