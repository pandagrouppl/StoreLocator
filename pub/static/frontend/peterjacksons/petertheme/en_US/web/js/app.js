define("Inliners", ["require", "exports", "jquery", "slick"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Inliners = (function () {
        function Inliners() {
            this._showSearch();
            this._preventSpinnerClick();
            this._toggleFilters();
            this._toggleResponsiveMenu();
            this._toggleSubmenuResponsive();
            this._toggleSizeChart();
            this._shirtFitGuide();
            this._sliders();
            this._cmsBannerZoom();
        }
        Inliners.prototype._showSearch = function () {
            $('.header-right__show-search-overlay').on('click', function () {
                $('.search-overlay').show();
            });
            $('.search-overlay').click(function () {
                $('.search-overlay').hide();
            });
            $('.search-overlay__form').click(function (event) {
                event.stopPropagation();
            });
        };
        Inliners.prototype._preventSpinnerClick = function () {
            $('.spinner').click(function (event) {
                event.preventDefault();
            });
        };
        Inliners.prototype._toggleFilters = function () {
            $('.layered-nav__button').click(function () {
                $('.layered-nav').toggle();
            });
        };
        Inliners.prototype._toggleResponsiveMenu = function () {
            $('.header-left__menu').click(function () {
                $('.header-left__menu-bar').toggleClass('header-left__menu-bar--open');
                $('.header-responsive').slideToggle();
            });
        };
        Inliners.prototype._toggleSubmenuResponsive = function () {
            $('.header-responsive__toggler').click(function () {
                $(this).toggleClass('header-responsive__toggler--open');
                $(this).parent().next().slideToggle();
            });
        };
        Inliners.prototype._toggleSizeChart = function () {
            $(document).on('click', '.product-content__size-chart, .size-chart__close', function () {
                $('#size-chart').toggleClass('size-chart--open');
            });
        };
        Inliners.prototype._shirtFitGuide = function () {
            $('.slider-with-text__slides').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                speed: 1500,
                prevArrow: '<div class="slick-prev"></div>',
                nextArrow: '<div class="slick-next"></div>',
                responsive: [
                    {
                        breakpoint: 675,
                        settings: {}
                    }
                ]
            });
        };
        Inliners.prototype._sliders = function () {
            $('.slider-regular__slides').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                speed: 1500,
                prevArrow: '<div class="slick-prev"></div>',
                nextArrow: '<div class="slick-next"></div>',
                responsive: [
                    {
                        breakpoint: 675,
                        settings: {}
                    }
                ]
            });
        };
        Inliners.prototype._cmsBannerZoom = function () {
            var img = $('.about-banner');
            $('.cms-banner').hover(function () {
                img.css({ 'background-size': 'auto 110%' });
            }, function () {
                img.css({ 'background-size': 'auto 100%' });
            });
        };
        return Inliners;
    }());
    exports.Inliners = Inliners;
});
define("LookBook", ["require", "exports", "jquery"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var LookBook = (function () {
        function LookBook() {
            var _this = this;
            this.time = 2;
            this.$slick = $('.look-book__slider');
            this.$slick.slick({
                draggable: true,
                adaptiveHeight: false,
                mobileFirst: true,
                pauseOnDotsHover: true,
                prevArrow: '<button type="button" class="look-book__arrow look-book__arrow--prev"></button>',
                nextArrow: '<button type="button" class="look-book__arrow look-book__arrow--next"></button>',
                appendArrows: $('.look-book')
            });
            this.$bar = $('.look-book__progress--inside');
            this.$slick.on({
                mouseenter: function () {
                    _this.isPause = true;
                },
                mouseleave: function () {
                    _this.isPause = false;
                }
            });
            this.startProgressbar();
        }
        LookBook.prototype.resetProgressbar = function () {
            this.$bar.css({
                width: 0 + '%'
            });
            clearTimeout(this.tick);
        };
        LookBook.prototype.interval = function () {
            if (this.isPause === false) {
                this.percentTime += 1 / (this.time + 0.1);
                this.$bar.css({
                    width: this.percentTime + "%"
                });
                if (this.percentTime >= 100) {
                    this.$slick.slick('slickNext');
                    this.startProgressbar();
                }
            }
        };
        LookBook.prototype.startProgressbar = function () {
            this.resetProgressbar();
            this.percentTime = 0;
            this.isPause = false;
            this.tick = setInterval(this.interval.bind(this), 30);
        };
        return LookBook;
    }());
    exports.LookBook = LookBook;
});
define("MadeToMeasure", ["require", "exports", "jquery", "slick"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var MadeToMeasure = (function () {
        function MadeToMeasure() {
            this._slider();
            this._arrowDown();
            this._scrollingSuit();
        }
        MadeToMeasure.prototype._scrollingSuit = function () {
            var _this = this;
            $(window).scroll(function () {
                //const $roll = $('.measure-suit');
                var offset = 200;
                //var bottom_of_object = $roll.offset().top + $roll.outerHeight() + offset;
                //var bottom_of_window = $(window).scrollTop() + $(window).height();
                //var object_height: number = $roll.height();
                //let opacity = 1;
                //if ((bottom_of_window + object_height) > bottom_of_object && bottom_of_window < bottom_of_object) {
                //opacity = this._opacityValue((bottom_of_window + object_height - bottom_of_object) / (object_height - offset));
                //$roll.css({'opacity': (1 - opacity)});
                //}
                var self = _this;
                $('.measure-suit__text').each(function (i) {
                    var bottom_of_object = $(this).offset().top;
                    var bottom_of_window = $(window).scrollTop() + $(window).height() - offset + 100;
                    if (bottom_of_window >= bottom_of_object) {
                        var opcty = self._opacityValue((bottom_of_window - bottom_of_object) / 100);
                        $(this).css({ 'opacity': opcty });
                    }
                    else {
                        $(this).css({ 'opacity': '0' });
                    }
                });
            });
        };
        MadeToMeasure.prototype._opacityValue = function (opacity) {
            if (opacity < 0) {
                return 0;
            }
            if (opacity > 1) {
                return 1;
            }
            return opacity;
        };
        MadeToMeasure.prototype._arrowDown = function () {
            $('.measure-top__arrow').on('click', function () {
                $('html, body').animate({ scrollTop: $('.measure-suit').offset().top }, 500);
            });
        };
        MadeToMeasure.prototype._slider = function () {
            $('.measure-slider').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                centerMode: true,
                centerPadding: '10%',
                responsive: [
                    {
                        breakpoint: 675,
                        settings: {
                            centerPadding: '0',
                            arrows: false,
                            autoplay: false
                        }
                    }
                ]
            });
        };
        return MadeToMeasure;
    }());
    exports.MadeToMeasure = MadeToMeasure;
});
define("sizeChart", ["require", "exports", "uiComponent", "jquery"], function (require, exports, Component, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    return Component.extend({
        initialize: function () {
            this._super();
            this.currentTab = $('.size-chart__tabs a').first().attr('href').replace(/^.*?(#|$)/, '');
            this.observe(['currentTab']);
        },
        changeCurrentTab: function (data, event) {
            this.currentTab($(event.target).attr('href').replace(/^.*?(#|$)/, ''));
        },
        isVisibleChart: function (name) {
            if (name === this.currentTab()) {
                return true;
            }
            return false;
        }
    });
});
//export class SizeChart {
//    currentTab: KnockoutObservable<string>;
//
//    constructor() {
//        this.currentTab = ko.observable($('.size-chart__tabs a').first().attr('data-name'));
//    }
//
//    changeCurrentTab(event): void {
//        console.log($(event.target));
//    }
//} 
define("our-mills", ["require", "exports", "jquery", "slick"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var OurMills = (function () {
        function OurMills() {
            this._youtubeOverlay();
            this._millsMenu();
        }
        OurMills.prototype._youtubeOverlay = function () {
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        };
        OurMills.prototype._millsMenu = function () {
            var acitveMenu = 'about-us-wrapper__mills-menu--active';
            var activeLi = 'about-us-wrapper__mills-flexbox--active';
            var menu = $('.about-us-wrapper__mills-menu li');
            var Li = $('.about-us-wrapper__mills-flexbox');
            menu.click(function (event) {
                if (event.target.className != acitveMenu) {
                    menu.toggleClass(acitveMenu);
                    Li.toggleClass(activeLi);
                }
            });
        };
        return OurMills;
    }());
    exports.OurMills = OurMills;
});
define("popups", ["require", "exports", "jquery"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Popups = (function () {
        function Popups() {
            this._faqPopup();
        }
        Popups.prototype._faqPopup = function () {
            $('.faq-click, .faq__close').on('click', function () {
                $('.faq__popup').toggle();
            });
            $('.faq__title').on('click', function () {
                $(this).toggleClass('faq__title--open').next().slideToggle();
            });
        };
        return Popups;
    }());
    exports.Popups = Popups;
});
define("main", ["require", "exports", "jquery", "Inliners", "MadeToMeasure", "LookBook", "our-mills", "popups"], function (require, exports, $, Inliners, MadeToMeasure, LookBook, mills, popups) {
    "use strict";
    return (function () {
        function Main() {
        }
        Main.prototype.start = function () {
            var ourMills = new mills.OurMills();
            $(function () {
                var inliners = new Inliners.Inliners();
                var madeToMeasure = new MadeToMeasure.MadeToMeasure();
                var popup = new popups.Popups();
                var lookBook = new LookBook.LookBook();
            });
            $(document).ajaxComplete(function () {
                var but = $('.checkout__button--calculate');
                //but.appendTo('.authentication-wrapper');
                if ($('.checkout__payments').is(':visible')) {
                    but.hide();
                }
            });
        };
        return Main;
    }());
});
var overlays = Array.from(document.getElementsByClassName('youtube-player-overlay'));
if (overlays.length) {
    var containers_1 = Array.from(document.getElementsByClassName('youtube-player-overlay__player'));
    var placeholders_1 = Array.from(document.getElementsByClassName('youtube-player__placeholder'));
    function onYouTubeIframeAPIReady() {
        var player = [];
        containers_1.map(function (item, i) {
            player.push(new YT.Player(item));
            placeholders_1[i].addEventListener("click", function () {
                overlays[i].className += ' youtube-player-overlay--active';
                player[i].playVideo();
            });
            overlays[i].addEventListener("click", function () {
                player[i].pauseVideo();
                overlays[i].className = 'youtube-player-overlay';
            });
        });
    }
}

//# sourceMappingURL=app.js.map
