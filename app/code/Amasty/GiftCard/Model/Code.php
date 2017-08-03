<?php
namespace Amasty\GiftCard\Model;

class Code extends \Magento\Framework\Model\AbstractModel
{
    const STATE_USED = 1;
    const STATE_UNUSED = 0;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\GiftCard\Model\ResourceModel\Code');
        $this->setIdFieldName('code_id');
    }

    public function isUsed()
    {
        return $this->getUsed() == self::STATE_USED;
    }

    public function loadFreeCode($codeSetId)
    {
        $this->_getResource()->loadFreeCode($this, $codeSetId);

        return $this;
    }
}
