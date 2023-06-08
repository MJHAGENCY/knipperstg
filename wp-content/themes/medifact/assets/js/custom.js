(function ($) {
    "use strict";

    var $main_window = $(window);

	/*====================================
		scroll to top js
	======================================*/
    $main_window.on('scroll', function () {
        if ($(this).scrollTop() > 250) {
            $('#goTop').slideDown(400);
        } else {
            $('#goTop').slideUp(400);
        }
    });
    $("#goTop").on("click", function () {
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
        return false;
    });

	/*=======================================
	    mobile menu js
	 ======================================= */

    var $mob_menu = $("#mobile-menu");
    $(".close-menu").on("click", function () {
        $mob_menu.toggleClass("menu-hide");
    });
    $(".menu-toggle").on("click", function () {
        $mob_menu.toggleClass("menu-hide");
    });

    $("ul.navbar-nav").clone().appendTo(".mobile-menu");

    $(".mobile-menu .has-drop .nav-link, .mobile-menu .has-sub .has-icon").on("click",function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('current');
        $(this).next().slideToggle();
    });


	/*=======================================
	   affix  menu js
	 ======================================= */

    $main_window.on('scroll', function () {  
        var scroll = $(window).scrollTop();
        if (scroll >= 400) {
            $(".menubar").addClass("sticky-menu");
        } else {
            $(".menubar").removeClass("sticky-menu");
        }
    });

    /*=======================================
	   mobile affi  menu js
	 ======================================= */
    $main_window.on('scroll', function () {  
        var scroll = $(window).scrollTop();
        if (scroll >= 300) {
            $(".mobile-affix").addClass("sticky-menu");
        } else {
            $(".mobile-affix").removeClass("sticky-menu");
        }
    });

	

	/*====================================
	   host slider
	======================================*/
    var owlmain = $('.host-slider1');
    owlmain.owlCarousel({
        margin: 0,
        loop: false,
        autoplay: true,
        nav: true,
        items: 1,
        navText: [
            '<i class="fa fa-chevron-left"></i>',
            '<i class="fa fa-chevron-right"></i>'
        ],
    });


	/*====================================
      features tab panel
	======================================*/
    if ($(".feature").length > 0) {
        $(".tabs-container a").on("click", function (event) {
            event.preventDefault();
            $('.feature-item').removeClass("current");
            $(this).parent().addClass("current");
            var tab = $(this).attr("href");
            $(".tab-pane-f").not(tab).css("display", "none");
            $(tab).fadeIn();
        });
    }

	/*====================================
        fearture-2 tab panel
	======================================*/
    if ($(".feature-2").length > 0) {
        $(".feature-list-group a").on("click", function (event) {
            event.preventDefault();
            $(this).parent().addClass("current");
            $(this).parent().siblings().removeClass("current");
            var tab = $(this).attr("href");
            $(".feature-box").not(tab).css("display", "none");
            $(tab).fadeIn();
        });
    }


	

	/*====================================
		Isotop And Masonry
	======================================*/

    $('.portfolio').imagesLoaded(function () {
        $('.portfolio-gallary').isotope({
            itemSelector: '.port-item',
            percentPosition: true,
            masonry: {
                columnWidth: '.port-item'
            }
        });

        $('.portfolio-sort ul li').on("click", function () {
            $('.portfolio-sort ul li').removeClass('active');
            $(this).addClass('active');

            var selector = $(this).attr('data-filter');
            $('.portfolio-gallary').isotope({
                filter: selector
            });
            return false;
        });

        var popup_gallary = $('.portfolio-gallary');
        popup_gallary.poptrox(
            {
                usePopupNav: true,
                popupPadding: 0,
                selector: '.trox',
                popupNavPreviousSelector: '.nav-previous',
                popupNavNextSelector: '.nav-next'
            }
        );

       
    });

    $('.blog').imagesLoaded(function () {
        $('.blog-isotope').isotope({
            itemSelector: '.blog-mason',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: '.blog-mason'
            }
        });
    });
    

	/*====================================
		PARTNER SLIDER
	======================================*/
    if ($(".partner").length > 0) {
        var partner = $(".partner-slider");
        partner.owlCarousel({
            margin: 0,
            loop: false,
            autoplay: true,
            autoplayHoverPause: true,
            nav: false,
            dots: false,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                },
                350: {
                    items: 2,
                },
                768: {
                    items: 3,
                },
                988: {
                    items: 4,
                },
                1200: {
                    items: 5,
                }
            }
        });
    }


	/*====================================
		SERVICE SLIDER
	======================================*/

    if ($(".service-2").length > 0) {

        var $service_slider = $(".service-slider");
        $service_slider.owlCarousel({
            margin: 20,
            loop: false,
            autoplay: true,
            autoplayHoverPause: true,
            nav: true,
            navText:
            ['<span class="service-btns prev"></span>',
            '<span class="service-btns next"></span>'],
            dots: false,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                },
                450: {
                    items: 2,
                },
                922: {
                    items: 3,
                },
                1200: {
                    items: 4,
                }
            }
        });
    }

	/*====================================
	    portfolio-slider
	======================================*/
    if ($(".project-detail").length > 0) {
        var sync1 = $(".project-detail-slider");
        var sync2 = $(".thumbnail-slider");
        var syncedSecondary = true;

        sync1.owlCarousel({
            items: 1,
            slideSpeed: 2000,
            nav: true,
            autoplay: true,
            dots: false,
            loop: true,
            responsiveRefreshRate: 200,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
        }).on('changed.owl.carousel', syncPosition);

        sync2
            .on('initialized.owl.carousel', function () {
                sync2.find(".owl-item").eq(0).addClass("current");
            })
            .owlCarousel({
                dots: false,
                nav: false,
                margin: 20,
                smartSpeed: 200,
                slideSpeed: 500,
                slideBy: 8,
                responsiveRefreshRate: 100,
                responsiveClass: true,
                responsive:
                {
                    0: {
                        items: 3
                    },
                    567: {
                        items: 5
                    },
                    922: {
                        items: 6
                    },
                    1200: {
                        items: 8
                    }
                }
            }).on('changed.owl.carousel', syncPosition2);

        sync2.on("click", ".owl-item", function (e) {
            e.preventDefault();
            var number = $(this).index();
            sync1.data('owl.carousel').to(number, 300, true);
        });
       }

       function syncPosition(el) {
        var count = el.item.count - 1;
        var current = Math.round(el.item.index - (el.item.count / 2) - 0.5);

        if (current < 0) {
            current = count;
        }
        if (current > count) {
            current = 0;
        }

        sync2
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
        var onscreen = sync2.find('.owl-item.active').length - 1;
        var start = sync2.find('.owl-item.active').first().index();
        var end = sync2.find('.owl-item.active').last().index();

        if (current > end) {
            sync2.data('owl.carousel').to(current, 100, true);
        }
        if (current < start) {
            sync2.data('owl.carousel').to(current - onscreen, 100, true);
            }
        }
        function syncPosition2(el) {
            if (syncedSecondary) {
                var number = el.item.index;
                sync1.data('owl.carousel').to(number, 100, true);
            }
        }
})(jQuery);



