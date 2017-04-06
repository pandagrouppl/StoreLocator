import "slick";
import $ = require("jquery");

export class OurMills {

    constructor() {
        this._youtubeOverlay();
    }

    _youtubeOverlay(): void {

        const ovrl = 'youtube-player-overlay';

        $('.youtube-player__play-img').forEach((item, index) => {
            console.log(item, ' AND ' ,index)
        });

        $('.youtube-player__play-img')[0].bind('click', () => {
            $(`.${ovrl}`).addClass(`${ovrl}--active`);
            $('#frame-movie-2')[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        });
        //.forEach(() => {
        //
        //    }
        //
        //);

        $('.popup-movie-1').on('click', function () {
            $('.popup-overlay-movie-1').addClass('active');
            $('#frame-movie-1')[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        });

        $('body').on('click touchstart','.popup-overlay.active', function () {
            $(this).removeClass('active');
            if( $('body').hasClass('cms-our-mills')) {
                $('.video-container iframe')[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                $('.video-container iframe')[1].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                $('.video-container iframe')[2].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            }
        });



        $('.popup-movie-2').on('click', function () {
            $('.popup-overlay-movie-2').addClass('active');
            $('#frame-movie-2')[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        });

        $('.popup-movie-3').on('click', function () {
            $('.popup-overlay-movie-3').addClass('active');
            $('#frame-movie-3')[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        });

        $('.popup-movie-close-1').on('click', function () {
            $('#frame-movie-1')[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        });

        $('.popup-movie-close-2').on('click', function () {
            $('#frame-movie-2')[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        });

        $('.popup-movie-close-3').on('click', function () {
            $('#frame-movie-3')[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        });
        
    }
}
