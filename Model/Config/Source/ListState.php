<?php

namespace PandaGroup\StoreLocator\Model\Config\Source;

class ListState implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Catalog config
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /** @var \PandaGroup\StoreLocator\Model\ResourceModel\CountriesData  */
    protected $countriesData;

    /** @var \PandaGroup\StoreLocator\Model\RegionsData  */
    protected $regionsData;

    /**
     * ListState constructor.
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param \PandaGroup\StoreLocator\Model\RegionsData $regionsData
     */
    public function __construct(
        \Magento\Catalog\Model\Config $catalogConfig,
        \PandaGroup\StoreLocator\Model\CountriesData $countriesData,
        \PandaGroup\StoreLocator\Model\RegionsData $regionsData
    )
    {
        $this->countriesData = $countriesData;
        $this->regionsData = $regionsData;
        $this->_catalogConfig = $catalogConfig;
    }

    /**
     * Retrieve option values array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __(' '), 'value' => ' '];
        foreach ($this->getRegionsAsArray() as $regionId => $regionName) {
            $options[] = ['label' => __($regionName), 'value' => $regionId];
        }
        return $options;
    }

    /**
     * Retrieve Catalog Config Singleton
     *
     * @return \Magento\Catalog\Model\Config
     */
    protected function _getCatalogConfig()
    {
        return $this->_catalogConfig;
    }

    public function getRegionsAsArray($countryCode = '')
    {
        /** @var \PandaGroup\StoreLocator\Model\ResourceModel\RegionsData\Collection $regionsDataCollection */
        $regionsDataCollection = $this->regionsData->getCollection();

        /** @var \PandaGroup\StoreLocator\Model\ResourceModel\CountriesData\Collection $countriesDataCollection */
        $countriesDataCollection = $this->countriesData->getCollection();

        if (false === empty($countryCode)) {
            $countriesDataCollection->addFilter('code', $countryCode);
            $countryId = $countriesDataCollection->getFirstItem()->getId();
            if (false === isset($countryId)) return null;
            $regionsDataCollection->addFilter('country_id', $countryId);
        }

        $regionsByCountry = [];
        $emptyRegionId = null;
        /*
        foreach ($regionsDataCollection as $region) {
//            if (false === empty($region->getName())) {           // Some rows in the database are empty
//                $regionsByCountry[$region->getId()] = $region->getName();
//            }

            if (false === empty($region->getName())) {           // Some rows in the database are empty
                $regionsByCountry[$region->getId()] = $region->getName();
            } else {
//                $regionsByCountry[$region->getId()] = '-';
                $emptyRegionId = $region->getId();
            }
        }

        if (true === empty($regionsByCountry)) {
            $regionsByCountry[$emptyRegionId] = $countriesDataCollection->addFilter('code', $countryCode)->getFirstItem()->getData('name');
            $regionsByCountry[''] = ' ';
        }
        */

        foreach ($regionsDataCollection as $region) {

            if (false === empty($region->getName())) {           // Some rows in the database are empty
                $regionsByCountry[$region->getId()] = $region->getName();
            } else {
                $regionsByCountry[$region->getId()] = '-';
                $emptyRegionId = $region->getId();
            }
        }

        $isArrayOnlyOfEmptyRegions = true;
        foreach ($regionsByCountry as $simpleRegion) {
            if ($simpleRegion != '-') {
                $isArrayOnlyOfEmptyRegions = false;
                break;
            }
        }

        // Clear empty regions only if there are some correct regions (nax to empty regions)
        if ($countryCode != '') {
            foreach ($regionsByCountry as $key => $value) {
                if ($value == '-') {
                    unset($regionsByCountry[$key]);
                }
            }
        }

        // If country has any region show country name on region list witch id form database
        // Countries without regions have one empty region
        if (true === empty($regionsByCountry) || $isArrayOnlyOfEmptyRegions) {
            $regionsByCountry[$emptyRegionId] = $countriesDataCollection->addFilter('code', $countryCode)->getFirstItem()->getData('name');
            $regionsByCountry[''] = ' ';
        }

        return $regionsByCountry;
    }
}
