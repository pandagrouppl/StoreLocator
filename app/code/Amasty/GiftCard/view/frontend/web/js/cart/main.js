define([
    "jquery",
    "jquery/ui"
], function ($) {

    $.widget('mage.amGiftcardCart', {
        options: {},

        _create: function () {
            this.checkCardUrl = this.options.checkCardUrl;
            if ($('#amgiftcard_check_status')) {
                $('#amgiftcard_check_status').on('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var form = $("#amgiftcard-form").serialize();

                    $.ajax({
                        url: this.checkCardUrl,
                        data: {"amgiftcard": form},
                        type: 'post',
                        showLoader: true,
                        success: function(response) {
                            $('#amgiftcard_info').html(response);
                        }
                    });


                }.bind(this));
            }
        }

    });
});