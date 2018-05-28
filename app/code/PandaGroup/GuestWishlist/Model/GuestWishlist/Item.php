<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model\GuestWishlist;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use PandaGroup\GuestWishlist\Api\Data\GuestWishlistItemInterface;

class Item extends \Magento\Wishlist\Model\Item implements IdentityInterface, GuestWishlistItemInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'guest_wishlist_item';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'guest_wishlist_item';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'guest_wishlist_item_object';

    /**
     * @var \PandaGroup\GuestWishlist\Model\GuestWishlist\Item\Option
     */
    protected $_wishlistOptFactory;

    /**
     * @var \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option\Collection
     */
    protected $_wishlOptionCollectionFactory;

    /**
     * Item constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrl
     * @param \PandaGroup\GuestWishlist\Model\GuestWishlist\Item\OptionFactory  $guestWishlistOptionFactory
     * @param \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option\CollectionFactory $guestWishlistOptionCollectionFactory
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \PandaGroup\GuestWishlist\Model\GuestWishlist\Item\OptionFactory $guestWishlistOptionFactory,
        \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option\CollectionFactory $guestWishlistOptionCollectionFactory,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productTypeConfig = $productTypeConfig;
        $this->_storeManager = $storeManager;
        $this->_date = $date;
        $this->_catalogUrl = $catalogUrl;
        $this->_wishlistOptFactory = $guestWishlistOptionFactory;
        $this->_wishlOptionCollectionFactory = $guestWishlistOptionCollectionFactory;

        \Magento\Framework\Model\AbstractModel::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->productRepository = $productRepository;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        if ($this->getId()) {
            return [self::CACHE_TAG . '_' . $this->getId()];
        }

        return [];
    }

    /**
     * Object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item::class);
    }
}
