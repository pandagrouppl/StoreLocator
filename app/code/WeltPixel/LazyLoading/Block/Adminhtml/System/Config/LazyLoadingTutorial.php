<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace WeltPixel\LazyLoading\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Notification
 * @package WeltPixel\Backend\Block\Adminhtml\System\Config
 */
class LazyLoadingTutorial extends Field
{
    protected $_template = 'WeltPixel_LazyLoading::system/config/lazyloading_tutorial.phtml';

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}