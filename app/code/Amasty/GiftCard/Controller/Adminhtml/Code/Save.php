<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Adminhtml\Code;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Amasty\GiftCard\Controller\Adminhtml\Code
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $model = $this->codeSetFactory->create();

                $id = $this->getRequest()->getParam('code_set_id');

                if ($id) {
                    $this->codeSetResource->load($model, $id);
                    if ($id != $model->getId()) {
                        throw new LocalizedException(__('The wrong Code Pool is specified.'));
                    }
                }

                $field = 'csv';
                if (!empty($this->getRequest()->getFiles($field)['tmp_name'])) {
                    try {
                        $info = pathinfo($this->getRequest()->getFiles($field)['tmp_name']);
                        $data['csv_info'] = $info;
                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage($e->getMessage());
                    }
                }

                $model->setData($data);

                $this->codeSetResource->save($model);

                $this->messageManager->addSuccessMessage(__('Record has been successfully saved'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('code_set_id');
                if (!empty($id)) {
                    $this->_redirect('*/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('*/*/index');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the Code Pool data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('code_set_id')]);
                return;
            }
        }
    }

}