define([
        'jquery',
        'jquery/ui',
        'mage/menu' ],
    function($){
        $.widget('light4website.menu', $.mage.menu, {

            _toggleDesktopMode: function () {
                this._on({
                    // Prevent focus from sticking to links inside menu after clicking
                    // them (focus should always stay on UL during navigation).
                    "mousedown .ui-menu-item > a": function (event) {
                        event.preventDefault();
                    },
                    "click .ui-state-disabled > a": function (event) {
                        event.preventDefault();
                    },
                    "click .ui-menu-item:has(a)": function (event) {
                        var target = $(event.target).closest(".ui-menu-item");
                        if (!this.mouseHandled && target.not(".ui-state-disabled").length) {
                            this.select(event);

                            // Only set the mouseHandled flag if the event will bubble, see #9469.
                            if (!event.isPropagationStopped()) {
                                this.mouseHandled = true;
                            }

                            // Open submenu on click
                            if (target.has(".ui-menu").length) {
                                this.expand(event);
                            } else if (!this.element.is(":focus") && $(this.document[0].activeElement).closest(".ui-menu").length) {

                                // Redirect focus to the menu
                                this.element.trigger("focus", [true]);

                                // If the active item is on the top level, let it stay active.
                                // Otherwise, blur the active item since it is no longer visible.
                                if (this.active && this.active.parents(".ui-menu").length === 1) {
                                    clearTimeout(this.timer);
                                }
                            }
                        }
                    },
                    "mouseenter .ui-menu-item": function (event) {
                        var target = $(event.currentTarget),
                            ulElement,
                            ulElementWidth,
                            width,
                            targetPageX,
                            rightBound;

                        if (target.has('ul')) {
                            ulElement = target.find('ul');
                            ulElementWidth = target.find('ul').outerWidth(true);
                            width = target.outerWidth() * 2;
                            targetPageX = target.offset().left;
                            rightBound = $(window).width();

                            if ((ulElementWidth + width + targetPageX) > rightBound) {
                                ulElement.addClass('submenu-reverse');
                            }
                            if ((targetPageX - ulElementWidth) < 0) {
                                ulElement.removeClass('submenu-reverse');
                            }
                        }

                        // Remove ui-state-active class from siblings of the newly focused menu item
                        // to avoid a jump caused by adjacent elements both having a class with a border
                        target.siblings().children(".ui-state-active").removeClass("ui-state-active");
                        this.focus(event, target);
                    },
                    "mouseleave": function (event) {
                        this.collapseAll(event, true);
                    },
                    "mouseleave .ui-menu": "collapseAll"
                });

                var categoryParent = this.element.find('.all-category'),
                    html = $('html');

                categoryParent.remove();

                if (html.hasClass('nav-open')) {
                    html.removeClass('nav-open');
                    setTimeout(function () {
                        html.removeClass('nav-before-open');
                    }, 300);
                }
            },
        });
        return $.light4website.menu;
    });
