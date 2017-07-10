<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {

                $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted the store.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {

                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a store to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
