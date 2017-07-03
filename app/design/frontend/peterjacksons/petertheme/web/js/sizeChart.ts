/// <amd-dependency path="uiComponent" name="Component" />
/// <amd-dependency path="jquery" name="$" />
import $ = require("jquery");
import ko = require("knockout");

return Component.extend({

    initialize(): void {
        this._super();
        this.currentTab = $('.size-chart__tabs a').first().attr('href').replace(/^.*?(#|$)/,'');
        this.observe(['currentTab']);
    },

    changeCurrentTab(data, event): void {
        const activeTab = $(event.target).attr('href').replace(/^.*?(#|$)/,'');
        this.currentTab(activeTab);
        this.activeClass(activeTab);
    },

    isActive(name): boolean {
        if(name === this.currentTab()) {
            return true;
        }
        return false;

    },
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