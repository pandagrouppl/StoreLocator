import $ = require("jquery");
import visibilityCheck = require("js/visibilityCheck")

/**
 * Shows floating add to cart on mobile view.
 */

const module = (config, element) => {
    const $element = $(element);
    const $header = $('.header');
    const $zopim = $('.catalog-product-view .zopim');

    $element.click(() => {
        const headerCorrection = $header.outerHeight() + $header.position().top * 1.7;
        $('html, body').animate({
            scrollTop: $('.product-info-main').offset().top - headerCorrection
        }, 1000);
    });

    $(window).scroll(() => {
        if (visibilityCheck('#product-addtocart-button')) {
            $element.fadeOut();
            $zopim.css({
                right: '4px', bottom: '12px'
            })

        } else {
            $element.fadeIn();
            $zopim.css({
                right: '4px', bottom: '112px'
            })
        }
    });

};

export = module;