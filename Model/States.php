<?php

namespace PandaGroup\StoreLocator\Model;

class States extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\States');
    }
}