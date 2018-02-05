import $ = require("jquery");

/**
 * Call with
 * {"js/submitFormShowPopup":{"popupSelector": "popup id"}}
 * @param config
 * @param element
 */

const module = (config, element) =>  {
    const $form = $(element);
    const $popup = $(`#${config.popupSelector}`);
    $form.submit((event) => {
        event.preventDefault();
        const spinner = $('.panda-spinner');
        spinner.toggleClass('panda-spinner--active');
        const url = $form.attr('action');
        $.ajax({
            url: url,
            method: 'post',
            data: new FormData(element),
            processData: false,
            contentType: false,
            timeout: 0
        }).done((json) => {
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

export = module;