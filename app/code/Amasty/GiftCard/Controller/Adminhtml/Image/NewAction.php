<?php

namespace Amasty\GiftCard\Controller\Adminhtml\Image;

class NewAction extends \Amasty\GiftCard\Controller\Adminhtml\Image
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
