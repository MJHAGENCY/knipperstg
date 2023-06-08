	<footer>
		<div class="inner clearfix centertext">
			<div>
				<?php 
					//! FOOTER WYSIWYG
					the_field('opts_footer','options');
					
					//! SOCIAL MEDIA ICONS/LINKS
					//Em_Parts::get_social_media(); ?>
					
					<span class="footer-divider">|</span><?php

					//! FOOTER MENU 
					if ( has_nav_menu('footer-menu') ) :
						wp_nav_menu(array(
							'theme_location'  => 'footer-menu',
							'menu'            => '',
							'container'       => 'nav',
							'container_class' => 'footer-menu-container inline',
							'items_wrap' 	  => '<menu class="%2$s">%3$s</menu>',
							'menu_class'      => 'footer-menu clearfix',
						));
					endif;	
				?>
			</div>
		</div>
	</footer>
	
	<script>

		/* Accordion Menu */		
		jQuery( '.menu-item-has-children > a.expand' ).click( function(event) {
    
	        if(jQuery(this).next('ul').is(':hidden')) { // sub-menu is collapsed
	        	//console.log("Show it");
	            event.preventDefault(); // don't try to go to link
	
	            jQuery( this ).parent().toggleClass( 'active' );
	            jQuery( this ).parent().children( 'ul' ).slideDown();     
	        }
	        
	        else {	// sub-menu is expanded
	        	//console.log("Hide it");
				event.preventDefault();            
	
	            jQuery( this ).parent().toggleClass( 'active' );
	            jQuery( this ).parent().children( 'ul' ).slideUp(); 
	        }
        
		});
		
		
		/* Home Page Sliders */
		jQuery(document).ready(function() {
		
			/* Partners Section */
			jQuery("#homePartners").owlCarousel({
				singleItem: true,
				autoPlay: false,
				navigation: true,
				pagination: false,
				transitionStyle : "fade",
			    navigationText: [
			      "<i class=\"fa fa-3x fa-chevron-left\"></i>",
			      "<i class=\"fa fa-3x fa-chevron-right\"></i>"]
			});	
		
			
			/* Top Slider - Synced Owl Carousels */
			var sync1 = jQuery("#homeSlides");
			var sync2 = jQuery("#homeSlidesNav");
     
			<?php
			/* Get whether it's manual or auto */
			$pageID = get_option('page_on_front');
			$behavior = get_field('slider_behavior', $pageID);
			if($behavior == "auto") :
				$autoPlay = "true";
			else :
				$autoPlay = "false";
			endif;
			?>	
     
			sync1.owlCarousel({
				singleItem : true,
			    slideSpeed : 1000,
			    navigation : false,
			    pagination : false,
			    afterAction : syncPosition,
			    responsiveRefreshRate : 200,
			    autoPlay : <?php echo $autoPlay; ?>,
			    transitionStyle : "fade"
			});
     
			sync2.owlCarousel({
				items : 6,
				itemsDesktop : [1199, 6],
				itemsTablet : [1024, 1],
				itemsMobile : [640, 1],
				pagination : false,
				responsiveRefreshRate : 100,
				afterInit : function(el){
					el.find(".owl-item").eq(0).addClass("synced");
				},
				navigation : true,
				navigationText: [
			      "<i class=\"fa fa-3x fa-chevron-left\"></i>",
			      "<i class=\"fa fa-3x fa-chevron-right\"></i>"],
			    transitionStyle : "fade",
			    center : true
			});
     
			function syncPosition(el){
		        var current = this.currentItem;
		        jQuery("#homeSlidesNav")
		          .find(".owl-item")
		          .removeClass("synced")
		          .eq(current)
		          .addClass("synced")
		        if(jQuery("#homeSlidesNav").data("owlCarousel") !== undefined){
		          center(current)
		        }
			}

			function center(number){
				var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
				var num = number;
				var found = false;
				for(var i in sync2visible){
					if(num === sync2visible[i]){
						var found = true;
					}
				}
				if(found===false){
					if(num>sync2visible[sync2visible.length-1]) {
						sync2.trigger("owl.goTo", num - sync2visible.length+2)
					} 
					else {
			            if(num - 1 === -1) {
							num = 0;
						}
						sync2.trigger("owl.goTo", num);
					}
				} 
				else if(num === sync2visible[sync2visible.length-1]) {
					sync2.trigger("owl.goTo", sync2visible[1])
				} 
				else if(num === sync2visible[0]) {
					sync2.trigger("owl.goTo", num-1)
				}
			}
						     
			jQuery("#homeSlidesNav").on("click touchstart", ".owl-item", function(e){
				e.preventDefault();
				var number = jQuery(this).data("owlItem");
				sync1.trigger("owl.goTo",number);
			});
			
			jQuery("#homeSlidesNav").on("click touchstart", ".owl-prev", function(e){
				sync2.trigger("owl.prev");
				sync1.trigger("owl.prev");
			});
		      
			jQuery("#homeSlidesNav").on("click touchstart", ".owl-next", function(e){
				sync2.trigger("owl.next");
		        sync1.trigger("owl.next");
			});
			
		});

		/* Sticky Header */
		jQuery(window).scroll(function() {
			if (jQuery(this).scrollTop() > 100){  
			    jQuery('header').addClass("sticky");
			  }
			  else{
			    jQuery('header').removeClass("sticky");
			  }
		});
		
		/* Menu and Search icon popup triggers */
		var menuExpanded = false;
		jQuery("#mainMenuTrigger").click(function() {
			if(menuExpanded) {
				jQuery(this).removeClass("open");
				jQuery(this).html("Menu <i class=\"fa fa-2x fa-bars middle\"></i>");
				jQuery(".main-menu-container").hide();
				menuExpanded = false;
				
			}
			else {
				if(searchExpanded) {
					jQuery("#searchTrigger").removeClass("open");
					jQuery("#searchForm").fadeOut();
					searchExpanded = false;
				}
				jQuery(this).addClass("open");
				jQuery(this).html("Menu <i class=\"fa fa-2x fa-times\" style=\"vertical-align: sub\"></i>");
				jQuery(".main-menu-container").show();
				menuExpanded = true;
				
			}	
		});
		
		var searchExpanded = false;
		jQuery("#searchTrigger").click(function() {
			if(searchExpanded) {
				jQuery(this).removeClass("open");
				jQuery("#searchForm").fadeOut();
				searchExpanded = false;
			}
			else {
				if(menuExpanded) {
					jQuery("#mainMenuTrigger").removeClass("open");
					jQuery("#mainMenuTrigger").html("Menu <i class=\"fa fa-2x fa-bars middle\"></i>");
					jQuery(".main-menu-container").hide();
					menuExpanded = false;
				}
				jQuery(this).addClass("open");
				jQuery("#searchForm").fadeIn();
				searchExpanded = true;
			}
		});
		
		
		

    </script>
	
	<?php wp_footer(); ?>
	</body>
</html>
