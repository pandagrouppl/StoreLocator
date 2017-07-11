<?php

namespace PandaGroup\StoreLocator\Model\Config\Source;

class ListCountry implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Catalog config
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /** @var \PandaGroup\StoreLocator\Model\ResourceModel\CountriesData  */
    protected $countriesData;

    /**
     * ListCountry constructor.
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param \PandaGroup\StoreLocator\Model\CountriesData $countriesData
     */
    public function __construct(
        \Magento\Catalog\Model\Config $catalogConfig,
        \PandaGroup\StoreLocator\Model\CountriesData $countriesData
    )
    {
        $this->_catalogConfig = $catalogConfig;
        $this->countriesData = $countriesData;
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
        foreach ($this->getCountryCodesAsArray() as $countryName => $countryCode) {
            $options[] = ['label' => __($countryName), 'value' => $countryCode];
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

    private function getCountryCodesAsArray()
    {
        $countries = $this->countriesData->getCollection()->getData();

        $countryCodes = [];
        foreach ($countries as $country) {
            $countryCodes[$country['name']] = strtoupper($country['code']);
        }

        return $countryCodes;
    }
}
