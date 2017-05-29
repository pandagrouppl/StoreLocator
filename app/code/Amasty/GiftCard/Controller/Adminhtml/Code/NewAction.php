<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Controller\Adminhtml\Code;

class NewAction extends \Amasty\GiftCard\Controller\Adminhtml\Code
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
