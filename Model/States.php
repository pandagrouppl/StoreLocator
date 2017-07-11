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

        // Check if already exist corrected region
        $statesCollection = $this->getCollection();

        $statesCollection
            ->addFilter('country', strtoupper($country))
            ->addFilter('state_source_id', $sourceStateId);

        $stateId = $statesCollection->getFirstItem()->getId();
        if (isset($stateId)) {
            return $stateId;
        }

        // If there aren't created earlier -> create new

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
        $statesModel = $objectManager->create('PandaGroup\StoreLocator\Model\States');

        /** @var \PandaGroup\StoreLocator\Model\GoogleApi $googleApiModel */
        $googleApiModel = $objectManager->create('PandaGroup\StoreLocator\Model\GoogleApi');

        /** @var \Magento\Framework\Message\Manager $messageManager */
        $messageManager = $objectManager->create('Magento\Framework\Message\Manager');

//        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
//        $statesModel = $this->create();

        if (true === empty($shortStateName)) {
            $shortStateName = $googleApiModel->getRegionShortName($stateName);

            if (null === $shortStateName) {
                $shortStateName = $stateName;
            }
        }

        $coordinates = $googleApiModel->getCoordinatesByAddress($stateName . ', ' . $country);

        $params = [
            'state_source_id'   => $sourceStateId,
            'state_name'        => $stateName,
            'state_short_name'  => $shortStateName,
            'country'           => $country,
            'latitude'          => $coordinates['lat'],
            'longtitude'        => $coordinates['lat'],
            'zoom_level'        => 5,
        ];

        $statesModel->addData($params);

        try {
            $this->getResource()->save($statesModel);
            $messageManager->addSuccessMessage(__('You created the new region.'));
        } catch (\Exception $e) {
            $messageManager->addSuccessMessage(__('Something went wrong while creating the new region.'));
            return null;
        }
        return (int) $statesModel->getId();
    }

//    protected function addOrSaveStateFromRegionsData($stateIdFromStatesDataSource, $data)
//    {
//        $stateExists = $this->getCollection()->addFilter('state_source_id', $stateIdFromStatesDataSource)->getData();
//        if (true === empty($stateExists)) {
//
//            $state = $this->create();
//
//            $params = [
//                'state_source_id'   => $stateIdFromStatesDataSource,
//                'state_name'        => $this->regionsData->load($stateIdFromStatesDataSource)->getData('name'),
//                'state_short_name'  => $this->regionsData->load($stateIdFromStatesDataSource)->getData('name'),
//                'country'           => $data['country'],
//            ];
//
//            $state->addData($params);
//
//            try {
//                $state->save();
//                $this->messageManager->addSuccessMessage(__('You created the new region.'));
//            } catch (\Exception $e) {
//                $this->messageManager->addSuccessMessage(__('Something went wrong while creating the new region.'));
//            }
//            return (int) $state->getId();
//
//        } else {
//            $state = $this->states->load($stateExists[0]['state_id']);
//            return (int) $state->getId();
//        }
//
//    }


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
}
