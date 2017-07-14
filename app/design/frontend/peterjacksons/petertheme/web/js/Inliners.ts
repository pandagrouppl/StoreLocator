import "slick";
import $ = require("jquery");

export class Inliners {

    constructor() {
        this._showSearch();
        this._preventSpinnerClick();
        this._toggleFilters();
        this._toggleResponsiveMenu();
        this._toggleSubmenuResponsive();
        this._toggleSizeChart();
        this._shirtFitGuide();
        this._sliders();
        this._cmsBannerZoom();
        this._toggleFilter();
        this._scrollTopArrow();
        this._footerNav();
        this._footerLinksAlteration();
        this._successCloseOverlay();
    }

    private _toggleFilter(): void {
        $('.layered-nav__title').on('click', function() {
            $(this).find('figure').toggleClass('layered-nav__minus--plus');
            $(this).next().toggle();
        });
    }

    private _showSearch(): void {
        $('.header-right__show-search-overlay').on('click', () => {
            console.log('elo');
            $('.search-overlay').show();
        });
        $('.search-overlay').click(() => {
            $('.search-overlay').hide();
        });
        $('.search-overlay__form').click((event) => {
            event.stopPropagation();
        });
    }

    private _preventSpinnerClick(): void {
        $('.spinner').click((event) => {
            event.preventDefault();
        })
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

    private _toggleSizeChart(): void {
        $(document).on('click', '.product-content__size-chart, .size-chart__close', () => {
            $('#size-chart').toggleClass('size-chart--open');
        });
    }

    private _shirtFitGuide(): void {
        $('.slider-with-text__slides').slick({
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

    private _cmsBannerZoom(): void {
        const img = $('.about-banner');
        $('.cms-banner').hover(
            () => {
                img.css({'background-size': 'auto 110%'})
            },
            () => {
                img.css({'background-size': 'auto 100%'})
            });
    }

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

    private _footerLinksAlteration(): void {
        $(".page-footer__linkbox a").map((i , v) => {
            const e = $(v);
            e.attr('title', e.text());
        });
    }

    private _successCloseOverlay(): void {
        const $overlay = $('.popup-success');
        $('.popup-success__content').click((e) => {
            e.stopPropagation();
        });
        [$overlay,
        $('.popup-success__close'),
        $('.popup-success__content .continue-button')
        ].map(($i) => {
                $i.click(() => {$overlay.hide()});
        });

    }
}