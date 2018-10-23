<?php
namespace WeltPixel\Backend\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Notification
 * @package WeltPixel\Backend\Block\Adminhtml\System\Config
 */
class Notification extends Field
{
    protected $_template = 'WeltPixel_Backend::system/config/notification.phtml';

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}