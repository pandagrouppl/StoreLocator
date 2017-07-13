define(['jquery','Magento_Ui/js/form/element/select'], function ($, Select) {
    'use strict';

    return Select.extend({
        initialize: function() {
            this._super();
            this.timeout();
        },
        timeout: function() {
            var that = this;
            this.checkExist = setInterval(function() {
                if ($('.admin__control-select[name="country"]').length) {
                    that.domReadyInits();
                    clearInterval(that.checkExist);
                }
            }, 200);
        },
        domReadyInits: function() {
            this.$cntry = $('.admin__control-select[name="country"]');
            this.$state = $('.admin__control-select[name="state_source_id"]');
            this.initLoaders();
            this.bindClick();
            this.updtSelect();
        },
        initLoaders: function() {
            $(document).ajaxStart(function(){
                $('.admin__form-loading-mask[data-role="spinner"]').show();
            });
            $(document).ajaxComplete(function(){
                $('.admin__form-loading-mask[data-role="spinner"]').hide();
            });
        },
        bindClick: function() {
            this.$cntry.click(this.updtSelect.bind(this))
        },
        updtSelect: function() {
            var activeState = this.$state.val();
            $.ajax({
                url: '/storelocator/regions/getbycountry',
                context: this,
                data: {country: this.$cntry.val()},
                method: 'get'
            })
                .done(function(json) {
                    if (json.status) {
                        this.$state.empty();
                        for (var state in json.states) {
                            if (json.states.hasOwnProperty(state)) {
                                this.$state
                                    .append($("<option></option>")
                                        .attr("value",state)
                                        .text(json.states[state]));
                            }
                        }
                        if ($('.admin__control-select[name="state_source_id"] option[value=' + activeState + ']').length > 0) {
                            this.$state.val(activeState).trigger("change");;
                        }
                    } else {
                        window.alert(json.error);
                    }
                })
                .fail(function() {
                    window.alert('External error!');
                });
        }
    });

});


