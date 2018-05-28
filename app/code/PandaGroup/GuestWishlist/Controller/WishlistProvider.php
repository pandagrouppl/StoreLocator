<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Wishlist\Controller\WishlistProviderInterface;

/**
 * Class WishlistProvider
 */
class WishlistProvider extends \Magento\Wishlist\Controller\WishlistProvider implements WishlistProviderInterface
{
    /**
     * @var \PandaGroup\GuestWishlist\Model\GuestWishlist
     */
    private $guestWishlist;

    /**
     * @var \PandaGroup\GuestWishlist\Model\GuestWishlistFactory
     */
    private $guestWishlistFactory;

    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    private $configModel;

    /**
     * @var \PandaGroup\GuestWishlist\Model\Cookie
     */
    private $cookie;

    /**
     * @param \Magento\Wishlist\Model\WishlistFactory $wishlistFactory
     * @param \PandaGroup\GuestWishlist\Model\GuestWishlistFactory $guestWishlistFactory
     * @param \PandaGroup\GuestWishlist\Model\Cookie $cookie
     * @param \PandaGroup\GuestWishlist\Model\Config $configModel
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        \PandaGroup\GuestWishlist\Model\GuestWishlistFactory $guestWishlistFactory,
        \PandaGroup\GuestWishlist\Model\Cookie $cookie,
        \PandaGroup\GuestWishlist\Model\Config $configModel,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        parent::__construct($wishlistFactory, $customerSession, $messageManager, $request);

        $this->guestWishlistFactory = $guestWishlistFactory;
        $this->configModel = $configModel;
        $this->cookie = $cookie;
    }

    /**
     * Retrieve current wishlist
     *
     * @param int|null $wishlistId
     *
     * @return \Magento\Wishlist\Model\Wishlist|\PandaGroup\GuestWishlist\Model\GuestWishlist
     */
    public function getWishlist($wishlistId = null)
    {
        if (false === $this->configModel->isGuestWishlistEnabled()) {
            return parent::getWishlist($wishlistId);
        }

        if (true === $this->customerSession->isLoggedIn()) {
            return parent::getWishlist($wishlistId);
        }

        return $this->getGuestWishlist($wishlistId);
    }

    /**
     * @param null $guestWishlistId
     *
     * @return bool|\PandaGroup\GuestWishlist\Model\GuestWishlist
     */
    public function getGuestWishlist($guestWishlistId = null)
    {
        if (false === is_null($this->guestWishlist)) {
            return $this->guestWishlist;
        }

        try {
            if (false === is_null($guestWishlistId)) {
                $guestWishlistId = $this->request->getParam('guest_wishlist_id');
            }

            $cookie = $this->cookie->getValue();
            if (true === is_null($cookie)) {
                $cookie = $this->cookie->generateAndSetValue();
            }

            $guestWishlist = $this->guestWishlistFactory->create();

            if (!$guestWishlistId && !$cookie) {
                return $guestWishlist;
            }

            if ($guestWishlistId) {
                $guestWishlist->getResource()->load($guestWishlist, $guestWishlistId);
            } elseif ($cookie) {
                $guestWishlist->loadByCookie($cookie, true);
            }

            if ((true === is_null($guestWishlist->getId())) || ($guestWishlist->getCookie() != $cookie)) {
                throw new NoSuchEntityException(__('The requested Wishlist doesn\'t exist.'));
            }
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t create the Wishlist right now.'));
            return false;
        }

        $this->guestWishlist = $guestWishlist;

        return $guestWishlist;
    }

    /**
     * @return \PandaGroup\GuestWishlist\Model\GuestWishlist
     */
    public function getGuestWishlistFromCookies()
    {
        $cookie = $this->cookie->getValue();

        /** @var \PandaGroup\GuestWishlist\Model\GuestWishlist $guestWishlist */
        $guestWishlist = $this->guestWishlistFactory->create();

        return $guestWishlist->loadByCookie($cookie);
    }

    /**
     * @param string    $message
     * @param bool      $error
     *
     * @return array
     */
    public function getResponseArray($message = '', $error = true)
    {
        return [
            'message'   => __("{$message}"),
            'error'     => $error,
        ];
    }
}
