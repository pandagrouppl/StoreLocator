import $ = require("jquery");
import customerData = require('Magento_Customer/js/customer-data');

/**
 * Marks visible wishlist items
 */

const module = () => {
    const wish = customerData.get('wishlist')();
    const items = wish.items;
    if (items) {
        const len = items.length;
        for (let i = 0; i < len ; i ++) {
            let id = items[i].product_id;
            let $element = $(`[data-wishlist-product-id=${id}]`);
            if ($element.length) {
                $element[0].children[0].classList.add('active');
                $element[0].dataset.config = items[i].delete_item_params;
                $element[0].dataset.message = 'Removing...';
                $element[0].title = 'Remove from wishlist';
            }
        }
    }
};

export = module;