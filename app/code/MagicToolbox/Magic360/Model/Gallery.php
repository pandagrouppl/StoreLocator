<?php

namespace MagicToolbox\Magic360\Model;

class Gallery extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('MagicToolbox\Magic360\Model\ResourceModel\Gallery');
    }
}
