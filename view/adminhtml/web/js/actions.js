define(['jquery','Magento_Ui/js/form/element/select'], function ($, Select) {
    'use strict';

    return Select.extend({
        initialize: function() {
            this._super();
            this.timeout();
        },
        updtSelect: function() {
            var $cntry = $('.admin__control-select[name="country"]');
            var $state = $('.admin__control-select[name="state_source_id"]');
            console.log($cntry);
            $.ajax({
                url: '/storelocator/regions/getbycountry',
                data: {country: $cntry.val()},
                method: 'get'
            })
                .done(function(json) {
                    if (json.status) {
                        $state.empty();
                        for (var state in json.states) {
                            if (json.states.hasOwnProperty(state)) {
                                $state
                                    .append($("<option></option>")
                                        .attr("value",state)
                                        .text(json.states[state]));
                            }
                        }
                    } else {
                        window.alert(json.error);
                    }
                })
                .fail(function() {
                    window.alert('External error!');
                });
        },
        timeout: function() {
            $.proxy(function() {
                window.setTimeout(this.bindClick(), 3000)
            }, this);
        },
        bindClick: function() {
            $(document).ajaxStart(function(){
                $('.admin__form-loading-mask[data-role="spinner"]').show();
            });
            $(document).ajaxComplete(function(){
                $('.admin__form-loading-mask[data-role="spinner"]').hide();
            });
            var $cntry = $('.admin__control-select[name="country"]');
            console.log($cntry);
            $cntry.click(this.updtSelect())
        }
    });

});


