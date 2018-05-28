<?php

namespace PandaGroup\Checkout\Controller\Size;

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
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\Product $product,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->cart = $cart;
        $this->product = $product;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        $valueSizeId = (int)$this->getRequest()->getParam('size');
        $deleteItemId = (int)$this->getRequest()->getParam('delete');
        $attrSizeId = (int)$this->getRequest()->getParam('sizeid');
        $qty = (int)$this->getRequest()->getParam('qty');

        try {
            /** @var $product \Magento\Catalog\Model\ProductRepository */
            $product = $this->productRepository->getById($productId);

            $sizeOption = array();
            $sizeOption[$attrSizeId] = $valueSizeId;

            $params = array(
                'qty'   => $qty,
                'super_attribute' => $sizeOption
            );

            if ($product) {
                $this->cart->addProduct($product, $params);
                $this->cart->removeItem($deleteItemId);
                $this->cart->save();
            }

            $this->messageManager->addSuccessMessage(__('Item size changed successfully.'));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {

            $this->messageManager->addExceptionMessage($e, __('We can\'t add this item to your shopping cart right now.'));

            $message = __('PandaGroup\Checkout\Controller\Size\Change: ' . $e->getMessage());
            $this->logger->debug($message);

        } catch (\Exception $e) {

            $this->messageManager->addExceptionMessage($e, __('We can\'t add this item to your shopping cart right now.'));

            $message = __('PandaGroup\Checkout\Controller\Size\Change: ' . $e->getMessage());
            $this->logger->debug($message);
        }

        $this->_redirect('checkout/cart/index');
        return;
    }

}
