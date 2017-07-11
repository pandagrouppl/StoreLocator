define([
    'jquery'
], function ($) {
    'use strict';

    window.setTimeout( function () {
        var $cntry = $('.admin__control-select[name="country"]');
        console.log($cntry);
        $cntry.click(function(){
            $.ajax({
                url: '/storelocator/regions/getbycountry',
                data: {country: $cntry.val()},
                method: 'get'
            }).done(function(json) {
                    console.log(json);

            }).fail(function() {
                window.alert(err_ajax);
                //$lat.val(err_ajax).css({border: "1px solid #ff0050"});
                //$lng.val(err_ajax).css({border: "1px solid #ff0050"})
            });
        })
    }, 5000);
});