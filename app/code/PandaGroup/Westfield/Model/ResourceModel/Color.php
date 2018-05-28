<?php

namespace PandaGroup\Westfield\Model\ResourceModel;

class Color extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('light4website_westfield_color', 'entity_id');
    }
}
