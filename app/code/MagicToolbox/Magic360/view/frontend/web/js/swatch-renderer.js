
define([
    'jquery',
    'MagicToolbox_Magic360/js/swatch-renderer-mixin'
], function ($, mixin) {
    'use strict';

    return function (swatchRenderer) {
        /* NOTE: for Magento v2.0.x */
        if (typeof(swatchRenderer) == 'undefined') {
            swatchRenderer = $.custom.SwatchRenderer;
        }
        /* NOTE: to skip multiple mixins */
        if (typeof(swatchRenderer.prototype.options.mtConfig) != 'undefined') {
            return swatchRenderer;
        }
        $.widget('magictoolbox.SwatchRenderer', swatchRenderer, mixin);
        return $.magictoolbox.SwatchRenderer;
    };
});
