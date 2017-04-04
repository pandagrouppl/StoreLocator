
define([
    'jquery',
    'mage/template',
    'productGallery'
], function ($, mageTemplate) {
    'use strict';

    /**
     * Formats incoming bytes value to a readable format.
     *
     * @param {Number} bytes
     * @returns {String}
     */
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'],
            i;

        if (bytes === 0) {
            return '0 Byte';
        }

        i = window.parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));

        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    /**
     * Magic360 gallery widget
     */
    $.widget('magictoolbox.magic360Gallery', $.mage.productGallery, {
        options: {
            imageSelector: '[data-role=magic360image]',
            imageElementSelector: '[data-role=image-element]',
            template: '[data-template=image]',
            initialized: false
        },

        /**
         * Gallery creation
         * @protected
         */
        _create: function () {
            this.options.images = this.options.images || this.element.data('images');
            this.options.parentComponent = this.options.parentComponent || this.element.data('parent-component');

            this.imgTmpl = mageTemplate(this.element.find(this.options.template).html().trim());

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
            this._on({
                addItem: '_addItem',
                removeItem: '_removeItem',
                setPosition: '_setPosition',
                resort: '_resort',
                'mouseup [data-role=delete-button]': function (event) {
                    var $imageContainer;

                    event.preventDefault();
                    $imageContainer = $(event.currentTarget).closest(this.options.imageSelector);
                    this.element.find('[data-role=dialog]').trigger('close');
                    this.element.trigger('removeItem', $imageContainer.data('imageData'));
                },
            });

            this.element.sortable({
                distance: 8,
                items: this.options.imageSelector,
                tolerance: 'pointer',
                cancel: 'input, button, .uploader',
                update: $.proxy(function () {
                    this.element.trigger('resort');
                }, this)
            });
        },

        /**
         * Add image
         * @param event
         * @param imageData
         * @private
         */
        _addItem: function (event, imageData) {
            var count = this.element.find(this.options.imageSelector).length,
                element,
                imgElement;

            imageData = $.extend({
                'file_id': imageData.value_id ? imageData.value_id : Math.random().toString(33).substr(2, 18),
                'position': count + 1,
                sizeLabel: bytesToSize(imageData.size)
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

            imgElement = element.find(this.options.imageElementSelector);

            imgElement.on('load', this._updateImageDimesions.bind(this, element));

            this._contentUpdated();
        },
    });

    return $.magictoolbox.magic360Gallery;
});
