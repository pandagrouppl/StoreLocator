<?php

namespace PandaGroup\Westfield\Model\ResourceModel\Color;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'light4website_westfield_color_collection';
    protected $_eventObject = 'color_collection';

    protected function _construct()
    {
        $this->_init('PandaGroup\Westfield\Model\Color','PandaGroup\Westfield\Model\ResourceModel\Color');
    }

}
