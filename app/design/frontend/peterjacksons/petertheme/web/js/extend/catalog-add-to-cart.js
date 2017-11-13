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

            /* Trigger content loading from quickview */
            if (window.frameElement && window.frameElement.nodeName === "IFRAME") {
                var eventLoading = new CustomEvent('miniCartContentLoading', {
                    detail: {
                        minicartSelector: self.options.minicartSelector,
                        status: 'contentLoading'
                    }
                });
                parent.document.dispatchEvent(eventLoading);
            }

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
                    console.log('success', res);
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }
                    /* Quick view close & update minicart */
                    if (window.frameElement && window.frameElement.nodeName === "IFRAME") {
                        var parent = window.parent;
                        var body = parent.document.getElementsByTagName('BODY')[0];
                        body.classList.remove('fancybox-lock');
                        body.style.marginRight = null;
                        var event = new CustomEvent('quickView', {
                            detail: {
                                method: 'post',
                                action: form.attr('action'),
                                minicartSelector: self.options.minicartSelector,
                                status: 'contentUpdated'
                            }

                        });
                        parent.document.dispatchEvent(event);
                        parent.document.querySelector('.fancybox-overlay').remove();
                    }

                    if (res.backUrl) {
                        self.enableFailedAddToCartButton(form);
                        $(self.options.minicartSelector).trigger('contentLoaded');
                    } else {
                        self.enableAddToCartButton(form);
                        $(self.options.minicartSelector).trigger('openMinicart');
                        $(self.options.minicartSelector).trigger('contentUpdated');
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
                }
            });
        },

        enableFailedAddToCartButton: function(form) {
            var addToCartButtonTextAdded = this.options.addToCartButtonTextAdded || $t('Failed to add');
            var self = this,
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);

            addToCartButton.find('span').text(addToCartButtonTextAdded);
            addToCartButton.attr('title', addToCartButtonTextAdded);

            setTimeout(function() {
                var addToCartButtonTextDefault = self.options.addToCartButtonTextDefault || $t('Add to Cart');
                addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                addToCartButton.find('span').text(addToCartButtonTextDefault);
                addToCartButton.attr('title', addToCartButtonTextDefault);
            }, 1000);
        },

        enableAddToCartButton: function(form) {
            var addToCartButtonTextAdded = this.options.addToCartButtonTextAdded || $t('Added');
            var self = this,
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);

            addToCartButton.find('span').text(addToCartButtonTextAdded);
            addToCartButton.attr('title', addToCartButtonTextAdded);
            $('#success-popup').fadeIn();

            setTimeout(function() {
                var addToCartButtonTextDefault = self.options.addToCartButtonTextDefault || $t('Add to Cart');
                addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                addToCartButton.find('span').text(addToCartButtonTextDefault);
                addToCartButton.attr('title', addToCartButtonTextDefault);
            }, 1000);
        }
    });

    return $.light4website.catalogAddToCart;
});
