define(
    [
        'uiComponent'
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'PandaGroup_CommentBox/checkout/order-comment-block'
            },
            getValue: function() {
                var quoteData = window.checkoutConfig.quoteData;

                return quoteData.order_comment;
            }
        });
    }
);
