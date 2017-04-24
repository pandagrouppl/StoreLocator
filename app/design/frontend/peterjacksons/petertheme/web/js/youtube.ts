const overlays = Array.from(document.getElementsByClassName('youtube-player-overlay'));

if (overlays.length) {
    const containers = Array.from(document.getElementsByClassName('youtube-player-overlay__player'));
    const placeholders = Array.from(document.getElementsByClassName('youtube-player__placeholder'));

    function onYouTubeIframeAPIReady() {
        const player = [];
        containers.map((item, i) => {
            player.push(new YT.Player(item));
            placeholders[i].addEventListener("click", () => {
                overlays[i].className += ' youtube-player-overlay--active';
                player[i].playVideo();
            });
            overlays[i].addEventListener("click", () => {
                player[i].pauseVideo();
                overlays[i].className = 'youtube-player-overlay';
            });

        });
    }
}