
define(['jquery'], function ($) {
    'use strict';

    var mixin = {

        options: {
            mtConfig: {
                enabled: false,
                simpleProductId: null,
                useOriginalGallery: true,
                currentProductId: null,
                galleryData: [],
                tools: {},
                thumbSwitcherOptions: {},
                mtContainerSelector: 'div.MagicToolboxContainer'
            }
        },

        /**
         * @private
         */
        _create: function () {

            this._super();

            var spConfig = this.options.jsonConfig;

            if (typeof(spConfig.magictoolbox) != 'undefined' && typeof(spConfig.productId) != 'undefined') {
                this.options.mtConfig.enabled = true;
                this.options.mtConfig.currentProductId = spConfig.productId;
                this.options.mtConfig.useOriginalGallery = spConfig.magictoolbox.useOriginalGallery;
                this.options.mtConfig.galleryData = spConfig.magictoolbox.galleryData;
                this.options.mtConfig.tools = {
                    'Magic360': {
                        'idTemplate': '{tool}-{page}-{id}',
                        'objName': 'Magic360',
                        'undefined': true
                    },
                    'MagicSlideshow': {
                        'idTemplate': '{tool}-{page}-{id}',
                        'objName': 'MagicSlideshow',
                        'undefined': true
                    },
                    'MagicScroll': {
                        'idTemplate': '{tool}-{page}-{id}',
                        'objName': 'MagicScroll',
                        'undefined': true
                    },
                    'MagicZoomPlus': {
                        'idTemplate': '{tool}Image-{page}-{id}',
                        'objName': 'MagicZoom',
                        'undefined': true
                    },
                    'MagicZoom': {
                        'idTemplate': '{tool}Image-{page}-{id}',
                        'objName': 'MagicZoom',
                        'undefined': true
                    },
                    'MagicThumb': {
                        'idTemplate': '{tool}Image-{page}-{id}',
                        'objName': 'MagicThumb',
                        'undefined': true
                    }
                };
                for (var tool in this.options.mtConfig.tools) {
                    this.options.mtConfig.tools[tool].undefined = (typeof(window[tool]) == 'undefined');
                }
            }
        },

        /**
         * @private
         */
        _initThumbSwitcherOptions: function () {
            var container = $(this.options.mtConfig.mtContainerSelector);
            if (container.length && container.magicToolboxThumbSwitcher) {
                //NOTE: get thumb switcher options
                this.options.mtConfig.thumbSwitcherOptions = container.magicToolboxThumbSwitcher('getOptions');
            }
        },

        /**
         * Callback for product media
         *
         * @param $this
         * @param response
         * @private
         */
        _ProductMediaCallback: function ($this, response) {
            //NOTE: init thumb switcher options
            if (!this.options.mtConfig.useOriginalGallery && !Object.keys(this.options.mtConfig.thumbSwitcherOptions).length) {
                this._initThumbSwitcherOptions();
            }

            if (response.variantProductId) {
                this.options.mtConfig.simpleProductId = response.variantProductId;
            } else {
                this.options.mtConfig.simpleProductId = null;
            }

            this._super($this, response);
        },

        /**
         * Set images types
         * @param {Array} images
         */
        _setImageType: function (images) {
            if (!this.options.mtConfig.enabled) {
                images = this._super(images);
                return $.extend(true, [], images);
            }

            if (images.length) {
                images.map(function (img) {
                    img.type = 'image';
                });
            }

            return images;
        },

        /**
         * Update [gallery-placeholder] or [product-image-photo]
         * @param {Array} images
         * @param {jQuery} context
         * @param {Boolean} isProductViewExist
         */
        updateBaseImage: function (images, context, isProductViewExist) {
            if (!this.options.mtConfig.enabled) {
                this._super(images, context, isProductViewExist);
                return;
            }

            var spConfig = this.options.jsonConfig,
                galleryData = [],
                tools = {};

            if (this.options.mtConfig.useOriginalGallery) {
                images = spConfig.images[this.options.mtConfig.simpleProductId];
                if (!images) {
                    images = this.options.mediaGalleryInitial;
                }
                this._super(images, context, isProductViewExist);
                return;
            }

            var productId = spConfig.productId;
            if (this.options.mtConfig.simpleProductId) {
                productId = this.options.mtConfig.simpleProductId;
            }

            galleryData = this.options.mtConfig.galleryData;

            //NOTE: associated product has no images
            if (!galleryData[productId].length) {
                productId = spConfig.productId;
            }

            //NOTE: there is no need to change gallery
            if (this.options.mtConfig.currentProductId == productId) {
                return;
            }

            tools = this.options.mtConfig.tools;

            var ids = {},
                id,
                uniqueId,
                newId,
                newUniqueId,
                page = isProductViewExist ? 'product' : 'category';

            //NOTE: stop tools
            for (var tool in tools) {
                if (tools[tool].undefined) {
                    continue;
                }

                id = tools[tool].idTemplate.replace('{page}', page).replace('{tool}', tool);

                if (spConfig.productId == this.options.mtConfig.currentProductId) {
                    uniqueId = id.replace('{id}', this.options.mtConfig.currentProductId);
                } else {
                    uniqueId = id.replace('{id}', spConfig.productId+'-'+this.options.mtConfig.currentProductId);
                }

                newId = id.replace('{id}', productId);
                newUniqueId = productId == spConfig.productId ? newId : id.replace('{id}', spConfig.productId+'-'+productId);

                id = id.replace('{id}', this.options.mtConfig.currentProductId);

                id = isProductViewExist ? id : uniqueId;

                ids[tool] = {
                    'id': id,
                    'newId': newId,
                    'uniqueId': uniqueId,
                    'newUniqueId': newUniqueId,
                };

                if (document.getElementById(id)) {
                    window[tools[tool].objName].stop(id);
                }
            }

            if (isProductViewExist) {
                //NOTE: stop MagiScroll on selectors
                var selectorsElId = 'MagicToolboxSelectors'+this.options.mtConfig.currentProductId,
                    selectorsEl = document.getElementById(selectorsElId);
                if (!tools['MagicScroll'].undefined && selectorsEl && selectorsEl.className.match(/(?:\s|^)MagicScroll(?:\s|$)/)) {
                    MagicScroll.stop(selectorsElId);
                }

                //NOTE: replace gallery
                $(this.options.mtConfig.mtContainerSelector).replaceWith(galleryData[productId]);

                //NOTE: start MagiScroll on selectors
                selectorsElId = 'MagicToolboxSelectors'+productId;
                selectorsEl = document.getElementById(selectorsElId);
                if (!tools['MagicScroll'].undefined && selectorsEl && selectorsEl.className.match(/(?:\s|^)MagicScroll(?:\s|$)/)) {
                    MagicScroll.start(selectorsElId);
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
            } else {
                //NOTE: replace gallery
                var galleryHtml = galleryData[productId];
                for (var tool in ids) {
                    galleryHtml = galleryHtml.replace('id="'+ids[tool].newId+'"', 'id="'+ids[tool].newUniqueId+'"');
                }
                context.find('div.MagicToolboxContainer').replaceWith(galleryHtml);
            }

            //NOTE: update current product id
            this.options.mtConfig.currentProductId = productId;

            //NOTE: start tools
            for (var tool in ids) {
                id = isProductViewExist ? ids[tool].newId : ids[tool].newUniqueId;
                if (document.getElementById(id)) {
                    window[tools[tool].objName].start(id);
                }
            }

        }
    };

    return mixin;
});
