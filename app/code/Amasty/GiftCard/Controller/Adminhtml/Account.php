<?php

namespace Amasty\GiftCard\Controller\Adminhtml;

use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;

abstract class Account extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    /**
     * @var LoggerInterface
     */
    protected $logInterface;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var \Amasty\GiftCard\Model\AccountFactory
     */
    protected $accountFactory;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account
     */
    protected $accountResource;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $orderModel;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account\Collection
     */
    protected $accountCollection;
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        LoggerInterface $logInterface,
        \Amasty\GiftCard\Model\AccountFactory $accountFactory,
        \Amasty\GiftCard\Model\ResourceModel\Account $accountResource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Sales\Model\Order $orderModel,
        \Amasty\GiftCard\Model\ResourceModel\Account\Collection $accountCollection,
        \Magento\Ui\Component\MassAction\Filter $filter
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->logInterface = $logInterface;
        $this->session = $context->getSession();
        $this->accountFactory = $accountFactory;
        $this->accountResource = $accountResource;
        $this->storeManager = $storeManager;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->orderModel = $orderModel;
        $this->accountCollection = $accountCollection;
        $this->filter = $filter;
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_GiftCard::giftcard_account');
        $resultPage->addBreadcrumb(__('Gift Code Accounts'), __('Gift Code Accounts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Gift Code Accounts'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_GiftCard::giftcard_account');
    }
}
