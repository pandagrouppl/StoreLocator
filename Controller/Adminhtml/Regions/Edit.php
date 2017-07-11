<?php
namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

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
        $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\States');

        if ($id) {
            $model->load($id);
//            var_dump($model); exit;

            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This region no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('states_data', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->addBreadcrumb(__('Edit Region'), __('New Region'));
        $resultPage->addBreadcrumb(__('Edit Region'), __('New Region'));

        $resultPage->setActiveMenu('Magento_Backend::system_store');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Regions'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Region'));

        return $resultPage;
    }

}
