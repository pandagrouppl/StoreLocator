<?php
namespace Amasty\GiftCard\Model\ResourceModel\Code;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\GiftCard\Model\Code', 'Amasty\GiftCard\Model\ResourceModel\Code');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * @param $codeSet
     *
     * @return int
     */
    public function countOfFreeCodesByCodeSet($codeSet)
    {
        $this->addFieldToFilter("used", 0)
            ->addFieldToFilter("enabled", 1)
            ->addFieldToFilter("code_set_id", $codeSet);

        return $this->count();
    }
}
