import $ = require("jquery");

export class SizeChart {

    constructor() {
        this._toggleSizeChart();
        this._toogleChartTab();
        this.$charts = $('.size-chart__charts');
        this.cssActive = 'size-chart__section--active';
        this.cssActiveTab = 'size-chart__tab--active';
    }

    private _toggleSizeChart(): void {
        $(document).on('click', '.product-info-main__size-chart, .size-chart__close', () => {
            $('#size-chart').toggleClass('size-chart--open');
        });
    }

    private _toogleChartTab(): void {
        $('.size-chart__tab').map((i , v) => {
            const $v = $(v);
            $v.click((e) => {
                this._showTab($v, e)
            });
        });

    }

    private _showTab($slctd, e): void {
        e.preventDefault();
        const url = $slctd.attr('href');
        this.$charts.find(`.${this.cssActive}`).removeClass(this.cssActive);
        this.$charts.find(`[data-href=${url}]`).addClass(this.cssActive);
        $('.size-chart__tabs').find('.' + this.cssActiveTab).removeClass(this.cssActiveTab);
        $slctd.addClass(this.cssActiveTab);
    }
}