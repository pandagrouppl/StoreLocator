<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Code;

class Gridcode extends \Magento\User\Controller\Adminhtml\User\Role
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}

