import $ = require("jquery");
import "slick";

export class corporate {

    constructor() {
        this._slick();
    }

    _slick(): void {
        $( '.corporate-slider' ).slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: true,
            centerPadding: '10%',
            responsive: [
                {
                    breakpoint: 675,
                    settings: {
                        centerPadding: '0',
                        dots: true,
                        arrows: false,
                        autoplay: false
                    }
                }
            ]
        });
    }

}
