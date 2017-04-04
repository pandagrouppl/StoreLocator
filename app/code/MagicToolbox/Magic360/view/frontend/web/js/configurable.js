/*jshint browser:true jquery:true*/

define([
    'jquery',
    'mage/template',
    'Magento_ConfigurableProduct/js/configurable'
], function ($, mageTemplate) {
    'use strict';

    $.widget('magictoolbox.configurable', $.mage.configurable, {

        options: {
            mtConfig: {
                enabled: false,
                useOriginalGallery: true,
                currentProductId: null,
                galleryData: [],
                tools: {},
                thumbSwitcherOptions: {},
                mtContainerSelector: 'div.MagicToolboxContainer'
            }
        },

        /**
         * Initialize tax configuration, initial settings, and options values.
         * @private
         */
        _initializeOptions: function () {

            this._super();

            if (typeof(this.options.spConfig.magictoolbox) == 'undefined' || typeof(this.options.spConfig.productId) == 'undefined') {
                return;
            }

            this.options.mtConfig.enabled = true;
            this.options.mtConfig.currentProductId = this.options.spConfig.productId;
            this.options.mtConfig.useOriginalGallery = this.options.spConfig.magictoolbox.useOriginalGallery;
            this.options.mtConfig.galleryData = this.options.spConfig.magictoolbox.galleryData;
            this.options.mtConfig.tools = {
                'Magic360': {
                    'idTemplate': '{tool}-product-{id}',
                    'objName': 'Magic360',
                    'undefined': true
                },
                'MagicSlideshow': {
                    'idTemplate': '{tool}-product-{id}',
                    'objName': 'MagicSlideshow',
                    'undefined': true
                },
                'MagicScroll': {
                    'idTemplate': '{tool}-product-{id}',
                    'objName': 'MagicScroll',
                    'undefined': true
                },
                'MagicZoomPlus': {
                    'idTemplate': '{tool}Image-product-{id}',
                    'objName': 'MagicZoom',
                    'undefined': true
                },
                'MagicZoom': {
                    'idTemplate': '{tool}Image-product-{id}',
                    'objName': 'MagicZoom',
                    'undefined': true
                },
                'MagicThumb': {
                    'idTemplate': '{tool}Image-product-{id}',
                    'objName': 'MagicThumb',
                    'undefined': true
                }
            };
            for (var tool in this.options.mtConfig.tools) {
                this.options.mtConfig.tools[tool].undefined = (typeof(window[tool]) == 'undefined');
            }

            //NOTE: get thumb switcher options
            var container = $(this.options.mtConfig.mtContainerSelector);
            if (container.length && container.magicToolboxThumbSwitcher) {
                this.options.mtConfig.thumbSwitcherOptions = container.magicToolboxThumbSwitcher('getOptions');
            }
        },

        /**
         * Change displayed product image according to chosen options of configurable product
         * @private
         */
        _changeProductImage: function () {
            var galleryData = [],
                tools = {};

            if (!this.options.mtConfig.enabled || this.options.mtConfig.useOriginalGallery) {
                this._super();
                return;
            }

            var productId = this.options.spConfig.productId;
            if (typeof(this.simpleProduct) != 'undefined') {
                productId = this.simpleProduct;
            }

            galleryData = this.options.mtConfig.galleryData;

            //NOTE: associated product has no images
            if (!galleryData[productId].length) {
                productId = this.options.spConfig.productId;
            }

            //NOTE: there is no need to change gallery
            if (this.options.mtConfig.currentProductId == productId) {
                return;
            }

            tools = this.options.mtConfig.tools;

            //NOTE: stop tools
            for (var tool in tools) {
                if (tools[tool].undefined) {
                    continue;
                }
                var id = tools[tool].idTemplate.replace('{tool}', tool).replace('{id}', this.options.mtConfig.currentProductId);
                if (document.getElementById(id)) {
                    window[tools[tool].objName].stop(id);
                }
            }

            //NOTE: stop MagiScroll on selectors
            var id = 'MagicToolboxSelectors'+this.options.mtConfig.currentProductId,
                selectorsEl = document.getElementById(id);
            if (!tools['MagicScroll'].undefined && selectorsEl && selectorsEl.className.match(/(?:\s|^)MagicScroll(?:\s|$)/)) {
                MagicScroll.stop(id);
            }

            //NOTE: replace gallery
            $(this.options.mtConfig.mtContainerSelector).replaceWith(galleryData[productId]);

            //NOTE: start MagiScroll on selectors
            id = 'MagicToolboxSelectors'+productId;
            selectorsEl = document.getElementById(id);
            if (!tools['MagicScroll'].undefined && selectorsEl && selectorsEl.className.match(/(?:\s|^)MagicScroll(?:\s|$)/)) {
                MagicScroll.start(id);
            }

            //NOTE: initialize thumb switcher widget
            var container = $(this.options.mtConfig.mtContainerSelector);
            if (container.length) {
                this.options.mtConfig.thumbSwitcherOptions.productId = productId;
                if (container.magicToolboxThumbSwitcher) {
                    container.magicToolboxThumbSwitcher(this.options.mtConfig.thumbSwitcherOptions);
                } else {
                    //NOTE: require thumb switcher widget
                    /*
                    require(["magicToolboxThumbSwitcher"], function ($) {
                        container.magicToolboxThumbSwitcher(this.options.mtConfig.thumbSwitcherOptions);
                    });
                    */
                }
            }

            //NOTE: update current product id
            this.options.mtConfig.currentProductId = productId;

            //NOTE: start tools
            for (var tool in tools) {
                if (tools[tool].undefined) {
                    continue;
                }
                var id = tools[tool].idTemplate.replace('{tool}', tool).replace('{id}', this.options.mtConfig.currentProductId);
                if (document.getElementById(id)) {
                    window[tools[tool].objName].start(id);
                }
            }
        }
    });

    return $.magictoolbox.configurable;
});
