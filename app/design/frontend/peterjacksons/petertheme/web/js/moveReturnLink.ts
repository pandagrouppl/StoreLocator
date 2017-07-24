import $ = require("jquery");

const moveReturnLink = () => {
    'use strict';
    $('.breadcrumbs__items').append($('#return-to-previous'));

};

export = moveReturnLink;