define([
    'underscore',
    'jquery',
    'prototype',
    "mage/adminhtml/form"
], function (_, jQuery) {

    AmastyFormElementDependenceController = Class.create(FormElementDependenceController,
        {
            initialize: function ($super, elementsMap, groupValues, groupFields, fieldsets, config) {
                this.groupFields = groupFields;
                $super(elementsMap, config);
                this.elementsMap = elementsMap;
                var self = this;
                _.each(groupValues, function (el, index) {
                    _.each(el, function (values, field) {
                        Event.observe($(field), 'change', self.onSelectChanged.bindAsEventListener(self, index, values));
                        Event.observe($(index), 'change', self.onDisplayModeChanged.bindAsEventListener(self, field, values));
                        self.onSelectChanged($(field), index, values);
                    });
                });

                _.each(fieldsets, function (el, index) {
                    _.each(el, function (values, field) {
                        Event.observe($(field), 'change', self.onFieldsetToogle.bindAsEventListener(self, index, values));
                        self.onFieldsetToogle($(field), index, values);
                    });
                });

            },

            _config: {
                levels_up: 1,
                notices: null,
                enabled_types: null,
                change_labels: null
            },

            elementsMap: {},
            groupGen: null,
            isSwatch: null,
            groupFields: null,

            onSelectChanged: function (e, index, values) {

                var frontendInput = (e instanceof Event) ? e.target : e;
                var valueDefault = -1;
                if (this.isNotEmpty(values)) {
                    if (!this.isEditable(frontendInput)) {
                        valueDefault = $(index).value;
                    }
                    var group = this.getGroup(frontendInput);

                    if (this.groupGen != group) {
                        this.changeDisplayOptions(index, values, group);
                    }
                    var displayMode = _.findKey(values.dependencies[group], function (value) {
                        return $(frontendInput).value === value;
                    }.bind(this));
                    var is_category_filter = $('attribute_code') && $('attribute_code').value == 'category_ids';
                    if (!is_category_filter) {
                        if (!this.isEditable(frontendInput) || this.isEnabledTypes($(frontendInput).value)) {

                            $(index).setValue(valueDefault);
                            if (displayMode == -1 || typeof displayMode == 'undefined') {
                                this.toogleTab(0);
                            } else {
                                this.toogleTab(1);
                            }
                        } else {
                            if (displayMode !== -1) {
                                $(index).setValue(displayMode);
                                this.toogleTab(1);
                            }
                            if (typeof displayMode == 'undefined') {
                                $(index).setValue("");
                                this.toogleTab(0);
                            }
                        }
                    }
                    this.callTrack(index);
                }
            },

            onFieldsetToogle: function (e, index, values) {
                var Element = (e instanceof Event) ? e.target : e;
                if ($(Element).value == values['value']) {
                    if (values['negative'] == false) {
                        $(index).hide();
                    } else {
                        $(index).show();
                    }
                } else {
                    if (values['negative'] == false) {
                        $(index).show();
                    } else {
                        $(index).hide();
                    }
                }
            },

            onDisplayModeChanged: function (e, index, values) {
                var Element = e.target;
                $($(Element).id + '-note').innerHTML = '';

                var group = this.getGroup(index);
                if ((this.isEditable(index) &&
                        this.isNotEmpty(values) &&
                        _.has(values.dependencies[group], $(Element).value)
                    ) || this.isEnabledTypes($(index).value) || this.groupGen != 'price') {
                    var inputValue = this.searchInFieldValues(index,values.dependencies[group], $(Element).value);
                    $(index).setValue(inputValue);
                    jQuery("#" + index).trigger('change');
                    if (_.has(this._config.notices, $(Element).value)) {
                        $($(Element).id + '-note').innerHTML = this._config.notices[$(Element).value];
                    }
                }
            },
            searchInFieldValues: function(index, values, value) {
                var result = null;
                var array  = [];
                _.each($(index).options, function(record) {
                    array.push(record.value);
                });

                if (_.indexOf(array, values[value]) != -1) {
                    result = values[value];
                }

                if (result == null) {
                    _.each(values, function (record) {
                        if (record != value) {
                            if (result  == null) {
                                if (_.indexOf(array, record) != -1) {
                                    result = record;
                                }
                            }
                        }
                    })
                }

                return result;
            },

            isEditable: function (field) {
                return !$(field).disabled;
            },

            isNotEmpty: function (values) {
                return values;
            },

            isEnabledTypes: function (value) {
                return _.indexOf(this._config.enabled_types, value);
            },

            callTrack: function (element) {
                var self = this;
                _.each(this.elementsMap, function (val, index) {
                    _.each(val, function (record, el) {
                        if (el == element) {
                            self.trackChange(null, index, val);
                        }
                    })
                })
            },

            getGroup: function (element) {
                return ($(element).value != 'price') ? 'default' : 'price';
            },

            changeDisplayOptions: function (index, values, group) {
                var self = this;
                var collection = $(index).childElements();
                _.each(collection, function (option) {
                    _.each(self._config.change_labels[group], function (value, index) {
                        if ($(option).value != "") {
                            if ($(option).value == index) {
                                $(option).innerHTML = value;
                            }
                        }
                    });
                    $(option).hide();
                });

                var keys = _.keys(values.values[group]);
                _.each(collection, function (val, index) {
                    var result = _.indexOf(keys, String($(val).value));
                    if (result !== -1) {
                        $(val).show();
                    }
                });

                this.groupGen = group;
            },

            toogleTab: function (toogle)
            {
                var Element = $('product_attribute_tabs').select('[data-ui-id=attribute-edit-tabs-tab-item-amasty-shopby]')[0];
                if (toogle) {
                    Element.show();
                } else {
                    Element.hide();
                }
            },
            trackChange : function($super, e, idTo, valuesFrom)
            {
                $super(e, idTo, valuesFrom);
                    if (idTo in this.groupFields) {
                        if (this.groupFields[idTo] != this.groupGen) {
                         jQuery(".field-" +idTo).hide();
                        }
                    }

            }

        });
});
