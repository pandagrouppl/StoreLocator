<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Adminhtml\Image;

class Delete extends \Amasty\GiftCard\Controller\Adminhtml\Image
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $id = $this->getRequest()->getParam('id');
            $imageModel = $this->imageFactory->create();
            $this->imageResource->load($imageModel, $id);
            $this->imageResource->delete($imageModel);
            $this->messageManager->addSuccessMessage(__('Image has been deleted.'));
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}