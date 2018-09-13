import $ = require("jquery");

const newsletter = (id) => {
    'use strict';
    const $form = $('#' + id);
    const $message = $form.children('.newsletter').children('.newsletter__messages');
    const pattern = /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;

    if ( $form.length > 0 ) {
        $form.submit((event) => {
            event.preventDefault();

            const $emailInput = $('.newsletter__input[name=email]').val();
            const url = $form.attr('action');

            if(pattern.test($emailInput)) {

                $.ajax({
                    type: $form.attr('method'),
                    url: url,
                    data: $form.serialize(),
                    dataType    : 'jsonp',
                    jsonpCallback: 'subscribeSalesforce'
                }).done(function(json) {
                    if (json.success == 'True') {
                        $message.text(json.message).css("color", "green");
                        $('.popup-success').show();
                    } else {
                        $message.text(json.message).css("color", "red");
                    }
                }).fail(function() {
                    $message.text('Internal Error! Check your internet connection!').css("color", "red");
                });
            }
            else {
                $message.text('Please enter a valid email address. For example john@post.com').css("color", "red");
            }
        });
    }
};

export = newsletter;