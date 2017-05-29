define([
    'jquery',
    'Magento_Ui/js/modal/alert'
], function ($, alert) {
    'use strict';

    return function (config) {
        var frontendInput = $("#frontend_input").val();
        var id = 'manage-options-panel';
        if(frontendInput == 'swatch_visual') {
            id = 'swatch-visual-options-panel'
        } else if(frontendInput == 'swatch_text') {
            id = 'swatch-text-options-panel';
        }

        var addButtonsFunction = function(){
            $('#'+id+' td.col-delete').each(function(){
                if (!$(this).find('.amshopby-button-option').length) {
                    var optionId = $(this).attr('id').replace('delete_button_swatch_container_', '');
                    optionId = optionId.replace('delete_button_container_', '');

                    $(this).prepend('<button id="settings_button_' + optionId + '" class="amshopby-button-option action-settings" data-option-id="' + optionId + '"><span>' + config.buttonText + '</span></button>');
                }
            });

            $('.amshopby-button-option').on('click', function(e){
                var $button = $(this);
                var optionId = $button.data('option-id');
                //alert(optionId);

                var url = config.url.replace('__option_id__', optionId);
                var modalListSettings = alert({
                    title: config.modalHeadText,
                    content: $('#loader-spinner-html').html(),
                    buttons: [
                        {
                            text: 'Save',
                            class: 'action-primary action-accept',
                            click: function () {
                                $("#edit_options_form").submit();
                                //this.closeModal(true);
                                //amFinderCloseImportPopUp();
                            }
                        },
                        {
                            text: 'Cancel',
                            class: 'action-secondary',
                            click: function () {
                                this.closeModal(true);
                                //amFinderCloseImportPopUp();
                            }
                        }
                    ]
                });

                var functionUpdateModal = function(data){
                    $(modalListSettings).html(data);
                    $(modalListSettings).trigger('contentUpdated');

                    $('#preview_form').submit(function(e){
                        var formObj = $(this);
                        $("#edit_options_form").append($('#loader-spinner-html').html());
                        var formURL = formObj.attr("action");
                        var formData = formObj.serialize();

                        $.ajax({
                            url: formURL,
                            type: 'GET',
                            data:  formData,
                            cache: false,
                            processData:false,
                            success: functionUpdateModal
                        });
                        e.preventDefault(); //Prevent Default action.
                        e.stopPropagation();
                    });

                    $("#edit_options_form").submit(function(e)
                    {
                        var formObj = $(this);
                        $("#edit_options_form").append($('#loader-spinner-html').html());
                        var formURL = formObj.attr("action");
                        var formData = new FormData(this);
                        //$.scrollTo(modalListSettings);
                        /*window.scrollTo(0, 0);
                        $(modalListSettings).animate({
                            scrollTop: 0
                        }, 700);*/

                        $.ajax({
                            url: formURL,
                            type: 'POST',
                            data:  formData,
                            mimeType:"multipart/form-data",
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: functionUpdateModal
                        });
                        e.preventDefault(); //Prevent Default action.
                        e.stopPropagation();
                    });

                };
                $.ajax({
                    url: url,
                    dataType: "html",
                    data: {form_key: FORM_KEY},
                    success: functionUpdateModal
                });
                e.stopPropagation();
                e.preventDefault();
            });
        };

        $('body').on('processStop', addButtonsFunction);
    }
});
