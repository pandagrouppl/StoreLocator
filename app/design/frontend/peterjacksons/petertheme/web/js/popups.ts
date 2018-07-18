import $ = require("jquery");
import urlBuilder = require("mage/url");

export class Popups {

    constructor() {
        this._faqPopup();
        this._shippingPopup();
        this._contactPopup();
        this._successCloseOverlay();
        this._showSearch();
        this._cartAdd();
        this._genericPopup();
        this._minicartPopup();
    }

    _showSearch() {
        $('[class*="show-search-overlay"]').on('click', () => {
            $('.search-overlay').show();
        });
        $('.search-overlay').click(() => {
            $('.search-overlay').hide();
        });
        $('.search-overlay__form').click((event) => {
            event.stopPropagation();
        });
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

    _shippingPopup() {
        $(' .shipping-returns-click, .product-info-main__free-delivery-trigger ').on(' click ', () => {
            $(' .overlay__overlay-shipping ').toggle();
        });
        $(' .overlay__close, .overlay__overlay ').on(' click ', () => {
            $(' .overlay__overlay-shipping ').hide()
        });
        $(' .overlay__popup ').click((evt) => {
            evt.stopPropagation();
        });
    }

    _contactPopup() {
        $(' .contact-us-click-checkout ').on(' click ', () => {
            $(' .overlay__overlay-contact ').toggle();
        });
        $(' .overlay__close, .overlay__overlay ').on('click', () => {
            $(' .overlay__overlay-contact ').hide()
        });
        $(' .overlay__popup ').click((evt) => {
            evt.stopPropagation();
        });
    }

    _successCloseOverlay() {
        $('.popup-success__content').click((e) => {
            e.stopPropagation();
        });
        $('.popup-success, .popup-success__close, .popup-success__content .continue-button, .success-popup-corporate__close, .success-popup__overlay, .success-popup, .success-popup--contact').on('click', () => {
            $('.popup-success, .success-popup__overlay').hide()
        });
    }

    _cartAdd() {
        $('.success-popup__popup').click((e) => {
            e.stopPropagation();
        });
        $('.success-popup, .success-popup__continue, .success-popup__overlay').on('click', () => {
            $('.success-popup, .success-popup-corporate__popup').hide();
        });
    }

    _genericPopup() {
        const $trigger = $('.show-general-popup');
        const $popup = $('.general-popup');
        console.log($popup);
        $trigger.on('click ', (e) => {
            $(e.currentTarget).next('.general-popup').toggle();
        });
        $('.general-popup__overlay, .general-popup a, .general-popup button').on('click', () => {
            $popup.hide()
        });
        $('.general-popup__popup').click((e) => {
            e.stopPropagation();
        });
    }

    _minicartPopup() {

        $(document).on('click','.minicart__overlay, .minicart__close, .headers', () => {
            $('.minicart__overlay').removeClass('minicart__overlay--shown');
            $('.headers').removeClass('headers--minicart-active');
        });
    }



}
