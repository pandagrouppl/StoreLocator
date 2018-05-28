<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index;

use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Helper\Data as WishlistHelper;

/**
 * Class Fromcart
 */
class Fromcart extends \Magento\Wishlist\Controller\Index\Fromcart
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Action\Context $context
     * @param WishlistProviderInterface $wishlistProvider
     * @param WishlistHelper $wishlistHelper
     * @param CheckoutCart $cart
     * @param CartHelper $cartHelper
     * @param Escaper $escaper
     * @param Validator $formKeyValidator
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        WishlistProviderInterface $wishlistProvider,
        WishlistHelper $wishlistHelper,
        CheckoutCart $cart,
        CartHelper $cartHelper,
        Escaper $escaper,
        Validator $formKeyValidator,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context, $wishlistProvider, $wishlistHelper, $cart, $cartHelper, $escaper, $formKeyValidator);
    }

    /**
     * Add cart item to wishlist and remove from cart
     *
     * //TODO: to refactor
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     */
    public function execute()
    {
        if (true === $this->getRequest()->isAjax()) {
            /** @var \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider */
            $wishlistProvider = $this->wishlistProvider;

            /** @var \Magento\Framework\Controller\Result\JsonFactory $resultJson */
            $resultJson = $this->resultJsonFactory->create();

            //TODO
//            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
//            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
//            if (!$this->formKeyValidator->validate($this->getRequest())) {
//                return $resultRedirect->setPath('*/*/');
//            }

            try {
                $wishlist = $this->wishlistProvider->getWishlist();
                if (!$wishlist) {
                    throw new NotFoundException(__('Page not found.'));
                }

                $itemId = (int)$this->getRequest()->getParam('item');
                $item = $this->cart->getQuote()->getItemById($itemId);
                if (!$item) {
                    throw new LocalizedException(
                        __('The requested cart item doesn\'t exist.')
                    );
                }

                $productId = $item->getProductId();
                $buyRequest = $item->getBuyRequest();
                $wishlist->addNewItem($productId, $buyRequest);

                $this->cart->getQuote()->removeItem($itemId);
                $this->cart->save();

                $this->wishlistHelper->calculate();
                $wishlist->getResource()->save($wishlist);

                $message = __(
                    "%1 has been moved to your wishlist.",
                    $this->escaper->escapeHtml($item->getProduct()->getName())
                )->render();
                $resultArray = $wishlistProvider->getResponseArray($message, false);

                return $resultJson->setData($resultArray);
            } catch (LocalizedException $e) {
                $message = $e->getMessage();
            } catch (\Exception $e) {
                $message = __("We can't move the item to the wishlist.")->render();
            }

            $resultArray = $wishlistProvider->getResponseArray($message);

            return $resultJson->setData($resultArray);
        } else {
            return parent::execute();
        }
    }
}
