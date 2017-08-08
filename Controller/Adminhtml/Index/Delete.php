<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    /** @var \PandaGroup\StoreLocator\Logger\Logger  */
    protected $logger;

    /**
     * Delete constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\StoreLocator\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\StoreLocator\Logger\Logger $logger
    ) {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            $this->logger->info('Start deleting store.');
            try {
                $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted the store.'));
                $this->logger->info('    Store was successful deleted. Id='.$id);

                $this->logger->info('Finish deleting store.');
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->logger->error('    Error while deleting the store id=' . $id, $e->getMessage());

                $this->logger->info('Finish deleting store.');
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a store to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
