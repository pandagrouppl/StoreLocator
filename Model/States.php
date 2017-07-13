<?php

namespace PandaGroup\StoreLocator\Model;

class States extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('PandaGroup\StoreLocator\Model\ResourceModel\States');
    }

    public function addNewRegion($sourceStateId, $stateName, $shortStateName = '', $country) {

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \PandaGroup\StoreLocator\Logger\Logger $logger */
        $logger = $objectManager->create('PandaGroup\StoreLocator\Logger\Logger');

        $logger->info('Start adding new region.');

    // Check if already exist corrected region
        $statesCollection = $this->getCollection();

        $statesCollection
            ->addFilter('country', strtoupper($country))
            ->addFilter('state_source_id', $sourceStateId);

        $stateId = $statesCollection->getFirstItem()->getId();
        if (isset($stateId)) {
            $logger->info('    Adding new state was finish by founding exist state under id='.$stateId);
            $logger->info('Finish adding new region.');
            return $stateId;
        } else {
            $logger->info('    Cannot found state for country='.strtoupper($country).' and state_source_id='.$sourceStateId);
        }


    // If there aren't created earlier -> create new

        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
        $statesModel = $objectManager->create('PandaGroup\StoreLocator\Model\States');

        /** @var \PandaGroup\StoreLocator\Model\GoogleApi $googleApiModel */
        $googleApiModel = $objectManager->create('PandaGroup\StoreLocator\Model\GoogleApi');

        /** @var \Magento\Framework\Message\Manager $messageManager */
        $messageManager = $objectManager->create('Magento\Framework\Message\Manager');

        /** @var \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider */
        $configProvider = $objectManager->create('PandaGroup\StoreLocator\Helper\ConfigProvider');

//        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
//        $statesModel = $this->create();

//        if ($stateName == '-') {
//            $stateName = $country;
//            $logger->info('    Replace state name by country name: '.$country);
//        }
        if (true === empty($shortStateName)) {
            $shortStateName = $googleApiModel->getRegionShortName($stateName);
            $logger->info('    Download short state name from Google Api: '.$shortStateName);

            if (null === $shortStateName) {
                $shortStateName = $stateName;
                $logger->warning('    Replace short state name by long: '.$stateName.'->'.$shortStateName);
            }
        }

        $isCoordinateDownloadError = false;
        $coordinates = $googleApiModel->getCoordinatesByAddress($stateName . ', ' . $country);
        $logger->info('    Download coordinates to new state from Google Api: lat->'.$coordinates['lat'].', lng->'.$coordinates['lng'].' for region: '.$stateName . ', ' . $country);
        if (null === $coordinates) {
            $coordinates['lat'] = $configProvider->getMapLatitude();
            $coordinates['lng'] = $configProvider->getMapLongitude();
            $isCoordinateDownloadError = true;
            $logger->warning('    Coordinates was not downloaded correctly. In was set to default (country coordinates).');
        }

        $params = [
            'state_source_id'   => $sourceStateId,
            'state_name'        => $stateName,
            'state_short_name'  => $shortStateName,
            'country'           => $country,
            'latitude'          => $coordinates['lat'],
            'longtitude'        => $coordinates['lng'],
            'zoom_level'        => 5,
        ];

        $statesModel->addData($params);

        try {
            $this->getResource()->save($statesModel);

            if (false === $isCoordinateDownloadError) {
                $messageManager->addSuccessMessage(__('You created the new region.'));
                $logger->info('    State was correctly saved.');
            } else {
                $messageManager->addNoticeMessage(__('You created the new region but "Region short name" was set to full name and coordinates was set to default because of Google API error. You can change it manually in Regions table.'));
                $logger->warning('    State was saved with warnings. "Region short name" was set to full name and coordinates was set to default because of Google API error.');
            }

        } catch (\Exception $e) {
            $messageManager->addSuccessMessage(__('Something went wrong while creating the new region.'));
            $logger->error('    Error while adding new region: ' . $e->getMessage());
            $logger->info('Finish adding new region.');
            return null;
        }
        $logger->info('Finish adding new region.');
        return (int) $statesModel->getId();
    }


    /**
     * Delete regions which no store is assigned
     *
     * @return int
     */
    public function deleteUnused()
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \PandaGroup\StoreLocator\Model\StoreLocator $storeLocatorModel */
        $storeLocatorModel = $objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator');

        $storeLocatorCollection = $storeLocatorModel->getCollection();

        $statesCollection = $this->getCollection();

        $qtyOfDeleted = 0;

        foreach ($statesCollection as $state) {
            $stateId = $state->getId();
            $toDelete = true;

            foreach ($storeLocatorCollection as $store) {
                if ($stateId === $store->getData('state_id')) {
                    $toDelete = false;
                }
            }

            if (true === $toDelete) {
                try {
                    $this->getResource()->load($this, $state->getId());
                    $this->getResource()->delete($this);

                    $qtyOfDeleted++;
                } catch (\Exception $e) {
                    return null;
                }
            }
        }

        return $qtyOfDeleted;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getStatesCollection()
    {
        $statesCollection = $this->getCollection();

        return $statesCollection;
    }
}
