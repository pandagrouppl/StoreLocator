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

    //remove empty ID attributes log errors and eliminate  console log error
    $('.slick-cloned button, .slick-cloned select').removeAttr('id');
    $('.slick-cloned .looknbuy__select-options, .slick-cloned .product-info-main__wishlist-wrapper').remove();


};

export = shoplook;
