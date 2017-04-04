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
    }

    private _showSearch(): void {
        $('.header-right__show-search-overlay').on('click', () => {
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
        $('.layered-nav__button').click(() => {
            $('.layered-nav').toggle();
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
        $('.about-us-wrapper__labels-flexbox-img').slick({
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