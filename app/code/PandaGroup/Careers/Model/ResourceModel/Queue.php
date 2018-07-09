<?php

namespace PandaGroup\Careers\Model\ResourceModel;

class Queue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('pandagroup_careers_email_queue', 'email_id');
    }
}
