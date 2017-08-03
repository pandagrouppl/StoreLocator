<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Code;

use Amasty\GiftCard\Controller\Adminhtml\Code\Index;

class Delete extends Index
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $id = $this->getRequest()->getParam('id');
            $codeSetModel = $this->codeSetFactory->create();
            $this->codeSetResource->load($codeSetModel, $id);
            $this->codeSetResource->delete($codeSetModel);
            $this->messageManager->addSuccessMessage(__('Code Pool has been deleted.'));
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}