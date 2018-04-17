import $ = require("jquery");
import url = require("mage/url");

export class Popups {

    constructor() {
        this._faqPopup();
        this._shippingPopup();
        this._contactPopup();
        this._successCloseOverlay();
        this._showSearch();
        this._cartAdd();
        this._showAccHeaderPanel();
        this._genericPopup();
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
        $('.popup-success, .popup-success__close, .popup-success__content .continue-button').on('click', () => {
            $('.popup-success').hide()
        });
    }

    _cartAdd() {
        $('.success-popup__popup').click((e) => {
            e.stopPropagation();
        });
        $('.success-popup, .success-popup__continue').on('click', () => {
            $('.success-popup').hide();
        });
        $('.success-popup__gotocheckout').on('click', () => {
            window.location.replace(url.build('/checkout/'));
        });
    }

    _showAccHeaderPanel() {
        const $popup = $('.header-left__account-popup');
        $('.header-left__account-popup-toggle').click(() => {
            $popup.toggle();
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


}
