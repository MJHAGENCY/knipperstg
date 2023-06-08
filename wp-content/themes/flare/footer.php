<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 *
 * @package Flare 
 * @since Flare 1.0.0
 */


/**
 * flare_action_after_content hook
 * @since Flare 1.0.0
 *
 * @hooked null
 */
do_action( 'flare_action_after_content' );

/**
 * flare_action_before_footer hook
 * @since Flare 1.0.0
 *
 * @hooked flare_before_footer - 10
 */
do_action( 'flare_action_before_footer' );

/**
 * flare_action_widget_before_footer hook
 * @since Flare 1.0.0
 *
 * @hooked flare_widget_before_footer - 10
 */
do_action( 'flare_action_widget_before_footer' );

/**
 * flare_action_footer hook
 * @since Flare 1.0.0
 *
 * @hooked flare_footer - 10
 */
do_action( 'flare_action_footer' );

/**
 * flare_action_after_footer hook
 * @since Flare 1.0.0
 *
 * @hooked null
 */
do_action( 'flare_action_after_footer' );

/**
 * flare_action_after hook
 * @since Flare 1.0.0
 *
 * @hooked flare_page_end - 10
 */
do_action( 'flare_action_after' );
?>
<?php wp_footer(); ?>
</body>
</html>