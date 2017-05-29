define([
    "jquery",
], function ($) {
    var BrandFilter = function () {
        return {
            apply: function (containerSelector) {
                var elements = $(containerSelector);
                elements.each( function() {
                    $(this).show();
                });

                if (! $(this).hasClass('letter-all')) {
                    var letter = '';
                    var classList = $(this).attr('class').split(/\s+/);
                    $.each(classList, function(index, item) {
                        if (item.indexOf("letter-") >= 0) {
                            return letter = item;
                        }
                    });

                    elements.each( function() {
                        if (! $(this).hasClass(letter)) {
                            $(this).hide();
                        }
                    });
                }

                $(this).parent().siblings().addBack().each(function() {
                    $(this).children("[class*='letter-']").each(function() {
                        $(this).removeClass('active');
                    });
                });
                return $(this).addClass('active');
            }
        };
    } ();
    $.fn.extend({
        applyBrandFilter: BrandFilter.apply
    });
});
