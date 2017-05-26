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
        'mage/translate'
    ],
    function ($, storage, globalMessageList, $t) {
        'use strict';
        var callbacks = [],
            successCallbacks = [],
            action = function (url, forgotData, isGlobal, messageContainer) {
                messageContainer = messageContainer || globalMessageList;
                return storage.post(
                    url,
                    JSON.stringify(forgotData),
                    isGlobal
                ).done(function (response) {
                    if (response.errors) {
                        messageContainer.addErrorMessage(response);
                    } else {
                        messageContainer.addSuccessMessage(response);
                    }
                    callbacks.forEach(function (callback) {
                        callback(response);
                    });
                }).fail(function () {
                    var response = {'message': $t('Could not forgot password. Please try again later')};
                    messageContainer.addErrorMessage(response);
                    callbacks.forEach(function (callback) {
                        callback(response);
                    });
                });
            };

        action.registerCallback = function (callback) {
            callbacks.push(callback);
        };

        return action;
    }
);