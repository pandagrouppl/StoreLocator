<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 */
class Config
{
    const XML_PATH_WISHLIST_GENERAL_ACTIVE = 'wishlist/general/active';
    const XML_PATH_GUEST_WISHLIST_GENERAL_ACTIVE = 'guest_wishlist/general/active';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isWishlistEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_WISHLIST_GENERAL_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param bool $checkWishlist
     *
     * @return bool
     */
    public function isGuestWishlistEnabled($checkWishlist = true)
    {
        $guestWishlistEnabled = $this->scopeConfig->isSetFlag(
            self::XML_PATH_GUEST_WISHLIST_GENERAL_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );

        if (($checkWishlist === true) && ($this->isWishlistEnabled() === true)) {
            return $guestWishlistEnabled;
        }

        return false;
    }
}
