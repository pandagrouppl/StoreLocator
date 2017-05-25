<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_PopupLogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\Popuplogin\Block\Adminhtml\System\Config\Form;

class FormFields extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_elementId;

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '<input id="prpopuplogin_registration_form_fields" type="hidden" name="groups[registration][fields][form_fields][value][fake][enable]" value="1">';
        $html .= $this->getLayout()->createBlock('Plumrocket\Popuplogin\Block\Adminhtml\System\Config\Form\FormFields\InputTable')
            ->setContainerFieldId($element->getName())
            ->setRowKey('name')
            ->addColumn('orig_label', [
                'header'    => __('Field'),
                'index'     => 'orig_label',
                'type'      => 'label',
                'width'     => '36%',
            ])
            ->addColumn('label', [
                'header'    => __('Displayed Name'),
                'index'     => 'label',
                'type'      => 'input',
                'width'     => '36%',
            ])
            ->addColumn('sort_order', [
                'header'    => __('Sort Order'),
                'index'     => 'sort_order',
                'type'      => 'input',
                'width'     => '20%',
            ])
            ->addColumn('enable', [
                'header'    => __('Enable'),
                'index'     => 'enable',
                'type'      => 'checkbox',
                'value'     => 1,
                'width'     => '8%',
            ])
            ->setArray($element->getValue())
            ->toHtml();

        return $html;
    }


    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_elementId = $element->getId();
        $html = parent::render($element);
        $html = preg_replace('/<div class="messages">.*<\/div>/i', '', $html);
        return  $html.$this->_getJs();
    }


    /**
     * Get js
     * @return string
     */
    protected function _getJs()
    {
        return "
        <style>
            #row_prpopuplogin_registration_form_fields .admin__data-grid-wrap {
                margin-bottom: 0;
                padding-bottom: 0;
                padding-top: 0;
            }
            #row_prpopuplogin_registration_form_fields .admin__data-grid-wrap td {
                padding: 1rem;
                vertical-align: middle;
            }
            #row_prpopuplogin_registration_form_fields .admin__data-grid-wrap td input.checkbox[disabled] {
                display: none;
            }
            #row_prpopuplogin_registration_form_fields tr.not-active td {
                color: #999999;
            }
            #row_prpopuplogin_registration_form_fields div.admin__data-grid-wrap {
                overflow: visible;
            }
        </style>
        <script type='text/javascript'>
            require(['jquery', 'mage/mage'], function($){
                $('#config-edit-form select option[value=\"__none__\"]').prop('disabled', true);
                $('#row_".$this->_elementId."').mage('formFields');
            });
        </script>";
    }
}
