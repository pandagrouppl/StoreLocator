import "slick";
import $ = require("jquery");

export class OurMills {

    constructor() {
        this._youtubeOverlay();
    }

    _youtubeOverlay(): void {

        var tag = document.createElement('script');
        tag.id = 'iframe-demo';
        tag.src = 'https://www.youtube.com/iframe_api';
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);


    }
}
