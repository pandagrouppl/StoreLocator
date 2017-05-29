define([
    "jquery",
    "jquery/ui",
    "amasty_fancybox"
], function ($, ui, fancybox) {

    $.widget('mage.amQuickview', {
        options: {},
        imageSelector: '.product-image-photo',

        _create: function (options) {
            var self = this;
            self.element.on(
                {
                    'mouseenter': function() {self.showLen(this)},
                    'mouseleave': function() {self.hideLen(this)},
                    'touchstart': function() {self.showLen(this)},
                    'touchend'  : function(){
                        var event = this;
                        setTimeout(function(){
                            self.hideLen(event);
                        }, 2000);
                    }
                }
            );
        },

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

            element.css({
                position : 'relative'
            });

            var link = $('<a />', {
                class : 'amquickview-link',
                id : 'amquickview-link-' + productId
            });
            link.attr('data-product-id', productId);
            link.html(this.options['text']);

            hover.appendTo(element).hide();
            link.click(function( event ) {
                self.showPopup( event )
            });
            link.appendTo(hover);
            /*
             * set hover block at the bottom of the image
             * */
            var image = element.find(this.imageSelector);
            if (image.length) {
                image = $(image[0]);
                var imageHeight = image.height();
                var itemPadding= parseInt(element.css('paddingTop'))
                var hoverHeight = hover.outerHeight();
                var px = (imageHeight - hoverHeight + itemPadding);
                hover.css({
                    top: px + 'px'
                });

                var imageMargin = parseInt(image.css('marginLeft'));
                if (imageMargin) {
                    hover.css({
                        marginLeft: imageMargin + 'px'
                    });
                }
            }

            return hover;
        },

        showPopup : function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            var element = $(e.target);
            if (undefined == this.options['url']) return false;
            if (element.hasClass('am-quickview-icon')) {
                element = element.parent();
            }

            var productId = element.attr('data-product-id');
            if (!productId) return;

            var url = this.options['url'] +"?id=" + productId;

            $.fancybox.open({
                padding :0,
                href    : url,
                type    : 'iframe'
            });

            this.hideLen(element.parent());
            return false;
        },

        showLen : function(element) {
            if (!element) return false;
            element = $(element);
            var hover = element.find('.amquickview-hover').first();
            if (!hover.length) {
                hover = this.createHover(element);
            }

            if (hover) {
                hover.show();
            }
        },


        hideLen : function(element) {
            if (! element) return false;
            var hover = $(element).find('.amquickview-hover').first();
            if (hover.length) {
                hover.hide();
            }
        },

        getProductId: function(parent) {
            var element = parent.find('[data-product-id]');
            if (element[0]) {
                var id = $(element[0]).attr('data-product-id');
                id = parseInt(id);
                if (id) {
                    return id;
                }
            }
            var element = parent.find('[data-post]');
            if (element[0]) {
                var dataPost = $(element[0]).attr('data-post');
                dataPost = jQuery.parseJSON(dataPost);
                if (dataPost && dataPost.data && dataPost.data.product) {
                    id = parseInt(dataPost.data.product);
                    return id;
                }
            }

            return false;
        }
    });

    return $.mage.amQuickview;
});
