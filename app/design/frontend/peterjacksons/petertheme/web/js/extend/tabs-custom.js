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
         * Add support for tab closing tab when clicking on (-)
         * @private
         */

        _create : function () {
            if((typeof this.options.disabled) === "string") {
                this.options.disabled = this.options.disabled.split(" ").map(function(item) {
                    return parseInt(item, 10);
                });
            }
            this._processPanels();

            this._handleDeepLinking();

            this._processTabIndex();

            this._closeOthers();

            this._bind();

            /**
             * Trying to implement tab hiding on (-) clicking
             */
            // var self = this;
            // var tabIds = Object.keys(this.element["0"].children).filter(v => v.indexOf('tab-label') !== -1);
            // var tabsIds = tabIds.map(v => '#' + v.replace(/\./g, '\\.'));
            // $(tabsIds.join(', ')).click(function(e) {
            //     console.log(e.currentTarget.className);
            //     console.log(e.currentTarget.className.indexOf('active'));
            //     if (e.currentTarget.className.indexOf('active') !== -1) {
            //         this.deactivate();
            //     }
            // });

        },


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
