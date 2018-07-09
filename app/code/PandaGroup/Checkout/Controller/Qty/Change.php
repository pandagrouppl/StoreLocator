<?php

namespace PandaGroup\Checkout\Controller\Qty;

class Change extends \Magento\Framework\App\Action\Action
{
    /**
     * @var  \Magento\Framework\View\Result\Page
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->cart = $cart;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        $qty = (int)$this->getRequest()->getParam('qty');

        try {

            if (false === empty($productId)) {

                $this->cart->getQuote()->getItemById($productId)->setQty($qty);
                $this->cart->saveQuote();

//                $errors = $this->cart->getQuote()->getMessages();
//                if (false === isset($errors['qty'])) {
//                    /** @var \Magento\Framework\Message\Error $errors */
//                    $errors = $errors['qty'];
//                    if ('error' !== $errors->getType()) {
//                        $this->messageManager->addSuccessMessage(__('Item quantity successfully changed.'));
//                    }
//                }

            } else {
                $this->messageManager->addErrorMessage(__('Item quantity cannot be changed.'));
            }

            $this->_redirect('checkout/cart/index');
            return;

        } catch (\Magento\Framework\Exception\LocalizedException $e) {

            $this->messageManager->addExceptionMessage($e, __($e->getMessage()));

            $message = __('PandaGroup\Checkout\Controller\Size\Change: ' . $e->getMessage());
            $this->logger->debug($message);

        } catch (\Exception $e) {

            $this->messageManager->addExceptionMessage($e, __($e->getMessage()));

            $message = __('PandaGroup\Checkout\Controller\Size\Change: ' . $e->getMessage());
            $this->logger->debug($message);
        }

        $this->_redirect('checkout/cart/index');
        return;
    }

}
