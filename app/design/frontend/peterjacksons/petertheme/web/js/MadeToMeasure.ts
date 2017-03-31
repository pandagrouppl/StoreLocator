/// <amd-dependency path="slick" />
import $ = require("jquery");

export class MadeToMeasure {

    constructor() {
        this._slider();
        this._arrowDown();
        this._scrollingSuit();
    }

    _scrollingSuit(): void {
        $(window).scroll(() => {

            //const $roll = $('.measure-suit');
            const offset: number = 200;
            //var bottom_of_object = $roll.offset().top + $roll.outerHeight() + offset;
            //var bottom_of_window = $(window).scrollTop() + $(window).height();
            //var object_height: number = $roll.height();
            //let opacity = 1;

            //if ((bottom_of_window + object_height) > bottom_of_object && bottom_of_window < bottom_of_object) {

                //opacity = this._opacityValue((bottom_of_window + object_height - bottom_of_object) / (object_height - offset));
                //$roll.css({'opacity': (1 - opacity)});
            //}
            const self = this;
            $('.measure-suit__text').each(function (i) {

                var bottom_of_object = $(this).offset().top;
                var bottom_of_window = $(window).scrollTop() + $(window).height() - offset + 100;

                if (bottom_of_window >= bottom_of_object) {

                    const opcty = self._opacityValue((bottom_of_window - bottom_of_object) / 100);
                    $(this).css({'opacity': opcty});
                } else {
                    $(this).css({'opacity': '0'});
                }
            });

        });
    }

    _opacityValue(opacity): number {
        if (opacity < 0) {
            return 0;
        }
        if (opacity > 1) {
            return 1;
        }
        return opacity;
    }

    _arrowDown(): void {
        $('.measure-top__arrow').on('click', function () {
            $('html, body').animate({scrollTop: $('.measure-suit').offset().top}, 500);
        });
    }

    _slider(): void {
        $('.measure-slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            centerMode: true,
            centerPadding: '10%',
            responsive: [
                {
                    breakpoint: 675,
                    settings: {
                        centerPadding: '0',
                        arrows: false,
                        autoplay: false
                    }
                }
            ]
        });
    }
}
