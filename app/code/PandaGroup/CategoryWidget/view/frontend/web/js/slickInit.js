define(['jquery','PandaGroup_CategoryWidget/js/vendor/slick'], function($) {

    /**
     * Inits slick slider on the element. Accepts alternative rules, included in js init data-mage-init='{"slickInit": {"slidesToScroll":"4"}}'
     */

    return function (config, element) {

        $(element).slick_dotsCentered(Object.assign({}, {
            infinite: true,
            autoplay: true,
            autoplaySpeed: 5000,
            speed: 2000,
            useTransform: true,
            cssEase: 'ease-in-out',
            dots: true,
            arrows: false,
            responsive: [
                {
                    breakpoint: 6000,
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
        }, config));
    }
});
