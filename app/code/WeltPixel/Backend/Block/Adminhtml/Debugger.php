<?php

namespace WeltPixel\Backend\Block\Adminhtml;

/**
 * Class Debugger
 * @package WeltPixel\Backend\Block\Adminhtml
 */
class Debugger extends  \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'weltpixelbackend_debugger';
        $this->_blockGroup = 'WeltPixel_Backend';
        parent::_construct();
        $this->removeButton('add');
        $this->addButton(
            'only-custom',
            [
                'label' => __("Only Custom Modules"),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/rewritescustom') . '\')',
                'class' => 'primary'
            ]
        );
        $this->addButton(
            'all-core',
            [
                'label' => __("All Magento Modules"),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/rewritesall') . '\')',
                'class' => 'secondary'
            ]
        );
    }
}
