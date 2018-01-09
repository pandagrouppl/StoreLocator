var config = {
    deps: [
        "js/main",
        "jquery/jquery.cookie"
    ],
    paths: {
        slick: 'js/vendor/slick.min'
    },
    map: {
        "*": {
            "catalogAddToCart": "js/extend/catalog-add-to-cart",
            "configurable": "js/extend/configurable-extend",
            "Amasty_Quickview/js/amquickview-vendor": "Amasty_Quickview/js/amquickview",
            "Amasty_Quickview/js/amquickview": "js/extend/amquickview",
            "Amasty_Quickview/js/fancybox/jquery.fancybox.min": "js/vendor/jquery.fancybox.min"
        }
    }
};
