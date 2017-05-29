<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\ResourceModel\OptionSetting;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\Shopby\Model\OptionSetting', 'Amasty\Shopby\Model\ResourceModel\OptionSetting');
    }

    public function addLoadParams($filterCode, $optionId, $storeId)
    {
        $listStores = [0];
        if($storeId > 0) {
            $listStores[] = $storeId;
        }
        $this->addFieldToFilter('filter_code', $filterCode)
            ->addFieldToFilter('value', $optionId)
            ->addFieldToFilter('store_id', $listStores)
            ->addOrder('store_id', self::SORT_ORDER_DESC);
        return $this;
    }

    /**
     * for some reason returned null @todo ???
     */
    public function getIdFieldName()
    {
        return 'option_setting_id';
    }
}
