/**
 * Magedelight
 * Copyright (C) 2014 Magedelight <info@magedelight.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category Magedelight
 * @package Magedelight_Looknbuy
 * @copyright Copyright (c) 2016 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */


function addToCart(url)
{
    var cloned = document.querySelectorAll('.slick-cloned select:enabled');

    cloned.forEach(element => {
        element.disabled = true;
    });

    jQuery.ajax({
        url        : url,
        type       : 'POST',
        dataType   : 'json',
        showLoader     : true,
        data: $('look_items_form').serialize()
    }).done(function(data) {
        //window.location.href = data.url;

        cloned.forEach(element => {
            element.disabled = false;
        });
        return true;
    });
}