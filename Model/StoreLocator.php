<?php

namespace PandaGroup\StoreLocator\Model;


class StoreLocator extends \Magento\Framework\Model\AbstractModel
{
    const GOOGLE_API_ADDRESS_URL = 'http://maps.googleapis.com/maps/api/geocode/json?address=';

    /** @var \PandaGroup\StoreLocator\Helper\ConfigProvider  */
    protected $configProvider;

    /** @var \PandaGroup\StoreLocator\Model\States  */
    protected $states;


    /**
     * Define resource model
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider
     * @param \PandaGroup\StoreLocator\Model\States $states
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider,
        \PandaGroup\StoreLocator\Model\States $states,
        array $data = []
    )
    {
        parent::__construct($context, $registry);
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator');
        $this->configProvider = $configProvider;
        $this->states = $states;
    }

    /**
     * Returns array to controller action with all stores data
     *
     * @return array
     */
    public function getStoresData()
    {
        $collection = $this->getCollection();

        $constants = [
            'apiKey'    => $this->configProvider->getGoogleApiKey(),
            'geo'       => [ 'lat' => $this->configProvider->getMapLatitude(), 'lng' => $this->configProvider->getMapLongitude() ],
            'zoom'      => $this->configProvider->getMapZoomLevel(),
            'country'   => strtolower($this->configProvider->getStoresLocationCountryCode()),
            'pin'       => $this->configProvider->getPinImageLink()
        ];

        $regionsCollection = $this->states->getCollection();

        $regions = [];
        foreach($regionsCollection as $item) {
            $region = [
                'name'  => $item->getData('state_short_name'),
                'geo'   => ['lat' => $item->getData('latitude'), 'lng' => $item->getData('longtitude')],
                'zoom'  => $item->getData('zoom_level'),
            ];
            array_push($regions, $region);
        }
/*
        $regions = [
            [
                'name'  => 'VIC',
                'geo'   => ['lat' => -37.4713077, 'lng' => 144.7851531],
                'zoom'  => 5
            ],
            [
                'name' => 'SA',
                'geo' => [ 'lat' => -30.0002315, 'lng' => 136.2091547 ],
                'zoom' => 5
            ],
            [
                'name' => 'QLD',
                'geo' => [ 'lat' => -20.9175738, 'lng' => 142.7027956 ],
                'zoom' => 5
            ],
            [
                'name' => 'NSW',
                'geo' => [ 'lat' => -31.2532183, 'lng' => 146.921099 ],
                'zoom' => 5
            ],
            [
                'name' => 'ACT',
                'geo' => [ 'lat' => -35.4734679, 'lng' => 149.0123679 ],
                'zoom' => 9
            ]
        ];
*/
        $stores = [];
        foreach($collection as $item) {
            $store = [
                'name'      => $item->getData('name'),
                'id'        => $item->getData('storelocator_id'),
                'addr_strt' => $item->getData('address'),
                'addr_cty'  => $item->getData('city'),
                'zipcode'  => $item->getData('zipcode'),
                'geo'       => [ 'lat' => $item->getData('latitude'), 'lng' => $item->getData('longtitude')],
                'zoom'      => $item->getData('zoom_level'),
                'phone'     => $item->getData('phone'),
                'email'     => $item->getData('email'),
                'region'    => $item->getData('state_short_name'),
                'hours'     => [
                    'SUN' => [
                        date('h:i A', strtotime($item->getData('sunday_open'))),
                        date('h:i A', strtotime($item->getData('sunday_close')))
                    ],
                    "MON"=> [
                        date('h:i A', strtotime($item->getData('monday_open'))),
                        date('h:i A', strtotime($item->getData('monday_close')))
                    ],
                    "TUE"=> [
                        date('h:i A', strtotime($item->getData('tuesday_open'))),
                        date('h:i A', strtotime($item->getData('tuesday_close')))
                    ],
                    "WED"=> [
                        date('h:i A', strtotime($item->getData('wednesday_open'))),
                        date('h:i A', strtotime($item->getData('wednesday_close')))
                    ],
                    "THU"=> [
                        date('h:i A', strtotime($item->getData('thursday_open'))),
                        date('h:i A', strtotime($item->getData('thursday_close')))
                    ],
                    "FRI"=> [
                        date('h:i A', strtotime($item->getData('friday_open'))),
                        date('h:i A', strtotime($item->getData('friday_close')))
                    ],
                    "SAT"=> [
                        date('h:i A', strtotime($item->getData('saturday_open'))),
                        date('h:i A', strtotime($item->getData('saturday_close')))
                    ]
                ]
            ];

            array_push($stores, $store);
        }

        $response = [
            'constants' => $constants,
            'regions' => $regions,
            'stores' => $stores
        ];

        return $response;
    }

