import $ = require("jquery");

export class Popups {

    constructor() {
        this._faqPopup();
    }

    _faqPopup() {
        $('.faq-click, .faq__close').on('click', () => {
            $('.faq__popup').toggle();
        });
        $('.faq__title').on('click', function() {
            $(this).toggleClass('faq__title--open').next().slideToggle();
        });
    }
}
