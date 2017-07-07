<?php
namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

class Index extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory = false;

    protected $resultPage = null;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        //Call page factory to render layout and page content
//        $this->_setPageData();
//        return $this->getResultPage();


        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Backend::system_store');
        $resultPage->addBreadcrumb(__('System'), __('System'));
        $resultPage->addBreadcrumb(__('Manage Regions'), __('Manage Regions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Regions'));
        return $resultPage;
    }

    /**
     * Check Grid List Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PandaGroup_StoreLocator::storelocator');
    }

//    public function getResultPage()
//    {
//        if (is_null($this->resultPage)) {
//            $this->resultPage = $this->resultPageFactory->create();
//        }
//        return $this->resultPage;
//    }
//
//    protected function _setPageData()
//    {
//        $resultPage = $this->getResultPage();
//        $resultPage->setActiveMenu('PandaGroup_StoreLocator::index');
//        $resultPage->getConfig()->getTitle()->prepend((__('Store Locator Manager')));
//
//        //Add bread crumb
//        $resultPage->addBreadcrumb(__('Panda Group'), __('Panda Group'));
//        $resultPage->addBreadcrumb(__('Store Locator'), __('Manage Stores'));
//
//        return $this;
//    }

}
