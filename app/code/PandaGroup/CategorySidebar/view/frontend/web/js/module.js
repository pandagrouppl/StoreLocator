/* ==========================================================================
 Scripts voor de frontend
 ========================================================================== */
require(['jquery'], function ($) {
    $(function () {

        $('.c-sidebar').on('click','.o-list .expand, .o-list .expanded', function () {
            var element = $(this).parent('li');

            if (element.hasClass('active')) {
                element.find('figure').addClass('layered-nav__minus--plus');

                element.find('ul').slideUp();
                element.find('figure').removeClass('layered-nav__minus--plus');

                element.removeClass('active');
                element.find('li').removeClass('active');
            }

            else {
                element.children('ul').slideDown();
                element.find('figure').addClass('layered-nav__minus--plus');

                element.siblings('li').children('ul').slideUp();
                element.addClass('active');
                element.siblings('li').removeClass('active');
                element.siblings('li').find('li').removeClass('active');
                element.siblings('li').find('figure').removeClass('layered-nav__minus--plus');

                element.siblings('li').find('ul').slideUp();
            }
        });

        $(document).ready(function () {
            $('.o-list').find('.layered-nav__title').next('ul').css("display", "block");
        });

    });
});
