//scroll down
(function ($) {

    $(document).ready(function () {
     // WOW JS
     new WOW().init();

     //slick slider 
      $('.center').slick({          
          slidesToShow: 1,
          dots: true,
          arrows: true,
          autoplay: true,
          autoplaySpeed: 2000,
          responsive: [
            {
              breakpoint: 768,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 480,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            }
          ]
       });

      //sticky nav
       var  mn = $("#masthead");
            mns = "main-nav-scrolled";
            hdr = $('.header-accessories').height();

        $(window).scroll(function() {
          if( $(this).scrollTop() > hdr ) {
            mn.addClass(mns);
          } else {
            mn.removeClass(mns);
          }
        });

      
       //slidenav
        $("#toggleNav").click(function () {
            $("#site-navigation").toggleClass("background-black"); 
            $("#toggleNav").toggleClass("backgroundchange");             
        });            
               
      
        //add class on first child of top nav 
        $("ul#top-menu li:first-child").first().addClass("first");

        //add class on last child ofsidebar items to remove the border

        $("aside#secondary ul li").last().addClass("last");
        $("li.cat-item").last().addClass("last");

        //add class for last article to remove the border

        $("article").last().addClass("last-article");
        $("article").first().addClass("first-article");



        //Check to see if the window is top if not then display button
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#gotop').fadeIn("300");
            } else {
                $('#gotop').fadeOut("300");
            }
        });

        //Click event to scroll to top
        $('#gotop').click(function () {
            $('html, body').animate({
                scrollTop: 0
            }, 400);
            return false;
        });
    });


})(jQuery);