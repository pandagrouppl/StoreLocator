<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class CustomerCard extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_customer_card', 'customer_card_id');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        if(is_array($field) || is_array($value)) {
            if(is_array($field) && is_array($value)) {
                $listFieldsValues = array_combine($field, $value);
            } elseif(is_array($field)) {
                $listFieldsValues = $field;
            } else {
                $listFieldsValues = $value;
            }

            $select = $this->getConnection()->select()
                ->from($this->getMainTable());
            foreach($listFieldsValues as $field=>$value) {
                $field  = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $field));
                $select->where($field . '=?', $value);
            }
        } else {
            $select = parent::_getLoadSelect($field, $value, $object);
        }
        return $select;
    }
}
