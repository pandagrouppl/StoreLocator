<?php

namespace PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\StoreLocator\Model\StoreLocator',
            'PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator'
        );
    }

    /**
     * Define model & resource model
     */
    const YOUR_TABLE = 'tablename';

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_init(
            'PandaGroup\StoreLocator\Model\StoreLocator',
            'PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator'
        );
        parent::__construct(
            $entityFactory, $logger, $fetchStrategy, $eventManager, $connection,
            $resource
        );
        $this->storeManager = $storeManager;
    }
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->joinLeft(
            ['secondTable' => $this->getTable('storelocator_states')],
            'main_table.state_id = secondTable.state_id',
            ['state_source_id', 'state_name']
        );
    }
}