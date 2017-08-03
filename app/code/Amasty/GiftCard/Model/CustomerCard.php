<?php
namespace Amasty\GiftCard\Model;

class CustomerCard extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\GiftCard\Model\ResourceModel\CustomerCard');
        $this->setIdFieldName('customer_card_id');
    }
}