import $ = require("jquery");
import visibilityCheck = require("js/visibilityCheck")

/**
 * Shows floating add to cart on mobile view.
 */

const module = (config, element) => {
    const $element = $(element);
    const $header = $('.header');

    $element.click(() => {
        const headerCorrection = $header.outerHeight() + $header.position().top * 2;
        $('html, body').animate({
            scrollTop: $('.product-info-main').offset().top - headerCorrection
        }, 1000);
    });

    $(window).scroll(() => {
        if (visibilityCheck('#product-addtocart-button')) {
            $element.fadeOut();
        } else {
            $element.fadeIn();
        }
    });

};

export = module;