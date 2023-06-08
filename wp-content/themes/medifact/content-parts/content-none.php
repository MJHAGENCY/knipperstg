<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * @package WordPress
 * @subpackage Medifact
 */
?>
<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( esc_html_e( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'medifact' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

<?php elseif ( is_search() ) : ?>

	<h4 class="search-no"><?php printf( esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'medifact' ), '<span>' . get_search_query() . '</span>' ); ?>
	</h4>
	<div class="widget widget_search cc-search">
		 <?php get_search_form(); ?>
	</div>

<?php else : ?>

	<p><?php echo(esc_html__( 'It seems we can not  find what you are looking for. Perhaps searching can help.', 'medifact' )); ?></p>
	<?php get_search_form(); ?>

<?php endif; ?>