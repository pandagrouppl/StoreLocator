<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Block;

use Magento\Wishlist\Block\Customer\Wishlist;

class GuestWishlist extends Wishlist
{
    /**
     * Store GuestWishlist Model
     *
     * @var \PandaGroup\GuestWishlist\Model\GuestWishlist
     */
    protected $_wishlist;

    /**
     * GuestWishlist Product Items Collection
     *
     * @var \PandaGroup\GuestWishlist\Model\ResourceModel\GuestWishlist\Item\Collection
     */
    protected $_collection;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Catalog\Helper\Product\ConfigurationPool $helperPool
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Helper\Product\ConfigurationPool $helperPool,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $httpContext,
            $helperPool,
            $currentCustomer,
            $postDataHelper,
            $data
        );
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        //TODO
      //  if ($this->currentCustomer->getCustomerId()) {
            return \Magento\Framework\View\Element\Template::_toHtml();
//        } else {
//            return '';
//        }
    }

    /**
     * Preparing global layout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Wishlist'));
    }

    /**
     * Retrieve Back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getBaseUrl();
    }
}