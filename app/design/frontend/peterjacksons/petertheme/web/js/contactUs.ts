import $ = require("jquery");

const careers = (config, element) =>  {
    const $form = $(element);
    const $popup = $('#contact-success-popup');
    $form.submit((event) => {
        event.preventDefault();
        const spinner = $('.panda-spinner');
        spinner.toggleClass('panda-spinner--active');
        const url = $form.attr('action');
        const form = new FormData($form);
        $.ajax({
            url: url,
            method: 'post',
            data: $form.serialize(),
            processData: false,
            contentType: false,
            timeout: 0
        }).done((json) => {
            console.log(json);
            $('.success-popup__title').text(json.title);
            $('.success-popup__text').text(json.text);
            $popup.show();
        }).fail((json) => {
            $('.success-popup__title').text(json.title);
            $('.success-popup__text').text(json.text);
            $popup.show();
        }).always(() => {
            spinner.toggleClass('panda-spinner--active');
            setTimeout(() => {
                $popup.hide();
            }, 5000)
        });

    });
};

export = careers;