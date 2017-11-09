/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "jquery/ui",
    "mage/mage",
    "js/extend/collapsible-animate-override",
    "mage/tabs"
], function($){
    "use strict";

    $.widget("light4website.tabs", $.mage.tabs, {

        /**
         * Adding callback to close others tabs when one gets opened. Modified to ude deactivate instead of force (no hide animation)
         * @private
         */
        _closeOthers: function() {
            var self = this;
            $.each(this.collapsibles, function() {
                $(this).on("beforeOpen", function () {
                    self.collapsibles.not(this).collapsible("deactivate");
                });
            });
        }
    });

    return $.light4website.tabs;
});
