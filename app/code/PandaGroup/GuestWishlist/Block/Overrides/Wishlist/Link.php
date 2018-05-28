<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Block\Overrides\Wishlist;

class Link extends \Magento\Wishlist\Block\Link
{
    /**
     * @var \PandaGroup\GuestWishlist\Helper\Data
     */
    protected $_wishlistHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PandaGroup\GuestWishlist\Helper\Data $guestWishlistHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PandaGroup\GuestWishlist\Helper\Data $guestWishlistHelper,
        array $data = []
    ) {
        parent::__construct($context, $guestWishlistHelper, $data);
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->_wishlistHelper->getListUrl(null);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('');
    }
}
