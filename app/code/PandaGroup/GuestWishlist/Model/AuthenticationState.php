<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model;

use Magento\Wishlist\Model\AuthenticationState as WishlistAuthenticationState;
use Magento\Wishlist\Model\AuthenticationStateInterface;

/**
 * Class AuthenticationState
 */
class AuthenticationState extends WishlistAuthenticationState implements AuthenticationStateInterface
{
    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    private $configModel;

    /**
     * AuthenticationState constructor.
     *
     * @param Config $configModel
     */
    public function __construct(
        \PandaGroup\GuestWishlist\Model\Config $configModel
    ) {
        $this->configModel = $configModel;
    }

    /**
     * Is authentication enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->configModel->isGuestWishlistEnabled() === true) {
            return false;
        }

        return parent::isEnabled();
    }
}
