<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.emagine.com
 * @since      1.0.0
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/public/partials
 */

// Stores the plugin settings.
$cookie_settings = get_option( 'em_cookie_notification' );

// Sets the position. Defaults to top.
$position    = isset( $cookie_settings['position'] ) ? $cookie_settings['position'] : 'top';
$button_text = ! empty( $cookie_settings['button_text'] ) ? $cookie_settings['button_text'] : __( 'Continue', 'em-cookie-notification' );

// Make sure cookie notice is enabled.
if ( 1 === (int) $cookie_settings['enable_notice'] ) :
?>
	<div class="cookie-notification cookie-notification--<?php echo esc_attr( $position ); ?>" style="display: none;">
		<div class="cookie-notification__inner">
			<div class="cookie-notification__content">
				<?php
				// Output Message.
				if ( ! empty( $cookie_settings['notification_text'] ) ) {

					// Store the message in a variable.
					$content = $cookie_settings['notification_text'];

					// Manually filter the content through the filters used in the_content.
					$content = wptexturize( $content );
					$content = convert_smilies( $content );
					$content = convert_chars( $content );
					$content = wpautop( $content );
					$content = shortcode_unautop( $content );
					$content = do_shortcode( $content );

					// Output content.
					echo wp_kses_post( $content );
				}
				?>
			</div>
			<div class="cookie-notification__button">
				<button class="btn" href="#"><?php echo esc_html( $button_text ); ?></button>
			</div>
		</div>
	</div>
<?php endif; ?>
