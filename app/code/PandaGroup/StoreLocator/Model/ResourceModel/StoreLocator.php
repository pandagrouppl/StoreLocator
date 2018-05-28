<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel;

class StoreLocator extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('storelocator', 'storelocator_id');
    }
}