   
   (function ($) {
    "use strict";
    var $main_window = $(window);

	/*====================================
	preloader js
	======================================*/
    $main_window.on('load', function () {
        $('#preloader').fadeOut('slow');
    });
	/*====================================
		scroll to top js
	======================================*/
    $main_window.on('scroll', function () {
        if ($(this).scrollTop() > 250) {
            $('#goTop').fadeIn(200);
        } else {
            $('#goTop').fadeOut(200);
        }
    });
    $("#goTop").on("click", function () {
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
        return false;
    });

	/*====================================
		demo scroll
	======================================*/
    $('.scrollbtn').on('click', function () {
        $('html, body').animate({ scrollTop: $(".home-demos").offset().top + 50 }, 500);
    });

    })(jQuery);