    /**
     * Send front message
     *
     * @param $message
     * @param string $type
     */
    protected function sendMessage($message, $type = 'notice') {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var  $messageManager \Magento\Framework\Message\ManagerInterface */
        $messageManager = $objectManager->create('\Magento\Framework\Message\ManagerInterface');

        if (false === empty($message)) {
            switch ($type) {
                case 'success': {
                    $messageManager->addSuccessMessage(__($message));
                    break;
                }
                case 'error': {
                    $messageManager->addErrorMessage(__($message));
                    break;
                }
                default: {
                    $messageManager->addNoticeMessage(__($message));
                }
            }
        }
    }

//    /**
//     * Update region for StoreLocator Model
//     *
//     * @param $store
//     * @param $region
//     *
//     * @return bool
//     *
//     * @internal param $storeId
//     */
//    public function setNewRegion($store, $region) {
//
//        /** @var  $storeLocatorModel \PandaGroup\StoreLocator\Model\StoreLocator */
//        $storeLocatorModel = $store;
//
//        try {
//            $storeLocatorModel->setData('state', $region);
//            $storeLocatorModel->save();
//        } catch (\Exception $e) {
//            return false;
//        }
//        return true;
//    }



//    /**
//     * Update all stores regions in the database, which have incorrect region
//     */
//    public function updateRegions()
//    {
//        /** @var  $storesCollection \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\Collection */
//        $storesCollection = $this->getCollection();
//
//        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
//        $jsonHelper     = $objectManager->create('\Magento\Framework\Json\Helper\Data');
//
//        $path = self::GOOGLE_API_ADDRESS_URL;
//
//        $qtyOfFoundedRegions = 0;
//        $qtyOfRegions = 0;
//
//        foreach($storesCollection as $item) {
//
//            $countryName = $item->getData('country');
//            $state       = $item->getData('state');
//
//            if (null  === $state or
//                'VIC' !== $state or
//                'SA'  !== $state or
//                'QLD' !== $state or
//                'NSW' !== $state or
//                'ACT' !== $state)
//            {
//                $qtyOfRegions++;
//
//                $address = $item->getData('address') .' '. $countryName;
//                $addressLink = urlencode($address);
//
//                $url = $path . $addressLink;
//
//                $result = file_get_contents($url);
//                $json = $jsonHelper->jsonDecode($result);
//
//                if (isset($json['results'][0]['address_components'])) {
//                    $names = $json['results'][0]['address_components'];
//                }
//                else {
//                    $message = 'Cannot found correctly address for: ' . $address;
//                    $this->sendMessage($message, 'error');
//                    continue;
//                }
//
//                $isFounded = false;
//                foreach ($names as $regionName) {
//
//                    if ($regionName['short_name'] == 'VIC' or
//                        $regionName['short_name'] == 'SA'  or
//                        $regionName['short_name'] == 'QLD' or
//                        $regionName['short_name'] == 'NSW' or
//                        $regionName['short_name'] == 'ACT')
//                    {
//                        $isFounded = true;
//
//                        $saveStatus = $this->setNewRegion($item, $regionName['short_name']);
//                        if (false == $saveStatus) {
//                            $this->sendMessage('Cannot save region for: ' . $address, 'error');
//                        } else {
//                            $qtyOfFoundedRegions++;
//                        }
//                    }
//                }
//                if ($isFounded === false) {
//                    $this->sendMessage('Cannot found region for: ' . $address, 'error');
//                }
//            }
//        }
//
//        $message = 'Updates ' . $qtyOfFoundedRegions . ' new regions of '.$qtyOfRegions.'.';
//        $this->sendMessage($message, 'success');
//    }

