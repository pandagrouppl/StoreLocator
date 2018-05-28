<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index;

use Magento\Catalog\Model\Product\Exception as ProductException;
use Magento\Framework\Controller\ResultFactory;

class Cart extends \Magento\Wishlist\Controller\Index\Cart
{
    /**
     * @var \Magento\Wishlist\Model\Item\OptionFactory|\PandaGroup\GuestWishlist\Model\GuestWishlist\Item\OptionFactory
     */
    protected $optionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \PandaGroup\GuestWishlist\Model\Config
     */
    protected $guestWishlistConfigModel;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor
     * @param \Magento\Wishlist\Model\ItemFactory $wishlistItemFactory
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Wishlist\Model\Item\OptionFactory $wishlistOptionFactory
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Wishlist\Helper\Data $helper
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \PandaGroup\GuestWishlist\Model\GuestWishlist\ItemFactory $guestWishlistItemFactory
     * @param \PandaGroup\GuestWishlist\Model\GuestWishlist\Item\OptionFactory $guestWishlistItemOptionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor,
        \Magento\Wishlist\Model\ItemFactory $wishlistItemFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Wishlist\Model\Item\OptionFactory $wishlistOptionFactory,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Framework\Escaper $escaper,
        \Magento\Wishlist\Helper\Data $helper,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \PandaGroup\GuestWishlist\Model\GuestWishlist\ItemFactory $guestWishlistItemFactory,
        \PandaGroup\GuestWishlist\Model\GuestWishlist\Item\OptionFactory $guestWishlistItemOptionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \PandaGroup\GuestWishlist\Model\Config $guestWishlistConfigModel
    ) {
        parent::__construct(
            $context,
            $wishlistProvider,
            $quantityProcessor,
            $wishlistItemFactory,
            $cart,
            $wishlistOptionFactory,
            $productHelper,
            $escaper,
            $helper,
            $cartHelper,
            $formKeyValidator
        );

        $this->customerSession = $customerSession;
        $this->guestWishlistConfigModel = $guestWishlistConfigModel;

        if ((false === $this->customerSession->isLoggedIn())
            && (true === $this->guestWishlistConfigModel->isGuestWishlistEnabled()))
        {
            $this->itemFactory = $guestWishlistItemFactory;
            $this->optionFactory = $guestWishlistItemOptionFactory;
        } else {
            $this->itemFactory = $wishlistItemFactory;
            $this->optionFactory = $wishlistOptionFactory;
        }
    }

    /**
     * Parent method without changes (resolve problem with $this->optionFactory)
     *
     * @return \Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }

        $itemId = (int)$this->getRequest()->getParam('item');
        /* @var $item \Magento\Wishlist\Model\Item */
        $item = $this->itemFactory->create()->load($itemId);
        if (!$item->getId()) {
            $resultRedirect->setPath('*/*');
            return $resultRedirect;
        }
        $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
        if (!$wishlist) {
            $resultRedirect->setPath('*/*');
            return $resultRedirect;
        }

        // Set qty
        $qty = $this->getRequest()->getParam('qty');
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }
        $qty = $this->quantityProcessor->process($qty);
        if ($qty) {
            $item->setQty($qty);
        }

        $redirectUrl = $this->_url->getUrl('*/*');

        //TODO
//        $configureUrl = $this->_url->getUrl(
//            '*/*/configure/',
//            [
//                'id' => $item->getId(),
//                'product_id' => $item->getProductId(),
//            ]
//        );
        //temporary solution
        $configureUrl = $item->getProductUrl();

        try {
            /** @var \Magento\Wishlist\Model\ResourceModel\Item\Option\Collection $options */
            $options = $this->optionFactory->create()->getCollection()->addItemFilter([$itemId]);
            $item->setOptions($options->getOptionsByItem($itemId));

            $buyRequest = $this->productHelper->addParamsToBuyRequest(
                $this->getRequest()->getParams(),
                ['current_config' => $item->getBuyRequest()]
            );

            $item->mergeBuyRequest($buyRequest);
            $item->addToCart($this->cart, true);
            $this->cart->save()->getQuote()->collectTotals();
            $wishlist->save();

            if (!$this->cart->getQuote()->getHasError()) {
                $message = __(
                    'You added %1 to your shopping cart.',
                    $this->escaper->escapeHtml($item->getProduct()->getName())
                );
                $this->messageManager->addSuccess($message);
            }

            if ($this->cartHelper->getShouldRedirectToCart()) {
                $redirectUrl = $this->cartHelper->getCartUrl();
            } else {
                $refererUrl = $this->_redirect->getRefererUrl();
                if ($refererUrl && $refererUrl != $configureUrl) {
                    $redirectUrl = $refererUrl;
                }
            }
        } catch (ProductException $e) {
            $this->messageManager->addError(__('This product(s) is out of stock.'));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addNotice($e->getMessage());
            $redirectUrl = $configureUrl;
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t add the item to the cart right now.'));
        }

        $this->helper->calculate();

        if ($this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData(['backUrl' => $redirectUrl]);
            return $resultJson;
        }

        $resultRedirect->setUrl($redirectUrl);
        return $resultRedirect;
    }
}
