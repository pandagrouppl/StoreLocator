<?php

namespace PandaGroup\SlackIntegration\Helper\Messages;

class NewReviewMessage{
    static function getMessage(){
        return __('"attachments": [
                    {
                        "fallback": "#New Review",
                        "color": "#e67e22",
                        "pretext": "New Review",
                        "title": "Review infomation",
                        "fields": [
                            {
                                "title": "Product name",
                                "value": "$productName",
                                "short": true
                            },
                            {
                                "title": "Store Name",
                                "value": "$storeName",
                                "short": false
                            },
                            {
                                "title": "Customer name",
                                "value": "$customerName",
                                "short": true
                            },
                            {
                                "title": "Title",
                                "value": "$title",
                                "short": true
                            },
                            {
                                "title": "Rating",
                                "value": "$rating",
                                "short": false
                            },
                            {
                                "title": "Detail",
                                "value": "$detail",
                                "short": false
                            }
                        ],
                        "footer": "PandaGroup",
                        "footer_icon": "https://pandagroup.co/wp-content/uploads/2017/06/cropped-favicon-32x32.png",
                        "ts": $timestamp
                    }
                ]');
    }
}