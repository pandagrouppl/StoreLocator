<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Image;


class Index extends \Amasty\GiftCard\Controller\Adminhtml\Image
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();

        return $resultPage;
    }
}
