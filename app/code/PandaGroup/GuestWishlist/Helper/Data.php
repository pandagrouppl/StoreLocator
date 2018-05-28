<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Helper;

/**
 * Class Data
 */
class Data extends \Magento\Wishlist\Helper\Data
{
    /**
     * TODO:: move to config
     */
    const USE_AJAX = true;

    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    protected $_guestWishlistConfigModel;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Model\WishlistFactory $wishlistFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Customer\Helper\View $customerViewHelper
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Customer\Helper\View $customerViewHelper,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
    ) {
        parent::__construct(
            $context,
            $coreRegistry,
            $customerSession,
            $wishlistFactory,
            $storeManager,
            $postDataHelper,
            $customerViewHelper,
            $wishlistProvider,
            $productRepository
        );

        $this->_guestWishlistConfigModel = $guestWishlistConfigModel;
    }

    /**
     * Calculate count of wishlist items and put value to customer session.
     * Method called after wishlist modifications and trigger 'wishlist_items_renewed' event.
     * Depends from configuration.
     *
     * @return $this
     */
    public function calculate()
    {
        $count = 0;

        if (true === $this->_canCalculate()) {
            $collection = $this->getWishlistItemCollection()->setInStockFilter(true);

            if ($this->_getConfigValue(self::XML_PATH_WISHLIST_LINK_USE_QTY)) {
                $count = $collection->getItemsQty();
            } else {
                $count = $collection->getSize();
            }

            $this->_customerSession->setWishlistDisplayType(
                $this->_getConfigValue(self::XML_PATH_WISHLIST_LINK_USE_QTY)
            );


            $this->_customerSession->setDisplayOutOfStockProducts(
                $this->_getConfigValue(self::XML_PATH_CATALOGINVENTORY_SHOW_OUT_OF_STOCK)
            );
        }

        $this->_customerSession->setWishlistItemCount($count);
        $this->_eventManager->dispatch('wishlist_items_renewed');

        return $this;
    }

    /**
     * Retrieve params for removing item from wishlist
     *
     * @param \Magento\Catalog\Model\Product|\Magento\Wishlist\Model\Item $item
     * @param bool $addReferer
     *
     * @return string
     */
    public function getRemoveParams($item, $addReferer = false)
    {
        $url = $this->_getUrl('wishlist/index/remove');
        $params = ['item' => $item->getWishlistItemId()];

        if (true === $addReferer) {
            $params = $this->addRefererToParams($params);
        }

        if (true === $this->useAjax()) {
            $params['ajax'] = 1;
        }

        return $this->_postDataHelper->getPostData($url, $params);
    }

    /**
     * Retrieve params for adding product to wishlist
     *
     * @param \Magento\Catalog\Model\Product|\Magento\Wishlist\Model\Item $item
     * @param array $params
     *
     * @return string
     */
    public function getAddParams($item, array $params = [])
    {
        if (true === $this->useAjax()) {
            $params['ajax'] = 1;
        }

        return parent::getAddParams($item, $params);
    }

    /**
     * Retrieve params for adding product to wishlist
     *
     * @param int $itemId
     *
     * @return string
     */
    public function getMoveFromCartParams($itemId)
    {
        $url = $this->_getUrl('wishlist/index/fromcart');
        $params = ['item' => $itemId];

//        if (true === $this->useAjax()) {
//            $params['ajax'] = 1;
//        }

        return $this->_postDataHelper->getPostData($url, $params);
    }

    /**
     * Check is allow wishlist module
     * //TODO to rebuild (check guest wishlist)
     * @return bool
     */
    public function isAllow()
    {
        if ($this->_moduleManager->isOutputEnabled($this->_getModuleName()) && $this->scopeConfig->getValue(
                'wishlist/general/active',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check is allow wishlist action in shopping cart
     *
     * @return bool
     */
    public function isAllowInCart()
    {
        return $this->isAllow();
    }

    /**
     * Retrieve wishlist/guest-wishlist url
     *
     * @param int|null $wishlistId
     *
     * @return string
     */
    public function getListUrl($wishlistId = null)
    {
        if ((false === $this->_customerSession->isLoggedIn())
            && (true === $this->_guestWishlistConfigModel->isGuestWishlistEnabled()))
        {
            return $this->_getUrl('guest-wishlist');
        }

        return parent::getListUrl($wishlistId);
    }

    /**
     * @return bool
     */
    public function useAjax()
    {
        return self::USE_AJAX;
    }

    /**
     * @return bool
     */
    private function _canCalculate()
    {
        if (true === $this->_guestWishlistConfigModel->isGuestWishlistEnabled()) {
            return true;
        }

        if (null !== $this->getCustomer()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $path
     * @param string $scopeType
     *
     * @return mixed
     */
    private function _getConfigValue($path, $scopeType = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue($path, $scopeType);
    }
}
