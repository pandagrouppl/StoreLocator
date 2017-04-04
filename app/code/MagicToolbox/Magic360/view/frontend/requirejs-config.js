
var config = {
    config: {
        mixins: {
            'mage/gallery/gallery': {
                'MagicToolbox_Magic360/js/gallery': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'MagicToolbox_Magic360/js/swatch-renderer': true
            },
            /* NOTE: for Magento v2.0.x */
            'Magento_Swatches/js/SwatchRenderer': {
                'MagicToolbox_Magic360/js/swatch-renderer': true
            }
        }
    },
    map: {
        '*': {
            configurable:              'MagicToolbox_Magic360/js/configurable'
        }
    }
};
