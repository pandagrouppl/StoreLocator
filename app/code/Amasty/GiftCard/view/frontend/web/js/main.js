define([
    "jquery",
    "jquery/ui"
], function ($) {

    $.widget('mage.amGiftcard', {
        options: {},
        
        _create: function () {
            this.productId = this.options.productId;
            this.feeType = this.options.feeType;
            this.feeValue = this.options.feeValue;

            if ($('#am_giftcard_amount')) {
                $('#am_giftcard_amount').on('change', this.onGiftChanged.bind(this));
            }
            if ($('#am_giftcard_amount_custom')) {
                $('#am_giftcard_amount_custom').on('change', this.onGiftChanged.bind(this));
            }
            if ($('#am_giftcard_type')) {
                $('#am_giftcard_type').on('change', this.visibilityFields.bind(this));
            }
            if ($('#product-addtocart-button')) {
                $('#product-addtocart-button').on('click', function (event) {
                    $('#am_giftcard_image').show();
                    if (!$('#product_addtocart_form').validate().form()) {
                        $('input#am_giftcard_image').hide();
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        $('input#am_giftcard_image').hide();
                    }
                });
            }

            this.clickImage();
        },

        onGiftChanged: function(event) {
            var value = $('#am_giftcard_amount option:selected').data('value'),
                valueForToggle = event.target.value;

            if(valueForToggle == "custom" || valueForToggle == "") {
                $('#amgiftcard_amount_custom_block').show();
                value = valueForToggle;
                return;
            } else if(event.target.id == 'am_giftcard_amount') {
                $('#amgiftcard_amount_custom_block').hide();
            } else if(value === undefined
                    && typeof valueForToggle === "string"
            ){
                value = valueForToggle;
            }

            var feeValue = parseFloat(this.feeValue);
            if (isNaN(feeValue)) {
                feeValue = 0;
            }
            value = parseFloat(value);

            if (this.feeType == 1) {    // PRICE_TYPE_PERCENT
                value += value * feeValue / 100;
            } else if (this.feeType == 2) {     // PRICE_TYPE_FIXED
                value += feeValue;
            }

            var changes = {
                "giftcard": {
                    "finalPrice": {
                        "amount": value
                    }
                }
            };

            $('#product-price-' + this.productId).trigger('updatePrice', changes);
        },

        visibilityFields: function () {
            var value = event.target.value;
            $.each($('.am_giftcard_recipient_data'), function( key, val ) {
                $(val).show();
            });
            if(value == 2) {
                $.each($('.am_giftcard_recipient_data'), function( key, val ) {
                    $(val).hide();
                });
            }
            if(value == 2 || value == 3) { /*TYPE_PRINTED = 2; TYPE_COMBINED = 3;*/
                $.each($('.am-giftcard-delivery-info'), function( key, val ) {
                    $(val).show();
                });
            } else {
                $.each($('.am-giftcard-delivery-info'), function( key, val ) {
                    $(val).hide();
                });
            }
        },

        clickImage: function () {
            var imageInputField = $('#am_giftcard_image');
            $.each($('.amgiftcard-image'), function( key, val ) {
                $(val).on('click', function(event){
                    $.each($('.amgiftcard-image'), function( key, value ) {
                        $(value).removeClass('selected');
                    });

                    var elem = event.target;
                    $(elem).addClass('selected');
                    imageInputField.val($(val).attr('data-id'));

                    var fullImage = $('img.fotorama__img');
                    if(fullImage) {
                        fullImage.attr('src', $(elem).attr('src'));
                    }

                });
            });

        }

    });

    return $.mage.amGiftcard;
});
