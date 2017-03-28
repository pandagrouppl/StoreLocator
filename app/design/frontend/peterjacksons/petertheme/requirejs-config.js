var config = {
    deps: [
        "web/js/app"
    ],
    bundles: {
        "web/js/app": [ "main", "sizeChart" ]
    },
    "map": {
        "*": {
            "tabs": "js/extend/tabs-custom"
            //'Magento_Ui/js/form/element/abstract': 'Vendor_ModuleName/js/form/components/collection',
            //'Magento_Ui/templates/form/element/input.html': 'Vendor_ModuleName/template/form/components/collection.html'
        }
    }
};



