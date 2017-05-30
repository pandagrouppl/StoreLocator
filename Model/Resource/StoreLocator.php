<?php

namespace PandaGroup\StoreLocator\Model\Resource;

//use Magento\Framework\Model\Resource\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class StoreLocator extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('storelocator', 'storelocator_id');
    }
}