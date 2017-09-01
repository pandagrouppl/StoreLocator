import $ = require("jquery");
import "slick";

const suitFit = () => {

    const $slider = $('.init-slick');

    $slider.slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
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

    const $slides = $('.fyf-nav').children();

    $slider.on('beforeChange', (e, slick, currentSlide, nextSlide) => {
        $($slides[currentSlide]).toggleClass('fyf-nav__item--active');
        $($slides[nextSlide]).toggleClass('fyf-nav__item--active');
    });

    $slides.map((i, v) => {
        $(v).click(() => {
            $slider.slick('slickGoTo', i);
        })
    });

};

export = suitFit;