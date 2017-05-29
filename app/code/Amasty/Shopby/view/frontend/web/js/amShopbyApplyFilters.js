define([
    "underscore",
    "jquery",
    "jquery/ui"
], function (_, $) {
    'use strict';

    $.widget('mage.amShopbyApplyFilters', {
        canApplyFilter: false,
        _create: function () {
            var self = this;
            $(function(){
                self.initEvents();
                function normilizeData(data){
                    var normilizeData = [];

                    _.each(data, function(item){
                        if (item.value.trim() != '' && item.value != '-1') {
                            var normilizeItem = _.find(normilizeData, function (normilizeItem) {
                                return normilizeItem.name === item.name && normilizeItem.value === item.value;
                            });

                            if (!normilizeItem) {
                                normilizeData.push(item);
                            }
                        }
                    });
                    return normilizeData;
                }

                var $element = $(self.element[0]);
                var $navigation = $element.closest(self.options.navigationSelector);
                if(self.options.buttonPosition == 'sidebar') {
                    $navigation.find('#narrow-by-list').append($element.parent());
                } else {
                    $navigation.find('strong[role=heading]').addClass('has-apply-button').append($element.parent())
                }

                $element.on('click', function(e){
                    var valid = true;
                    $(this).trigger('amshopby:apply_filters_before');
                    $navigation.find('form').each(function(){
                        valid = valid && $(this).valid();
                    });
                    var forms = $('form[data-amshopby-filter]');
                    var data = normilizeData(forms.serializeArray());
                    var validData = _.filter(data, function(item){
                        return item.name !== 'amasty_shopby_replace_url';
                    });
                    if (valid && self.canApplyFilter) {
                        $(this).trigger('amshopby:apply_filters', [
                            data,
                            self.options.clearUrl
                        ]);
                        if (self.options.ajaxEnabled !== 1) {
                            var params = $.param(data);

                            var url = self.options.clearUrl +
                                (self.options.clearUrl.indexOf('?') === -1 ? '?' : '&') +
                                params;
                            document.location.href = url;
                        }
                    }

                    this.blur();
                    return true;
                });

            });
        },
        initEvents: function(){
            $(document).on("change","[data-amshopby-filter]",function() {
                this.canApplyFilter = true;
            }.bind(this));
        }
    });
});
