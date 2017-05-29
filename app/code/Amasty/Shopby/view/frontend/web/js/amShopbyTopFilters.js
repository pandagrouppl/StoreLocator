/**
 * @author    Amasty Team
 * @copyright Copyright (c) Amasty Ltd. ( http://www.amasty.com/ )
 * @package   Amasty_Shopby
 */

define([
    'jquery'
], function ($, mediaCheck) {
    'use strict';

    return {
        moveTopFiltersToSidebar: function(){
            if($('.sidebar.sidebar-main #layered-filter-block').first().length == 0) {
                var $element = $(".catalog-topnav #layered-filter-block").clone();
                $element
                    .addClass('amshopby-all-top-filters-append-left')
                    .attr('data-mage-init', '{"collapsible":{"openedState": "active", "collapsible": true, "active": false, "collateral": { "openedState": "filter-active", "element": "body" } }}')
                $element.find('#narrow-by-list')
                    .attr('data-mage-init', '{"accordion":{"openedState": "active", "collapsible": true, "active": false, "multipleCollapsible": false}}');
                $('.sidebar.sidebar-main').first().append($element);

                $('.sidebar.sidebar-main').first().trigger('contentUpdated');
                return;
            }

            /*if($('.sidebar.sidebar-main .filter-content #narrow-by-list').length == 0) {
                var $element = $(".catalog-topnav #narrow-by-list").clone().addClass('amshopby-all-top-filters-append-left')
                    .attr('data-mage-init', '{"accordion":{"openedState": "active", "collapsible": true, "active": false, "multipleCollapsible": false}}');
                $('.sidebar.sidebar-main .filter-content').append($element);
                $('.sidebar.sidebar-main').first().trigger('contentUpdated');
                return;
            }*/

            $(".catalog-topnav #narrow-by-list .filter-options-item").each(function () {
                var isPresent = false;
                var classes = $(this).find('.items, .swatch-attribute').first().attr('class');
                if(classes) {
                    var listClasses = classes.split(" ");
                    var currentClass = '';
                    for(var i = 0; i<listClasses.length; i++) {
                        if(listClasses[i].indexOf('am_shopby_filter_items_') != -1) {
                            currentClass = listClasses[i];
                            break;
                        }
                    }
                    if(currentClass != '' && $('.sidebar.sidebar-main #narrow-by-list .'+currentClass).length > 0) {
                        isPresent = true;
                    }
                }

                if(isPresent) {
                    return;
                }

                $('.sidebar.sidebar-main #narrow-by-list').first().append($(this).clone().addClass('amshopby-filter-top'));

            });
            $(".sidebar.sidebar-main #narrow-by-list")
                .attr('data-mage-init', '{"accordion":{"openedState": "active", "collapsible": true, "active": false, "multipleCollapsible": true}}');
            $('.sidebar.sidebar-main .block.filter').first().trigger('contentUpdated');
        },
        removeTopFiltersFromSidebar: function(){
            if($(".catalog-topnav").length == 0) {
                return;
            }
            $('.sidebar.sidebar-main #narrow-by-list .amshopby-filter-top').remove();
            $('.sidebar.sidebar-main .amshopby-all-top-filters-append-left').remove();
        }
    };
});
