<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Adminhtml\Account;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Amasty\GiftCard\Controller\Adminhtml\Account
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $model = $this->accountFactory->create();
                if (isset($data['account_id'])) {
                    $this->accountResource->load($model, $data['account_id']);
                }

                $model->addData($data);
                $model->setIsSent(0);
                $this->accountResource->save($model);

                if ($this->getRequest()->getParam('send')) {
                    $model->sendDataToMail();
                    $this->messageManager->addSuccessMessage(__('The email has been sent successfully.'));
                }

                $this->messageManager->addSuccessMessage(__('The code account has been saved.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('account_id');
                if (!empty($id)) {
                    $this->_redirect('*/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('*/*/index');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the Account data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('account_id')]);
                return;
            }
        }
    }
}