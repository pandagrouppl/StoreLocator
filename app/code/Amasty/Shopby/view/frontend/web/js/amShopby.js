/**
 * @author    Amasty Team
 * @copyright Copyright (c) Amasty Ltd. ( http://www.amasty.com/ )
 * @package   Amasty_Shopby
 */
define([
    "jquery",
    "jquery/ui",
    "mage/tooltip",
    'mage/validation',
    'mage/translate',
    "Amasty_Shopby/js/jquery.ui.touch-punch.min",
    'Amasty_Shopby/js/chosen/chosen.jquery'
], function ($) {
    'use strict';

    $.widget('mage.amShopbyFilterAbstract', {
        filters: {},
        options: {
            isAjax: 0,
            collectFilters: 0
        },
        getFilter: function () {
            var filter = {
                'code': this.element.attr('amshopby-filter-code'),
                'value': this.element.attr('amshopby-filter-value')
            };
            return filter;
        },
        getFilterCode: function (filter) {
            return filter.code + '-' + filter.value;
        },
        addFilter: function () {
            var filter = this.getFilter();
            this.filters[this.getFilterCode(filter)] = filter;
        },
        cancelFilter: function () {
            var filter = this.getFilter();
            var filterCode = this.getFilterCode(filter);
            if (_.has(this.filters, filterCode)) {
                delete this.filters[filterCode];
            }
        },
        apply: function (link) {
            if (this.options.collectFilters === 1 && !this.element.attr('amshopby-forceload')) {
                this.addFilter();
            } else {
                window.location = link;
            }
        },
        getSignsCount: function (step, isPrice) {
            if ((step < 1 && step > 0) && !isPrice) {
                return step.toString().length - step.toString().indexOf(".") - 1;
            }

            return 0;
        },
        getFloatNumber: function (size) {
            if (!size) {
                size = 2;
            }

            return 1 / parseInt(this.buildNumber(size));
        },
        buildNumber: function (size) {
            var str = "1";
            for (var i = 1; i <= size; i++) {
                str += "0";
            }

            return str;
        },
        getFixed: function (value, isPrice) {
            var fixed = 2;
            if (value) {
                fixed = this.getSignsCount(this.options.step, isPrice);
            }

            return fixed;
        },
        isPrice: function () {
            return (typeof this.options.code != 'undefined' && this.options.code == 'price');
        },
        getPriceCorrect: function() {
            return 0.01;
        }
    });

    $.widget('mage.amShopbyFilterItemDefault', $.mage.amShopbyFilterAbstract, {
        options: {},
        _create: function () {
            var self = this;
            $(function () {
                var link = self.element;
                var parent = link.parents('.item');
                var checkbox = link.find('input[type=checkbox], input[type=radio]');

                var params = {
                    parent: parent,
                    checkbox: checkbox,
                    link: link
                };

                checkbox.bind('click', params, function (e) {
                    var checkbox = $(this);
                    var link = e.data.link;
                    self.apply(link.prop('href'));
                    setTimeout(function () {
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        checkbox.trigger('change');
                    }, 10);
                    e.stopPropagation();
                    e.preventDefault();
                });

                link.bind('click', params, function (e) {
                    var element = e.data.checkbox;
                    element.prop('checked', !element.prop('checked'));
                    self.apply(e.data.link.prop('href'));
                    element.trigger('change');
                    e.stopPropagation();
                    e.preventDefault();
                });

                parent.bind('click', params, function (e) {
                    var element = e.data.checkbox;
                    var link = e.data.link;
                    element.prop('checked', !element.prop('checked'));
                    element.trigger('change');
                    self.apply(link.prop('href'));
                    e.stopPropagation();
                });

                checkbox.on('change', function (e) {
                    self.markAsSelected($(this));
                });

                checkbox.on('amshopby:sync_change', function (e) {
                    self.markAsSelected($(this));
                });

                self.markAsSelected(checkbox);
            })
        },
        markAsSelected: function (checkbox) {
            checkbox.closest('form').find('a').each(function () {
                if (!$(this).find('input').prop("checked")) {
                    $(this).removeClass('am_shopby_link_selected');
                } else {
                    $(this).addClass('am_shopby_link_selected');
                }
            });
        }
    });


    $.widget('mage.amShopbyFilterCategoryLabelsFolding', $.mage.amShopbyFilterAbstract, {
        options: {},
        _create: function () {
            var self = this;
            $(function () {

                var link = self.element;
                var parent = $(link.parents('.item')[0]);
                var checkbox = link.find('input[type=checkbox], input[type=radio]');

                var params = {
                    parent: parent,
                    checkbox: checkbox,
                    link: link
                };

                checkbox.bind('click', params, function (e) {
                    var link = e.data.link;
                    self.apply(link.prop('href'));
                    self.toggleCheckParentAndChildrenCheckboxes(e.data.parent, e.data.checkbox.prop('checked'));
                    e.stopPropagation();
                });

                link.bind('click', params, function (e) {
                    var element = e.data.checkbox;
                    element.prop('checked', !element.prop('checked'));
                    self.apply(e.data.link.prop('href'));
                    self.toggleCheckParentAndChildrenCheckboxes(e.data.parent, element.prop('checked'));
                    element.trigger('change');
                    e.stopPropagation();
                    e.preventDefault();
                });

                checkbox.on('change', function (e) {
                    self.markAsSelected($(this));
                });

                checkbox.on('amshopby:sync_change', function (e) {
                    self.markAsSelected($(this));
                });

                self.markAsSelected(checkbox);
            })
        },
        markAsSelected: function (checkbox) {
            checkbox.closest('form').find('a').each(function () {
                if (!$(this).find('input').prop("checked")) {
                    $(this).removeClass('am_shopby_link_selected');
                } else {
                    $(this).addClass('am_shopby_link_selected');
                }
            });
        },
        toggleCheckParentAndChildrenCheckboxes: function (element, isChecked) {
            element.find('.items input[type="checkbox"]').prop('checked', false);
            element.parents('.item').find('>a input[type=checkbox]').prop('checked', false);
        }
    });

    $.widget('mage.amShopbyFilterDropdown', $.mage.amShopbyFilterAbstract, {
        options: {},
        _create: function () {
            var self = this;
            $(function () {
                var $select = $(self.element[0]);
                $select.change(function () {
                    self.apply($select.find("option[value='" + $select.val() + "']").attr("href"));
                });
            })
        }
    });

    $.widget('mage.amShopbyFilterMultiselect', $.mage.amShopbyFilterAbstract, {
        options: {},
        _create: function () {
            var self = this;
            $(function () {
                var $select = $(self.element[0]);
                $select.chosen({
                    width: '100%',
                    placeholder_text: self.options.placeholderText
                });
                $select.change(function (ev, elem) {
                    self.apply($select.find("option[value='" + elem.selected + "']").attr("href"));
                });
            })
        }
    });

    $.widget('mage.amShopbyFilterSwatch', $.mage.amShopbyFilterAbstract, {
        options: {},
        _create: function () {
            var self = this;
            $(function () {
                var inputSelector = '[name="amshopby[' + getAtrtibuteCode() + '][]"]';

                function getAtrtibuteCode() {
                    return $(self.element[0]).closest('form[data-amshopby-filter]').attr('data-amshopby-filter');
                }

                function checked($link) {
                    return $link.find(inputSelector).prop('checked') == 1
                }

                $(self.element[0]).find('a').on('click', function (e) {
                    var $link = $(this);
                    self.apply($link.attr('href'));
                    var $input = $link.find(inputSelector);
                    $input.prop('checked', checked($link) ? 0 : 1);
                    $input.trigger('change');
                    markSelected();
                    e.stopPropagation();
                    e.preventDefault();
                });

                $(self.element[0]).find(inputSelector).on('amshopby:sync_change', markSelected);

                function markSelected() {
                    $(self.element[0]).find('a').each(function () {
                        var $link = $(this);

                        if (checked($link)) {
                            $link.find('.swatch-option').addClass('selected');
                        } else {
                            $link.find('.swatch-option').removeClass('selected');
                        }
                    });
                }
            })
        }
    });

    $.widget('mage.amShopbyFilterSlider', $.mage.amShopbyFilterAbstract, {
        options: {},
        slider: null,
        value: null,
        display: null,
        _create: function () {
            $(function () {
                this.value = this.element.find('[amshopby-slider-id="value"]');
                this.slider = this.element.find('[amshopby-slider-id="slider"]');
                this.display = this.element.find('[amshopby-slider-id="display"]');
                var fromLabel = this.options.from ? this.options.from : this.options.min;
                var toLabel = this.options.to ? this.options.to : this.options.max;
                this.slider.slider({
                    step: this.options.step,
                    range: true,
                    min: this.options.min,
                    max: this.options.max,
                    values: [fromLabel, toLabel],
                    slide: this.onSlide.bind(this),
                    change: this.onChange.bind(this)
                });

                if (this.options.from && this.options.to) {
                    this.setValue(this.options.from, this.options.to, false);
                } else {
                    this.value.trigger('change');
                }

                this.renderLabel(fromLabel, toLabel);

                this.value.on('amshopby:sync_change', this.onSyncChange.bind(this));

                if (this.options.hideDisplay) {
                    this.display.hide();
                }
            }.bind(this));
        },

        onChange: function (event, ui) {
            if (this.slider.skipOnChange !== true) {
                this.setValue(ui.values[0], ui.values[1], true);
            }

            return true;
        },

        onSlide: function (event, ui) {
            var from = ui.values[0];
            var to = ui.values[1];

            this.setValue(from, to, false);
            this.renderLabel(from, to);
            return true;
        },

        onSyncChange: function (event, values) {
            var value = values[0].split('-');
            if (value.length === 2) {
                var fixed = this.getSignsCount(this.options.step, 0);
                var extraToValue = this.getFloatNumber(this.getSignsCount(this.options.step, this.isPrice()));
                var from = (parseFloat(value[0])).toFixed(fixed);
                var to = parseFloat(value[1]).toFixed(fixed);
                to = parseFloat(to) + parseFloat(extraToValue);
                var options = {
                    values: [
                        parseFloat(from).toFixed(fixed),
                        parseFloat(to).toFixed(fixed)
                    ]
                };

                this.slider.skipOnChange = true;

                this.slider.slider('values', [parseFloat(from).toFixed(fixed), parseFloat(to).toFixed(fixed)]);
                this.setValueWtihoutChange(parseFloat(from).toFixed(fixed), parseFloat(to).toFixed(fixed));
                this.slider.skipOnChange = false;
            }
        },
        setValue: function (from, to, apply) {
            var fixed = this.getSignsCount(this.options.step, 0);
            from = (parseFloat(from)).toFixed(fixed);
            to = (parseFloat(to)).toFixed(fixed);
            var newVal = from + '-' + to;

            var changed = this.value.val() != newVal;
            this.value.val(newVal);
            if (changed) {
                this.value.trigger('change');
            }
            var fixed = this.getSignsCount(this.options.step, 0);
            var extraToValue = this.getFloatNumber(this.getSignsCount(this.options.step, this.isPrice()));
            from = (parseFloat(from)).toFixed(fixed);
            to = parseFloat(to).toFixed(fixed);
            to = parseFloat(to) + parseFloat(extraToValue);
            newVal = from + '-' + to;
            this.value.val(newVal);
            if (apply !== false) {
                var linkHref = this.options.url.replace('amshopby_slider_from', from).replace('amshopby_slider_to', to);
                this.apply(linkHref);
            }
        },
        setValueWtihoutChange: function(from, to) {
            var fixed = this.getSignsCount(this.options.step, 0);
            var extraToValue = this.getFloatNumber(this.getSignsCount(this.options.step, this.isPrice()));
            from = (parseFloat(from)).toFixed(fixed);
            to = parseFloat(to).toFixed(fixed);
            to = parseFloat(to) + parseFloat(extraToValue);
            var newVal = from + '-' + to;
            this.value.val(newVal);

            this.value.val(newVal);

        },
        getLabel: function (from, to) {
            return this.options.template.replace('{from}', from.toString()).replace('{to}', to.toString());
        },
        renderLabel: function (from, to) {
            var fixed = this.getSignsCount(this.options.step, 0);
            from = (parseFloat(from)).toFixed(fixed);
            to = (parseFloat(to)).toFixed(fixed);
            this.display.html(this.getLabel(from, to));
        }
    });

    $.widget('mage.amShopbyFilterFromTo', $.mage.amShopbyFilterAbstract, {
        from: null,
        to: null,
        value: null,
        timer: null,
        go: null,
        skip:false,
        _create: function () {
            $(function () {
                this.value = this.element.find('[amshopby-fromto-id="value"]');
                this.from = this.element.find('[amshopby-fromto-id="from"]');
                this.to = this.element.find('[amshopby-fromto-id="to"]');
                this.go = this.element.find('[amshopby-fromto-id="go"]');

                this.value.on('amshopby:sync_change', this.onSyncChange.bind(this));
                this.value.trigger('amshopby:sync_change', [[this.value.val() ? this.value.val() : '']]);
                if (this.go.length > 0) {
                    this.go.on('click', this.applyFilter.bind(this));
                }

                this.changeEvent(this.from, this.onChange.bind(this));
                this.changeEvent(this.to, this.onChange.bind(this));

                this.element.find('form').mage('validation', {
                    errorPlacement: function (error, element) {
                        var parent = element.parent();
                        if (parent.hasClass('range')) {
                            parent.find(this.errorElement + '.' + this.errorClass).remove().end().append(error);
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    messages: {
                        'am_shopby_filter_widget_attr_price_from': {
                            'greater-than-equals-to': $.mage.__('Please enter a valid price range.'),
                            'validate-digits-range': $.mage.__('Please enter a valid price range.')
                        },
                        'am_shopby_filter_widget_attr_price_to': {
                            'greater-than-equals-to': $.mage.__('Please enter a valid price range.'),
                            'validate-digits-range': $.mage.__('Please enter a valid price range.')
                        }
                    }
                });

            }.bind(this));
        },
        onChange: function (event) {
            var to = parseFloat(this.to.val());

            var fixed = this.getFixed(this.isSlider(), this.isPrice());
            to = to.toFixed(fixed);
            to = parseFloat(to) + this.getPriceCorrect();

            var newVal = this.from.val() + '-' + to;

            var changed = newVal != this.value.val();

            this.value.val(newVal);

            if (changed && this.element.find('form').valid()) {
                this.value.trigger('change');
                if (!this.options.collectFilters && this.go.length == 0) {
                    this.applyFilter();
                }
            }
        },
        applyFilter: function (e) {

            var to = parseFloat(this.to.val());
            var isPrice = this.isPrice();
            var fixed = this.getFixed(this.isSlider(), isPrice);
            to = to.toFixed(fixed);
            to = parseFloat(to) + this.getPriceCorrect();
            var from = this.from.val();

            var linkHref = this.options.url.replace('amshopby_slider_from', from).replace('amshopby_slider_to', to);
            this.apply(linkHref);

            if (e) {
                e.stopPropagation();
                e.preventDefault();
            }
        },

        onSyncChange: function (event, values) {
                var value = values[0].split('-');
                var to = this.options.max, from = this.options.min;

                if (value.length === 2) {
                    from = value[0] == '' ? 0 : parseFloat(value[0]);
                    to = value[1] == '' ? this.options.max : parseFloat(value[1]);
                    if (this.isPrice() && !this.isSlider()) {
                        to -= this.getPriceCorrect();
                    }
                    if (this.isDropDown()) {
                        to = Math.ceil(to) - this.getPriceCorrect();
                    }
                    var fixed = this.getFixed(this.isSlider(), 0);
                    if (!this.isSlider()) {
                        fixed = 0;
                    }
                    if (!this.isDropDown()) {
                        var extraToValue = this.getFloatNumber(this.getSignsCount(this.options.step, this.isPrice()));
                        to = parseFloat(to) + parseFloat(extraToValue);

                        from = from.toFixed(fixed);
                        to = to.toFixed(fixed);
                        if (!fixed) {
                            var toValue = parseFloat(to) + parseFloat(extraToValue);
                            var newVal = from + '-' + toValue;
                            this.value.val(newVal);
                        }
                    }
                }
                this.element.find('[amshopby-fromto-id="from"]').val(from);
                this.element.find('[amshopby-fromto-id="to"]').val(to);
        },
        /**
         * trigger keyup on input with delay
         * @param input
         * @param callback
         */
        changeEvent: function (input, callback) {
            input.on('keyup', function () {
                if (this.timer != null) {
                    clearTimeout(this.timer);
                }
                this.timer = setTimeout(callback.bind(this), 1000);
            }.bind(this));
        },

        isSlider: function () {
            return (typeof this.options.isSlider != 'undefined' && this.options.isSlider);
        },

        isDropDown: function () {
            return (typeof this.options.isDropdown != 'undefined' && this.options.isDropdown);
        }
    });

    $.widget('mage.amShopbyFilterSearch', {
        options: {
            highlightTemplate: "",
            itemsSelector: ""
        },

        previousSearch: '',

        _create: function () {
            var self = this;
            var $items = $(this.options.itemsSelector + " .item");
            $(self.element).keyup(function () {
                self.search(this.value, $items);
            });
        },

        search: function (searchText, $items) {
            var self = this;

            searchText = searchText.toLowerCase();
            if (searchText == this.previousSearch) {
                return;
            }
            this.previousSearch = searchText;

            if (searchText != '') {
                $(this.element).trigger('search_active');
            }

            $items.each(function (key, li) {
                if (li.hasAttribute('data-label')) {
                    var val = li.getAttribute('data-label').toLowerCase();
                    if (!val || val.indexOf(searchText) > -1) {
                        if (searchText != '' && val.indexOf(searchText) > -1) {
                            self.hightlight(li, searchText);
                        } else {
                            self.unhightlight(li);
                        }
                        $(li).show();
                    }
                    else {
                        self.unhightlight(li);
                        $(li).hide();
                    }
                }
            });

            if (searchText == '') {
                $(this.element).trigger('search_inactive');
            }
        },
        hightlight: function (element, searchText) {
            this.unhightlight(element);
            var $a = $(element).find('a');
            var label = $(element).attr('data-label');
            var newLabel = label.replace(new RegExp(searchText, 'gi'), this.options.highlightTemplate);
            $a.find('.label').html(newLabel);
        },
        unhightlight: function (element) {
            var $a = $(element).find('a');
            var label = $(element).attr('data-label');
            $a.find('.label').html(label);
        }
    });

    $.widget('mage.amShopbyFilterHideMoreOptions', {
        options: {
            numberUnfoldedOptions: 0,
            _hideCurrent: false,
            buttonSelector: ""
        },
        _create: function () {
            var self = this;

            if ($(this.element).find(".item").length <= this.options.numberUnfoldedOptions) {
                $(this.options.buttonSelector).parent().hide();
                return;
            }

            $(this.element).parents('.filter-options-content').on('search_active', function () {
                if (self.options._hideCurrent) {
                    self.toggle(self.options.buttonSelector);
                }
                $(self.options.buttonSelector).parent().hide();
            });

            $(this.element).parents('.filter-options-content').on('search_inactive', function () {
                if (!self.options._hideCurrent) {
                    self.toggle(self.options.buttonSelector);
                }
                $(self.options.buttonSelector).parent().show();
            });

            $(this.options.buttonSelector).click(function () {
                self.toggle(this);
                return false;
            });
            $(this.options.buttonSelector).parent().click(function () {
                self.toggle(self.options.buttonSelector);
            });

            // for hide in first load
            $(this.options.buttonSelector).click();
        },

        toggle: function (button) {
            var $button = $(button);
            if (this.options._hideCurrent) {
                this.showAll();
                $button.html($button.attr('data-text-less'));
                $button.attr('data-is-hide', 'false');
                this.options._hideCurrent = false;
            } else {
                this.hideAll();
                $button.html($button.attr('data-text-more'));
                $button.attr('data-is-hide', 'true');
                this.options._hideCurrent = true;
            }
        },

        hideAll: function () {
            var self = this;
            var count = 0;
            $(this.element).find(".item").each(function () {
                count++;
                if (count > self.options.numberUnfoldedOptions) {
                    $(this).hide();
                }
            });
        },
        showAll: function () {
            $(this.element).find(".item").show();
        }
    });

    $.widget('mage.amShopbyFilterAddTooltip', {
        options: {
            content: "",
            tooltipTemplate: ""
        },
        _create: function () {
            var template = this.options.tooltipTemplate.replace('{content}', this.options.content);
            var $template = $(template);

            var $place = $(this.element).parents('.filter-options-item').find('.filter-options-title');
            if ($place.length == 0) {
                $place = $(this.element).parents('dd').prev('dt');
            }
            if ($place.length > 0) {
                $place.append($template);
            }

            $template.tooltip({
                position: {
                    my: "left bottom-10",
                    at: "left top",
                    collision: "flipfit flip",
                    using: function (position, feedback) {
                        $(this).css(position);
                        $("<div>")
                            .addClass("arrow")
                            .addClass(feedback.vertical)
                            .addClass(feedback.horizontal)
                            .appendTo(this);
                    }
                },
                content: function () {
                    return $(this).prop('title');
                }
            });
        }
    });


    $.widget('mage.amShopbyFilterCategoryDropdown', $.mage.amShopbyFilterAbstract, {
        options: {},
        _create: function () {
            var self = this;
            $(function () {
                var $element = $(self.element[0]);
                $element.click(function (e) {
                    self.apply($element.data('remove-url'));
                    e.preventDefault();
                    e.stopPropagation();
                });
            })
        }
    });

    $.widget('mage.amShopbyFilterContainer', {
        options: {
            collectFilters: 0
        },

        _create: function () {
            var self = this;
            $(function () {
                var $element = $(self.element[0]);
                var links = $element.find('.am_shopby_state_container');
                var allClear = $element.siblings('.filter-actions');
                var filters = [];
                if (links.length) {
                    $(links).each(function (index, value) {
                        var filter = {
                            attribute: $(value).attr("data-container"),
                            value: $(value).attr("data-value")
                        };
                        filters.push(filter);

                        $(value).find('a').on("click", function (e) {
                            self.apply(filter);
                            if (e) {
                                e.stopPropagation();
                                e.preventDefault();
                            }
                        });

                    });
                }

                if (filters.length) {
                    $.each(filters, function (index, filter) {
                        self.checkInForm(filter);
                    });
                }
            })
        },
        apply: function (filter) {
            var self = this;
            if (this.options.collectFilters === 1) {
                this.sliderDefault(filter.attribute);
                this.fromToDefault(filter.attribute);
                var array = this.buildValues(filter.value);
                $.each(array, function (index, value) {
                    self.setDefault(filter.attribute, value);
                });

                $('li[data-container="' + filter.attribute + '"]').remove();
                this.clearBlock();
                $("[data-amshopby-filter]").trigger('change');
            } else {
                var link = $('li[data-container="' + filter.attribute + '"] a').attr('href');
                window.location = link;
            }
        },

        buildValues: function (value) {
            var array = [];
            if (value.indexOf(',') !== -1) {
                array = value.split(",");
            } else {
                array.push(value);
            }

            return array;
        },

        clearBlock: function () {
            if (!$('#am_shopby_container').find("li").length) {
                $('#am_shopby_container').remove();
                $(".filter-actions").remove();
            }
        },

        setDefault: function (name, value) {
            var object = $('[name="amshopby[' + name + '][]"]');
            var type = $(object).prop("tagName");
            switch (type) {
                case 'SELECT' :
                    object.find('[value=' + value + ']').removeAttr('selected', 'selected');
                    break;
                case 'INPUT' :
                    if ($(object).attr("type") != 'text' && $(object).attr("type") != 'hidden') {
                        var selected = $('[name="amshopby[' + name + '][]"][value=' + value + ']');
                        selected.removeAttr("checked");
                        selected.siblings('.swatch-option.selected').removeClass('selected');
                    } else if ($(object).attr("type") == 'hidden') {
                        var selected = $('[name="amshopby[' + name + '][]"]');
                        selected.val("");
                    }
                    break;
            }
        },

        sliderDefault: function (name) {
            var slider = $('[name="amshopby[' + name + '][]"]').siblings('[amshopby-slider-id="slider"]');
            if (slider.length) {
                var $parent = $('[name="amshopby[' + name + '][]"]').parent();
                $(slider[0]).slider("values", [$parent.attr('data-min'), $parent.attr('data-max')]);
                $(slider).siblings('.am_shopby_slider_display').text($parent.attr('data-min') + ' - ' + $parent.attr('data-max'));
            }
        },

        fromToDefault: function (name) {
            var range = $('[name="amshopby[' + name + '][]"]').siblings('.range');
            if (range.length) {
                var from = range.find('[amshopby-fromto-id="from"]');
                var to = range.find('[amshopby-fromto-id="to"]');
                var digits = $(from).attr('validate-digits-range');
                var regexp = /\[([\d\.]+)-([\d\.]+)\]/g;
                var ranges = regexp.exec(digits);
                $(from).val(ranges[1]);
                $(to).val(ranges[2]);
            }
        },

        checkInForm: function (filter) {
            var name = filter.attribute;
            var value = filter.value;
            if (!$('[name="amshopby[' + name + '][]"]').length) {
                $('#layered-filter-block').append('<form data-amshopby-filter="' + name + '"><input value="' + value + '" type="hidden" name="amshopby[' + name + '][]"></form>')
            }
        }
    });
});
