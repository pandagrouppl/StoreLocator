/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "jquery/ui",
    "Amasty_Quickview/js/amquickview-vendor"
], function($, ui, quickview){

    $.widget('l4w.amQuickview', $.mage.amQuickview, {

        createHover : function(element) {
            var self = this;
            var productId = this.getProductId(element);
            if (!productId) {
                console.debug("We didn't find price block with product id");
                return false;
            }

            var hover = $('<div />', {
                class : 'amquickview-hover'
            });
            hover.attr("style", this.options['css']);
            var anchor = element.find('.product-item-photo')

            anchor.css({
                display : 'block',
                position : 'relative'
            });

            hover.css({
                position : 'absolute',
                top: 'calc(100% - 40px)'
            });

            var link = $('<a />', {
                class : 'amquickview-link',
                id : 'amquickview-link-' + productId
            });
            link.attr('data-product-id', productId);
            link.html(this.options['text']);
            hover.appendTo(anchor).hide();

            link.click(function( event ) {
                self.showPopup( event )
            });
            link.appendTo(hover);

            return hover;
        },

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
