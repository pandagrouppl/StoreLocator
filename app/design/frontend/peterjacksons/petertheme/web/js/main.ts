import $ = require("jquery");
import ko = require("knockout");

import * as SizeChart from "./sizeChart";
import * as Inliners from "./Inliners";
import * as MadeToMeasure from "./MadeToMeasure";
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
            const popup = new popups.Popups();
            const lookBook = new LookBook.LookBook();
        });

        $(document).ajaxComplete(() => {
            const but = $('.checkout__button--calculate');
            //but.appendTo('.authentication-wrapper');
            if ($('.checkout__payments').is(':visible')) {
                but.hide();
            }
        });

    }
}
