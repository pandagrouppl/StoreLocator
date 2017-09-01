import "slick";
import $ = require("jquery");

export class Inliners {

    constructor() {
        this._preventSpinnerClick();
        this._toggleFilters();
        this._toggleResponsiveMenu();
        this._toggleSubmenuResponsive();
        this._sliders();
        this._toggleFilter();
        this._scrollTopArrow();
        this._footerNav();
        this._footerLinksAlteration();
        this._pinHeader();
        this._headerWidth();
    }

    private _preventSpinnerClick(): void {
        $('.spinner').click((event) => {
            event.preventDefault();
        })
    }

    private _toggleFilter(): void {
        $('.layered-nav__title').on('click', function() {
            $(this).find('figure').toggleClass('layered-nav__minus--plus');
            $(this).next().toggle();
        });
    }

    private _toggleFilters(): void {
        $('.layered-nav__button').click(function() {
            const $sidebar = $('.sidebar.sidebar-main');

            if($(this).hasClass('layered-nav__button--closed')) {
                $sidebar.css('minWidth', '180px');
            } else {
                $sidebar.css('minWidth', '0px');
            }

            $sidebar.find('.layered-nav').toggle();
            $(this).toggleClass('layered-nav__button--closed');
        });
    }

    private _sliders(): void {
        $('.slider-regular__slides').slick({
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
    }

// FOOTER

    // display scrolltop after 900 px
    private _scrollTopArrow(): void {
        const arrow = $('.arrow-scroller');

        $(window).scroll(function(){
            if ($(this).scrollTop() > 900) {
                arrow.fadeIn();
            } else {
                arrow.fadeOut();
            }
        });

        arrow.on('click', () => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        });
    }
    
    private _footerNav(): void {
        $(".page-footer__linkbox h3").on("click", function () {
            if ($(window).width() <= 800) {
                if (true === $(this).hasClass("active")) {
                    $(this).removeClass("active").addClass("inactive");
                    let $ul = $(this).next("ul");
                    $ul.stop().slideUp(400, () => {
                        $ul.css("display","");
                    });

                } else {
                    $(this).removeClass("inactive").addClass("active");
                    $(this).next("ul").stop().slideDown(400);
                }
            }
        });
    }
    // prevents header containers move due to bold on hover
    private _footerLinksAlteration(): void {
        $(".page-footer__linkbox a").map((i , v) => {
            const e = $(v);
            e.attr('title', e.text());
        });
    }


// HEADER

    private _toggleResponsiveMenu(): void {
        $('.header-left__menu').click(() => {
            $('.header-left__menu-bar').toggleClass('header-left__menu-bar--open');
            $('.header-responsive').slideToggle();
        });
    }

    private _toggleSubmenuResponsive(): void {
        $('.header-responsive__toggler').click(function() {
            $(this).toggleClass('header-responsive__toggler--open');
            $(this).parent().next().slideToggle();
        });
    }

    private _pinHeader(): void {
            const cssClassName = 'headers';
            const docked = cssClassName+'--fixed';
            const $nav = $('.'+cssClassName);
            const offset = $('.header-shippingbar').outerHeight();
            $(window).scroll(() => {
                if ($(window).scrollTop() > offset) {
                    $nav.addClass(docked);
                } else {
                    $nav.removeClass(docked);
                }
            });
    }
    // this silly fix is required for Safari bugged column rendering
    private _headerWidth(): void {
        $('.header-middle__columns').each(function() {
            const $this = $(this);
            const len = $this.children().length;
            const cols = Math.ceil(len/6);
            $this.css({
                'flex-basis': 200 * cols,
                '-webkit-flex-basis': 200 * cols,
                'max-width': 200 * cols});
        })
    }


}