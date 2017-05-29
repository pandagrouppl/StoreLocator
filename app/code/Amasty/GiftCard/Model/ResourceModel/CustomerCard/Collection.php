<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\ResourceModel\CustomerCard;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\GiftCard\Model\CustomerCard', 'Amasty\GiftCard\Model\ResourceModel\CustomerCard');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

}
