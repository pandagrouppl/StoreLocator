/**
 * Filename: canvas360.js
 *
 * Creates a 360 view using an array of images using a canvas element.
 */

function canvas360(params) {

    params = params || {};
    /**
     *  Canvas ID should match the ID or class of the div where the canvas will be added.
     */
    params.canvasId = params.canvasId || false;
    /**
     *  canvasWidth is the width of the canvas element.
     */
    params.canvasWidth = params.canvasWidth || 640;
    /**
     *  canvasHeight is the height of the canvas element.
     */
    params.canvasHeight = params.canvasHeight || 360;
    /**
     *  framesPath is the location where the frames can be found.
     */
    params.framesPath = params.framesPath || 'img/360/';
    /**
     *  framesFile is the filenames array.
     */
    params.framesFile = params.framesFile || '';
    /**
     *  framesCount is the number of frames that will exist.
     */
    params.framesCount = params.framesCount || 36;
    /**
     *  Do we need to reverse the frame order?
     */
    params.framesReverse = params.framesReverse || false;
    /**
     *  logoImagePath is the url of the logo which will show up during loading.
     */
    params.logoImagePath = params.logoImagePath || 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHwAAAAXCAMAAAD+89sVAAAA2FBMVEUAAAD///////////////////z///////////////////////////////////////3//////////fj//////////////vn//////vf/////////++z//////////////////////////////////////////////////////////////////v7////////////////+3Vv9zwr////90SL+56391Db92FP+9NT++On8ywD+7a7+44H90y7+5ZT+44f+6Lv+4XD////9zhr8ygD9zRT8zA391CndTDoyAAAAQnRSTlMAPdnwKAxHHzIEQ51QTS83mBAVVKpXGiQIw70BekrTavdczciQfrCKYIFxhKW3ZOIrr8/rui6zkayn7q6hxG5jgKx0TLk6AAADaklEQVRIx8WU55KiQBCAG5YkUTIo4IquskY2XM6z6L3/G90Ey8F1rup+3X5VCjTNfDM9AZLUHQMjVdwMCGNpV9yvbP0UbvUTaQoYtW0TUFKd49LMd98/PD+///AjAELg6m0GDF0xfBCBEDL5bYMvd+jMzGBhzpMOS3yZoksAHn533fHl5dB13eEnAGxJOAXKLUJDgZq+8LjcBh0RbupcpjcLgNceGyE5vowtEmpmHI7d7zFYLJlSIbQWym8QMri8papiAARlj++3JMMCPwgC3y9xYC7hP1WytbIsNTUk+ZXXdS99uhdcP26vEbr/m1zlRQgi/AGfnyHx5Lg3cMLDvbFpm4yAVef55Ob2HzvE7biId2QpDU6whcXk1SLHbEc41SHzebkirKjXb3wfaVy+RJgVqN3hrD0cjkc8748TnOkj+WYJALuhVYSa6WiM0jQ1p5SovA+ZqQygP/SadCqXMVVFUhTpLN+TZxev8+OBTjXl8+Pjt4eH7461nQM8jdAIYCYo+3hwkuN603+xPEd9hrA7yYNzWd8x6bf3D1+/fPy0GdXV7eKOvlvSLypadvGcp3zOyXyG12WX74d0AclOArBiSrrbIiBkv6g0n8m35006YVkFvmh4CMu/yrkpWOA/shT4nPq47AV7zdYmaxZH+Sa9R6+RGiaHDXuei+UX+1yhihWrfFqTIZAMdkTQRtkhBBEaRZad2nEYhrHZvHbf0n4DYfbPcgfay0Mm759PNzQjlEcrU45mT9y1h0v3E9BDhn8lkIuP1zVvpPZYeHvOqJFETprl1fG66nWlBDYtwGCLQ4QimQNgeJLJzpukmUeRtTJY2DWMbAwDKYyLFPzYDjTT9SXP4Eg00w/XVrRZl6w53SRRhmmYiVBOUsdjEKD6KsvwE7eMp7HCHiHz8QvhByAOs10tguz+QhG8mEzMcAeZq8V2bLu9tua2t3PgmmJlOJPsOt6sATamUL5PPL0yruPlEFQ9r2JbauGCZOsGlmj9jCZ6nItGaE2LJQipXdPdpNfxzVAJXU+GKwJLc+/WIkfROmtd5KiGIKYiW1G6jPnStJnsyySJBCXRayXwqkww8saHKBRO7Q7EtOSX9cSKHcYhGcBUa4RrVIqdqSieTJ1GETr0AP6BwLAdR8rgTZgUtg9vhTM1VPh//AHAidcBf3YHngAAAABJRU5ErkJggg==';
    /**
     *  loaderBarColor should be an HTML color code matching the color you wish
     *  to be used to frame in the progress bar when loading images and data.
     */
    params.loaderBarColor = params.loaderBarColor || '#ffffff';
    /**
     *  loaderFillColor should be an HTML color code matching the color you
     *  wish to be used to fill the progress bar.
     */
    params.loaderFillColor = params.loaderFillColor || '#fecd18';
    /**
     *  loaderFillGradient set's canvas360 to use a gradient fill on the progress bar.
     *  If false it will use loaderFillColor. If true it will draw a gradient from
     *  loaderFillColor to loaderFillColor2.
     */
    params.loaderFillGradient = params.loaderFillGradient || false;
    /**
     *  loaderFillColor2 is the second color for the progressbar if loaderFillGradient
     *  is set to true.
     */
    params.loaderFillColor2 = params.loaderFillColor2 || '#ffffff';

    var frameImages = [];

    var strokeX = 0;
    var strokeY = 0;
    var strokeWidth = 0;
    var strokeHeight = 15;
    var countFrames = 0;
    var loadPercent = 0;
    var curFrame = 0;
    var frameCount = 0;
    var animDirection = 0;
    var countFrames = 0;
    var animatingFrames = false;

    var imagePositionX = 0;
    var imagePositionY = 0;

    //***** Swipe Detection Code

    var HORIZONTAL = 1;
    var VERTICAL = 2;
    var AXIS_THRESHOLD = 30;
    var GESTURE_DELTA = 50;

    var direction = HORIZONTAL;

    /** Extend the window with a custom function that works off of either the
     * new requestAnimationFrame functionality which is available in many modern
     * browsers or utilizes the setTimeout function where not available.
     */

    window.requestAnimFrame = (function() {
        return window.requestAnimationFrame || // Chromium
            window.webkitRequestAnimationFrame || // WebKit
            window.mozRequestAnimationFrame || // Mozilla
            window.oRequestAnimationFrame || // Opera
            window.msRequestAnimationFrame || // IE
            function(callback, element) {
                window.setTimeout(callback, 17);
            };
    })();

    var onswiperight = function(delta) {
        deltaDif = Math.ceil(delta / (GESTURE_DELTA / 2));
        countFrames += deltaDif;
        animDirection = 1;
        if (!animatingFrames) {
            animatingFrames = true;
            animateFrames();
        }
    }
    var onswipeleft = function(delta) {
        deltaDif = deltaDif = Math.ceil(delta / (GESTURE_DELTA / 2));
        countFrames += deltaDif;
        animDirection = 2;
        countFrames += deltaDif;

        if (!animatingFrames) {
            animatingFrames = true;
            animateFrames();
        }
    }
    var inGesture = false;

    var _originalX = 0;
    var _originalY = 0;

    var mousedown = function(event) {
        event.preventDefault();
        inGesture = true;
        _originalX = (event.touches) ? event.touches[0].pageX : event.pageX;
        _originalY = (event.touches) ? event.touches[0].pageY : event.pageY;

        // Only iphone
        if (event.touches && event.touches.length != 1) {
            inGesture = false;
        }
    }
    var mouseup = function() {
        inGesture = false;
    }
    var mousemove = function(event) {
        event.preventDefault();
        var delta = 0;
        var currentX = (event.touches) ? event.touches[0].pageX : event.pageX;
        var currentY = (event.touches) ? event.touches[0].pageY : event.pageY;

        if (inGesture) {

            if (direction == HORIZONTAL) {
                delta = Math.abs(currentY - _originalY);
            } else {
                delta = Math.abs(currentX - _originalX);
            }
            if (delta > AXIS_THRESHOLD) {
                //inGesture = false;
            }
        }

        if (inGesture) {
            if (direction == HORIZONTAL && !params.framesReverse) {
                delta = Math.abs(currentX - _originalX);
                if (currentX > _originalX) {
                    vDirection = 0;
                } else {
                    vDirection = 1;
                }
            } else if (direction == HORIZONTAL && params.framesReverse) {
                delta = Math.abs(currentX - _originalX);
                if (currentX < _originalX) {
                    vDirection = 0;
                } else {
                    vDirection = 1;
                }
            }else{
                delta = Math.abs(currentY - _originalY);
                if (currentY > _originalY) {
                    vDirection = 2;
                } else {
                    vDirection = 3;
                }
            }

            if (delta >= GESTURE_DELTA) {

                var handler = null;
                switch(vDirection) {
                    case 0:
                        handler = onswiperight;
                        break;
                    case 1:
                        handler = onswipeleft;
                        break;
                }
                if (handler != null) {
                    handler(delta);
                }
                _originalX = (event.touches) ? event.touches[0].pageX : event.pageX;
                _originalY = (event.touches) ? event.touches[0].pageY : event.pageY;
                //inGesture = false;
            }
        }
    }
    //***** End swipe detection Code

    var canvas = null;
    var context = null;

    var self = this;

    var number_format = function(number, decimals) {

        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number, prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), sep = ( typeof thousands_sep === 'undefined') ? ',' : thousands_sep, dec = ( typeof dec_point === 'undefined') ? '.' : dec_point, s = '', toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = ( prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    startPreload = function() {

        imageLogo = new Image();
        imageLogo.src = params.logoImagePath;
        imageLogo.onload = function() {
            logoWidth = imageLogo.width;
            logoHeight = imageLogo.height;

            logoX = ((params.canvasWidth - logoWidth) / 2);
            logoY = ((params.canvasHeight - logoHeight) / 2);

            var canvasCentreX = canvas.width / 2;
            var canvasCentreY = canvas.height / 2;
            var gradient = context.createRadialGradient(canvasCentreX, canvasCentreY, 250, canvasCentreX, canvasCentreY, 0);
            gradient.addColorStop(0, "rgb(0, 0, 0)");
            gradient.addColorStop(1, "rgb(125, 125, 125)");
            context.save();
            context.fillStyle = gradient;
            context.fillRect(0, 0, canvas.width, canvas.height);
            context.restore();

            context.drawImage(imageLogo, logoX, logoY);
            context.strokeStyle = params.loaderBarColor;

            strokeX = logoX - 20;
            strokeY = logoY + logoHeight + 10;
            strokeWidth = logoWidth + 40;

            context.strokeRect(strokeX, strokeY, strokeWidth, strokeHeight);
            for ( i = 0; i < params.framesCount; i++) {
                frameImages[i] = new Image();

                var repVal = ((i < 9) ? '0' : '') + (i + 1);
                frameImages[i].src = params.framesPath + params.framesFile.replace('{col}', repVal);
            }
            setTimeout(function() {
                preloadImages();
            }, 20);
        }
    }
    var preloadImages = function() {

        for ( i = 0; i < params.framesCount; i++) {

            if (frameImages[i].complete) {
                loadPercent++;
            }
            loaderWidth = Math.ceil((strokeWidth - 2) * (loadPercent / 100));

        }
        if (!params.loaderFillGradient) {
            context.fillStyle = params.loaderFillColor;
        } else {

            gradient = context.createLinearGradient(strokeX + 1, strokeY + 1, loaderWidth, strokeHeight - 2);
            gradient.addColorStop(0, params.loaderFillColor);
            gradient.addColorStop(1, params.loaderFillColor2);
            context.save();
            context.fillStyle = gradient;
        }

        context.clearRect(strokeX + 1, strokeY + 1, loaderWidth, strokeHeight - 2);
        context.fillRect(strokeX + 1, strokeY + 1, loaderWidth, strokeHeight - 2);
        self = this;

        if (loadPercent >= params.framesCount) {
            // Done so draw and exit;
            drawFrame();
            return;
        } else {
            setTimeout(function() {
                preloadImages();
            }, 20);
        }

    }
    var animateFrames = function() {

        if (animDirection == 1) {
            curFrame++;
        }

        if (animDirection == 2) {
            curFrame--;
        }

        frameCount++;
        if (curFrame < 0) {
            curFrame = params.framesCount - 1;
        }
        if (curFrame > (params.framesCount - 1)) {
            curFrame = 0;
        }
        drawFrame();
        if (frameCount < countFrames) {

            requestAnimFrame(function() {
                animateFrames();
            });

        } else {
            animDirection = 0;
            countFrames = 0;
            frameCount = 0;
            animatingFrames = false;
        }

    }
    var drawFrame = function() {
        if (curFrame > (params.framesCount - 1)) {
            curFrame = 0;
        }
        if (curFrame < 0) {
            curFrame = (params.framesCount - 1);
        }

        currentImage = frameImages[curFrame];

        if (curFrame >= 0 && curFrame < (params.framesCount - 1)) {
            context.drawImage(currentImage, imagePositionX, imagePositionY);
        }

    }
    if (params.canvasId) {
        // Set the variable elem to the object with the specified params.canvasId
        var elem = document.querySelector(params.canvasId);

        // If the element is not an object then show a message letting the user know.
        if (!elem) {
            alert('Invalid element ID.');
            return;
        } else {
            // Create a canvas object.
            canvas = document.createElement("canvas");

            // Set the canvas width to the width defined in the parameters.
            canvas.width = params.canvasWidth;

            // Set the actual height of the canvas object
            canvas.height = params.canvasHeight;

            // Add a class to the element where the canvas will be placed.
            elem.className += ' canvas360Wrapper';

            // Set the width of the div to match the width of the canvas.
            elem.style.width = ((canvas.width) + 'px');

            // Set the height of the div to match the height of the canvas.
            elem.style.height = ((canvas.height) + 'px');

            // Remove the existing HTML in the container element.
            elem.innerHTML = '';

            // Append the canvas to the element.
            elem.appendChild(canvas);

            // Define a context from the canvas.
            context = canvas.getContext('2d');

            // Set a mousedown event on the canvas.
            canvas.onmousedown = mousedown;

            // Map touchstart
            //canvas.addEventListener("touchstart", mousedown, false);
            canvas.ontouchstart = mousedown;

            // Set a mousemove event on the canvas.
            canvas.onmousemove = mousemove;

            // Map touchmove
            //canvas.addEventListener("touchmove", mousemove, false);
            canvas.ontouchmove = mousemove;

            // Set a keydown event on the canvas.
            window.onkeypress = function(event) {
                var k = event.keyCode || event.charCode;
                if (!k)
                    return;
                if (k == 37) {// left arrow
                    if(params.framesReverse) {
                        onswiperight();
                    } else {
                        onswipeleft();
                    }
                } else if (k == 39) {// right arrow
                    if(params.framesReverse) {
                        onswipeleft();
                    } else {
                        onswiperight();
                    }
                }
            }

            // Fix Andriod 4 Chrome bug.
            document.body.addEventListener('touchstart', function() {});

            // Set a mouseup event on the canvas.
            window.onmouseup = mouseup;

            // Start the preload method.
            startPreload();

        }

    }

}

$('a[href="#pop360"]').on('click', function(e) {
    e.preventDefault();
    // Add our modal and target divs
    $('body').append($('<div />').addClass('popbk')).append($('<div />').addClass('pop360'));
    // Notice data attributes lose camelCase
    canvas360({
        canvasId : '.pop360',
        canvasWidth : parseInt($(this).data('canvaswidth')),
        canvasHeight: parseInt($(this).data('canvasheight')),
        framesPath : $(this).data('framespath') || '',
        framesFile : $(this).data('framesfile'),
        framesReverse: !!$(this).data('framesreverse') || false
    });
    // add our closer
    $('.pop360').append('<a class="x-closer">&#215;</a>');
});
$(document).on('click', '.x-closer, .popbk', function(e) {
    $('.popbk, .pop360').fadeOut('fast', function() {
        $('.popbk, .pop360').remove();
    });
});