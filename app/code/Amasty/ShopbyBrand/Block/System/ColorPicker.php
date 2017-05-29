<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyBrand\Block\System;

class ColorPicker extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * add color picker in admin configuration fields
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string script
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = $element->getElementHtml();
        $value = $element->getData('value');

        $html .= '
        <script>
            require([
            "jquery",
            "jquery/colorpicker/js/colorpicker",
            "domReady!"
            ], function ($) {
                var $el = $("#' . $element->getHtmlId() . '");
                $el.css("backgroundColor", "' . $value . '");
 
                // Attach the color picker
                $el.ColorPicker({
                    color: "' . $value . '",
                    onChange: function (hsb, hex, rgb) {
                    $el.css("backgroundColor", "#" + hex).val("#" + hex);
                }
              });
            });
        </script>';
        return $html;
    }
}