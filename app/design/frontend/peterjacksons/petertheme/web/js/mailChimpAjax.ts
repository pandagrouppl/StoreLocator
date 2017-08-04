import $ = require("jquery");

const MailChimpAjax = (id) => {
    'use strict';
    const $form = $('#' + id);
    const form = document.getElementById(id);
    console.log(form.checkValidity());
    const $message = $form.children('.newsletter').children('.newsletter__messages');
    console.log($form, $message);
    if ( $form.length > 0 ) {
        $('#' + id +' input[type="submit"]').bind('click', function ( event ) {
            if ( event ) event.preventDefault();
            const url = $form.attr('action');
            $.ajax({
                type: $form.attr('method'),
                url: url,
                data: $form.serialize(),
                dataType    : 'jsonp',
                jsonpCallback: 'subscribeSalesforce'
            }).done(function(json) {
                console.log(json);
                if (json.success == 'True') {
                    $message.text(json.message).css("color", "green");
                    $('.popup-success').show();
                } else {
                    $message.text(json.message).css("color", "red");
                }
            }).fail(function() {
                $message.text('Internal Error! Check your internet connection!').css("color", "red");
            });
        });
    }
};

export = MailChimpAjax;