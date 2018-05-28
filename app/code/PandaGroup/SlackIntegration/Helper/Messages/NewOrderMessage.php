<?php

namespace PandaGroup\SlackIntegration\Helper\Messages;

class NewOrderMessage{
    static function getMessage(){
        return __('"attachments": [{
                            "fallback": "#New Order with id: $id",
                            "color": "#fc3c3c",
                            "pretext": "#New Order with id: $id",
                            "title": "Order infomation",
                            "fields": [{
                                    "title": "Store Name",
                                    "value": "$storeName",
                                    "short": false
                                },
                                {
                                    "title": "Customer Name",
                                    "value": "$customerName",
                                    "short":true
                                },
                                {
                                    "title": "Email",
                                    "value": "$email",
                                    "short":true
                                },
                                {
                                    "title": "Telephone",
                                    "value": "$telephone",
                                    "short":true
                                },
                                {
                                    "title": "Shipping Method",
                                    "value": "$shippingMethod",
                                    "short":true
                                },
                                {
                                    "title": "Total",
                                    "value": "$total",
                                    "short":false
                                },
                                {
                                    "title": "Product list",
                                    "value": "$produstList",
                                    "short":false
                                }],
                            "footer": "PandaGroup",
                            "footer_icon": "https://pandagroup.co/wp-content/uploads/2017/06/cropped-favicon-32x32.png",
                            "ts": $timestamp
                        }] ');
    }
}