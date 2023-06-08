<?php
/**
 * Home Page
 *
 */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main diagstrbg" role="main">

		<?php 
			while ( have_posts() ) : the_post(); ?>
			
				<!-- Slider of Type=Slides -->
				<?php get_template_part( 'template-parts/home', 'slides' ); ?>

				<!-- Three (more/less?) WYSIWYG content areas -->
				<div class="inner clearfix spotlights">
					<?php
					/* Made this a repeater field in case they 
					 want to display != 3 content areas */
					if( have_rows('content_areas') ):
				    while ( have_rows('content_areas') ) : the_row(); 
				    	$image = get_sub_field('top_image'); ?>
				       <div class="thirtythree inline top">
					       <h2 class="blue"><?php the_sub_field('header'); ?></h2>
					       <?php
					       if($image) { ?>
					       	<div class="vignette" style="background-image: url(<?php echo $image['url']; ?>);">
					       		<img src="<?php echo $image['url']; ?>">
					       	</div>
					       <?
					       }
					       ?>
					       <p><?php the_sub_field('content'); ?></p>
				       </div><?php		
				    endwhile;
					endif;
					?>
				</div>

			<?php 
			endwhile; // End of the loop for this page's content
			wp_reset_query();
			?>
			
			<!-- Slider of Type=Partners -->
			<?php get_template_part( 'template-parts/home', 'partners' ); ?>
			
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?><!-- maybe -->