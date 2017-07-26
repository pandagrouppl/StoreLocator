<?php

namespace PandaGroup\StoreLocator\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class StoreLocatorRepository
 *
 * @package PandaGroup\StoreLocator\Model
 */
class StoreLocatorRepository implements \PandaGroup\StoreLocator\Api\StoreLocatorRepositoryInterface
{
    protected $resource = null;

    protected $storeLocatorFactory = null;

    protected $collectionFactory = null;

    /**
     * StoreLocatorRepository constructor.
     *
     * @param \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator $resource
     * @param \PandaGroup\StoreLocator\Model\StoreLocatorFactory $storeLocatorFactory
     * @param \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory $collectionFactory
     */
    public function __construct(
        \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator $resource,
        \PandaGroup\StoreLocator\Model\StoreLocatorFactory $storeLocatorFactory,
        \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory $collectionFactory)
    {
        $this->resource = $resource;
        $this->storeLocatorFactory = $storeLocatorFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Save Store
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $store
     * @return \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $store)
    {
        try {
            $this->resource->save($store);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }

        return $store;
    }

    /**
     * Retrieve Store
     *
     * @param $storeId
     * @return \PandaGroup\StoreLocator\Model\StoreLocator
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($storeId)
    {
        $store = $this->storeLocatorFactory->create();
        $this->resource->load($store, $storeId);
        if (!$store->getId()) {
            throw new NoSuchEntityException(__('Store with id "%1" does not exist.', $storeId));
        }

        return $store;
    }

    /**
     * Retrieve entity matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PandaGroup\StoreLocator\Api\Data\StatesInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                     $sortOrder->getField(),
                     ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        return $collection->getItems();
    }

    /**
     * Delete store
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $store
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $store)
    {
        try {
            $this->resource->delete($store);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete entity by ID.
     *
     * @param int $storeId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($storeId)
    {
        return $this->delete($this->getById($storeId));
    }


}

