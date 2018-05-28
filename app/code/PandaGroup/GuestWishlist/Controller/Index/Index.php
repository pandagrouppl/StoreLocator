<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index;

use \Magento\Framework\App\Action\Action;
use \PandaGroup\GuestWishlist\Controller\IndexInterface;

class Index extends Action implements IndexInterface
{
    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    private $configModel;

    /**
     * @var \PandaGroup\GuestWishlist\Controller\WishlistProvider
     */
    private $wishlistProvider;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \PandaGroup\GuestWishlist\Model\Config $configModel
     * @param \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \PandaGroup\GuestWishlist\Model\Config $configModel,
        \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider
    ) {
        $this->configModel = $configModel;
        $this->wishlistProvider = $wishlistProvider;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
    }
}
