import $ = require("jquery");
import ko = require("knockout");

import * as SizeChart from "./SizeChart";
import * as Inliners from "./Inliners";
import * as MadeToMeasure from "./MadeToMeasure";
import * as MailChimpAjax from "./mailChimpAjax";
import * as LookBook from "./LookBook";
import * as mills from "./our-mills";
import * as popups from "./popups";

export = class Main {

    constructor() {
    }

    start() {
        const ourMills = new mills.OurMills();
        $(() =>  {
            const inliners = new Inliners.Inliners();
            const madeToMeasure = new MadeToMeasure.MadeToMeasure();
            const mailChimpAjax1 = MailChimpAjax('subscribe-form-footer');
            const mailChimpAjax2 = MailChimpAjax('subscribe-form-blog');
            const popup = new popups.Popups();
            const lookBook = new LookBook.LookBook();
            const sizeChart = new SizeChart.SizeChart();
        });
    }
}
