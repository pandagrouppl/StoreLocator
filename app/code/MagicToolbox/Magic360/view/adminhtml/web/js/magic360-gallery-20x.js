define([
    'jquery',
    'mage/template',
    'jquery/ui',
    'baseImage'
], function ($, mageTemplate) {
    'use strict';

    /**
     * Magic360 gallery widget
     */
    $.widget('mage.magic360Gallery', {
        options: {
            imageSelector: '[data-role=magic360image]',
            template: '[data-template=image]',
            initialized: false
        },

        /**
         * Gallery creation
         * @protected
         */
        _create: function () {
            this.options.images = this.options.images || this.element.data('images');

            this.imgTmpl = mageTemplate(this.element.find(this.options.template).html());

            this._bind();

            $.each(this.options.images, $.proxy(function (index, imageData) {
                this.element.trigger('addItem', imageData);
            }, this));

            this.options.initialized = true;
        },

        /**
         * Bind handler to elements
         * @protected
         */
        _bind: function () {
            var events = {
                addItem: '_addItem',
                removeItem: '_removeItem',
                setPosition: '_setPosition',
                resort: '_resort',
                'mouseup [data-role=delete-button]': function (event) {
                    var $imageContainer;
                    event.preventDefault();
                    $imageContainer = $(event.currentTarget).closest(this.options.imageSelector);
                    this.element.trigger('removeItem', $imageContainer.data('imageData'));
                }
            };

            this._on(events);

            this.element.sortable({
                distance: 8,
                items: this.options.imageSelector,
                tolerance: "pointer",
                cancel: 'input, button, .uploader',
                update: $.proxy(function () {
                    this.element.trigger('resort');
                }, this)
            });
        },

        /**
         * Find element by fileName
         * @param {Object} data
         * @returns {Element}
         */
        findElement: function (data) {
            return this.element.find(this.options.imageSelector).filter(function () {
                return $(this).data('imageData').file === data.file;
            }).first();
        },

        /**
         * Add image
         * @param event
         * @param imageData
         * @private
         */
        _addItem: function (event, imageData) {
            var count = this.element.find(this.options.imageSelector).length,
                element;

            imageData = $.extend({
                file_id: Math.random().toString(33).substr(2, 18),
                position: count + 1
            }, imageData);

            element = this.imgTmpl({
                data: imageData
            });

            element = $(element).data('imageData', imageData);
            if (count === 0) {
                element.prependTo(this.element);
            } else {
                element.insertAfter(this.element.find(this.options.imageSelector + ':last'));
            }
        },

        /**
         * Remove Image
         * @param {jQuery.Event} event
         * @param imageData
         * @private
         */
        _removeItem: function (event, imageData) {
            var $imageContainer = this.findElement(imageData);
            imageData.isRemoved = true;
            $imageContainer.addClass('removed').hide().find('.is-removed').val(1);
        },

        /**
         * Resort images
         * @private
         */
        _resort: function () {
            this.element.find('.position').each($.proxy(function (index, element) {
                var value = $(element).val();

                if (value != index) {
                    this.element.trigger('moveElement', {
                        imageData: $(element).closest(this.options.imageSelector).data('imageData'),
                        position: index
                    });
                    $(element).val(index);
                }
            }, this));
        },

        /**
         * Set image position
         * @param event
         * @param data
         * @private
         */
        _setPosition: function (event, data) {
            var $element = this.findElement(data.imageData);
            var curIndex = this.element.find(this.options.imageSelector).index($element);
            var newPosition = data.position + (curIndex > data.position ? -1 : 0);

            if (data.position != curIndex) {
                if (data.position === 0) {
                    this.element.prepend($element);
                } else {
                    $element.insertAfter(
                        this.element.find(this.options.imageSelector).eq(newPosition)
                    );
                }
                this.element.trigger('resort');
            }
        }
    });

    return $.mage.magic360Gallery;
});
