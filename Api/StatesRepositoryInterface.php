<?php

namespace PandaGroup\StoreLocator\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface StatesRepositoryInterface
 *
 * @package PandaGroup\StoreLocator\Api
 */
interface StatesRepositoryInterface
{
    /**
     * Save State
     *
     * @param Data\StatesInterface $states
     * @return \PandaGroup\StoreLocator\Api\Data\StatesInterface
     */
    public function save(\PandaGroup\StoreLocator\Api\Data\StatesInterface $states);

    /**
     * Retrieve State
     *
     * @param $stateId
     * @return \PandaGroup\StoreLocator\Api\Data\StatesInterface
     */
    public function getById($stateId);

    /**
     * Retrieve entity matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \PandaGroup\StoreLocator\Api\Data\StatesInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete State
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StatesInterface $states
     * @return bool
     */
    public function delete(\PandaGroup\StoreLocator\Api\Data\StatesInterface $states);

    /**
     * Delete entity by ID.
     *
     * @param int $stateId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($stateId);

}
