<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

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

                    $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\States');
                    $model->load($id);
                    $model->delete();

                    $qtyOfDeleted++;

                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('You deleted '. $qtyOfDeleted .' selected regions. Check your stores which used this region.'));

        return $resultRedirect->setPath('*/*/');
    }
}
