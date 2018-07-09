/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Checkout/js/action/redirect-on-success',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Paypal/js/action/set-payment-method',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/quote',
], function (
    $,
    additionalValidators,
    redirectOnSuccessAction,
    stepNavigator,
    setPaymentMethodAction,
    customerData,
    quote
) {
    'use strict';

    var mixin = {

        /**
         * moves place order button. Use with
         * afterRender: moveButt('buttonid')
         * and
         * visible: (getCode() == isChecked()),
         * @param id
         */

        moveButt: function (id) {
                $('#'+id).appendTo('.checkout__buttons');
            },

        /**
         * Navigates to shipping if there is something wrong with address (in case someone entered /checkout/#payment directly)
         * @param data
         * @param event
         * @returns {boolean}
         */

        placeOrderAlt: function (data, event) {
            var self = this;

            if (event) {
                event.preventDefault();
            }

            if (this.validate() && additionalValidators.validate()) {
                this.isPlaceOrderActionAllowed(false);

                this.getPlaceOrderDeferredObject()
                    .fail(
                        function () {
                            self.isPlaceOrderActionAllowed(true);
                        }
                    ).done(
                    function () {
                        self.afterPlaceOrder();

                        if (self.redirectAfterPlaceOrder) {
                            redirectOnSuccessAction.execute();
                        }
                    }
                );

                return true;
            } else {
                /**
                 * if there will be problem with /rest/default/V1/guest-carts/(...)/shipping-information
                 * replace code below with
                 * window.location.replace('/checkout/')
                 */
                stepNavigator.navigateTo('shipping');
            }

            return false;
        },

        /**
         * Navigates to shipping if there is something wrong with address (in case someone entered /checkout/#payment directly)
         * @returns {boolean}
         */

        continueToPayPalAlt: function () {
            if (additionalValidators.validate()) {
                //update payment method information if additional data was changed
                this.selectPaymentMethod();
                setPaymentMethodAction(this.messageContainer).done(
                    function () {
                        customerData.invalidate(['cart']);
                        $.mage.redirect(
                            window.checkoutConfig.payment.paypalExpress.redirectUrl[quote.paymentMethod().method]
                        );
                    }
                );

                return false;
            } else {
                /**
                 * if there will be problem with /rest/default/V1/guest-carts/(...)/shipping-information
                 * replace code below with
                 * window.location.replace('/checkout/')
                 */
                stepNavigator.navigateTo('shipping');
            }

        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
