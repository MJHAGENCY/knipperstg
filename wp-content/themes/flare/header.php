<?php
/**
 * The default template for displaying header
 *
 * @package salient themes
 * @subpackage Flare
 * @since Flare 1.0.0
 */

/**
 * flare_action_before_head hook
 * @since Flare 1.0.0
 *
 * @hooked flare_set_global -  0
 * @hooked flare_doctype -  10
 */
do_action( 'flare_action_before_head' );?>



<head>

	<?php
	/**
	 * flare_action_before_wp_head hook
	 * @since Flare 1.0.0
	 *
	 * @hooked flare_before_wp_head -  10
	 */
	do_action( 'flare_action_before_wp_head' );

	wp_head();

	/**
	 * flare_action_after_wp_head hook
	 * @since Flare 1.0.0
	 *
	 * @hooked null
	 */
	do_action( 'flare_action_after_wp_head' );
	?>

</head>

<body <?php body_class(); ?>>

<?php
/**
 * flare_action_before hook
 * @since Flare 1.0.0
 *
 * @hooked flare_page_start - 15
 */
do_action( 'flare_action_before' );

/**
 * flare_action_before_header hook
 * @since Flare 1.0.0
 *
 * @hooked flare_skip_to_content - 10
 */
do_action( 'flare_action_before_header' );

/**
 * flare_action_header hook
 * @since Flare 1.0.0
 *
 * @hooked flare_after_header - 10
 */
do_action( 'flare_action_header' );

/**
 * flare_action_before_content hook
 * @since Flare 1.0.0
 *
 * @hooked null
 */
do_action( 'flare_action_before_content' );
?>
