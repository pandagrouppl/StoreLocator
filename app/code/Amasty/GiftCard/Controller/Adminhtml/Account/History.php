<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Account;

class History extends \Magento\User\Controller\Adminhtml\User\Role
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}

