import $ = require("jquery");
import ko = require("knockout");

import * as SizeChart from "./SizeChart";
import * as Inliners from "./Inliners";
import * as newsletter from "./newsletter";
import * as popups from "./popups";

export = class Main {

    constructor() {
    }

    start() {

        $(() =>  {
            const inliners = new Inliners.Inliners();
            const newsletter1 = newsletter('subscribe-form-footer');
            const newsletter2 = newsletter('subscribe-form-blog');
            const popup = new popups.Popups();
            const sizeChart = new SizeChart.SizeChart();
        });
    }
}
