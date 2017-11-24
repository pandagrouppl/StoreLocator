import $ = require("jquery");

/**
 * Used to hide messages, not used right now, left for future use
 * @param config
 * @param element
 */

const module = (config, element) => {
    "use strict";
    console.log('elo', this, config);
    setTimeout(function() {
        $(element).slideUp()
    }, 4000);
};

export = module;

