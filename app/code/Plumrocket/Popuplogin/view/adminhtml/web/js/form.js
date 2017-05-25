define([
    "jquery",
    'domReady!'
], function ($) {
    "use strict";

    $.widget('popuplogin.formfields', {

        _create: function () {
            $('#prpopuplogin_registration_form_fields_inherit').click().click();
            var self = this;
            var _table_ch_items = this.element.find('input.checkbox[name$="[enable]"]');
            self.setChecks(_table_ch_items);
            if ($('#prpopuplogin_registration_form_fields_inherit').length) {
                $('#prpopuplogin_registration_form_fields_inherit').on("click", function () {
                    self.setChecks(_table_ch_items);
                });
            }
        },

        setChecks: function (table_ch_items) {
            var self = this;
            for (var i = 0, _len = table_ch_items.length; i < _len; i++) {
                $(table_ch_items[i]).on("click", self._checkEnable);
                self._checkEnable({target: table_ch_items[i]});
            }
        },

        _checkEnable: function (ev) {
            var chk = $(ev.target);
            var $tr = chk.closest('tr');

            if ($('#prpopuplogin_registration_form_fields_inherit').length && $('#prpopuplogin_registration_form_fields_inherit').is(':checked')) {
                $tr.addClass('not-active');
                chk.prop("disabled", true);
            } else {
                if (!chk.is(':checked')) {
                    $tr.addClass('not-active');
                    if (chk.attr('name').search(/\[password\]/i) >= 0) {
                        var $confirm = chk.closest('tbody').find('input.checkbox[name="groups[registration][fields][form_fields][value][password_confirmation][enable]"]');
                        if (!$confirm.closest('tr').hasClass('not-active')) {
                            $confirm.closest('tr').addClass('not-active');
                        }
                        $confirm.prop("disabled", true);
                    }
                } else {
                    $tr.removeClass('not-active');
                    if (chk.attr('name').search(/\[password\]/i) >= 0 ) {
                        var $confirm = chk.closest('tbody').find('input.checkbox[name="groups[registration][fields][form_fields][value][password_confirmation][enable]"]');
                        if ($confirm.is(':checked')) {
                            $confirm.closest('tr').removeClass('not-active');
                        }
                        $confirm.prop("disabled", false);
                    }
                }
            }
            if (chk.attr('name').search(/\[email\]/i) >= 0) {
                chk.prop("disabled", true);
            }
        }

    });
    
    return $.popuplogin.formfields;
});