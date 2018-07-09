define(['jquery','PandaGroup_CategoryWidget/js/vendor/slick.min'], function($) {

    /**
    * Inits slick slider on the element. Accepts alternative rules, included in js init data-mage-init='{"slickInit": {"slidesToScroll":"4"}}'
    */

    return function (config, element) {

        $(element).slick(Object.assign({}, {
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            speed: 1000,
            dots: true,
            prevArrow: '<div class="slick-prev"></div>',
            nextArrow: '<div class="slick-next"></div>',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        dots: true
                    }
                },
                {
                    breakpoint: 533,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    }
                }
            ]
        }, config));
    }
});
