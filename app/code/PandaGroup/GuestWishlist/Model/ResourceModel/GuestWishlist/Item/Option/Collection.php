<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option;

class Collection extends \Magento\Wishlist\Model\ResourceModel\Item\Option\Collection
{
    /**
     * Define resource model for collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'PandaGroup\GuestWishlist\Model\GuestWishlist\Item\Option',
            'PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Option'
        );
    }
}
