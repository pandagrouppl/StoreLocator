<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index\Plugin;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Wishlist\Model\AuthenticationStateInterface;
use PandaGroup\GuestWishlist\Model\Config as GuestWishlistConfigModel;

class Authentication extends \Magento\Wishlist\Controller\Index\Plugin
{
    /**
     * @var GuestWishlistConfigModel
     */
    private $guestWishlistConfigModel;

    /**
     * @param CustomerSession $customerSession
     * @param AuthenticationStateInterface $authenticationState
     * @param ScopeConfigInterface $config
     * @param RedirectInterface $redirector
     * @param GuestWishlistConfigModel $guestWishlistConfigModel
     */
    public function __construct(
        CustomerSession $customerSession,
        AuthenticationStateInterface $authenticationState,
        ScopeConfigInterface $config,
        RedirectInterface $redirector,
        GuestWishlistConfigModel $guestWishlistConfigModel
    ) {
        $this->guestWishlistConfigModel = $guestWishlistConfigModel;

        parent::__construct(
            $customerSession,
            $authenticationState,
            $config,
            $redirector
        );
    }

    /**
     * @param ActionInterface $subject
     * @param RequestInterface $request
     *
     * @return void
     * @throws NotFoundException
     */
    public function beforeDispatch(ActionInterface $subject, RequestInterface $request)
    {
        if ($this->guestWishlistConfigModel->isGuestWishlistEnabled() === false) {
            parent::beforeDispatch($subject, $request);
        }
    }
}
