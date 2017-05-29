<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Controller\Adminhtml\Code;

class Edit extends \Amasty\GiftCard\Controller\Adminhtml\Code
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->codeSetFactory->create();

        if ($id) {
            $this->codeSetResource->load($model, $id);
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

        $this->_coreRegistry->register('current_amasty_giftcard_code', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_GiftCard::giftcard_code');

        $title = $model->getId() ? __('Edit Gift Code Pool') : __('New Gift Code Pool');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb($title, $title);

        return $resultPage;

    }
}
