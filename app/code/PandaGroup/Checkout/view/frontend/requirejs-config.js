var config = {
    "map": {
        "*": {
            "sizeChanger": "PandaGroup_Checkout/js/sizeChanger",
            "qtyChanger": "PandaGroup_Checkout/js/qtyChanger",
            "breadcrumbsChanger": "PandaGroup_Checkout/js/breadcrumbsChanger",
            "Magento_Checkout/js/view/minicart": "PandaGroup_Checkout/js/minicart"
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/payment/default': {
                'PandaGroup_Checkout/js/default-extends': true
            },
            'Magento_Checkout/js/view/payment': {
                'PandaGroup_Checkout/js/move-giftcard-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'PandaGroup_Checkout/js/shipping-extends': true
            }
        }
    }
};