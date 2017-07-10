<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

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

                $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\States');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted the region. Check your stores which used this region.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {

                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a region to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
