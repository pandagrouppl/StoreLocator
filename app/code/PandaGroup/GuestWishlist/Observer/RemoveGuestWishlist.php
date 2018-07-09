<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Observer;

use \PandaGroup\GuestWishlist\Model\GuestWishlist;

/**
 * Class CustomerLogin
 */
class RemoveGuestWishlist implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Wishlist\Model\Wishlist $wishlist */
        $wishlist = $observer->getObject();

        if (true === $wishlist->hasData(GuestWishlist::GUEST_WISHLIST_TO_DELETE)) {
            $guestWishlist = $wishlist->getData(GuestWishlist::GUEST_WISHLIST_TO_DELETE);

            if ($guestWishlist instanceof GuestWishlist) {
                $guestWishlist->getResource()->delete($guestWishlist);
            }
        }

        return;
    }
}
