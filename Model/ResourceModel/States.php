<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel;

class States extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('storelocator_states', 'state_id');
    }
}