<?php

namespace PandaGroup\EmailTester\Model;

class EmailTester extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\EmailTester\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\EmailTester\Model\Config  */
    protected $config;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;

    /** @var \Magento\Framework\Mail\Template\TransportBuilder  */
    protected $transportBuilder;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;

    /** @var \Magento\Email\Model\Template  */
    protected $template;

    /** @var \Magento\Sales\Model\Order\Address\Renderer  */
    protected $addressRenderer;

    /** @var \Magento\Payment\Helper\Data  */
    protected $paymentHelper;

    /** @var \Magento\Sales\Model\Order\Email\Container\OrderIdentity  */
    protected $identityContainer;


    /**
     * EmailTester constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param Config $config
     * @param \PandaGroup\EmailTester\Logger\Logger $logger
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Email\Model\Template $template
     * @param \Magento\Sales\Model\Order\Address\Renderer $addressRenderer
     * @param \Magento\Payment\Helper\Data $paymentHelper
     * @param \Magento\Sales\Model\Order\Email\Container\OrderIdentity $identityContainer
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \PandaGroup\EmailTester\Model\Config $config,
        \PandaGroup\EmailTester\Logger\Logger $logger,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Email\Model\Template $template,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Sales\Model\Order\Email\Container\OrderIdentity $identityContainer
    ) {
        parent::__construct($context,$registry);
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->config = $config;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->template = $template;
        $this->addressRenderer = $addressRenderer;
        $this->paymentHelper = $paymentHelper;
        $this->identityContainer = $identityContainer;
    }

    /**
     * Send Email Template
     *
     * @param string $email
     * @param string $templateId
     * @return array
     */
    public function sendEmailTemplateTest($email, $templateId)
    {
        try {
            $templateModel = $this->template->load($templateId);

            $randomOrder = $this->getRandomOrder();
            /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
            $creditmemo = $randomOrder->getCreditmemosCollection()->getFirstItem();

            $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                ])
                ->setTemplateVars(
                    [
                        'order' => $randomOrder,
                        'customer' => $this->getRandomCustomer(),
                        'invoice' => $this->getRandomInvoice(),
                        'creditmemo' => $creditmemo,
                        'comment' => $creditmemo->getCustomerNoteNotify() ? $creditmemo->getCustomerNote() : '',
                        'billing' => $randomOrder->getBillingAddress(),
                        'payment_html' => $this->getPaymentHtml($randomOrder),
                        'store' => $randomOrder->getStore(),
                        'formattedShippingAddress' => $this->getFormattedShippingAddress($randomOrder),
                        'formattedBillingAddress' => $this->getFormattedBillingAddress($randomOrder),
                    ]
                )
                ->setFrom('general') // you can config 'general' email address in Store -> Configuration -> General -> Store Email Addresses
                ->addTo($email, 'EmailTester Magento Module')
                ->getTransport();
            $transport->sendMessage();

            $done = 1;
            $jsonMessage = 'Template ' . '"' . $templateModel->getTemplateCode() . '" was send to: ' . $email . ' correctly.';
            $this->logger->addInfo($jsonMessage);

        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = $e->getMessage();
            $this->logger->addError($jsonMessage);
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getRandomOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Sales\Model\Order $orderModel */
        $orderModel = $objectManager->create('Magento\Sales\Model\Order');
        $quantity = $orderModel->getCollection()->count();
        $randomNumber = mt_rand( 0, $quantity );

        return $orderModel->load($randomNumber);
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getRandomCustomer()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Customer\Model\Customer $customerModel */
        $customerModel = $objectManager->create('Magento\Customer\Model\Customer');
        $quantity = $customerModel->getCollection()->count();
        $randomNumber = mt_rand( 0, $quantity );

        return $customerModel->load($randomNumber);
    }

    public function getRandomInvoice()
    {
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        /** @var \Magento\Sales\Model\ResourceModel\Order\Invoice\Collection $invoice */
//        $invoice = $objectManager->create('Magento\Sales\Model\Service\InvoiceService');
//        $quantity = $invoice->count();
//        $randomNumber = mt_rand( 0, $quantity );
//
//        return $invoice->load($randomNumber);
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string|null
     */
    protected function getFormattedShippingAddress($order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    protected function getPaymentHtml(\Magento\Sales\Model\Order $order)
    {
        try {
            return $this->paymentHelper->getInfoBlockHtml(
                $order->getPayment(),
                $this->identityContainer->getStore()->getStoreId()
            );
        } catch (\Exception $e) {
            return '';
        }
    }
}
