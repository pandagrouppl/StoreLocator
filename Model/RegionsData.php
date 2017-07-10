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

//        $regionData = $regionsDataCollection->addFieldToFilter(
//            ['name', 'country_name'],
//            [
//                ['like' => '%'.$regionName.'%'],
//                ['like' => '%'.$countryName.'%']
//            ]
//        );

//        $regionsDataCollection->getSelect()->where('storelocator_data_countries.country_name=?', $countryName);
        $regionsDataCollection
            ->addFilter('secondTable.code', strtolower($countryName))
            ->addFilter('main_table.name', $regionName);



//        $regionData = $regionsDataCollection->getColumnValues('country_name');

        foreach ($regionsDataCollection as $regionData) {
            return $regionData->getData();
        }

        return null;

    }
}
