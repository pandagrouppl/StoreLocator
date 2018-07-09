/**
 * Created by ThaiVH on 20/04/2017.
 */
define(
    [
        'jquery',
        'Magenest_Anz/js/view/payment/method-renderer/anz-merchant-hosted',
    ], function ($, Component) {
        'use strict';

        return Component.extend({
            formatCCnum: function (data, e) {
                $(e.target).val(function (index, value) {
                    return value.replace(/\W/gi, '').replace(/(.{4})/g, '$1 ');
                });
                $('[name="payment[cc_number]"]').val(e.target.value.replace(/\s/g,'')).keyup();
            }
        });
    }
);
