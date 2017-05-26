/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'ko',
        'text!Plumrocket_Popuplogin/template/modal.html'
    ],
    function ($, modal, ko, popupTpl) {
        'use strict';
        var forms = ko.observableArray();
        var unbindPopup = ko.observable(false);

        return {
            isCreated: false,
            isOpened: false,
            $modalWindow: null,
            forms: forms,
            modeSelectors: {1:['.show_popup_login'], 2:['*'], 3:['a', 'button'], 4:['.show_popup_login']},
            formPrefix: 'prpl-',
            modalClass: 'prpl-popuplogin',

            options: window.popupLoginConfig,

            completeActions: ko.observableArray(),

            unbindPopup: unbindPopup,
            subscribeUnbindPopup: unbindPopup.subscribe(function (unbind) {
                if (unbind) {
                    $(document).off(".bindPopup");
                }
            }),


            /** Create popUp window for provided element */
            createPopupLogin: function (element) {
            
                var self = this;
                var modalOptions = {
                    type: 'popup',
                    autoOpen: false,
                    modalClass: this.modalClass,
                    themeClass: this.options.design.theme,
                    //animationClass: this.options.design.animation,
                    logo_src: this.options.design.logo,
                    logo_alt: this.options.design.alt,
                    title: "",
                    popupTpl: popupTpl,
                    responsive: true,
                    innerScroll: false,
                    allowedClose: (this.options.general.close == "0")? false: true,
                    opened: function () {
                        self.isOpened = true;
                    },
                    closed: function () {
                        self.isOpened = false;
                        $('#prpl-modal-inner-wrap').removeClass(self.options.design.animation);
                    },
                    buttons: [],
                };

                this.$modalWindow = $(element);
                this.$modalWindow.modal(modalOptions);
                this.$modalWindow.modal({transitionEvent: null});



                this.applyPopupLoginToExtraItems();
                this.bindPopupLogin();

                this.isCreated = true;
            },


            bindPopupLogin: function () {
            
                var self = this;
                if (this.options.general.mode == 1) {
                    $(document).on('contextmenu.bindPopup', function (e) {
                        return false;
                    });
                    self.showPopupLogin();
                }

                var targets = this.modeSelectors[this.options.general.mode];
                for (var target=0; target<targets.length; target++) {
                    $(document).on('contextmenu.bindPopup', targets[target], function (e) {
                        return false;
                    });
                    $(document).on('keydown.bindPopup mousedown.bindPopup click.bindPopup', targets[target], function (event) {
                        var $target = $(event.target);

                        if ($target.parents('.'+self.modalClass).length || $target.data('popup') == 'off') {
                            return true;
                        };

                        if (self.options.registration.success_page == '__complete__' || self.options.login.success_page == '__complete__') {
                            self.completeActions.push(function () {
                                self.unbindPopup(true);
                                $target.get(0).click();
                            });
                        }

                        event.stopPropagation();
                        event.preventDefault();

                        var defineForm = null;
                        if ($target.data('form')) {
                            defineForm = $target.data('form');
                        } else if ($target.parents(self.modeSelectors[4][0]).length && $($target.parents(self.modeSelectors[4][0]).get(0)).data('form')) {
                            defineForm = $($target.parents(self.modeSelectors[4][0]).get(0)).data('form');
                        };
                        self.showPopupLogin(self.formPrefix + defineForm);
                    });
                };
            },


            showForm: function (formId) {
            
                var self = this;
                formId = (formId)? formId: this.formPrefix+this.options.general.default_form;
                var visible = false;
                $.each(forms(), function (i, item) {
                    item.isVisible(item.id == formId);
                    if (item.id == formId ) {
                        visible = true;
                        if (item.title != 'registration-success' ) {
                            self.popupEventTracking(item.title, 'Show form');
                        }
                    }
                });
                if (!visible) {
                    $.each(forms(), function (i, item) {
                        if (item.title == 'Login' ) {
                            item.isVisible(true);
                            visible = true;
                            self.popupEventTracking(item.title, 'Show form');
                            return false;
                        }
                    });
                }
                if (!visible) {
                    $.each(forms(), function (i, item) {
                        if (item.title == 'Create an User' ) {
                            item.isVisible(true);
                            visible = true;
                            self.popupEventTracking(item.title, 'Show form');
                            return false;
                        }
                    });
                }
                if (!visible) {
                    $.each(forms(), function (i, item) {
                        if (item.title == 'Forgot Password' ) {
                            item.isVisible(true);
                            visible = true;
                            self.popupEventTracking(item.title, 'Show form');
                            return false;
                        }
                    });
                }
                return visible;
            },


            showPopupLogin: function (formId) {
            
                var self = this;
                if (this.isOpened) {
                    return true;
                }
                $('#prpl-modal-inner-wrap').addClass(this.options.design.animation);
                if (!this.showForm(formId)) {
                    var timerId = setInterval(function () {
                        if (self.showForm(formId)) {
                            clearInterval(timerId);
                        }
                    }, 500);
                }
                this.$modalWindow.modal('openModal');
            },


            applyPopupLoginToExtraItems: function () {
            
                if (this.options.login.show == 1) {
                    $('a[href*="/login/"]').addClass('show_popup_login').data('form', this.formPrefix+'login');
                }
                if (this.options.registration.show == 1) {
                    $('a[href*="/account/create/"]').addClass('show_popup_login').data('form', this.formPrefix+'registration');
                }
                if (this.options.forgotpassword.show == 1) {
                    $('a[href*="/account/forgotpassword/"]').addClass('show_popup_login').data('form', this.formPrefix+'forgotpassword');
                }
            },


            addForm: function (formId) {
            
                var formTitles = {};
                    formTitles[this.formPrefix+'login'] = 'Login';
                    formTitles[this.formPrefix+'registration'] = 'Create an User';
                    formTitles[this.formPrefix+'forgotpassword'] = 'Forgot Password';
                    formTitles[this.formPrefix+'registration-success'] = 'registration-success';

                var defaultForm = this.formPrefix+this.options.general.default_form;
                forms.push({
                    id: formId,
                    isVisible: ko.observable(formId == defaultForm),
                    title: formTitles[formId]
                });
            },


            popupEventTracking: function (action, label) {
            
                if (this.options.tracking.google && typeof _gaq !== 'undefined' && _gaq !== false) {
                    _gaq.push(['_trackEvent', 'Popup Login', action, label.replace(/(<([^>]+)>)/ig, '')]);
                }
            }

        }
    }
);