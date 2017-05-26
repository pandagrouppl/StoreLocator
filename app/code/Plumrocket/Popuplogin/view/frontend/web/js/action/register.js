/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'mage/storage',
        'Magento_Ui/js/model/messageList',
        'Magento_Customer/js/customer-data',
        'mage/translate'
    ],
    function ($, storage, globalMessageList, customerData, $t) {
        'use strict';
        var callbacks = [],
            successCallbacks = [],
            action = function (url, registerData, isGlobal, messageContainer) {
                messageContainer = messageContainer || globalMessageList;
                return storage.post(
                    url,
                    registerData,
                    isGlobal,
                    'application/x-www-form-urlencoded'
                ).done(function (response) {
                    if (response.errors) {
                        callbacks.forEach(function (callback) {
                            callback(response);
                        });
                        messageContainer.addErrorMessage(response);
                    } else {
                        customerData.invalidate(['customer']);
                        successCallbacks.forEach(function (callback) {
                            callback(response);
                        });
                    }
                }).fail(function () {
                    var response = {'message': $t('Could not registration. Please try again later')};
                    messageContainer.addErrorMessage(response);
                    callbacks.forEach(function (callback) {
                        callback(response);
                    });
                });
            };

        action.registerCallback = function (callback) {
            callbacks.push(callback);
        };

        action.registerSuccessCallback = function (callback) {
            successCallbacks.push(callback);
        };

        return action;
    }
);
