<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

class DeleteUnused extends \Magento\Backend\App\Action
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

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \PandaGroup\StoreLocator\Model\States $statesModel */
        $statesModel = $objectManager->create('\PandaGroup\StoreLocator\Model\States');

        $qtyOfDeleted = $statesModel->deleteUnused();

        if ($qtyOfDeleted > 0) {
            $this->messageManager->addSuccessMessage($qtyOfDeleted . __(' unused regions was deleted.'));
        } elseif ($qtyOfDeleted === 0) {
            $this->messageManager->addSuccessMessage(__('All of regions are used to some stores.'));
        } else {
            $this->messageManager->addErrorMessage(__('Something went wrong while deleting regions.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
