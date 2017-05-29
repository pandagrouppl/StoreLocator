/**
 * @author    Amasty Team
 * @copyright Copyright (c) Amasty Ltd. ( http://www.amasty.com/ )
 * @package   Amasty_Shopby
 */

define([
    'jquery',
    'matchMedia',
    'amShopbyTopFilters',
    'mage/tabs',
    'domReady!'
], function ($, mediaCheck, amShopbyTopFilters) {
    'use strict';

    mediaCheck({
        media: '(max-width: 768px)',
        entry: function(){
            amShopbyTopFilters.moveTopFiltersToSidebar();
        },
        exit: function(){

            amShopbyTopFilters.removeTopFiltersFromSidebar();
        }
    });
});
