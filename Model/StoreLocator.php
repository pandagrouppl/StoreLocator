<?php

namespace PandaGroup\StoreLocator\Model;

use PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface;

class StoreLocator extends \Magento\Framework\Model\AbstractModel implements \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface
{
    const GOOGLE_API_ADDRESS_URL = 'http://maps.googleapis.com/maps/api/geocode/json?address=';

    /** @var \PandaGroup\StoreLocator\Helper\ConfigProvider  */
    protected $configProvider;

    /** @var \PandaGroup\StoreLocator\Model\States  */
    protected $states;

    /** @var \PandaGroup\StoreLocator\Logger\Logger  */
    protected $logger;


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
        \PandaGroup\StoreLocator\Logger\Logger $logger,
        array $data = []
    )
    {
        parent::__construct($context, $registry);
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator');
        $this->configProvider = $configProvider;
        $this->states = $states;
        $this->logger = $logger;
    }

    /**
     * Returns array to controller action with all stores data
     *
     * @return array
     */
    public function getStoresData()
    {
        $collection = $this->getCollection()->addFilter('status', 1);

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
                        $this->stringToDate($item->getData('sunday_status'), $item->getData('sunday_open')),
                        $this->stringToDate($item->getData('sunday_status'), $item->getData('sunday_close'))
                    ],
                    "MON"=> [
                        $this->stringToDate($item->getData('monday_status'), $item->getData('monday_open')),
                        $this->stringToDate($item->getData('monday_status'), $item->getData('monday_close'))
                    ],
                    "TUE"=> [
                        $this->stringToDate($item->getData('tuesday_status'), $item->getData('tuesday_open')),
                        $this->stringToDate($item->getData('tuesday_status'), $item->getData('tuesday_close'))
                    ],
                    "WED"=> [
                        $this->stringToDate($item->getData('wednesday_status'), $item->getData('wednesday_open')),
                        $this->stringToDate($item->getData('wednesday_status'), $item->getData('wednesday_close'))
                    ],
                    "THU"=> [
                        $this->stringToDate($item->getData('thursday_status'), $item->getData('thursday_open')),
                        $this->stringToDate($item->getData('thursday_status'), $item->getData('thursday_close'))
                    ],
                    "FRI"=> [
                        $this->stringToDate($item->getData('friday_status'), $item->getData('friday_open')),
                        $this->stringToDate($item->getData('friday_status'), $item->getData('friday_close'))
                    ],
                    "SAT"=> [
                        $this->stringToDate($item->getData('saturday_status'), $item->getData('saturday_open')),
                        $this->stringToDate($item->getData('saturday_status'), $item->getData('saturday_close'))
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
     * @param $status
     * @param $stringValue
     * @return false|string
     */
    protected function stringToDate($status, $stringValue) {

        if ((int) $status === 0) {
            return date('h:i A', strtotime('0:00'));    // This format for open and close time sets 'Closed' on frontend
        }

        $timeFormat = $this->configProvider->getHoursTimeFormat();

        if ($timeFormat === 12) {
            return date('h:i A', strtotime($stringValue));
        }
        return date('h:i', strtotime($stringValue));
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

    public function setNewRegionId($store, $stateId) {

        /** @var  $storeLocatorModel \PandaGroup\StoreLocator\Model\StoreLocator */
        $storeLocatorModel = $store;

        $this->logger->info('Start setting new region id on stores table.');

        try {
            $storeLocatorModel->setData('state_id', $stateId);
            $storeLocatorModel->save();
            $this->logger->info('    Store region id was correctly updated.');
        } catch (\Exception $e) {
            $this->logger->error('    Error while update record: ', $e->getMessage());
            $this->logger->info('Finish setting new region id on stores table.');
            return false;
        }
        $this->logger->info('Finish setting new region id on stores table.');
        return true;
    }

    /**
     * Update all stores regions in the database, which have incorrect region
     */
    public function updateRegions()
    {
        /** @var  $storesCollection \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\Collection */
        $storesCollection = $this->getCollection();

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
        $statesModel = $objectManager->create('\PandaGroup\StoreLocator\Model\States');

        /** @var \PandaGroup\StoreLocator\Model\RegionsData $regionsDataModel */
        $regionsDataModel = $objectManager->create('\PandaGroup\StoreLocator\Model\RegionsData');

        $jsonHelper = $objectManager->create('\Magento\Framework\Json\Helper\Data');

        $path = self::GOOGLE_API_ADDRESS_URL;

        $qtyOfFoundedRegions = 0;
        $qtyOfRegions = 0;

        $this->logger->info('Start updating regions on stores table.');

        foreach($storesCollection as $item) {

            $countryName = $item->getData('country');
            $state       = $item->getData('state_id');

            if (null === $state) {
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
                    $this->logger->warning('    Cannot found correctly address for: ' . $address);
                    continue;
                }

                $isFounded = false;
                foreach ($names as $regionName) {

                    if ($regionName['types'][0] === 'administrative_area_level_1') {

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
                            $this->logger->info('    Region was correctly founded and updated.');
                        }
                    }

                }
                if ($isFounded === false) {
                    $this->sendMessage('Cannot found region for: ' . $address, 'error');
                }
            }
        }

        $message = 'Updates ' . $qtyOfFoundedRegions . ' new regions of '.$qtyOfRegions.'.';
        $this->logger->info('    '.$message);
        $this->sendMessage($message, 'success');
        $this->logger->info('Finish updating regions on stores table.');
    }

    /**
     * Get entityId value.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->_getData(self::ENTITY_ID);
    }

    /**
     * Set entityId value.
     *
     * @param int $entityId
     *
     * @return $this
     */
    public function setEntityId($entityId)
    {
        $this->setData(self::ENTITY_ID, $entityId);

        return $this;
    }

    /**
     * Get store name value.
     *
     * @return string
     */
    public function getStoreName()
    {
        return $this->_getData(self::STORE_NAME);
    }

    /**
     * Set store name value.
     *
     * @param string $storeName
     *
     * @return $this
     */
    public function setStoreName($storeName)
    {
        $this->setData(self::STORE_NAME, $storeName);

        return $this;
    }

    /**
     * Get store address value.
     *
     * @return string
     */
    public function getStoreAddress()
    {
        return $this->_getData(self::STORE_ADDRESS);
    }

    /**
     * Set store address value.
     *
     * @param string $storeAddress
     *
     * @return $this
     */
    public function setStoreAddress($storeAddress)
    {
        $this->setData(self::STORE_ADDRESS, $storeAddress);

        return $this;
    }

    /**
     * Get store city value.
     *
     * @return string
     */
    public function getStoreCity()
    {
        return $this->_getData(self::STORE_CITY);
    }

    /**
     * Set store city value.
     *
     * @param string $storeCity
     *
     * @return $this
     */
    public function setStoreCity($storeCity)
    {
        $this->setData(self::STORE_CITY, $storeCity);

        return $this;
    }

    /**
     * Get store zip code value.
     *
     * @return string
     */
    public function getStoreZipCode()
    {
        return $this->_getData(self::STORE_ZIPCODE);
    }

    /**
     * Set store zip code value.
     *
     * @param string $storeZipCode
     *
     * @return $this
     */
    public function setStoreZipCode($storeZipCode)
    {
        $this->setData(self::STORE_ZIPCODE, $storeZipCode);

        return $this;
    }

    /**
     * Get store email value.
     *
     * @return string
     */
    public function getStoreEmail()
    {
        return $this->_getData(self::STORE_EMAIL);
    }

    /**
     * Set store email value.
     *
     * @param string $storeEmail
     *
     * @return $this
     */
    public function setStoreEmail($storeEmail)
    {
        $this->setData(self::STORE_EMAIL, $storeEmail);

        return $this;
    }

    /**
     * Get store phone value.
     *
     * @return string
     */
    public function getStorePhone()
    {
        return $this->_getData(self::STORE_PHONE);
    }

    /**
     * Set store phone value.
     *
     * @param string $storePhone
     *
     * @return $this
     */
    public function setStorePhone($storePhone)
    {
        $this->setData(self::STORE_PHONE, $storePhone);

        return $this;
    }

    /**
     * Get store status value.
     *
     * @return string
     */
    public function getStoreStatus()
    {
        return $this->_getData(self::STORE_STATUS);
    }

    /**
     * Set store status value.
     *
     * @param string $storeStatus
     *
     * @return $this
     */
    public function setStoreStatus($storeStatus)
    {
        $this->setData(self::STORE_STATUS, $storeStatus);

        return $this;
    }
}