    public function setNewRegionId($store, $stateId) {

        /** @var  $storeLocatorModel \PandaGroup\StoreLocator\Model\StoreLocator */
        $storeLocatorModel = $store;

        try {
            $storeLocatorModel->setData('state_id', $stateId);
            $storeLocatorModel->save();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Update all stores regions in the database, which have incorrect region
     */
    public function updateRegions()
    {


        /** @var  $storesCollection \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\Collection */
        $storesCollection = $this->getCollection();

//        var_dump($storesCollection->getData()); exit;

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
        $statesModel = $objectManager->create('\PandaGroup\StoreLocator\Model\States');

        /** @var \PandaGroup\StoreLocator\Model\RegionsData $regionsDataModel */
        $regionsDataModel = $objectManager->create('\PandaGroup\StoreLocator\Model\RegionsData');

        $jsonHelper = $objectManager->create('\Magento\Framework\Json\Helper\Data');

        $path = self::GOOGLE_API_ADDRESS_URL;

        $qtyOfFoundedRegions = 0;
        $qtyOfRegions = 0;

        foreach($storesCollection as $item) {

            $countryName = $item->getData('country');
            $state       = $item->getData('state_id');

            if (null  === $state) {
                $qtyOfRegions++;

                $address = $item->getData('address') .' '. $countryName;
                $addressLink = urlencode($address);

                $url = $path . $addressLink;

                $result = file_get_contents($url);
                $json = $jsonHelper->jsonDecode($result);

                if (isset($json['results'][0]['address_components'])) {
                    $names = $json['results'][0]['address_components'];
                }
                else {
                    $message = 'Cannot found correctly address for: ' . $address;
                    $this->sendMessage($message, 'error');
                    continue;
                }

                $isFounded = false;
                foreach ($names as $regionName) {

                    if ($regionName['types'][0] === 'administrative_area_level_1') {
//                        var_dump($regionName);

                        $stateName = $regionName['long_name'];
                        $shortStateName = $regionName['short_name'];

                        // Find region on RegionsData table
                        $foundedRegion = $regionsDataModel->findRegionByName($stateName, $countryName);

//                        if (true === empty($foundedRegion)) {
//                            $this->sendMessage('Cannot found region for: ' . $address, 'error');
//                        }

                        $isFounded = true;

                        // Save region to States table
                        $addedRegionId = $statesModel->addNewRegion(
                            $foundedRegion['id'],
                            $stateName,
                            $shortStateName,
                            strtoupper($foundedRegion['country_code'])
                        );
                        if (false === $addedRegionId) {
                            $this->sendMessage('Something went wrong while creating the new region. Cannot save region for: ' . $address, 'error');
                            continue;
                        }

                        $saveStatus = $this->setNewRegionId($item, $addedRegionId);
                        if (false === $saveStatus) {
                            $this->sendMessage('Cannot add region for: ' . $address, 'error');
                        } else {
                            $qtyOfFoundedRegions++;
                        }

//                        var_dump($foundedRegion);
//                        exit;
                    }

                }
                if ($isFounded === false) {
                    $this->sendMessage('Cannot found region for: ' . $address, 'error');
                }
            }
        }

        $message = 'Updates ' . $qtyOfFoundedRegions . ' new regions of '.$qtyOfRegions.'.';
        $this->sendMessage($message, 'success');
    }
}
