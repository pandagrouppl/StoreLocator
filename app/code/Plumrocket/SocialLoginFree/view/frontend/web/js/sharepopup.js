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
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

define(
    [
        'jquery',
        "underscore",
        'uiComponent',
        'ko',
        'mage/translate',
    ],
    function (
        $,
        _,
        Component,
        ko,
        $t
    ) {
        'use strict';

        var self = null;

        var showPopup  = ($.cookieStorage.get('pslogin_show_popup')) ? $.cookieStorage.get('pslogin_show_popup') : 0;
        if (showPopup) {
            $.cookieStorage.set('pslogin_show_popup', 0);
            $('html').css('overflow', 'hidden');
        }

        return function(options, element) {

            var url = encodeURI(options.url);
            for(var i in options.buttons) {
                if (options.buttons[i]['key'] == 'addthis') {
                    options.buttons[i]['href'] = 'https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url='+url+'&ct=1&title=-&pco=tbxnj-1.0';
                } else {
                    options.buttons[i]['href'] = 'https://api.addthis.com/oexchange/0.8/forward/'+options.buttons[i]['key']+'/offer?url='+url+'&ct=1&title=-&pco=tbxnj-1.0';
                }
                options.buttons[i]['image'] = 'https://cache.addthiscdn.com/icons/v2/thumbs/32x32/'+options.buttons[i]['key']+'.png';
            }
            options.showPopup = showPopup;

            return Component.extend({
                defaults: options,
                hidePopup: function(){
                    $('#'+this.component).hide();
                    $('html').css('overflow', 'auto');
                    return false;
                },
                initialize: function() {
                    self = this;
                    this._super();
                    return this;
                },
            });
        };

    }
);
