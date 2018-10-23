<?php
namespace WeltPixel\Backend\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class SystemInformation
 * @package WeltPixel\Backend\Block\Adminhtml\System\Config
 */
class SystemInformation extends Field
{
    protected $_template = 'WeltPixel_Backend::system/config/information.phtml';

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return string
     */
    public function getMagentoMode() {
        return $this->_appState->getMode();
    }

    /**
     * @return string
     */
    public function getMagentoPath() {
        return $this->getRootDirectory()->getAbsolutePath();
    }
}