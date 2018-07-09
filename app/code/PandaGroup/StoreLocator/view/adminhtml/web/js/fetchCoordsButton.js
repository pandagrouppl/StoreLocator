define([
    'jquery',
    'Magento_Ui/js/form/components/button',
    'PandaGroup_StoreLocator/js/fetchCoords'
], function (
    $,
    Button,
    fetchCoords
) {
    'use strict';

    return Button.extend({
        action: function () {
            fetchCoords();
        }
    });
});

