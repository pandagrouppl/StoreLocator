<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist;

class Item extends \Magento\Wishlist\Model\ResourceModel\Item
{
    /**
     * Set main entity table name and primary key field name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('guest_wishlist_item','wishlist_item_id');
    }
}
