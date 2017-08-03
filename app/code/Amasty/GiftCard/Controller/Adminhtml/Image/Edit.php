<?php

namespace Amasty\GiftCard\Controller\Adminhtml\Image;

class Edit extends \Amasty\GiftCard\Controller\Adminhtml\Image
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->imageFactory->create();

        if ($id) {
            $this->imageResource->load($model, $id);
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

        $this->_coreRegistry->register('current_amasty_giftcard_image', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_GiftCard::giftcard_image');

        $title = $model->getId() ? __('Edit Gift Image') : __('New Gift Image');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb($title, $title);

        return $resultPage;

    }
}
