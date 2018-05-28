import $ = require("jquery");

/**
 * Wishlist ajax
 */

const module = (conf, element) => {
    const $element = $(element);
    $element.click(() => {
        const config = JSON.parse(element.dataset.config);
        var $message = $element.next('.wishlist-message');
        if ($message.length) {
            const $messageText = $message.find('.wishlist-message__text');
        } else {
            var $message = $('.wishlist-message');
            const $messageText = $('.wishlist-message__text');
        }

        $.ajax({
            url: config.action + 'ajax/1',
            method: 'post',
            data: config.data,
            beforeSend: function() {
                $messageText.text(element.dataset.message);
                $message.slideDown();
            }
        }).done((json) => {
            // remove from wishlist view
            if (json.message === 'Product has been removed') {
                $(`[id="item_${config.data.item}"]`).fadeOut();
            }

            // messages
            $messageText.text(json.message);
            setTimeout(() => {
                $message.slideUp(() => {
                    $messageText.text(element.dataset.message);
                });
            }, 4000);

            // handle readding
            if (json.add_params) {
                $element.children().toggleClass('active');
                $element[0].dataset.config = JSON.stringify(json.add_params);
                $element[0].dataset.message = 'Adding...';
                $element[0].title = 'Add to wishlist';
            }

        }).fail(() => {
            console.log('error');
        });
    });
};

export = module;