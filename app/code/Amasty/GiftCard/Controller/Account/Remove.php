<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Account;

class Remove extends \Amasty\GiftCard\Controller\Account
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $id = $this->getRequest()->getParam('id');

            $model = $this->customerCard->create();
            $currentCustomerId = $this->_getSession()->getCustomerId();
            $this->customerCardResourceModel->load(
                $model,
                [
                    'account_id'=> $id,
                    'customer_id' => $currentCustomerId
                ]
            );
            if ($model->getCustomerId() == $currentCustomerId){
                $this->customerCardResourceModel->delete($model);
            }

            $this->messageManager->addSuccessMessage(__('Gift Card has been successfully removed'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}