<?php

namespace Amasty\GiftCard\Block\Adminhtml\Image\Edit;

class Image extends \Magento\Framework\View\Element\Template
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('Amasty_GiftCard::image.phtml');
    }
}