<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.emagine.com
 * @since      1.0.0
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/admin/partials
 */

// Get cookie options.
$this->options = get_option( 'em_cookie_notification' );

// Output the settings form. ?>
<div class="wrap">
	<h1><?php esc_html_e( 'Simple Cookie Notification', 'em-cookie-notification' ); ?></h1>
	<form method="post" action="options.php">
		<?php
			// This prints out all hidden setting fields.
			settings_fields( 'cookie_notification_options' );    // Option Group.
			do_settings_sections( 'cookie-notification-admin' ); // Page.
			submit_button();
		?>
	</form>
</div>