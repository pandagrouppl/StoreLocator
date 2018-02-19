/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/translate',
    'jquery/ui',
    "Magento_Customer/js/customer-data",
    'mage/validation',
    'jquery/jquery-storageapi',
    'Magento_Catalog/js/catalog-add-to-cart'
], function($, $t, _, customerData) {
    "use strict";

    $.widget('light4website.catalogAddToCart', $.mage.catalogAddToCart, {

        _bindSubmit: function() {
            var self = this;
            this.element.on('submit', function(e) {
                e.preventDefault();
                if (e.target.checkValidity()) {
                    self.submitForm($(this));
                }
            });
        },


        /**
         * Ajax context is altered to handle quickview closing (otherwise call would terminate with iframe closing
         *
         * resBackUrl == true == error in adding, returns redirect url. Instead of reloading refresh messages
         * and show failed message on the button. EXCEPT: redirect url had paypal - then redirect to paypal.
         *
         * resBackUrl == false == openminicart (if in quickview - call event inside parent).
         *
         * Commented sections - disabled minicart loader due to weird behaviors (infinite loader)
         * @param form
         */

        ajaxSubmit: function(form) {
            var self = this;
            // $(self.options.minicartSelector).trigger('contentLoading');
            // console.log(Boolean($(form).validation()));


            self.disableAddToCartButton(form);

            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                context: parent.document,
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
                    if (res.backUrl) {
                        if ((res.backUrl.match(/paypal/)||[]).length) {
                            window.location = res.backUrl;
                        } else {
                            customerData.reload('messages');
                            self.enableFailedAddToCartButton(form);
                            // $(self.options.minicartSelector).trigger('contentLoaded');
                        }
                    } else {
                        // $(self.options.minicartSelector).trigger('contentLoading');
                        if (window.frameElement && window.frameElement.nodeName === "IFRAME") {
                            window.parent.jQuery(self.options.minicartSelector).trigger('openMinicart');
                            // window.parent.jQuery(self.options.minicartSelector).trigger('contentUpdated');
                            window.parent.jQuery.fancybox.close();
                        } else {
                            self.enableAddToCartButton(form);
                            // $(self.options.minicartSelector).trigger('contentUpdated');
                            $(self.options.minicartSelector).trigger('openMinicart');
                        }
                    }
                    if (res.messages) {
                        $(self.options.messagesSelector).html(res.messages);
                    }
                    if (res.minicart) {
                        $(self.options.minicartSelector).replaceWith(res.minicart);
                        $(self.options.minicartSelector).trigger('contentUpdated');
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
            $(self.options.minicartSelector).trigger('contentUpdated');

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
