<?php

namespace PandaGroup\Westfield\Model\ResourceModel\Status\Product;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'light4website_westfield_status_product_collection';
    protected $_eventObject = 'status_product_collection';

    protected function _construct()
    {
        $this->_init('PandaGroup\Westfield\Model\Status\Product','PandaGroup\Westfield\Model\ResourceModel\Status\Product');
    }
}
