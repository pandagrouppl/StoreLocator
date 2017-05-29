<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Controller\Adminhtml;

use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;

abstract class Code extends \Magento\Backend\App\Action
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
     * @var \Amasty\GiftCard\Model\CodeSetFactory
     */
    protected $codeSetFactory;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\CodeSet
     */
    protected $codeSetResource;
    /**
     * @var \Amasty\GiftCard\Model\CodeFactory
     */
    protected $codeFactory;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Code
     */
    protected $codeResource;
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\CodeSet\Collection
     */
    protected $codeSetCollection;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        LoggerInterface $logInterface,
        \Amasty\GiftCard\Model\CodeSetFactory $codeSetFactory,
        \Amasty\GiftCard\Model\CodeFactory $codeFactory,
        \Amasty\GiftCard\Model\ResourceModel\CodeSet $codeSetResource,
        \Amasty\GiftCard\Model\ResourceModel\Code $codeResource,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Amasty\GiftCard\Model\ResourceModel\CodeSet\Collection $codeSetCollection
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->logInterface = $logInterface;
        $this->session = $context->getSession();
        $this->codeSetFactory = $codeSetFactory;
        $this->codeSetResource = $codeSetResource;
        $this->codeFactory = $codeFactory;
        $this->codeResource = $codeResource;
        $this->filter = $filter;
        $this->codeSetCollection = $codeSetCollection;
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_GiftCard::giftcard_code');
        $resultPage->addBreadcrumb(__('Gift Code Pools'), __('Gift Code Pools'));
        $resultPage->getConfig()->getTitle()->prepend(__('Gift Code Pools'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_GiftCard::giftcard_code');
    }
}
