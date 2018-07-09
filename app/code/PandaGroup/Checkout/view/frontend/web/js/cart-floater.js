/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    "jquery"
], function($) {
    "use strict";
    const $summary = $('.cart-container__summary');
    let offset = $('.cart-container__form')[0].offsetTop;
    if (window.pageYOffset > offset && window.innerWidth > 768) {
        $summary.css('margin-top',window.pageYOffset - offset)
    }
    $(window).scroll(() => {
        let offset = $('.cart-container__form')[0].offsetTop;
        if (window.pageYOffset > offset && window.innerWidth > 768) {
            $summary.css('margin-top',window.pageYOffset - offset);
        }
    });

});