const containers = document.getElementsByClassName('youtube-player-overlay__player');
if (containers.length) {
    const containers_arr = Array.from(containers);
    function onYouTubeIframeAPIReady() {


        const players = containers_arr.map((item) => (
            new YT.Player(item, {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            })
        ));

        const placeholders = Array.from(document.getElementsByClassName('youtube-player__placeholder'));

        placeholders.map((item, index) => {
            item.addEventListener("click", () => {
                players[index].playVideo();
            })
        });

        const closers = Array.from(document.getElementsByClassName('youtube-player-overlay__close'));
        const overlays = Array.from(document.getElementsByClassName('youtube-player-overlay'));

        overlays.map((item, index) => {
            item.addEventListener("click", () => {
                players[index].pauseVideo();
            })
        });

        function onPlayerReady(event) {

        }

        function changeBorderColor(playerStatus) {

        }

        function onPlayerStateChange(event) {
            changeBorderColor(event.data);
        }

    }
}