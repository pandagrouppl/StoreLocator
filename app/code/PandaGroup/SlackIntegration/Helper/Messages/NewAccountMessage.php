<?php

namespace PandaGroup\SlackIntegration\Helper\Messages;

class NewAccountMessage{
    static function getMessage(){
        return __('"attachments": [
                    {
                        "color": "#148dff",
                        "pretext": "#New account with id: $id",
                        "title": "Customer infomation",
                        "fields": [
                            {
                                "title": "Customer Name",
                                "value": "$customerName",
                                "short":true
                            },
                            {
                                "title": "Email",
                                "value": "$email",
                                "short":true
                            }
                        ],
                        "thumb_url": "http://example.com/path/to/thumb.png",
                        "footer": "PandaGroup",
                        "footer_icon": "https://pandagroup.co/wp-content/uploads/2017/06/cropped-favicon-32x32.png",
                        "ts": $timestamp,
                        "fallback": "#New account with id: $id"
                    }
                ] ');
    }
}