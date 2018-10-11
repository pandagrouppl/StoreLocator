/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    "jquery"
], function($) {
    "use strict";

    function onPayment (url) {
        if (url.match(/payment/)) {
            $('.breadcrumbs__item--delivery').css({'font-weight': '300',
                'color': '#666'});
            $('.breadcrumbs__item--last').css({'font-weight': '500',
                'color': '#222'});
            $('.checkout__buttons').show();
        } else {
            $('.breadcrumbs__item--last').css('font-weight', '300');
            $('.breadcrumbs__item--delivery').css({'font-weight': '500',
                'color': '#222'});
            $('.checkout__buttons').hide();
        }
    }

    onPayment(window.location.href);
    window.addEventListener('hashchange', function(e) {onPayment(e.newURL)});

});