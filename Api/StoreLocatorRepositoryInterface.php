<?php

namespace PandaGroup\StoreLocator\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface StoreLocatorRepositoryInterface
 *
 * @package PandaGroup\StoreLocator\Api
 */
interface StoreLocatorRepositoryInterface
{
    /**
     * Save Store
     *
     * @param Data\StoreLocatorInterface $stores
     * @return \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function save(\PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $stores);

    /**
     * Retrieve Store
     *
     * @param $stateId
     * @return \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function getById($stateId);

    /**
     * Retrieve entity matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Store
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $stores
     * @return bool
     */
    public function delete(\PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $stores);

    /**
     * Delete entity by ID.
     *
     * @param int $storeId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($storeId);

}
