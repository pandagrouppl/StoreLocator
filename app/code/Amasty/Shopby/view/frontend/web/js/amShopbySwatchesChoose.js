define([
    "jquery",
    "Magento_Swatches/js/swatch-renderer"
], function ($, swatchesRenderer) {
    'use strict';

    $.widget('mage.amShopbySwatchesChoose',swatchesRenderer, {
        options: {
            listSwatches: {}
        },
        _create: function () {
            $.each(this.options.listSwatches, $.proxy(function (attributeCode, optionId) {
                this.element.find('.' + this.options.classes.attributeClass +
                    '[attribute-code="' + attributeCode + '"] [option-id="' + optionId + '"]').trigger('click');
            }, this));
        }
    });
});
