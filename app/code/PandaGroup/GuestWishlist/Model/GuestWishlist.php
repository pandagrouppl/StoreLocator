<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Wishlist\Model\Wishlist;
use PandaGroup\GuestWishlist\Api\Data\GuestWishlistInterface;

/**
 * Class GuestWishlist
 */
class GuestWishlist extends Wishlist implements IdentityInterface, GuestWishlistInterface
{
    const GUEST_WISHLIST_TO_DELETE = 'guest_wishlist_to_delete';

    /**
     * Cache tag
     */
    const CACHE_TAG = 'guest_wishlist';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'guest_wishlist';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'guest_wishlist_object';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = 'wishlist_id';

    /**
     * Guest Wishlist item collection
     *
     * @var \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Collection
     */
    protected $_itemCollection;

    /**
     * Wishlist data
     *
     * @var \Magento\Wishlist\Helper\Data
     */
    protected $_wishlistData;

    /**
     * @var \PandaGroup\GuestWishlist\Model\GuestWishlist\ItemFactory
     */
    protected $_wishlistItemFactory;

    /**
     * @var \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\CollectionFactory
     */
    protected $_wishlistCollectionFactory;

    /**
     * @var \Magento\Wishlist\Model\WishlistFactory
     */
    protected $_wishlistFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Wishlist\Helper\Data $wishlistData
     * @param \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist $resource
     * @param \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Collection $resourceCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \PandaGroup\GuestWishlist\Model\GuestWishlist\ItemFactory $guestWishlistItemFactory
     * @param \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\CollectionFactory $guestWishlistCollectionFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param bool $useCurrentWebsite
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Wishlist\Helper\Data $wishlistData,
        \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist $resource,
        \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Collection $resourceCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \PandaGroup\GuestWishlist\Model\GuestWishlist\ItemFactory $guestWishlistItemFactory,
        \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\CollectionFactory $guestWishlistCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        $useCurrentWebsite = true,
        array $data = []
    ) {
        $this->_useCurrentWebsite = $useCurrentWebsite;
        $this->_catalogProduct = $catalogProduct;
        $this->_wishlistData = $wishlistData;
        $this->_storeManager = $storeManager;
        $this->_date = $date;
        $this->_wishlistItemFactory = $guestWishlistItemFactory;
        $this->_wishlistCollectionFactory = $guestWishlistCollectionFactory;
        $this->_productFactory = $productFactory;
        $this->_wishlistFactory = $wishlistFactory;
        $this->mathRandom = $mathRandom;
        $this->dateTime = $dateTime;

        \Magento\Framework\Model\AbstractModel::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->productRepository = $productRepository;
    }

    /**
     * @param string $cookie
     * @param bool $create
     *
     * @return $this
     */
    public function loadByCookie($cookie, $create = false)
    {
        if ($cookie === null) {
            return $this;
        }

        $this->getResource()->load($this, $cookie, 'cookie');

        if (!$this->getId() && $create) {
            $this->setCookie($cookie);
            $this->setSharingCode($this->_getSharingRandomCode());
            $this->getResource()->save($this);
        }

        return $this;
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
     * Set date of last update for wishlist
     *
     * @return $this
     */
    public function beforeSave()
    {
        parent::beforeSave();

        if (is_null($this->getId()) === true) {
            $this->setCreatedAt($this->_date->gmtDate());
        }

        return $this;
    }

    /**
     * Check customer is owner this wishlist
     *
     * @param int $customerId
     * @return bool
     */
    public function isOwner($customerId)
    {
        //TODO: compare cookie value (temporary return true)
        return true;


        return $customerId == $this->getCustomerId();
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     *
     * @return $this
     */
    public function moveToWishlist(\Magento\Customer\Model\Customer $customer)
    {
        if ($this->getItemsCount() > 0) {
            /** @var \Magento\Wishlist\Model\Wishlist $wishlist */
            $wishlist = $this->_wishlistFactory->create();
            $wishlist->loadByCustomerId($customer->getId(), true);

            /** @var \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Collection $guestWishlistItems */
            $guestWishlistItems = $this->getItemCollection();

            foreach ($guestWishlistItems as $guestWishlistItem) {
                try {
                    /** @var \Magento\Catalog\Model\Product $product */
                    $product = $guestWishlistItem->getProduct();
                    /** @var \Magento\Framework\DataObject $buyRequest */
                    $buyRequest = $guestWishlistItem->getBuyRequest();

                    $result = $wishlist->addNewItem($product, $buyRequest);

                    if (true === is_string($result)) {
                        throw new \Magento\Framework\Exception\LocalizedException(__($result));
                    }
                } catch (\Exception $e) {
                    $this->_logger->error($e->getMessage());
                    continue;
                }
            }

            $wishlist->setData(self::GUEST_WISHLIST_TO_DELETE, $this);
            $wishlist->getResource()->save($wishlist);

            $this->_wishlistData->calculate();
        }

        return $this;
    }
}
