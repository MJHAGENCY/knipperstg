<?php
	$background = get_field('partners_slider_background');
	$args = array(	'post_type'		=> 'partner',
					'post_status'	=> 'publish',
					'orderby'		=> 'menu_order',
					'order'			=> 'ASC',
					'posts_per_page'=>	-1);
	$loop = new WP_Query($args); ?>
	
	<div class="partnersbg" style="background: url(<?php echo $background['url']; ?>) no-repeat center center;">
		<h2 class="centertext white" style="margin-bottom: 30px;">Partners</h2>
		<div id="homePartners" class="inner clearfix"><?php
			while($loop->have_posts()) : $loop->the_post(); 
				$logo = get_field('partner_logo'); 
				$link = get_field('partner_link'); 
				if($link == 'http://') $link = ""; ?>
				
				<div class="single-slide"><!-- For slider -->
					<div class="twentyfive inline padding10lr middle">
						<img src="<?php echo $logo['url']; ?>">
					</div>
					<div class="seventyfive inline padding10lr middle">
						<?php the_field('partner_description'); ?>
						<?php if($link) : ?>
							<a href="<?php echo get_field('partner_link'); ?>" target="_blank">
								<?php echo get_field('partner_link_text'); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
				<?	
			endwhile;
			wp_reset_query(); ?>
		</div>
	</div><?php
	
?>