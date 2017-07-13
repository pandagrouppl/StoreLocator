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

        foreach ($regionsDataCollection as $region) {
//            if (false === empty($region->getName())) {           // Some rows in the database are empty
//                $regionsByCountry[$region->getId()] = $region->getName();
//            }

            if (false === empty($region->getName())) {           // Some rows in the database are empty
                $regionsByCountry[$region->getId()] = $region->getName();
            } else {
//                $regionsByCountry[$region->getId()] = $countriesDataCollection->load($countryId)->getFirstItem()->getData('name');
                $regionsByCountry[$region->getId()] = '-';
            }
        }

        return $regionsByCountry;
    }
}
