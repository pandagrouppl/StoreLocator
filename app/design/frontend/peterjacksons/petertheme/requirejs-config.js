var config = {
    deps: [
        "js/main",
        'js/youtube'
    ],
    paths: {
        slick: 'js/vendor/slick.min'
    },
    "map": {
        "*": {
            "catalogAddToCart": "js/extend/catalog-add-to-cart"
            //'Magento_Ui/js/form/element/abstract': 'Vendor_ModuleName/js/form/components/collection',
            //'Magento_Ui/templates/form/element/input.html': 'Vendor_ModuleName/template/form/components/collection.html'
        }
    }
};



