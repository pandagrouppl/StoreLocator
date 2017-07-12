define(['jquery','domReady'], function ($, domReady) {
    domReady(function () {


            console.log('hello!');
            console.log('start!');
            var $cntry = $('.admin__control-select[name="country"]');
            console.log($('.admin__control-select[name="country"]'));
            var $state = $('.admin__control-select[name="state_source_id"]');
            $(document).ajaxStart(function(){
                $('.admin__form-loading-mask[data-role="spinner"]').show();
            });
            $(document).ajaxComplete(function(){
                $('.admin__form-loading-mask[data-role="spinner"]').hide();
            });


            var $state = $('.admin__control-select[name="state_source_id"]');
            console.log($state);
            $.ajax({
                url: '/storelocator/regions/getbycountry',
                data: {country: $state.val()},
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
                        window.alert('ello');
                    }
                })
                .fail(function() {
                    window.alert('External error!');
                });

            window.setTimeout( function () {
                console.log(this);
                $cntry.click(this.updtSelect)
            }, 4000);


    });
});


