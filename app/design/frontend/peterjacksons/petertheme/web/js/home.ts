import $ = require("jquery");
import "slick";

const home = () => {

    const $slider = $( '.slick-init' );
    const $widget = $('.widget__slider-container');

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

    $widget.slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        autoplay: true,
        autoplaySpeed: 7000,
        speed: 2000,
        cssEase: 'linear',
        dots: true,
        pauseOnHover:true,
        arrows: false,
        prevArrow: '<div class="arrows prev"></div>',
        nextArrow: '<div class="arrows next"></div>',
        responsive: [
            {
                breakpoint: 1260,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4

                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 750,
                settings: {
                    draggable: true,
                    swipe: true,
                    autoplaySpeed: 3000,
                    speed: 2500,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 375,
                settings: {
                    draggable: true,
                    swipe: true,
                    autoplaySpeed: 3000,
                    speed: 2000,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
        ]
    });

    // Scroll down button
    $( '.main-banner__scroll-down' ).click(function(evt) {
        evt.preventDefault();
        $( 'html, body' ).animate({
            scrollTop: $( '#top' ).offset().top - 135
        }, 800);
    });

};

export = home;