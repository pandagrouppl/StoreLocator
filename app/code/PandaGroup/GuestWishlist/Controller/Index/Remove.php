<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Controller\ResultFactory;

class Remove extends \Magento\Wishlist\Controller\Index\Remove
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    protected $guestWishlistConfigModel;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Customer\Model\Session $customerSession,
        \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->customerSession = $customerSession;
        $this->guestWishlistConfigModel = $guestWishlistConfigModel;
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context, $wishlistProvider, $formKeyValidator);
    }

    /**
     * Remove item from Wishlist or GuestWishlist
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     */
    public function execute()
    {
        if ((false === $this->customerSession->isLoggedIn())
            && (true === $this->guestWishlistConfigModel->isGuestWishlistEnabled()))
        {
            return $this->_removeItem('PandaGroup\GuestWishlist\Model\GuestWishlist\Item');
        }

        return $this->_removeItem('Magento\Wishlist\Model\Item');
    }

    /**
     * @param string $type
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     */
    private function _removeItem($type)
    {
        if (true === $this->getRequest()->isAjax()) {
            /** @var \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider */
            $wishlistProvider = $this->wishlistProvider;

            /** @var \Magento\Framework\Controller\Result\JsonFactory $resultJson */
            $resultJson = $this->resultJsonFactory->create();

            $id = (int)$this->getRequest()->getParam('item');
            /** @var \PandaGroup\GuestWishlist\Model\GuestWishlist\Item|\Magento\Wishlist\Model\Item $item */
            $item = $this->_objectManager->create($type)->load($id);

            if (!$item->getId()) {
                $resultArray = $wishlistProvider->getResponseArray("We can't find product");

                return $resultJson->setData($resultArray);
            }

            /** @var \PandaGroup\GuestWishlist\Model\GuestWishlist|\Magento\Wishlist\Model\Wishlist $wishlist */
            $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());

            if (!$wishlist) {
                $resultArray = $wishlistProvider->getResponseArray("We can't find your Wishlist");

                return $resultJson->setData($resultArray);
            }

            try {
                $item->getResource()->delete($item);
                $wishlist->getResource()->save($wishlist);

                $message = __('Product has been removed');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $message = __(
                    'We can\'t delete the item from Wishlist right now because of an error: %1.',
                    $e->getMessage()
                )->render();
            } catch (\Exception $e) {
                $message = __('We can\'t delete the item from the Wishlist right now.')->render();
            }

            /** @var \PandaGroup\GuestWishlist\Helper\Data $helper */
            $helper = $this->_objectManager->get('PandaGroup\GuestWishlist\Helper\Data');
            $helper->calculate();

            $resultArray = $wishlistProvider->getResponseArray($message, false);
            $resultArray['add_params'] = json_decode($helper->getAddParams($item), true);

            return $resultJson->setData($resultArray);
        } else {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            if (false === $this->formKeyValidator->validate($this->getRequest())) {
                return $resultRedirect->setPath('*/*/');
            }

            $id = (int)$this->getRequest()->getParam('item');
            //[EDIT]
            $item = $this->_objectManager->create($type)->load($id);

            if (!$item->getId()) {
                throw new NotFoundException(__('Page not found.'));
            }

            $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());

            if (!$wishlist) {
                throw new NotFoundException(__('Page not found.'));
            }

            try {
                $item->delete();
                $wishlist->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError(
                    __('We can\'t delete the item from Wishlist right now because of an error: %1.', $e->getMessage())
                );
            } catch (\Exception $e) {
                $this->messageManager->addError(__('We can\'t delete the item from the Wishlist right now.'));
            }

            $this->_objectManager->get('Magento\Wishlist\Helper\Data')->calculate();
            $request = $this->getRequest();
            $refererUrl = (string)$request->getServer('HTTP_REFERER');
            $url = (string)$request->getParam(\Magento\Framework\App\Response\RedirectInterface::PARAM_NAME_REFERER_URL);

            if ($url) {
                $refererUrl = $url;
            }

            if ($request->getParam(\Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED) && $refererUrl) {
                $redirectUrl = $refererUrl;
            } else {
                $redirectUrl = $this->_redirect->getRedirectUrl($this->_url->getUrl('*/*'));
            }

            $resultRedirect->setUrl($redirectUrl);

            return $resultRedirect;
        }
    }
}
