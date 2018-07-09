import $ = require("jquery");
import markAdded = require("PandaGroup_GuestWishlist/js/markAdded");
import sectionConfig = require("Magento_Customer/js/section-config")

/**
 * Marks visible wishlist items
 */

const module = () => {
    markAdded();
    $(document).on('ajaxComplete', function (event, xhr, settings) {
        if (settings.url.match(/sections(.*)wishlist/g)) {
            markAdded();
        }
    });
};

export = module;