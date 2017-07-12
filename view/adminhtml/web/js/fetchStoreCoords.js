/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiElement',
    'uiRegistry',
    'uiLayout',
    'mageUtils',
    'jquery'
], function (Element, registry, layout, utils, $) {
    'use strict';

    return Element.extend({
        defaults: {
            additionalClasses: {},
            displayArea: 'outsideGroup',
            displayAsLink: false,
            elementTmpl: 'ui/form/element/button',
            template: 'ui/form/components/button/simple',
            visible: true,
            disabled: false,
            title: ''
        },

        /**
         * Initializes component.
         *
         * @returns {Object} Chainable.
         */
        initialize: function () {
            return this._super()
                ._setClasses();
        },

        /** @inheritdoc */
        initObservable: function () {
            return this._super()
                .observe([
                    'visible',
                    'disabled',
                    'title'
                ]);
        },

        /**
         * Performs configured actions
         */
        action: function () {
            var $lat = $('.admin__control-text[name="latitude"]');
            var $lng = $('.admin__control-text[name="longtitude"]');
            var $zoom = $('.admin__control-select[name="zoom_level"]');
            $lat.css({transition: "border 1s"});
            $lng.css({transition: "border 1s"});
            var $cntry = $('.admin__control-select[name="country"]');
            var $addr = $('.admin__control-text[name="address"]');
            if ($zoom.val() === " ") {
                $zoom.val("15").trigger("change");
            }
            var err_g = 'Google Api Error! Cannot find such place! Check your Address!';
            var err_ajax = 'Internal Error! Check your internet connection!';
            $.ajax({
                url: 'http://maps.google.com/maps/api/geocode/json',
                data: {address: $cntry.val() + ' ' + $addr.val()},
                method: 'get'
            }).done(function(json) {
                if (json.status == 'OK') {
                    $lat.val(json.results[0].geometry.location.lat).css({border: "1px solid #10bf10"}).trigger("change");
                    $lng.val(json.results[0].geometry.location.lng).css({border: "1px solid #10bf10"}).trigger("change");
                } else {
                    $lat.val(err_g).css({border: "1px solid #ff0050"});
                    $lng.val(err_g).css({border: "1px solid #ff0050"})
                }
            }).fail(function() {
                window.alert(err_ajax);
                $lat.val(err_ajax).css({border: "1px solid #ff0050"});
                $lng.val(err_ajax).css({border: "1px solid #ff0050"})
            });
        },


        /**
         * Create target component from template
         *
         * @param {Object} targetName - name of component,
         * that supposed to be a template and need to be initialized
         */
        getFromTemplate: function (targetName) {
            var parentName = targetName.split('.'),
                index = parentName.pop(),
                child;

            parentName = parentName.join('.');
            child = utils.template({
                parent: parentName,
                name: index,
                nodeTemplate: targetName
            });
            layout([child]);
        },

        /**
         * Extends 'additionalClasses' object.
         *
         * @returns {Object} Chainable.
         */
        _setClasses: function () {
            if (typeof this.additionalClasses === 'string') {
                this.additionalClasses = this.additionalClasses
                    .trim()
                    .split(' ')
                    .reduce(function (classes, name) {
                        classes[name] = true;

                        return classes;
                    }, {}
                );
            }

            return this;
        }
    });
});

