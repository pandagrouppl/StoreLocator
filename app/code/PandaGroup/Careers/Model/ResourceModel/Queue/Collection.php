<?php

namespace PandaGroup\Careers\Model\ResourceModel\Queue;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\Careers\Model\Queue',
            'PandaGroup\Careers\Model\ResourceModel\Queue'
        );
    }
}
