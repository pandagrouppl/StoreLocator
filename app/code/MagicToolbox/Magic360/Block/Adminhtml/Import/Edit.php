<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Import;

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
        $this->_controller = 'adminhtml_import';
        $this->_blockGroup = 'MagicToolbox_Magic360';
        $this->_headerText = 'Import Magic 360 images';

        parent::_construct();

        $this->_formScripts[] = '
            require([\'magic360\'], function(magic360){
                magic360.initAdvancedRadios();
            });
        ';

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->updateButton('save', 'label', __('Import Images'));
        $this->updateButton('save', 'region', 'footer');
    }
}
