/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLoginFree
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

require([
    'jquery',
    'jquery/ui',
    'mage/adminhtml/events'
], function(pjQuery_1_11_3) {
    // 'use strict';

    pjQuery_1_11_3(function () {
        if(pjQuery_1_11_3('#psloginfree_general_enable').size()) {
            // Disable empty image fields.
            if(typeof varienGlobalEvents != undefined) {
                varienGlobalEvents.attachEventHandler('formSubmit', function() {
                    pjQuery_1_11_3('[id$=icon_btn], [id$=login_btn], [id$=register_btn]').each(function() {
                        var $input = pjQuery_1_11_3(this);
                        var canDisable = true;

                        // If is new value.
                        if($input.val()) {
                            canDisable = false;
                        }

                        // If is set value and not checked "Delete Image".
                        var isImageDelete = pjQuery_1_11_3('#'+ $input.attr('id') +'_delete');
                        if(isImageDelete.size() != false) {
                            if(isImageDelete.is(':checked')) {
                                canDisable = false;
                            }else{
                                // Remove hidden field, to avoid notice after save.
                                isImageDelete.nextAll('input[type="hidden"]').remove();
                            }
                        }

                        if(canDisable) {
                            $input.attr('disabled', 'disabled');
                        }
                    });
                });
            }
        }
        
        // Sortable.
        pjQuery_1_11_3('ul#sortable-visible, ul#sortable-hidden').sortable({
            connectWith: "ul",
            receive: function(event, ui) {
                ui.item.attr('id', ui.item.attr('id').replace(ui.sender.data('list'), pjQuery_1_11_3(this).data('list')));
            },
            update: function(event, ui) {
                var sortable = [
                    pjQuery_1_11_3('#sortable-visible').sortable('serialize'),
                    pjQuery_1_11_3('#sortable-hidden').sortable('serialize')
                ];

                pjQuery_1_11_3('#psloginfree_general_sortable').val( sortable.join('&') );
            },
            stop: function(event, ui) {
                if(this.id == 'sortable-visible' && pjQuery_1_11_3('#'+ this.id +' li').length < 1) {
                    alert('Sorry, "Visible Buttons" list can not be empty');
                    // return false;
                    pjQuery_1_11_3(this).sortable('cancel');
                }
            }
        })
        .disableSelection();
        

        if(pjQuery_1_11_3('#psloginfree_general_sortable_drag_and_drop').css('display') != 'none') {
            if(pjQuery_1_11_3('#psloginfree_general_sortable_inherit').length) {
                pjQuery_1_11_3('#psloginfree_general_sortable_inherit').on('change', function() {
                    var $sortLists = pjQuery_1_11_3('ul#sortable-visible, ul#sortable-hidden');
                    if(pjQuery_1_11_3(this).is(':checked')) {
                        $sortLists.sortable({ disabled: true });
                    }else{
                        $sortLists.sortable({ disabled: false });
                    }
                }).change();
            }
        }else{
            pjQuery_1_11_3('#row_psloginfree_general_sortable').hide();
        }

        /*pjQuery_1_11_3('select').on('change', function() {
            var $list = pjQuery_1_11_3(this);
            pjQuery_1_11_3('ul#sortable-visible, ul#sortable-hidden').find('li[data-enable='+ $list.attr('id') +']').toggle( $list.val() );
        });*/

        // Share Url.
        pjQuery_1_11_3('#psloginfree_share_page').find('option[value=__invitationsoff__], option[value=__none__]').prop('disabled', true);

        // Alert "Not installed".
        pjQuery_1_11_3('.psloginfree-notinstalled').parents('fieldset.config').each(function() {
            var $section = pjQuery_1_11_3('#'+ this.id +'-head').parents('div.entry-edit-head');
            $section.addClass('psloginfree-notinstalled-section');
            $section.find('a').append('<span class="psloginfree-notinstalled-title">(Not installed)</span>');
        });

        // Callback URL.
        pjQuery_1_11_3('.psloginfree-callbackurl-autofocus').on('focus click', function() {
            var $this = pjQuery_1_11_3(this);
            /*// Get provider name.
            var name = $this.parents('tr').attr('id');
            name = name.replace('row_psloginfree_', '').replace('_callbackurl', '');
            $this.val( $this.val().replace('_PROVIDER_', name) );*/

            $this.select();
        })
        .each(function(n, item) {
            var $item = pjQuery_1_11_3(item);
            if($item.val().indexOf('http://') >= 0) {
                $item.next('p.note').find('span span').css('color', 'red');
            }
        });
    });
});