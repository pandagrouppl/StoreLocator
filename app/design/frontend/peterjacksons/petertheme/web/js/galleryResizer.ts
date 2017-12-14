import $ = require("jquery");
import _ = require('underscore');

/**
 * Resizes gallery to "fit under fold" as Jade required
 */

const module = (config, element) => {
    const $element = $(element);
    $element.on('gallery:loaded', () => {
        const $window = $(window);
        const $stage = $('.fotorama__stage');
        const resize = _.debounce(() => {
            console.log('resize');
            if ($window.width() > 768 && !(window.frameElement)) {
                $stage.css({'max-height': '58vh'});
            } else {
                $stage.css({'max-height': 'none'});
            }
        }, 250);
        resize();
        $window.resize((e) => resize());
    });
};

export = module;