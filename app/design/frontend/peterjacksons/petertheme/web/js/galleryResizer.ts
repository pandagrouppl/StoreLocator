import $ = require("jquery");

/**
 *
 */

const module = (config, element) => {
    const $element = $(element);
    $element.on('gallery:loaded', () => {
        const $window = $(window);
        const $stage = $('.fotorama__stage');
        const resize = () => {
            if ($window.width() > 768 && !(window.frameElement)) {
                $stage.css({'max-height': '58vh'});
            } else {
                $stage.css({'max-height': 'none'});
            }
        };
        resize();
        $window.resize((e) => resize());
    });
};

export = module;