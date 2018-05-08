define([
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery'
], function (ko, Component, customerData, $) {
    'use strict';

    return Component.extend({

        initToggle: function(elements) {
            const $popup = $('.header-left__account-popup');
            $('.header-left__account-popup-toggle').click(() => {
                $popup.toggle();
            });
        },

        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.customer = customerData.get('customer');
        }
    });
});
