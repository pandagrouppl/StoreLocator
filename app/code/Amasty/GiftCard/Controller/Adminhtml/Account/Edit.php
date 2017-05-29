<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Controller\Adminhtml\Account;

class Edit extends \Amasty\GiftCard\Controller\Adminhtml\Account
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->accountFactory->create();

        if ($id) {
            $this->accountResource->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                return $this->_redirect('amasty_giftcard/*');
            }
        }
        // set entered data if was error when we do save
        $data = $this->session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->orderModel->getResource()->load($this->orderModel, $model->getOrderId());
        $model->addData(['order_number' => $this->orderModel->getIncrementId()]);

        $this->_coreRegistry->register('current_amasty_giftcard_account', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_GiftCard::giftcard_account');

        $title = $model->getId() ? __('Edit Gift Code Account') : __('New Gift Code Account');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb($title, $title);

        return $resultPage;

    }
}
