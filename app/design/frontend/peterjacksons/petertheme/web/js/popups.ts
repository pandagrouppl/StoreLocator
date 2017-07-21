import $ = require("jquery");

export class Popups {

    constructor() {
        this._faqPopup();
    }

    _faqPopup() {
        $('.faq-click, .faq__close, .faq__overlay').on('click', () => {
            $('.faq__overlay').toggle();
        });
        $('.faq__popup').click((e) => {
            e.stopPropagation();
        });
        $('.faq__title').on('click', function() {
            $(this).toggleClass('faq__title--open').next().slideToggle();
        });
    }
}
