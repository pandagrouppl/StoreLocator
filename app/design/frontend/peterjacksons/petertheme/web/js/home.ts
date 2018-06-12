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
        // slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        arrows: false,
        cssEase: 'linear',
        dots: true,
        centerMode: true,
        responsive: [
            {
                breakpoint: 1260,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1

                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 750,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 375,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
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