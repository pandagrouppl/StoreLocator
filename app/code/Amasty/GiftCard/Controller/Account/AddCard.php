<?php
namespace Amasty\GiftCard\Controller\Account;

class AddCard extends \Amasty\GiftCard\Controller\Account
{
    public function execute()
    {
        $code = trim($this->getRequest()->getParam('am_giftcard_code'));
        $account = $this->accountModel->create();
        $account->loadByCode($code);
        if($account->getId()) {
            try {
                $model = $this->customerCard->create();
                $currentCustomerId = $this->_getSession()->getCustomerId();
                $this->customerCardResourceModel->load(
                    $model,
                    [
                        'account_id'=> $account->getId(),
                        'customer_id' => $currentCustomerId
                    ]
                );

                if($model->getId()) {
                    $this->messageManager->addErrorMessage(
                        __('This Gift Code already exists')
                    );
                }
                $model->setAccountId($account->getId())->setCustomerId($currentCustomerId);
                $this->customerCardResourceModel->save($model);
                $this->messageManager->addSuccessMessage(__('Gift Card has been successfully added'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(
                __('Wrong Gift Card code')
            );
        }

        $this->_redirect('*/*/');
        return;
    }
}
