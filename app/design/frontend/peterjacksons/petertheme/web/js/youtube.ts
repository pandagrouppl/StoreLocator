if (!Array.from) {
    Array.from = (function () {
        var toStr = Object.prototype.toString;
        var isCallable = function (fn) {
            return typeof fn === 'function' || toStr.call(fn) === '[object Function]';
        };
        var toInteger = function (value) {
            var number = Number(value);
            if (isNaN(number)) { return 0; }
            if (number === 0 || !isFinite(number)) { return number; }
            return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
        };
        var maxSafeInteger = Math.pow(2, 53) - 1;
        var toLength = function (value) {
            var len = toInteger(value);
            return Math.min(Math.max(len, 0), maxSafeInteger);
        };
        return function from(arrayLike/*, mapFn, thisArg */) {
            var C = this;
            var items = Object(arrayLike);
            if (arrayLike == null) {
                throw new TypeError('Array.from requires an array-like object - not null or undefined');
            }
            var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
            var T;
            if (typeof mapFn !== 'undefined') {
                if (!isCallable(mapFn)) {
                    throw new TypeError('Array.from: when provided, the second argument must be a function');
                }
                if (arguments.length > 2) {
                    T = arguments[2];
                }
            }
            var len = toLength(items.length);
            var A = isCallable(C) ? Object(new C(len)) : new Array(len);
            var k = 0;
            var kValue;
            while (k < len) {
                kValue = items[k];
                if (mapFn) {
                    A[k] = typeof T === 'undefined' ? mapFn(kValue, k) : mapFn.call(T, kValue, k);
                } else {
                    A[k] = kValue;
                }
                k += 1;
            }
            A.length = len;
            return A;
        };
    }());
}

const overlays = Array.from(document.getElementsByClassName('youtube-player-overlay'));
console.log('overlays', overlays);
if (overlays.length) {
    const containers = Array.from(document.getElementsByClassName('youtube-player-overlay__player'));
    const placeholders = Array.from(document.getElementsByClassName('youtube-player__placeholder'));
    console.log('overlays length passed');
    window.onYouTubeIframeAPIReady = () => {
        console.log('yt api rdy');
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