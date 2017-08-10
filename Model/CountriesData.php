<?php

namespace PandaGroup\StoreLocator\Model;

class CountriesData extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\CountriesData');
    }
}
