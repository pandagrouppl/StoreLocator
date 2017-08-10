<?php

namespace PandaGroup\StoreLocator\Model;

class RegionsData extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\RegionsData');
    }

    public function findRegionByName($regionName, $countryName)
    {
        $regionsDataCollection = $this->getCollection();

        $regionsDataCollection
            ->addFilter('secondTable.code', strtolower($countryName))
            ->addFilter('main_table.name', $regionName);

        foreach ($regionsDataCollection as $regionData) {
            return $regionData->getData();
        }

        return null;
    }

}
