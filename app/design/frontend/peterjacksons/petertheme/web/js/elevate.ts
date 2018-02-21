import $ = require("jquery");
import "slick";

const elevate = () => {

    const $slider = $( '.slick-init' );
    const $widget = $('.widget__slider-container');

    $widget.slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: '<div class="arrows prev"></div>',
        nextArrow: '<div class="arrows next"></div>',
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

    $( '.main-banner__scroll-down' ).click(function(evt) {
        evt.preventDefault();
        $( 'html, body' ).animate({
            scrollTop: $( '#top' ).offset().top - 135
        }, 800);
    });

};

export = elevate;