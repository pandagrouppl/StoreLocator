<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\ResultFactory;

class Add extends \Magento\Wishlist\Controller\Index\Add
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param ProductRepositoryInterface $productRepository
     * @param Validator $formKeyValidator
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        ProductRepositoryInterface $productRepository,
        Validator $formKeyValidator,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context, $customerSession, $wishlistProvider, $productRepository, $formKeyValidator);
    }

    public function execute()
    {
        if (true === $this->getRequest()->isAjax()) {
            /** @var \PandaGroup\GuestWishlist\Controller\WishlistProvider $wishlistProvider */
            $wishlistProvider = $this->wishlistProvider;

            /** @var \Magento\Framework\Controller\Result\JsonFactory $resultJson */
            $resultJson = $this->resultJsonFactory->create();

            /** @var array $requestParams */
            $requestParams = $this->getRequest()->getParams();

            //TODO: missing formkey
            /*if (false === $this->formKeyValidator->validate($this->getRequest())) {
                $resultArray = $wishlistProvider->getResponseArray('Invalid formkey. Please reload page.');

                return $result->setData($resultArray);
            }*/

            /** @var null|int $productId */
            $productId = isset($requestParams['product']) ? (int)$requestParams['product'] : null;

            try {
                $product = $this->productRepository->getById($productId);
            } catch (NoSuchEntityException $e) {
                $product = null;
            }

            if ((null === $product) || (false == $product->isVisibleInCatalog())) {
                $resultArray = $wishlistProvider->getResponseArray("We can't specify a product.");

                return $resultJson->setData($resultArray);
            }

            /** @var \PandaGroup\GuestWishlist\Model\GuestWishlist|\Magento\Wishlist\Model\Wishlist $wishlist */
            $wishlist = $this->wishlistProvider->getWishlist();

            try {
                /** @var \Magento\Framework\DataObject $buyRequest */
                $buyRequest = new \Magento\Framework\DataObject($requestParams);

                /** @var \PandaGroup\GuestWishlist\Model\GuestWishlist\Item|\Magento\Wishlist\Model\Item\ $result */
                $result = $wishlist->addNewItem($product, $buyRequest);

                if (true === is_string($result)) {
                    throw new \Magento\Framework\Exception\LocalizedException(__($result));
                }

                $wishlist->getResource()->save($wishlist);

                $this->_eventManager->dispatch(
                    'wishlist_add_product',
                    ['wishlist' => $wishlist, 'product' => $product, 'item' => $result]
                );

                $this->_objectManager->get('Magento\Wishlist\Helper\Data')->calculate();

                $message = __('%1 has been added to your Wishlist.', $product->getData('name'))->render();
                $resultArray = $wishlistProvider->getResponseArray($message, false);

                return $resultJson->setData($resultArray);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $message = __("We can't add the item to Wishlist right now: %1.", $e->getMessage())->render();
            } catch (\Exception $e) {
                $message = __("We can't add the item to Wishlist right now.")->render();
            }

            $resultArray = $wishlistProvider->getResponseArray($message);

            return $resultJson->setData($resultArray);
        } else {
            return parent::execute();
        }
    }
}
