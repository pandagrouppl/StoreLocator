import $ = require("jquery");

export class Inliners {

    constructor() {
        this._showSearch();
        this._preventSpinnerClick();
        this._toggleFilters();
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
 }