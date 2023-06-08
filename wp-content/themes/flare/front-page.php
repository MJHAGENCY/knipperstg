<?php
/**
 * Template Name: Front page
 * The template for displaying home page.
 * @package Flare
 */
get_header();
if ( 'posts' == get_option( 'show_on_front' ) ) {
	include( get_home_template() );
    }
else{	
	/**
	 * flare_homepage hook
	 * @since Charitize 1.0.0
	 *
	 * @hooked flare_homepage -  10
	 * @sub_hooked flare_homepage -  30
	 */
	do_action( 'flare_homepage' );
	$flare_static_page = absint($flare_customizer_all_values['flare-enable-static-page']);
	if ($flare_static_page == 1) { ?>
	<div id="content" class="site-content">
		<div class="container tb-post-content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
						<?php
						while ( have_posts() ) : the_post();

							get_template_part( 'template-parts/content', 'single'  );


							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile; // End of the loop.
						?>
				</main><!-- #main -->
			</div><!-- #primary -->
			<?php
				get_sidebar();
			?>
	</div>
	<?php }
}
get_footer();