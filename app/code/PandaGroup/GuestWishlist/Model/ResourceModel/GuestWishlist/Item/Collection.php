<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item;

class Collection extends \Magento\Wishlist\Model\ResourceModel\Item\Collection
{
    /**
     * @var \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option\CollectionFactory
     */
    protected $_optionCollectionFactory;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Sales\Helper\Admin $adminhtmlSales
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Wishlist\Model\Config $wishlistConfig
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Framework\App\ResourceConnection $coreResource
     * @param \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option\CollectionFactory $optionCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\ConfigFactory $catalogConfFactory
     * @param \Magento\Catalog\Model\Entity\AttributeFactory $catalogAttrFactory
     * @param \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item $resource
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Sales\Helper\Admin $adminhtmlSales,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Wishlist\Model\Config $wishlistConfig, //TODO
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Framework\App\ResourceConnection $coreResource,
        \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option\CollectionFactory $optionCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\ConfigFactory $catalogConfFactory,
        \Magento\Catalog\Model\Entity\AttributeFactory $catalogAttrFactory,
        \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item $resource,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        $this->stockConfiguration = $stockConfiguration;
        $this->_adminhtmlSales = $adminhtmlSales;
        $this->_storeManager = $storeManager;
        $this->_date = $date;
        $this->_wishlistConfig = $wishlistConfig;
        $this->_productVisibility = $productVisibility;
        $this->_coreResource = $coreResource;
        $this->_optionCollectionFactory = $optionCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogConfFactory = $catalogConfFactory;
        $this->_catalogAttrFactory = $catalogAttrFactory;
        $this->_appState = $appState;

        \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            'PandaGroup\GuestWishlist\Model\GuestWishlist\Item',
            'PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item'
        );
    }
}
