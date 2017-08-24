define(["jquery"], function($){
    "use strict";

    var hideMessage = function () {
        $(function() {
            setTimeout(function() {
                $('.page-main__messages').slideUp()
            }, 4000);
        })
    };
    return hideMessage;
});
