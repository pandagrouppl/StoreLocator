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
        $options[] = ['label' => __(''), 'value' => ''];
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
        if (true === empty($countryCode)) {  // Load all regions - Ajax request will organize theirs
            $regions = $this->regionsData->getCollection()->getData();
        } else {                                                  // There is Ajax request condition
            $countries = $this->countriesData->getCollection()->addFilter('code', $countryCode)->getData();

            $regions = [];
            if (isset($countries[0]['id'])) {
                $countryId = $countries[0]['id'];
                $regions = $this->regionsData->getCollection()->addFilter('country_id', $countryId)->getData();
            }
        }

        $regionsByCountry = [];
        foreach ($regions as $region) {
            if (false === empty($region['name'])) {           // Some rows in the database are empty
                $regionsByCountry[$region['id']] = strtoupper($region['name']);
            }
        }

        return $regionsByCountry;
    }
}
