/*jshint browser:true jquery:true*/
define([
    "jquery",
    'Magento_Checkout/js/model/step-navigator'
], function($, stepNavigator) {
    "use strict";
    return function (config, element) {
        $(element).click(function(e) {
            if (window.location.hash === '#payment') {
                /**
                 * if there will be problem with /rest/default/V1/guest-carts/(...)/shipping-information
                 * replace code below with
                 * window.location.replace('/checkout/')
                 */
                stepNavigator.navigateTo('shipping');
            } else {
                window.location.replace('/')
            }
        })
    };
});