<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index\Plugin;

class Add
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    protected $guestWishlistConfigModel;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
     */
    public function __construct(
        \Magento\Customer\Model\Session  $customerSession,
        \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
    ) {
        $this->customerSession = $customerSession;
        $this->guestWishlistConfigModel = $guestWishlistConfigModel;
    }

    /**
     * @param \Magento\Wishlist\Controller\Index\Add $subject
     * @param \Closure $proceed
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function aroundExecute(\Magento\Wishlist\Controller\Index\Add $subject, \Closure $proceed)
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $proceed();

        if ((false === $this->customerSession->isLoggedIn())
            && (true === $this->guestWishlistConfigModel->isGuestWishlistEnabled()))
        {
            //TODO: quick fix (ajax)
          //  $resultRedirect->setPath('guest-wishlist');
        }

        return $resultRedirect;
    }
}
