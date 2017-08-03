<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Account;


class Index extends \Amasty\GiftCard\Controller\Adminhtml\Account
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();

        return $resultPage;
    }
}
