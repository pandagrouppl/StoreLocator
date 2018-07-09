/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-shipping-method'

], function (
    $,
    checkoutData,
    selectShippingMethodAction
) {
    'use strict';

    var mixin = {
        selectShippingMethodAlt: function (shippingMethod) {
            console.log('select');
            selectShippingMethodAction(shippingMethod);
            checkoutData.setSelectedShippingRate(shippingMethod.carrier_code + '_' + shippingMethod.method_code);
            $('#co-shipping-method-form').submit();
            return true;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
