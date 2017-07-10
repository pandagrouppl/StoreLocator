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

        foreach ($statesCollection as $state) {
            return $state->getId();
        }

        // If there aren't created earlier -> create new

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var $statesModel \PandaGroup\StoreLocator\Model\States */
        $statesModel    = $objectManager->create('\PandaGroup\StoreLocator\Model\States');

//        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
//        $statesModel = $this->create();

        if (true === empty($shortStateName)) {
            $shortStateName = $stateName;
        }

        $params = [
            'state_source_id'   => $sourceStateId,
            'state_name'        => $stateName,
            'state_short_name'  => $shortStateName,
            'country'           => $country,
        ];

        $statesModel->addData($params);

        try {
            $statesModel->save();
        } catch (\Exception $e) {
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