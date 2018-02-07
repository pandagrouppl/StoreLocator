import $ = require("jquery");
import visibilityCheck = require("js/visibilityCheck")

/**
 * Shows floating add to cart on mobile view.
 */

const module = (config, element) => {
    const $element = $(element);
    const $header = $('.headers');

    $('#floating-add-to-cart-name').text($('[itemprop=name]').text());
    $('#floating-add-to-cart-text').text($('#product-addtocart-button').text());

    $element.click(() => {
        const headerCorrection = $header.outerHeight() + $header.position().top * 2;
        $('html, body').animate({
            scrollTop: $('.product-info-main').offset().top - headerCorrection
        }, 1000);
    });

    const fadeIfVisible = () => {
        if (visibilityCheck('#product-addtocart-button')) {
            $element.fadeOut();
        } else {
            $element.fadeIn();
        }
    };

    fadeIfVisible();
    $(window).scroll(fadeIfVisible);

};

export = module;