<?php

namespace PandaGroup\StoreLocator\Model;

class RegionsData extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\RegionsData');
    }
}