
define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    if (typeof($.fn.magnify) == 'function') {
        $.fn.magnifyOriginal = $.fn.magnify;
        $.fn.magnify = function (options) {
            if (typeof(options.enabledDefault) == 'undefined') {
                options.enabledDefault = options.enabled;
            }
            if ($(this).find('.Magic360').length) {
                options.enabled = 'false';
            } else {
                options.enabled = options.enabledDefault;
            }
            return $.fn.magnifyOriginal.apply(this, arguments);
        };
    }

    var mixin = {

        initialize: function (config, element) {
            this._super(config, element);
            this.magic360ShowendHandler({'type': 'magictoolbox:showend'}, this.settings.api.fotorama, {});
            this.settings.$gallery.on('fotorama:showend', this.magic360ShowendHandler);
        },

        openFullScreen: function () {
            var fotorama = this.settings.fotoramaApi;
            if (fotorama.activeFrame.magic360) {
                return;
            }
            this._super();
        },

        /**
         * Handler for end of the show transition
         */
        magic360ShowendHandler: function (e, fotorama, extra) {
            if (typeof(Magic360) == 'undefined') {
                return;
            }

            if (typeof(fotorama.magic360) == 'undefined') {
                fotorama.magic360 = {
                    id: null
                };
            }

            if (fotorama.magic360.id && fotorama.magic360.id != fotorama.activeFrame.magic360) {
                Magic360.stop(fotorama.magic360.id);
                fotorama.magic360.id = null;
            }

            if (fotorama.activeFrame.magic360 && fotorama.activeFrame.magic360 != fotorama.magic360.id) {
                Magic360.start(fotorama.activeFrame.magic360);
                fotorama.activeFrame.$stageFrame.addClass('magic360-stage-frame');
                fotorama.magic360.id = fotorama.activeFrame.magic360;
            }
        },

        /**
         * Creates gallery's API.
         */
        initApi: function () {
            this._super();

            var settings = this.settings,
                fotorama = this.settings.fotoramaApi,
                api = this.settings.$element.data('gallery');

            /**
             * Updates gallery data partially by index
             * @param {Number} index - Index of image in data array to be updated.
             * @param {Object} item - Standart gallery image object.
             *
             */
            api.updateDataByIndex = function (index, item) {
                if (item.magic360) {
                    item.i = index + 1;
                    fotorama.splice(index, 1, $.extend({}, item));
                    return;
                }
                settings.fotoramaApi.spliceByIndex(index, item);
            }
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
