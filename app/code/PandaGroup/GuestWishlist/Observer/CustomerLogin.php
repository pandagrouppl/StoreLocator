<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Observer;

/**
 * Class CustomerLogin
 */
class CustomerLogin implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \PandaGroup\GuestWishlist\Helper\Data
     */
    protected $_guestWishlistHelper;

    /**
     * @var \PandaGroup\GuestWishlist\Controller\WishlistProvider
     */
    protected $_wishlistProvider;

    /**
     * @param \PandaGroup\GuestWishlist\Helper\Data $guestWishlistHelper
     * @param \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider
     */
    public function __construct(
        \PandaGroup\GuestWishlist\Helper\Data $guestWishlistHelper,
        \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider
    ) {
        $this->_guestWishlistHelper = $guestWishlistHelper;
        $this->_wishlistProvider = $wishlistProvider;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getData('customer');

        /** @var \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider */
        $wishlistProvider = $this->_wishlistProvider;

        /** @var \PandaGroup\GuestWishlist\Model\GuestWishlist $guestWishlist */
        $guestWishlist = $wishlistProvider->getGuestWishlistFromCookies();

        if (false === $guestWishlist->isObjectNew()) {
            $guestWishlist->moveToWishlist($customer);
        }

        return;
    }
}
