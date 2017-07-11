define([
    'jquery'
], function ($) {
    'use strict';

    window.setTimeout( function () {
        var $cntry = $('.admin__control-select[name="country"]');
        var $state = $('.admin__control-select[name="state_source_id"]');
        $cntry.click(function(){
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

        })
    }, 4000);
});