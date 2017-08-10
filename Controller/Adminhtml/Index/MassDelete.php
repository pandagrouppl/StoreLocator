<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

class MassDelete extends \Magento\Backend\App\Action
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

        $qtyOfDeleted = 0;
        $selectedIds = $this->getRequest()->getParam('selected');
        foreach ($selectedIds as $id) {
            if ($id) {
                try {
                    $id = (int) $id;
                    $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator');
                    $model->load($id);
                    $model->delete();

                    $qtyOfDeleted++;

                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('You deleted '. $qtyOfDeleted .' selected stores.'));

        return $resultRedirect->setPath('*/*/');
    }
}
