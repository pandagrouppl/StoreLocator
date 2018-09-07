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

    $(document).on('click', '.looknbuy__button', () => {
        $('html, body').animate({
            scrollTop: $(".page-main__message").offset().top - 150
        }, 1500);
    });

    //remove empty ID tags causing console log errors
    $('.slick-cloned button').removeAttr('id');

};

export = shoplook;
