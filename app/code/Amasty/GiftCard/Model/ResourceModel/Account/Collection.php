<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\ResourceModel\Account;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\GiftCard\Model\Account', 'Amasty\GiftCard\Model\ResourceModel\Account');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    public function savedByCustomer($customerId)
    {
        if ($customerId) {
            $this->getSelect()->join(
                array('customer_card' => $this->getTable('amasty_amgiftcard_customer_card')),
                'customer_card.account_id = main_table.account_id AND customer_card.customer_id = ' . $customerId
            );
        }

        return $this;
    }

    public function joinOrder()
    {
        $this->getSelect()->joinLeft(
            array('order' => $this->getTable('sales_order_grid')),
            'order.entity_id = main_table.order_id',
            array('order_number' => 'order.increment_id', 'order.store_id')
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function joinCode()
    {
        $this->getSelect()->join(
            array('code' => $this->getTable('amasty_amgiftcard_code')),
            'code.code_id = main_table.code_id',
            array('gift_code' => 'code.code')
        );
        return $this;
    }
}
