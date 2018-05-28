/**
 * Created by ThaiVH on 20/04/2017.
 */
define(
    [
        'jquery',
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        $,
        Component,
        rendererList
    ) {
        'use strict';
        var methods = [
            {
                type: 'anz_server_hosted',
                component: 'Magenest_Anz/js/view/payment/method-renderer/anz-server-hosted'
            },
            {
                type: 'anz_merchant_hosted',
                component: 'PandaGroup_Checkout/js/anz-merchant-hosted-extend'
            }
        ];

        $.each(methods, function (k, method) {
            rendererList.push(method);
        });
        /** Add view logic here if needed */
        return Component.extend({});
    }
);