define(['jquery','Magento_Ui/js/form/components/button'], function ($, Button) {
    'use strict';

    return Button.extend({
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
        }
    });
});

