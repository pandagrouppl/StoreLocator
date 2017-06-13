/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/translate',
    'jquery/ui',
    'Magento_Catalog/js/catalog-add-to-cart',
    'Magento_Customer/js/customer-data'
], function($, $t) {
    "use strict";

    $.widget('light4website.catalogAddToCart', $.mage.catalogAddToCart, {

        ajaxSubmit: function(form) {
            var self = this;
            $(self.options.minicartSelector).trigger('contentLoading');
            self.disableAddToCartButton(form);

            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStart);
                    }
                },
                success: function(res) {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }

                    /* Quick view close & update minicart */
                    if (window.frameElement && window.frameElement.nodeName === "IFRAME") {
                        var parent = window.parent;
                        var body = parent.document.getElementsByTagName('BODY')[0];
                        body.classList.remove('fancybox-lock');
                        body.style.marginRight = null;

                        const event = new CustomEvent('quickView', {
                            detail: {
                                method: 'post',
                                action: form.attr('action')
                            }
                        });
                        parent.document.dispatchEvent(event);
                        parent.document.querySelector('.fancybox-overlay').remove();
                    }

                    if (res.backUrl) {
                        window.location = res.backUrl;
                        return;
                    }

                    if (res.minicart) {
                        $(self.options.minicartSelector).replaceWith(res.minicart);
                        $(self.options.minicartSelector).trigger('contentUpdated');
                    }

                    if (res.messages) {
                        $(self.options.messagesSelector).html(res.messages);
                    }

                    if (res.product && res.product.statusText) {
                        $(self.options.productStatusSelector)
                            .removeClass('available')
                            .addClass('unavailable')
                            .find('span')
                            .html(res.product.statusText);
                    }
                    self.enableAddToCartButton(form);
                }
            });
        }
    });

    return $.light4website.catalogAddToCart;
});
