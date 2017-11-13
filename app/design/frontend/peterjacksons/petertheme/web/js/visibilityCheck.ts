import $ = require("jquery");

/**
 * Checks element visibility in viewport
 * @returns {Boolean}
 */

const isScrolledIntoView = (elem) => {
    const docViewTop = $(window).scrollTop();
    const docViewBottom = docViewTop + $(window).height();

    const elemTop = $(elem).offset().top;
    const elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
};

export = isScrolledIntoView;