import $ = require("jquery");
import "slick";

/**
 * Inits slick slider on the element. Accepts alternative rules, included in js init data-mage-init='{"slickInit": {"slidesToScroll":"4"}}'
 */

const sl = (config, element) => {

    $(element).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 2,
        speed: 1500,
        prevArrow: '<div class="slick-prev"></div>',
        nextArrow: '<div class="slick-next"></div>',
        responsive: [
            {
                breakpoint: 675,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    /**
     * Fix to reposition slick when it was hidden
     */

    $('.product-main__tab').click(() => {
        $(element).css('opacity', 0);
        setTimeout(() => {
            $(element).slick('setPosition');
            $(element).fadeTo(500, 1, "linear")
        }, 1);
    });
};

export = sl;