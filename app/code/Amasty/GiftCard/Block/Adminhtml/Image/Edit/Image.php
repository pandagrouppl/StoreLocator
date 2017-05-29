<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Block\Adminhtml\Image\Edit;

class Image extends \Magento\Framework\View\Element\Template
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('Amasty_GiftCard::image.phtml');
    }
}