<?php

namespace Amasty\GiftCard\Controller\Adminhtml;

use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;

abstract class Image extends \Magento\Backend\App\Action
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
    protected $imageFactory;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Image
     */
    protected $imageResource;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        LoggerInterface $logInterface,
        \Amasty\GiftCard\Model\ImageFactory $imageFactory,
        \Amasty\GiftCard\Model\ResourceModel\Image $imageResource,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->logInterface = $logInterface;
        $this->session = $context->getSession();
        $this->imageFactory = $imageFactory;
        $this->imageResource = $imageResource;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_GiftCard::giftcard_image');
        $resultPage->addBreadcrumb(__('Gift Card Images'), __('Gift Card Images'));
        $resultPage->getConfig()->getTitle()->prepend(__('Gift Card Images'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_GiftCard::giftcard_image');
    }
}
