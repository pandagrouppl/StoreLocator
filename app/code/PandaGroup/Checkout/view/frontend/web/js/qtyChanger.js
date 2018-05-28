/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    "jquery",
    "jquery/ui",
    "Magento_Customer/js/customer-data"
], function($, ui, customerData){
    "use strict";

    $.widget('mage.selectChange', {
        _create: function() {
            $(this.bindings[0]).on('change', $.proxy(function(e) {
                customerData.invalidate(['cart']);
                $('.panda-spinner').toggleClass('panda-spinner--active');
                location.href = '/cart/qty/change?' +
                'product=' + this.options.productId + '&' +
                'qty=' + e.target.value;
            }, this));
        }
    });

    return $.mage.selectChange;
});
