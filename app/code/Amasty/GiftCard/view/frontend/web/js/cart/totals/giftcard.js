define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Amasty_GiftCard/cart/totals/giftcard'
            },
            totals: quote.getTotals(),
            isDisplayed: function() {
                return this.isFullMode() && this.getPureValue() != 0;
            },
            getGiftCardCode: function() {
                if (this.totals()) {
                    return totals.getSegment('amasty_giftcard').title;
                }
                return null;
            },
            getPureValue: function() {
                var price = 0;
                if (this.totals() && totals.getSegment('amasty_giftcard').value) {
                    price = totals.getSegment('amasty_giftcard').value;
                }
                return price;
            },
            getValue: function() {
                return this.getFormattedPrice(this.getPureValue());
            }
        });
    }
);
