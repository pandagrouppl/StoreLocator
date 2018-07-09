<?php

namespace PandaGroup\Careers\Controller\Adminhtml\Resume;

class Index extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory = false;

    /** @var \Magento\Backend\Model\View\Result\Page */
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
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Backend::system_store');
        $resultPage->addBreadcrumb(__('System'), __('System'));
        $resultPage->addBreadcrumb(__('Careers'), __('Resumes'));
        $resultPage->getConfig()->getTitle()->prepend(__('Resumes'));
        return $resultPage;
    }

    /**
     * Check Grid List Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PandaGroup_StoreLocator::careers');
    }

}
