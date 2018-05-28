<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model\GuestWishlist\Item;

use Magento\Framework\DataObject\IdentityInterface;
use PandaGroup\GuestWishlist\Api\Data\GuestWishlistItemOptionInterface;
use Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface;

class Option extends \Magento\Wishlist\Model\Item\Option
    implements IdentityInterface, GuestWishlistItemOptionInterface, OptionInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'guest_wishlist_item_option';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'guest_wishlist_item_option';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'guest_wishlist_item_option_object';

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
        $this->_init(\PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option::class);
    }
}
