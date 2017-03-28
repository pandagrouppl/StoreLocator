import $ = require("jquery");
import ko = require("knockout");

import * as Module1 from "./moduleone";
import * as Module2 from "./moduletwo";
import * as SizeChart from "./sizeChart";
import * as Inliners from "./Inliners";


export = class Main {

    constructor() {
    }

    start() {

        let m1 = new Module1.ModuleOne();
        let m2 = new Module2.ModuleTwo();

        m1.sayHelloTo("David Wesst");
        m2.sayHelloTo("David Wesst");

        $(() =>  {
            let inliners = new Inliners.Inliners();
        });

    }
}
