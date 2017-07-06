<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel;

class CountriesData extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('storelocator_data_countries', 'id');
    }
}