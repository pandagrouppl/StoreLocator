<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Code;


class Index extends \Amasty\GiftCard\Controller\Adminhtml\Code
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();

        return $resultPage;
    }
}
