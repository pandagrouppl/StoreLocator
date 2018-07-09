/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function($){
    "use strict";

    $.widget('mage.selectChange', {
        _create: function() {
            $(this.bindings[0]).on('change', $.proxy(function(e) {
                $('.panda-spinner').toggleClass('panda-spinner--active');
                location.href = '/cart/size/change?' +
                'product=' + this.options.parentProductId + '&' +
                'sizeid=' + this.options.sizeAttributeId + '&' +
                'size=' + e.target.value + '&' +
                'qty=' + $(this.bindings[0]).parents('.items-table__options-and-actions').find('select[data-role="cart-item-qty"]')[0].value + '&' +
                'delete=' + this.options.childProductId;
            }, this));
        }
    });

    return $.mage.selectChange;
});
