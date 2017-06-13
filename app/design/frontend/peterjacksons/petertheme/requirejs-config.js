var config = {
    deps: [
        "web/js/app"
    ],
    paths: {
        slick: 'js/vendor/slick.min'
    },
    bundles: {
        "web/js/app": [ "main", "sizeChart", 'youtube']
    },
    "map": {
        "*": {
            "tabs": "js/extend/tabs-custom",
            "catalogAddToCart": "js/extend/catalog-add-to-cart"
            //'Magento_Ui/js/form/element/abstract': 'Vendor_ModuleName/js/form/components/collection',
            //'Magento_Ui/templates/form/element/input.html': 'Vendor_ModuleName/template/form/components/collection.html'
        }
    }
};



