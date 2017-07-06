<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel;

class RegionsData extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('storelocator_data_regions', 'id');
    }
}