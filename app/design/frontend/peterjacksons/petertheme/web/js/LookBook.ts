import $ = require("jquery");

export class LookBook {
    $bar;
    $slick;
    isPause:boolean;
    tick;
    percentTime:number;
    time:number = 2;

    constructor() {
        this.$slick = $('.look-book__slider');
        this.$slick.slick({
            draggable: true,
            adaptiveHeight: false,
            mobileFirst: true,
            pauseOnDotsHover: true,
            prevArrow: '<button type="button" class="look-book__arrow look-book__arrow--prev"></button>',
            nextArrow: '<button type="button" class="look-book__arrow look-book__arrow--next"></button>',
            appendArrows: $('.look-book')
        });

        this.$bar = $('.look-book__progress--inside');

        this.$slick.on({
            mouseenter: () => {
                this.isPause = true;
            },
            mouseleave: () => {
                this.isPause = false;
            }
        });

        this.startProgressbar();
    }

    resetProgressbar() {
        this.$bar.css({
            width: 0+'%'
        });
        clearTimeout(this.tick);
    }

    interval() {
        if (this.isPause === false) {
            this.percentTime += 1 / (this.time + 0.1);
            this.$bar.css({
                width: this.percentTime + "%"
            });
            if (this.percentTime >= 100) {
                this.$slick.slick('slickNext');
                this.startProgressbar();
            }
        }
    }

    startProgressbar() {
        this.resetProgressbar();
        this.percentTime = 0;
        this.isPause = false;
        this.tick = setInterval(this.interval.bind(this), 30);
    }
}
