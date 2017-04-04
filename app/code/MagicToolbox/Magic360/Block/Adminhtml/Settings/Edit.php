<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Settings;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'object_id';
        $this->_controller = 'adminhtml_settings';
        $this->_blockGroup = 'MagicToolbox_Magic360';
        $this->_headerText = 'Magic 360 Config';

        parent::_construct();

        $this->_formScripts[] = '
            require([\'magic360\'], function(magic360){
                magic360.initSettings();
            });
        ';

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->updateButton('save', 'label', __('Save Settings'));
    }
}
