import $ = require("jquery");
import "slick";

const shoplook = () => {

    const $looknbuy = $('.looknbuy-slick');

    $looknbuy.slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 500,
        centerMode: true,
        centerPadding: '5%',
        arrows: true,
        variableWidth: true,
        variableHeight: true,
        prevArrow: '<div class="slick-prev arrows prev"></div>',
        nextArrow: '<div class="slick-next arrows next"></div>',
        responsive: [
            {
                breakpoint: 5000,
                settings: "unslick"
            },
            {
                breakpoint: 769,
                settings: "slick"
            }
        ]
    });

};

export = shoplook;
