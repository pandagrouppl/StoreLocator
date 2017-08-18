import $ = require("jquery");
import ko = require("knockout");

import * as SizeChart from "./SizeChart";
import * as Inliners from "./Inliners";
//import * as MadeToMeasure from "./MadeToMeasure";
import * as newsletter from "./newsletter";
import * as mills from "./our-mills";
import * as popups from "./popups";

export = class Main {

    constructor() {
    }

    start() {

        const ourMills = new mills.OurMills();
        $(() =>  {
            const inliners = new Inliners.Inliners();
            //const madeToMeasure = new MadeToMeasure.MadeToMeasure();
            const newsletter1 = newsletter('subscribe-form-footer');
            const newsletter2 = newsletter('subscribe-form-blog');
            const popup = new popups.Popups();
            const sizeChart = new SizeChart.SizeChart();
        });
    }
}
