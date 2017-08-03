<?php
namespace Amasty\GiftCard\Model;

class Quote extends \Magento\Framework\Model\AbstractModel
{
    const STATE_USED = 1;
    const STATE_UNUSED = 0;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\GiftCard\Model\ResourceModel\Quote');
        $this->setIdFieldName('entity_id');
    }
}