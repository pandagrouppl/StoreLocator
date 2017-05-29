define([
    "jquery"
], function ($) {

  return {
        options: {},

        applyCard: function() {
            var data = {};
            data['amgiftcard_add'] = $('#amgiftcard_code').val();
            order.loadArea(
                ['items', 'shipping_method', 'totals', 'billing_method'],
                true,
                data
            );
        },

      removeGiftCard: function(code) {
            var data = {};
            data['amgiftcard_remove'] = code;
            order.loadArea(
                ['items', 'shipping_method', 'totals', 'billing_method'],
                true,
                data
            );
        }
  }
});
