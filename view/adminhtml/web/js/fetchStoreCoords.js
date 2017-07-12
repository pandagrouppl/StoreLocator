define(['jquery','Magento_Ui/js/form/components/button'], function ($, Button) {
    'use strict';

    return Button.extend({
        action: function () {
            var $lat = $('.admin__control-text[name="latitude"]');
            var $lng = $('.admin__control-text[name="longtitude"]');
            $lat.css({transition: "border 1s"});
            $lng.css({transition: "border 1s"});
            var $zoom = $('.admin__control-select[name="zoom_level"]');
            var $cntry = $('.admin__control-select[name="country"]');
            var $cty = $('.admin__control-text[name="city"]');
            var $addr = $('.admin__control-text[name="address"]');
            var $message = $('#google-api-message');
            if (!$message.length) {
                $('button[data-index="google-coords-fetch"]').after('<span id="google-api-message"></span>');
                $message = $($message.selector);
            }
            if ($zoom.val() === " ") {
                $zoom.val("15").trigger("change");
            }
            $.ajax({
                url: 'http://maps.google.com/maps/api/geocode/json',
                context: this,
                data: {address: $cntry.val() + ' ' + $cty.val() + ' ' + $addr.val()},
                method: 'get'
            }).done(function(json) {
                if (json.status == 'OK') {
                    $message.text(' Successfully fetched coordinates for address: ' + json.results[0].formatted_address);
                    $lat.val(json.results[0].geometry.location.lat).css({border: "1px solid #10bf10"}).trigger("change");
                    $lng.val(json.results[0].geometry.location.lng).css({border: "1px solid #10bf10"}).trigger("change");
                } else {
                    $message.text(' Google Api Error! Cannot find such place! Check your Address!');
                }
            }).fail(function() {
                $message.text(' Internal Error! Check your internet connection!');
            });
        }
    });
});

