define(
    [
        'jquery',
        "underscore",
        'Magento_Ui/js/form/form',
        'ko',
        'Plumrocket_Popuplogin/js/action/login',
        'Plumrocket_Popuplogin/js/action/forgot',
        'Plumrocket_Popuplogin/js/action/register',
        'mage/validation',
        'Plumrocket_Popuplogin/js/model/popuplogin',
        'mage/translate',
    ],
    function (
        $,
        _,
        Component,
        ko,
        loginAction,
        forgotAction,
        registerAction,
        validation,
        popuplogin,
        $t
    ) {
        'use strict';

        var self = null;

        var initInterval;

        return Component.extend({

            isLoading: ko.observable(false),
            defaults: popuplogin.options,

            socialLoginButtons: window.socialLoginButtons,
            socialRegisterButtons: window.socialRegisterButtons,

            initialize: function () {

                self = this;
                this._super();
                return this;
            },

            /** Init popup login window */
            setModalElement: function (element) {
                if (!popuplogin.isCreated) {
                    popuplogin.createPopupLogin(element);
                };
            },

            initForm: function (form) {

                popuplogin.addForm(form.id);
            },

            gotoForm: function (formId) {

                popuplogin.showForm(formId);
                $("#prpl-modal-popup .messages").hide();
            },

            isVisible: function (formId) {

                var visible = false;
                $.each(popuplogin.forms(), function (i, item) {
                    if (item.id == formId) {
                        visible = item.isVisible();
                        return false;
                    }
                });
                return visible;
            },


            /** Provide login action */
            toLogin: function (loginForm) {

                var loginData = {},
                    formDataArray = $(loginForm).serializeArray(),
                    afrerLoginAction = (self.login.success_page == '__complete__')? popuplogin.completeActions(): [];

                formDataArray.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });

                $("#prpl-modal-popup .messages").on('click', 'a', function (e) {
                    e.stopPropagation();
                    popuplogin.applyPopupLoginToExtraItems();
                    if ($(this).hasClass('show_popup_login')) {
                        self.gotoForm($(this).data('form'));
                        e.preventDefault();
                    }
                });

                if ($(loginForm).validation() && $(loginForm).validation('isValid')) {
                    self.isLoading(true);
                    loginAction.registerCallback(function (response) {
                        popuplogin.popupEventTracking('Login', response.message);
                        self.isLoading(false);
                    });
                    loginAction.registerSuccessCallback(function (response) {
                        popuplogin.popupEventTracking('Login', response.message);
                    });
                    afrerLoginAction.forEach(function (action) {
                        loginAction.registerSuccessCallback(action);
                    });
                    popuplogin.popupEventTracking('Login', 'Send request');
                    loginAction(self.login.url, loginData, self.login.success_page_url, false);
                };
            },


            /** Provide forgotpassword action */
            toForgot: function (forgotForm) {

                var forgotData = {},
                    formDataArray = $(forgotForm).serializeArray();

                formDataArray.forEach(function (entry) {
                    forgotData[entry.name] = entry.value;
                });

                $("#prpl-modal-popup .messages").on('click', 'a', function (e) {
                    e.stopPropagation();
                    popuplogin.applyPopupLoginToExtraItems();
                    if ($(this).hasClass('show_popup_login')) {
                        self.gotoForm($(this).data('form'));
                        e.preventDefault();
                    }
                });

                if ($(forgotForm).validation() && $(forgotForm).validation('isValid')) {
                    self.isLoading(true);
                    forgotAction.registerCallback(function (response) {
                        popuplogin.popupEventTracking('Forgot Password', response.message);
                        self.isLoading(false);
                    });
                    popuplogin.popupEventTracking('Forgot Password', 'Send request');
                    forgotAction(self.forgotpassword.url, forgotData, false);
                };
            },


            /** Provide registration action */
            toRegister: function (registerForm) {

                var registerData = {},
                    formDataArray = $(registerForm).serializeArray(),
                    afrerRegisterAction = (self.registration.success_page == '__complete__')? popuplogin.completeActions(): [];

                formDataArray.forEach(function (entry) {
                    registerData[entry.name] = entry.value;
                });

                this.source.set('params.invalid', false);
                this.source.trigger('prpl-popuplogin.data.validate');

                $("#prpl-modal-popup .messages").off('click.prpl-response', 'a');
                $("#prpl-modal-popup .messages").on('click.prpl-response', 'a', function (e) {
                    e.stopPropagation();
                    popuplogin.applyPopupLoginToExtraItems();
                    if ($(this).hasClass('show_popup_login')) {
                        self.gotoForm($(this).data('form'));
                        e.preventDefault();
                    }
                });

                if (!this.source.get('params.invalid')) {
                    self.isLoading(true);

                    registerAction.registerCallback(function (response) {
                        self.isLoading(false);
                        popuplogin.popupEventTracking('Create an User', response.message);
                    });

                    var SuccessCallback = function (response) {
                        var needReload = true;
                        $(window).on('beforeunload', function () {
                            needReload = false;
                        });
                        popuplogin.popupEventTracking('Create an User', response.message);
                        afrerRegisterAction.forEach(function (action) {
                            action();
                        });
                        if (needReload) {
                            if (self.registration.success_page_url) {
                                window.location.href = self.registration.success_page_url;
                            } else {
                                location.reload();
                            }
                        }
                    }
                    registerAction.registerSuccessCallback(function (response) {
                        self.isLoading(false);
                        self.gotoForm('prpl-registration-success');
                        if (self.isVisible('prpl-registration-success')) {
                            setTimeout(function () {
                                SuccessCallback(response);
                            }, 3000);
                        } else {
                            SuccessCallback(response);
                        }
                    });

                    this.source.remove('params');
                    this.source.remove('prpl-popuplogin');

                    popuplogin.popupEventTracking('Create an User', 'Send request');
                    registerAction(self.registration.url, registerData, false);
                };
            }

        });
    }
);
