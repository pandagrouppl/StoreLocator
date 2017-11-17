import $ = require("jquery");
import "slick";

const home = () => {

    const $slider = $('.slick-init');

    $slider.slick({
        infinite: true,
        draggable: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        speed: 2500,
        prevArrow: '<div class="slick-prev"></div>',
        nextArrow: '<div class="slick-next"></div>',
    });

    $('.home-banner__scroll-down').click(function(evt) {
        evt.preventDefault();
        $('html, body').animate({
            scrollTop: $('#top').offset().top - 135
        }, 800);
    });

};

export = home;