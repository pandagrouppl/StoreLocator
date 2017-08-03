<?php
namespace Amasty\GiftCard\Model\ResourceModel\CodeSet;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\GiftCard\Model\CodeSet', 'Amasty\GiftCard\Model\ResourceModel\CodeSet');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    public function joinCodes($fields = '*')
    {
        $this->getSelect()
            ->joinLeft(
                array('amgiftcard_code'=>$this->getTable('amasty_amgiftcard_code')),
                'amgiftcard_code.code_set_id = main_table.code_set_id',
                $fields
            );
        return $this;
    }

    public function joinCodeQtyAndUnused()
    {
        $fields = array(
            'qty'	=> new \Zend_Db_Expr('COUNT(amgiftcard_code.code_id)'),
            'qty_unused'	=> new \Zend_Db_Expr('SUM(IF(amgiftcard_code.used='.\Amasty\GiftCard\Model\Code::STATE_UNUSED.',1,0))')
        );
        $this->joinCodes($fields)->getSelect()->group('main_table.code_set_id');
        return $this;
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('code_set_id', 'title');
    }

}
