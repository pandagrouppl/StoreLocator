import $ = require("jquery");
import "slick";

const home = () => {

    // let video = document.getElementById("myVideo");
    //
    // if(video.paused) {
    //     video.play();
    // }

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
        autoplaySpeed: 5000,
        speed: 2000,
        useTransform: true,
        cssEase: 'ease-in-out',
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1440,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4

                }
            },
            {
                breakpoint: 1250,
                settings: {
                    autoplaySpeed: 4000,
                    speed: 2000,
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 950,
                settings: {
                    draggable: true,
                    swipe: true,
                    autoplaySpeed: 3000,
                    speed: 500,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 550,
                settings: {
                    draggable: true,
                    swipe: true,
                    autoplay: false,
                    speed: 200,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
        ]
    });

    // Scroll down button
    // $( '.main-banner__scroll-down' ).click(function(evt) {
    //     evt.preventDefault();
    //     $( 'html, body' ).animate({
    //         scrollTop: $( '#top' ).offset().top - 135
    //     }, 800);
    // });

    $('.search-overlay__row').find('#search').focus();


    $(".main-banner").ready(function() {

        $(".main-banner__img").load(function () {
            $(".main-banner__img").fadeIn(1000);
            $('#home-loader').css("display", "none");
            $(".main-banner__sign").fadeIn(1000);
        });
    });
};

export = home;