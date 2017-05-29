/**
 * @author    Amasty Team
 * @copyright Copyright (c) Amasty Ltd. ( http://www.amasty.com/ )
 * @package   Amasty_Shopby
 */
define([
    "jquery",
    'amShopbyTopFilters',
    "jquery/ui",
    "Amasty_Shopby/js/amShopby",
    "productListToolbarForm"
], function ($, amShopbyTopFilters) {
    'use strict';
    $.widget('mage.amShopbyAjax',{
        options:{
            _isAmshopbyAjaxProcessed: false
        },
        _create: function (){
            var self = this;
            $(function(){
                self.initAjax();
                if (typeof window.history.replaceState === "function") {
                    window.history.replaceState({url: document.URL}, document.title);

                    setTimeout(function() {
                        /*
                         Timeout is a workaround for iPhone
                         Reproduce scenario is following:
                         1. Open category
                         2. Use pagination
                         3. Click on product
                         4. Press "Back"
                         Result: Ajax loads the same content right after regular page load
                         */
                        window.onpopstate = function(e){
                            if(e.state){
                                self.updateContent(e.state.url, false);
                            }
                        };
                    }, 0)
                }
            });

        },

        updateContent: function(link, isPushState){
            var self = this;
            $("#amasty-shopby-overlay").show();
            if (typeof window.history.pushState === 'function' && isPushState) {
                window.history.pushState({url: link}, '', link);
            }
            $.ajax({
                cache: true,
                url: link,
                data: {isAjax: 1},
                dataType: "json",
                success: function(data) {
                    self.reloadHtml(data);
                    self.initAjax();
                }
            });
        },
        reloadHtml: function(data){
            if ($('.sidebar.sidebar-main .block.filter').first().length == 0) {
                $('.sidebar.sidebar-main').first().prepend("<div class='block filter'></div>");
            }
            $('.sidebar.sidebar-main .block.filter').first().replaceWith(data.navigation);
            $('.sidebar.sidebar-main .block.filter').first().trigger('contentUpdated');

            $('.catalog-topnav .block.filter').first().replaceWith(data.navigationTop);
            $('.catalog-topnav .block.filter').first().trigger('contentUpdated');

            if (data.categoryProducts) {
                $('#amasty-shopby-product-list').replaceWith(data.categoryProducts);
                $('#amasty-shopby-product-list').trigger('contentUpdated');
            } else if (data.cmsPageData != '') {
                $('#amasty-shopby-product-list').replaceWith(data.cmsPageData);
                $('#amasty-shopby-product-list').trigger('contentUpdated');
            }

            $('#page-title-heading').replaceWith(data.h1);
            $('#page-title-heading').trigger('contentUpdated');

            $('.breadcrumbs').replaceWith(data.breadcrumbs);
            $('.breadcrumbs').trigger('contentUpdated');

            $('title').html(data.title);
            if (data.categoryData != '') {
                if ($(".category-view").length == 0) {
                    $('<div class="category-view"></div>').insertAfter('.page.messages');
                }
                $(".category-view").replaceWith(data.categoryData);
            }

            mediaCheck({
                media: '(max-width: 768px)',
                entry: function(){
                    amShopbyTopFilters.moveTopFiltersToSidebar();
                },
                exit: function(){

                    amShopbyTopFilters.removeTopFiltersFromSidebar();
                }
            });

            $("#amasty-shopby-overlay").hide();
            $(document).trigger('amscroll_refresh');
            if (this.options.scrollUp) {
                $(document).scrollTop($('#amasty-shopby-product-list').offset().top);
            }
        },

        initAjax: function()
        {
            var self = this;
            if (this.options.submitByClick === 1) {

                $(document).on('amshopby:apply_filters', '[amshopby-apply-filter=1]', function(event, data, clearUrl){
                    $("#amasty-shopby-overlay").show();
                    $.post( clearUrl, data, function(data){
                        this.reloadHtml(data);
                        window.history.pushState({url: data.url}, '', data.url);
                    }.bind(this), 'json');
                }.bind(this));
            } else {
                $.mage.amShopbyFilterAbstract.prototype.apply = function (link) {
                    self.updateContent(link, true);
                }
                this.options._isAmshopbyAjaxProcessed = false;
                $.mage.productListToolbarForm.prototype.changeUrl = function (paramName, paramValue, defaultValue) {
                    if (self.options._isAmshopbyAjaxProcessed) {
                        return;
                    }
                    self.options._isAmshopbyAjaxProcessed = true;
                    var urlPaths = this.options.url.split('?'),
                        baseUrl = urlPaths[0],
                        urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                        paramData = {},
                        parameters;
                    for (var i = 0; i < urlParams.length; i++) {
                        parameters = urlParams[i].split('=');
                        paramData[parameters[0]] = parameters[1] !== undefined
                            ? window.decodeURIComponent(parameters[1].replace(/\+/g, '%20'))
                            : '';
                    }
                    paramData[paramName] = paramValue;
                    if (paramValue == defaultValue) {
                        delete paramData[paramName];
                    }
                    paramData = $.param(paramData);

                    //location.href = baseUrl + (paramData.length ? '?' + paramData : '');
                    self.updateContent(baseUrl + (paramData.length ? '?' + paramData : ''), true);
                }
                var changeFunction = function (e) {
                    self.updateContent($(this).prop('href'), true);
                    e.stopPropagation();
                    e.preventDefault();
                };
                $(".filter-current a").bind('click', changeFunction);
                $(".filter-actions a").bind('click', changeFunction);
                $(".toolbar .pages a").bind('click', changeFunction);
            }
        }
    });

});
