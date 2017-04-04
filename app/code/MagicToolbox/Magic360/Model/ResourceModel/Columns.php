<?php

namespace MagicToolbox\Magic360\Model\ResourceModel;

/**
 * Mysql resource
 *
 */
class Columns extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magic360_columns', 'product_id');
        $this->_isPkAutoIncrement = false;
    }
}
