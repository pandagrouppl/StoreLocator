<?php

namespace PandaGroup\StoreLocator\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class StatesRepository
 *
 * @package PandaGroup\StoreLocator\Model
 */
class StatesRepository implements \PandaGroup\StoreLocator\Api\StatesRepositoryInterface
{
    protected $resource = null;

    protected $statesFactory = null;

    protected $collectionFactory = null;

    public function __construct(
        \PandaGroup\StoreLocator\Model\ResourceModel\States $resource,
        \PandaGroup\StoreLocator\Model\StatesFactory $statesFactory,
        \PandaGroup\StoreLocator\Model\ResourceModel\States\CollectionFactory $collectionFactory)
    {
        $this->resource = $resource;
        $this->statesFactory = $statesFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Save State
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StatesInterface $state
     * @return \PandaGroup\StoreLocator\Api\Data\StatesInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\PandaGroup\StoreLocator\Api\Data\StatesInterface $state)
    {
        try {
            $this->resource->save($state);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }

        return $state;
    }

    /**
     * Retrieve State
     *
     * @param $stateId
     * @return \PandaGroup\StoreLocator\Model\States
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($stateId)
    {
        $state = $this->statesFactory->create();
        $this->resource->load($state, $stateId);
        if (!$state->getId()) {
            throw new NoSuchEntityException(__('Region with id "%1" does not exist.', $stateId));
        }

        return $state;
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
     * Delete state
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StatesInterface $state
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\PandaGroup\StoreLocator\Api\Data\StatesInterface $state)
    {
        try {
            $this->resource->delete($state);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete entity by ID.
     *
     * @param int $stateId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($stateId)
    {
        return $this->delete($this->getById($stateId));
    }


}

