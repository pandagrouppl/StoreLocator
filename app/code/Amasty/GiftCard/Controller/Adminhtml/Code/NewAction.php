<?php

namespace Amasty\GiftCard\Controller\Adminhtml\Code;

class NewAction extends \Amasty\GiftCard\Controller\Adminhtml\Code
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
