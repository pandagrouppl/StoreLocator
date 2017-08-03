<?php
namespace Amasty\GiftCard\Model\ResourceModel\Quote;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\GiftCard\Model\Quote', 'Amasty\GiftCard\Model\ResourceModel\Quote');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    public function joinAccount()
    {
        $this->getSelect()->join(
            array('amgiftcard_account' => $this->getTable('amasty_amgiftcard_account')),
            'amgiftcard_account.account_id = main_table.account_id'
        )->join(
            array('amgiftcard_code' => $this->getTable('amasty_amgiftcard_code')),
            'amgiftcard_code.code_id = amgiftcard_account.code_id'
        );

        return $this;
    }

    public function joinOrder()
    {
        $this->getSelect()
            ->join(
                array('order' => $this->getTable('sales_order')),
                'order.quote_id = main_table.quote_id',
                array()
            )
            ->join(
                array('order_grid' => $this->getTable('sales_order_grid')),
                'order_grid.entity_id = order.entity_id'
            );

        return $this;
    }

}
