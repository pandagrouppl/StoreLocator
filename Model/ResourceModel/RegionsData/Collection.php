<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel\RegionsData;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\StoreLocator\Model\RegionsData',
            'PandaGroup\StoreLocator\Model\ResourceModel\RegionsData'
        );
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->joinLeft(
            ['secondTable' => $this->getTable('storelocator_data_countries')],
            'main_table.country_id = secondTable.id',
            ['country_name' => 'name', 'country_code' => 'code']
        );
    }
}