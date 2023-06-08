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
	
	
	<!-- 1 - SYNCED MAIN SLIDES -->
	<div id="homeSlides" class="owl-carousel <?php echo $slideBehavior; ?>"><?php
	while($loop->have_posts()) : $loop->the_post(); 
	
		$titlesInOrder[] = get_the_title();
	
		$background = get_field('slide_image');
		
		$icon = get_field('header_icon');
		
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
			<div class="mobile-slide-background" style="background: url(<?php echo $background['url']; ?>) center center no-repeat; background-size: cover;">
				<img src="<?php echo $background['url']; ?>" class="slide-background">
				<div class="slide-foreground">
					<div class="inner clearfix">
						<div class="sixty">
							<?php if($icon) : ?>
								<div class="icon-wrapper">
									<div class="inline-perm middle">
										<img src="<?php echo get_template_directory_uri() . "/assets/icons/" . $icon . ".png"; ?>"
										 class="icon inline middle">
									</div>
									<div class="inline-perm middle padding10lr" style="color: <?php echo $color; ?>">
										<?php the_title(); ?>
									</div>
								</div>
							<?php endif; ?>
							<h1 style="color: <?php echo $color; ?>" <?php if($icon) echo "class=\"inline icon\""; ?>><?php the_field('slide_header'); ?></h1>
							<h2 style="color: <?php echo $color; ?>">
								<?php the_field('slide_subheader'); ?>
							</h2>
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
	
	<!-- 2 - SYNCED SLIDE NAV -->
	<div id="homeSlidesNav" class="owl-carousel <?php echo $slideBehavior; ?>"><?php
	foreach($titlesInOrder as $title) : ?>	
		<div class="home-slide">
			<?php echo $title; ?>
		</div>
		<?	
	endforeach; ?>
	</div>
	
