import $ = require("jquery");

export class Inliners {

    constructor() {
        this._showSearch();
    }

    private _showSearch(): void {
        $('.show-search-overlay').on('click', () => {
            $('.search-overlay').show();
        });
        $('.search-overlay').click(() => {
            $('.search-overlay').hide();
        });
        $('.search-overlay__form').click((event) => {
            event.stopPropagation();
        });
    }
 }