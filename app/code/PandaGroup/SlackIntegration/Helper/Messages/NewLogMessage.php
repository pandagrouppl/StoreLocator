<?php

namespace PandaGroup\SlackIntegration\Helper\Messages;

class NewLogMessage{
    static function getMessage(){
        return __('"attachments": [
                    {
                        "color": "#148dff",
                        "pretext": "#New log",
                        "title": "Log infomation",
                        "fields": [
                            {
                                "title": "Type",
                                "value": "$type",
                                "short":true
                            },
                            {
                                "title": "Module",
                                "value": "$channel",
                                "short":true
                            },
                            {
                                "title": "Message",
                                "value": "$message",
                                "short":false
                            }
                        ],
                        "thumb_url": "http://example.com/path/to/thumb.png",
                        "footer": "PandaGroup",
                        "footer_icon": "https://pandagroup.co/wp-content/uploads/2017/06/cropped-favicon-32x32.png",
                        "ts": $timestamp,
                        "fallback": "#New log"
                    }
                ] ');
    }
}