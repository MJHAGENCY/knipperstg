<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package flare
 */

get_header(); ?>
<div id="content" class="site-content page404 container">
	<div class="row">
		<div class="col-md-12">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<section class="error-404 not-found">
						<div class="page-content">
						<h1>4o4</h1>
							<p><?php esc_html_e( 'It looks like nothing was found at this location.', 'flare' ); ?></p>	
							 <div id="pagenotfound-search"> 
                                    <?php get_search_form();  ?>                       
                                </div>						
     					</div><!-- .page-content -->
					</section><!-- .error-404 -->

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div>
</div>

<?php
get_footer();
