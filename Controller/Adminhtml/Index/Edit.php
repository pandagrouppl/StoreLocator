<?php
namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

class Edit extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory = false;

    /** @var \Magento\Framework\View\Result\Page  */
    protected $resultPage = null;

    /** @var \Magento\Framework\Registry  */
    protected $coreRegistry;

    /**
     * Edit constructor.
     *
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator');

        if ($id) {
            $model->load($id);
//            var_dump($model); exit;

            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This store no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('storelocator', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->addBreadcrumb(__('Edit Store'), __('New Store'));
        $resultPage->addBreadcrumb(__('Edit Store'), __('New Store'));

        $resultPage->setActiveMenu('Magento_Backend::system_store');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Stores'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Store'));

        return $resultPage;
    }

}
