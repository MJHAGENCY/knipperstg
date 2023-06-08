<?php


	/* custom field on home page template determines auto or manual */
	$slideBehavior = get_field('slider_behavior');
	
	/* args for slides loop */
	$args = array(	'post_type'		=> 'slides',
					'post_status'	=> 'publish',
					'orderby'		=> 'menu_order',
					'order'			=> 'ASC',
					'posts_per_page'=>	-1);
	$loop = new WP_Query($args); 
	
	/* store all the titles for the slide nav so we're not hitting the db twice */
	$titlesInOrder = array();
	?>
	
	
	<!-- 1 - MAIN SLIDER -->
	<div id="homeSlides" class="owl-carousel <?php echo $slideBehavior; ?>"><?php
	while($loop->have_posts()) : $loop->the_post(); 
	
		$titlesInOrder[] = get_the_title();
	
		$desktop_image = get_field('slide_image_desktop');
		$tablet_image = get_field('slide_image_tablet');
		$mobile_image = get_field('slide_image_mobile');
		
		$background_image = $desktop_image ? $desktop_image['sizes']['thumbnail'] : '';
		$desktop_image = $desktop_image ? '<span data-src="' . $desktop_image['url'] . '" data-media="(min-width: 900px)"></span>' : '';
		$tablet_image = $tablet_image ? '<span data-src="' . $tablet_image['url'] . '" data-media="(min-width: 600px)"></span>' : '';
		$mobile_image = $mobile_image ? '<span data-src="' . $mobile_image['url'] . '"></span>' : '';
		
		
		$icon = get_field('header_icon');
		if($icon) {
			$class = "extrapadding";
		}
		else {
			$class = "extrapadding";
		}
		
		$link = get_field('slide_link');
		
		$theme = get_field('slide_theme');
		if($theme == 'dark') {
			$color = "#000000";
		}
		else {
			$color = "#ffffff";
		}
		?>
		
		<div class="home-slide">
			<div class="mobile-slide-background">
				<div class="slide-background picturefill-background">
					<?php echo $mobile_image, $tablet_image, $desktop_image ?>
				</div>
				<div class="slide-foreground <?php echo $class; ?>">
					<div class="inner clearfix">
						<div class="sixty">
							<?php if($icon) : ?>
								<div class="icon-wrapper <?php echo $icon . " " . $theme; ?>" style="color: <?php echo $color; ?>">
									<?php the_title(); ?>
								</div>
								<h2 style="color: <?php echo $color; ?>" style="margin: 0.5em 0;">
									<?php the_field('slide_header'); ?>
								</h2>
								<p style="color: <?php echo $color; ?>">
									<?php the_field('slide_subheader'); ?>
								</p>
							<?php else : ?>
								<h1 style="color: <?php echo $color; ?>"><?php the_field('slide_header'); ?></h1>
								<h3 style="color: <?php echo $color; ?>">
									<?php the_field('slide_subheader'); ?>
								</h3>
							<?php endif; ?>
							<a class="button" href="<?php echo $link; ?>">
								<?php echo get_field('slide_link_text'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?	
	endwhile; 
	wp_reset_query(); ?>
	</div>
	
	<!-- 2 - CUSTOM SLIDER NAV -->
	<div id="homeSlidesNav">
		<div class="slide-nav-arrows prev"><i class="fa fa-chevron-left"></i></div>
			<div class="inner flex-container">
			<?php
				$i = 0;
				foreach($titlesInOrder as $title) : ?>		
					<div class="slide-nav <?php if($i == 0) : ?>active<? endif; ?>" data-slide-number="<?php echo $i; ?>">
						<span class="arrow"> </span>
						<a href="javascript:void(0);">
							<?php echo $title; ?>
						</a>
					</div>
					<?	
					$i++;
				endforeach; ?>
			</div>
		<div class="slide-nav-arrows next"><i class="fa fa-chevron-right"></i></div>
	</div>
	
