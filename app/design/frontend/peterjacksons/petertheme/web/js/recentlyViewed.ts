import $ = require("jquery");
import "slick";

const suitFit = () => {

    const $slider = $('.init-slick');

    $slider.slick({
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

                }
            }
        ]
    });
};

export = suitFit;