<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Adminhtml\Code;

use Amasty\GiftCard\Controller\Adminhtml\Code\Index;

class DeleteCode extends Index
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $id = $this->getRequest()->getParam('code_id');
            $codeModel = $this->codeFactory->create();
            $this->codeResource->load($codeModel, $id);
            $codeSetId = $codeModel->getCodeSetId();
            $this->codeResource->delete($codeModel);
            $this->messageManager->addSuccessMessage(__('Code has been deleted.'));
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/edit', array('id' => $codeSetId));
    }
}