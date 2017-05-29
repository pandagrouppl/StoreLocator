define([
    'prototype'
], function() {
    "use strict";
    return {
        loadUrl: null,
        addUrl: null,
        counter: 0,
        init: function(loadUrl, addUrl, counter){
            loadUrl = loadUrl + (loadUrl.match(new RegExp('\\?')) ? '&isAjax=true' : '?isAjax=true');
            addUrl = addUrl + (addUrl.match(new RegExp('\\?')) ? '&isAjax=true' : '?isAjax=true');
            this.loadUrl = loadUrl;
            this.addUrl = addUrl;
            this.counter = counter;
        },
        load: function(areaId, idx, id){
            new Ajax.Request(this.loadUrl, {
                parameters: {id: id, idx: idx},
                loaderArea: $(areaId),
                onSuccess: function(transport) {
                    try {
                        if (transport.responseText.isJSON()) {
                            var response = transport.responseText.evalJSON()
                            if (response.error) {
                                alert(response.message);
                            }
                            if(response.ajaxExpired && response.ajaxRedirect) {
                                setLocation(response.ajaxRedirect);
                            }
                        } else {
                            $(areaId).update(transport.responseText);
                        }
                    }
                    catch (e) {
                        $(areaId).update(transport.responseText);
                    }
                }
            });
        },
        add: function(areaId, id)
        {
            new Ajax.Request(this.addUrl, {
                parameters: {
                    id: id,
                    counter: this.counter
                },
                onSuccess: function(transport) {
                    try {
                        if (transport.responseText.isJSON()) {
                            var response = transport.responseText.evalJSON()
                            if (response.error) {
                                alert(response.message);
                            }
                            if(response.ajaxExpired && response.ajaxRedirect) {
                                setLocation(response.ajaxRedirect);
                            }

                            if (response.html) {

                                $(areaId).insert({
                                    'before': response.html
                                });
                                this.counter++;
                            }
                        }
                    }
                    catch (e) {
                        $(areaId).insert({
                            'before': transport.responseText
                        });
                    }
                }.bind(this)
            });
        },
        remove: function(areaId)
        {
            $(areaId).remove();
        }
    };
})