import $ = require("jquery");

const MailChimpAjax = (id) => {
    'use strict';
    const $form = $('#' + id);
    const $message = $form.children('.mailchimp__messages');
    if ( $form.length > 0 ) {
        $('#' + id +' input[type="submit"]').bind('click', function ( event ) {
            if ( event ) event.preventDefault();
            const url = $form.attr('action').replace('/post?', '/post-json?').concat('&c=?');
            $.ajax({
                type: $form.attr('method'),
                url: url,
                data: $form.serialize(),
                dataType    : 'jsonp'
            }).done(function(json) {
                if (json.result == 'success') {
                    $message.text('Successfully signed up.').css("color", "green");
                    $('.popup-success').show();
                } else {
                    $message.text('Please fill in the form.').css("color", "red");
                }
            }).fail(function() {
                $message.text('Internal Error! Check your internet connection!').css("color", "red");
            });
        });
    }
};

export = MailChimpAjax;