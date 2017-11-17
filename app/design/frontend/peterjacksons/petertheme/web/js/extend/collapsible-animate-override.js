/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "jquery/ui",
    'jquery/jquery-storageapi',
    "mage/mage"
], function($){
    "use strict";

    $.widget("light4website.collapsible", $.mage.collapsible, {

        /**
         * adding support of animation breakpoint. Also, window resize is fired to recalculate container for slick.
         * @private
         */
        _animate: function(prop) {

            var duration,
                easing,
                animate = this.options.animate;

            if ( typeof animate === "number" ) {
                duration = animate;
            }
            if (typeof animate === "string" ) {
                animate = $.parseJSON(animate);
            }
            duration = duration || animate.duration;
            easing = animate.easing;
            if (window.innerWidth > this.options.animationBreakpoint) {
                this.content.animate(prop, 0, easing);
            } else {
                this.content.animate(prop, duration, easing);
            }
        }
    });

    return $.light4website.tabs;
});
