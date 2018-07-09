/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery'
], function ($) {
    'use strict';

    var mixin = {
        moveGiftcard: function () {
                $('#checkout-discounts').appendTo('#discount-fields');
            }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
