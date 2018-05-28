<?php

namespace PandaGroup\Westfield\Model\ResourceModel\Status;

class Collection extends  \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'light4website_westfield_status_collection';
    protected $_eventObject = 'status_collection';

    protected function _construct()
    {
        $this->_init('PandaGroup\Westfield\Model\Status','PandaGroup\Westfield\Model\ResourceModel\Status');
    }
}
