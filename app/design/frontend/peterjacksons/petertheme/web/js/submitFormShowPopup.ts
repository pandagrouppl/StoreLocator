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

    const fillAndShow = (title, text, popup) => {
        $('.success-popup__title').text(title);
        $('.success-popup__text').text(text);
        popup.show();
        setTimeout(() => {
            popup.hide();
            $('.overlay__overlay').hide();
        }, 3500)
    };

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
            fillAndShow(json.title, json.text, $popup);
        }).fail((json) => {
            fillAndShow(json.title, json.text, $popup);
        }).always(() => {
            spinner.toggleClass('panda-spinner--active');
        });

    });
};

export = module;