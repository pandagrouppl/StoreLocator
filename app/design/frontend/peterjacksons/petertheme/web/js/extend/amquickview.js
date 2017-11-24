/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "jquery/ui",
    "Amasty_Quickview/js/fancybox/jquery.fancybox.min",
    "Magento_Customer/js/customer-data",
    "Amasty_Quickview/js/amquickview-vendor"
], function($, ui, fancybox, customerData, quickview){

    $.widget('l4w.amQuickview', $.mage.amQuickview, {

        showPopup : function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            var element = $(e.target);

            if (undefined == this.options['url']) {
                return false;
            }

            if (element.hasClass('am-quickview-icon')) {
                element = element.parent();
            }

            var productId = element.attr('data-product-id');
            if (!productId) return;

            var url = this.options['url'] +"?id=" + productId;
            $.fancybox.open({
                customerData: this.customerData,
                src    : url,
                type   : 'iframe',
                opts   : {
                    iframe : {
                        css : {
                            width : '900px',
                            "max-height": '642px'
                        }
                    },
                    afterClose : function() {
                        var sections = ['cart', 'messages'];
                        this.customerData.reload(sections);
                    }
                }
            });

            this.hideLen(element.parent());
            return false;
        }
    });

    return $.l4w.amQuickview;
});
