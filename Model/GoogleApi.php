<?php

namespace PandaGroup\StoreLocator\Model;

class GoogleApi extends \Magento\Framework\Model\AbstractModel
{
    const GOOGLE_API_ADDRESS_URL = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

    /** @var \PandaGroup\StoreLocator\Helper\ConfigProvider  */
    protected $configProvider;

    /** @var \Magento\Framework\Json\Helper\Data */
    protected $jsonHelper;


    /**
     * GoogleApi constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    )
    {
        parent::__construct($context, $registry);
        $this->configProvider = $configProvider;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @param $addressName
     * @return mixed
     */
    public function getCoordinatesByAddress($addressName)
    {
        $apiKey = $this->configProvider->getGoogleApiKey();

        $addressName = urlencode($addressName);

        $url = self::GOOGLE_API_ADDRESS_URL . $addressName . '&key=' . $apiKey;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        $countryInformation = $this->jsonHelper->jsonDecode($output);

        if (isset($countryInformation['status'])) {
            $requestStatus = $countryInformation['status'];
        }

        if (isset($countryInformation['error_message'])) {
            $requesterrorMessage = $countryInformation['status'];
        }

        if (isset($countryInformation['results'][0]['geometry']['location']['lat'])
         && isset($countryInformation['results'][0]['geometry']['location']['lng'])
        ) {
            $coordinates['lat'] = $countryInformation['results'][0]['geometry']['location']['lat'];
            $coordinates['lng'] = $countryInformation['results'][0]['geometry']['location']['lng'];
        } else {
            $coordinates['lat'] = null;
            $coordinates['lng'] = null;
        }

        return $coordinates;
    }

    /**
     * @param $regionName
     * @return null
     */
    public function getRegionShortName($regionName)
    {
        $apiKey = $this->configProvider->getGoogleApiKey();

        $regionName = urlencode($regionName);

        $url = self::GOOGLE_API_ADDRESS_URL . $regionName . '&key=' . $apiKey;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        $countryInformation = $this->jsonHelper->jsonDecode($output);

        if (isset($countryInformation['status'])) {
            $requestStatus = $countryInformation['status'];
        }

        if (isset($countryInformation['error_message'])) {
            $requesterrorMessage = $countryInformation['status'];
        }

        $shortStateName = null;
        foreach ($countryInformation['results'] as $region) {

            if (false === isset($region['address_components'])) continue;

            foreach ($region['address_components'] as $addressComponent) {

                if (false === isset($addressComponent['types'][0])) continue;

                if ($addressComponent['types'][0] === 'administrative_area_level_1') {
                    $shortStateName = $addressComponent['short_name'];
                }
            }
        }

        return $shortStateName;
    }

}
