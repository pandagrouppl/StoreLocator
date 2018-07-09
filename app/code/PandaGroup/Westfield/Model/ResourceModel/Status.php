<?php

namespace PandaGroup\Westfield\Model\ResourceModel;

class Status extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('light4website_westfield_status', 'entity_id');
    }
}
