<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
namespace Amasty\ShopbyBrand\Model\ResourceModel\Slider\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\Search\AggregationInterface;
use Amasty\Shopby\Model\ResourceModel\OptionSetting\Collection as BrandCollection;
use Amasty\Shopby\Api\Data\OptionSettingInterface;
use Magento\Store\Model\Store;

/**
 * Class Collection
 * Collection for displaying grid of slider brands
 */

/**
 * Class Collection
 * Collection for displaying grid of slider brands
 * 
 * @package Amasty\ShopbyBrand\Model\ResourceModel\Slider\Grid
 * @author Evgeni Obukhovsky
 */
class Collection extends BrandCollection implements SearchResultInterface
{
    /**
     * @var AggregationInterface
     */ 
    protected $_aggregations;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Collection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $mainTable
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $eventPrefix
     * @param $eventObject
     * @param $resourceModel
     * @param string $model
     * @param null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_scopeConfig = $scopeConfig;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
        $this->_prepareCollection();
    }

    /**
     * add current attribute and default store_id filters
     * @return $this
     */
    protected function _prepareCollection()
    {
        $attrCode   = $this->_scopeConfig->getValue('amshopby_brand/general/attribute_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $filterCode = \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . $attrCode;
        $this->addFieldToFilter(OptionSettingInterface::FILTER_CODE, $filterCode);
        $this->addFieldToFilter(OptionSettingInterface::STORE_ID, Store::DEFAULT_STORE_ID);
        $this->getSelect()->joinInner(
            ['amshopbybrand_option' => $this->getTable('eav_attribute_option')],
            'main_table.value = amshopbybrand_option.option_id',
            []
        );
        return $this;
    }

    /**
     * add second order by title
     *
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function setOrder($field, $direction = self::SORT_ORDER_DESC)
    {
        $titleField = \Amasty\Shopby\Api\Data\OptionSettingInterface::TITLE;
        if ($field != $titleField) {
            parent::setOrder($field, $direction);
            $field = $titleField;
            $direction = 'ASC';
        }
        return parent::setOrder($field, $direction);

    }

    /**
     * Remove default store_id == 0 filer.
     * @return $this
     * @throws \Zend_Db_Select_Exception
     */
    protected function _removeStoreFilter()
    {
        $conditions = $this->getSelect()->getPart('where');
        foreach ($conditions as $index => $cond) {
            if (strpos($cond, OptionSettingInterface::STORE_ID) !== false) {
                unset($conditions[$index]);
            }
        }
        $this->getSelect()->setPart('where', $conditions);
        return $this;
    }

    /**
     * Remove brands with store_id = 0 from selection, which have values in the current store view.
     * @param int $requestedStoreId
     * @return $this
     */
    protected function _removeDefault($requestedStoreId)
    {
        $storeField = OptionSettingInterface::STORE_ID;
        $query = 'SELECT `value`,'
            . " MAX(IF(`$storeField` = $requestedStoreId,$requestedStoreId,"
            . Store::DEFAULT_STORE_ID . '))'
            . ' FROM `' . $this->getMainTable() . '`'
            . ' GROUP BY `value`';
        $this->getSelect()->where("(`value`, `$storeField`) IN ($query)");
        return $this;
    }

    /**
     * Correctly process store_id filter
     * @param array|string $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == OptionSettingInterface::STORE_ID && is_array($condition)) {
            $requestedStoreId = intVal(array_pop($condition));
            $this->_removeStoreFilter();
            $this->_removeDefault($requestedStoreId);
            $condition = [$requestedStoreId,  Store::DEFAULT_STORE_ID];
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->_aggregations;
    }

    /**
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->_aggregations = $aggregations;
    }


    /**
     * Retrieve all ids for collection
     * Backward compatibility with EAV collection
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
}
