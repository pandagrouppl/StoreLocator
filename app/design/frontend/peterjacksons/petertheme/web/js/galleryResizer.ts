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
            console.log(!($element.hasClass('fotorama--fullscreen')));
            if ($window.width() > 768) {
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