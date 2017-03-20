/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "jquery/ui",
    "mage/mage",
    "mage/collapsible",
    "mage/tabs"
], function($){
    "use strict";

    $.widget("light4website.tabs", $.mage.tabs, {

        _create : function () {
            if((typeof this.options.disabled) === "string") {
                this.options.disabled = this.options.disabled.split(" ").map(function(item) {
                    return parseInt(item, 10);
                });
            }

            this._processPanels();

            this._mobileToggle();

            this._handleDeepLinking();

            this._processTabIndex();

            this._closeOthers();

            this._bind();
        },

        /**
         * Move description to align proper on mobile
         * @private
         */
        _mobileToggle: function() {
            if(window.innerWidth < 560) {
                $.each(this.collapsibles, function() {
                    var tab_id = this.getAttribute('aria-controls');
                    var tab_source = document.getElementById(tab_id);
                    $(this).append(tab_source);
                });
            }
        }
    });

    return $.light4website.tabs;
});
