<?php

namespace PandaGroup\SlackIntegration\Helper\Messages;

class NewContactMessage{
    static function getMessage(){
        return __('"attachments": [{
                        "fallback": "#New Contact.",
                        "color": "#36a64f",
                        "pretext": "#New Contact",
                        "title": "Contact infomation",
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
                                "title": "Comment",
                                "value": "$comment",
                                "short":false
                            }],
                        "footer": "PandaGroup",
                        "footer_icon": "https://pandagroup.co/wp-content/uploads/2017/06/cropped-favicon-32x32.png",
                        "ts": $timestamp
                    }] ');
    }
}