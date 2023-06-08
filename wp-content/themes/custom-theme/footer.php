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
			      "<i class=\"fa fa-3x fa-angle-left\"></i>",
			      "<i class=\"fa fa-3x fa-angle-right\"></i>"]
			});	
     
			<?php
			/* Get whether it's manual or auto */
			$pageID = get_option('page_on_front');
			$behavior = get_field('slider_behavior', $pageID);
			if($behavior == "auto") :
				$autoPlay = "15000";
			else :
				$autoPlay = "false";
			endif;
			?>
			
			var sync1 = jQuery("#homeSlides");
     
			sync1.owlCarousel({
				singleItem : true,
			    slideSpeed : 1000,
			    navigation : false,
			    pagination : false,
			    responsiveRefreshRate : 200,
			    autoPlay : <?php echo $autoPlay; ?>,
			    transitionStyle : "fade",
			    afterAction : function() {
				    
				    jQuery('#homeSlidesNav .slide-nav').removeClass('active');
				    jQuery('#homeSlidesNav [data-slide-number="' + this.owl.currentItem + '"]').addClass('active');
				    
			    }
			});
       
			jQuery("#homeSlidesNav .slide-nav").on("click touchstart", function(e){
				e.preventDefault();
				var number = jQuery(this).data("slide-number");
				sync1.trigger('owl.stop');
				sync1.trigger("owl.goTo",number);
				jQuery(".slide-nav").removeClass("active");
				jQuery(this).addClass("active");
			});
			
			jQuery("#homeSlidesNav .slide-nav-arrows.prev").on("click touchstart", function(e){
				
				sync1.trigger("owl.prev");
				var newActiveSlide = sync1.data('owlCarousel').currentItem;	
				jQuery(".slide-nav").removeClass("active");
				jQuery("#homeSlidesNav").find("[data-slide-number='" + newActiveSlide + "']").addClass("active");

			});
		      
			jQuery("#homeSlidesNav .slide-nav-arrows.next").on("click touchstart", function(e){

		        sync1.trigger("owl.next");

		        var newActiveSlide = sync1.data('owlCarousel').currentItem;
		        jQuery(".slide-nav").removeClass("active");
				jQuery("#homeSlidesNav").find("[data-slide-number='" + newActiveSlide + "']").addClass("active");

			});
			
		});

		/* Menu and Search icon popup triggers */
		var menuExpanded = false;
		jQuery("#mainMenuTrigger").click(function() {
			if(menuExpanded) {
				jQuery(this).removeClass("open");
				jQuery(this).html("Menu <i class=\"fa fa-2x fa-bars middle\"></i>");
				//jQuery(".main-menu-container").hide();
				jQuery(".main-menu-container").removeAttr('style');
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
