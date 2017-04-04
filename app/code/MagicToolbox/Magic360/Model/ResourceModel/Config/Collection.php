<?php

namespace MagicToolbox\Magic360\Model\ResourceModel\Config;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagicToolbox\Magic360\Model\Config', 'MagicToolbox\Magic360\Model\ResourceModel\Config');
    }
}
