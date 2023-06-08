<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.emagine.com
 * @since      1.0.0
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/admin
 * @author     emagine <wordpress@emagine.com>
 */
class Em_Cookie_Notification_Admin {

	/**
	 * The options value of this plugin retrieved from options table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The array of options for this plugin.
	 */
	private $options;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = get_option( 'em_cookie_notification' );
	}

	/**
	 * Enqueues admin scripts.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/em-cookie-notification-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ), $this->version, true );
		wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css', array(), $this->version );
	}

	/**
	 * Add the settings page for Cookie Notification.
	 *
	 * @since    1.0.0
	 */
	public function settings_page() {
		add_options_page( 'Cookie Notification', 'Cookie Notification', 'manage_options', 'simple_cookie_notification', array( $this, 'settings_page_display' ) );
	}

	/**
	 * Include the markup required to display the Cookie Notification settings page.
	 *
	 * @since    1.0.0
	 */
	public function settings_page_display() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/em-cookie-notification-admin-display.php';

	}

	/**
	 * Initialize the settings page.
	 *
	 * Registers the settings using the WP Settings API.
	 *
	 * @since    1.0.0
	 */
	public function settings_page_init() {
		register_setting(
			'cookie_notification_options', // Option group.
			'em_cookie_notification',      // Option name.
			array(
				'sanitize_callback' => array( $this, 'sanitize' ), // Sanitize.
			)
		);

		add_settings_section(
			'cookie_notification_settings',       // ID.
			'Settings',                           // Title.
			array( $this, 'print_section_info' ), // Callback.
			'cookie-notification-admin'           // Page.
		);

		// Setting: Enable/disable notification.
		add_settings_field(
			'enable_notice',                          // ID.
			'Enable Cookie Notice?',                  // Title.
			array( $this, 'enable_notice_callback' ), // Callback.
			'cookie-notification-admin',              // Page.
			'cookie_notification_settings'            // Section.
		);

		// Setting: Hide in US
		add_settings_field(
			'hide_in_us', // ID
			'Hide in the US?', // Title
			array( $this, 'hide_in_us_callback' ), // Callback
			'cookie-notification-admin', // Page
			'cookie_notification_settings' // Section
		);

		// Setting: Hide in US
		add_settings_field(
			'google_api', // ID
			'Google API Key (Only if you want to hide it in the US)', // Title
			array( $this, 'google_api_callback' ), // Callback
			'cookie-notification-admin', // Page
			'cookie_notification_settings' // Section
		);

		// Setting: Effective Date.
		add_settings_field(
			'effective_date',                                 // ID.
			__( 'Effective Date', 'em-cookie-notification' ), // Title.
			array( $this, 'effective_date_callback' ),           // Callback.
			'cookie-notification-admin',                      // Page.
			'cookie_notification_settings'                    // Section.
		);

		// Setting: Cookie Expiry (in days).
		add_settings_field(
			'expiry_days',                            // ID.
			__( 'Expiry', 'em-cookie-notification' ), // Title.
			array( $this, 'expiry_days_callback' ),   // Callback.
			'cookie-notification-admin',              // Page.
			'cookie_notification_settings'            // Section.
		);

		// Setting: Front-end position (top or bottom).
		add_settings_field(
			'position',                          // ID.
			'Cookie Notice Position',            // Title.
			array( $this, 'position_callback' ), // Callback.
			'cookie-notification-admin',         // Page.
			'cookie_notification_settings'       // Section.
		);

		// Setting: Enable front-end styles.
		add_settings_field(
			'styles',                          // ID.
			'Cookie Notice Styles',            // Title.
			array( $this, 'styles_callback' ), // Callback.
			'cookie-notification-admin',       // Page.
			'cookie_notification_settings'     // Section.
		);

		// Setting: Cookie text/message.
		add_settings_field(
			'notification_text',                          // ID.
			'Notification Text',                          // Title.
			array( $this, 'notification_text_callback' ), // Callback.
			'cookie-notification-admin',                  // Page.
			'cookie_notification_settings'                // Section.
		);

		// Setting: Button Text.
		add_settings_field(
			'button_text',                          // ID.
			'Button Text',                          // Title.
			array( $this, 'button_text_callback' ), // Callback.
			'cookie-notification-admin',            // Page.
			'cookie_notification_settings'          // Section.
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @since    1.0.0
	 *
	 * @param array $input Contains all settings fields as array keys.
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['enable_notice'] ) && ( 1 === (int) $input['enable_notice'] ) ) {
			$new_input['enable_notice'] = 1;
		}

		if ( isset( $input['hide_in_us'] ) && ( 1 == (int) $input['hide_in_us']) ) {
			$new_input['hide_in_us'] = 1;
		}

		if ( isset( $input['google_api'] ) ) {
			$new_input['google_api'] = sanitize_text_field( $input['google_api'] );
		}

		if ( isset( $input['effective_date'] ) ) {
			if ( ! empty( $input['effective_date'] ) ) {
				// Strip any html tags.
				$sanitized_value = strip_tags( $input['effective_date'] );
				// Change m/d/Y format to Ymd like 20170631.
				$formatted_date = strtotime( $sanitized_value );
				// Create a new date string using the formatted date.
				$new_input['effective_date'] = date( 'Ymd', $formatted_date );
			} else {
				$new_input['effective_date'] = date( 'Ymd' );
			}
		}

		if ( isset( $input['expiry_days'] ) ) {
			$new_input['expiry_days'] = intval( $input['expiry_days'] );
		}

		if ( isset( $input['styles'] ) ) {
			$new_input['styles'] = sanitize_text_field( $input['styles'] );
		}

		if ( isset( $input['position'] ) ) {
			$new_input['position'] = sanitize_text_field( $input['position'] );
		}

		if ( isset( $input['notification_text'] ) ) {
			$new_input['notification_text'] = wp_kses_post( $input['notification_text'] );
		}

		if ( isset( $input['button_text'] ) ) {
			$new_input['button_text'] = sanitize_text_field( $input['button_text'] );
		}

		return $new_input;

	}

	/**
	 * Print the Section text
	 *
	 * @since    1.0.0
	 */
	public function print_section_info() {
		print 'Enter your settings below:';
	}

	/**
	 * Setting: Enable cookie notice.
	 *
	 * Gets the settings option array and print one of its values.
	 *
	 * @since    1.0.0
	 */
	public function enable_notice_callback() {
	?>
		<input type="checkbox" name="em_cookie_notification[enable_notice]" value="1"<?php checked( 1 === (int) $this->options['enable_notice'] ); ?> />
	<?php
	}

	/**
	 * Setting: Effective Date.
	 *
	 * Determines when cookie notification begins its display.
	 * This value will be stored in the cookie via JS on the client side, so
	 * when user updates the effective date, user will be forced to re-acknowledge
	 * the cookie notice.
	 *
	 * This value will be used when enqueueing scripts, using `wp_localize_script`.
	 *
	 * @since    1.0.0
	 */
	public function effective_date_callback() {

		// If a date exists, format it to be human readable format like 06/31/2017.
		if ( ! empty( $this->options['effective_date'] ) ) {
			// Convert to date stamp.
			$datestamp = strtotime( $this->options['effective_date'] );
			// Format datestamp to be human readable.
			$effective_date_value = date( 'm/d/Y', $datestamp );
		} else {
			// Sets fallback date to today's date if none exists.
			$effective_date_value = date( 'm/d/Y' );
		}

		// Displays the expiry_days field.
	?>
		<input class="em-cookie-notification-datepicker" type="text" name="em_cookie_notification[effective_date]" value="<?php echo esc_attr( $effective_date_value ); ?>" autocomplete="off" />
		<p class="description"><?php esc_html_e( 'Enter the start date when the cookie notification will be effective.', 'em-cookie-notification' ); ?><br>
		<?php esc_html_e( 'Note: Updating this value in the future will force users to reacknowledge the cookie notification.', 'em-cookie-notification' ); ?><br>
		<?php esc_html_e( 'If using a caching service/plugin, be sure to purse the cache so pages receive this change.', 'em-cookie-notification' ); ?></p>
	<?php
	}

	/**
	 * Setting: Expiry (in days).
	 *
	 * Determines the length of time the cookie is valid for, before expiring.
	 *
	 * This value will be used when enqueueing scripts, using `wp_localize_script`.
	 *
	 * @since    1.0.0
	 */
	public function expiry_days_callback() {

		// Retrieves expiry_days value. If empty, defaults to 365 (days).
		$expiry_value = ! empty( $this->options['expiry_days'] ) ? (int) $this->options['expiry_days'] : 365;

		// Displays the expiry_days field.
	?>
		<input type="number" min="0" name="em_cookie_notification[expiry_days]" value="<?php echo esc_attr( $expiry_value ); ?>" />
		<p class="description">Enter the number of days that the cookie will stay valid for, before expiring.</p>
	<?php
	}

	/**
	 * Setting: Hide in the US?
	 *
	 * Gets the settings option array and print one of its values.
	 *
	 * @since    1.0.0
	 */
	public function hide_in_us_callback() {
	?>
		<input type="checkbox" name="em_cookie_notification[hide_in_us]" value="1"<?php checked( 1 == $this->options['hide_in_us'] ); ?> />
	<?php
	}

	/**
	 * Setting: Hide in the US?
	 *
	 * Gets the settings option array and print one of its values.
	 *
	 * @since    1.0.0
	 */
	public function google_api_callback() {
		printf(
			'<input style="width: 100%%" type="text" id="google_api" name="em_cookie_notification[google_api]" value="%s" />',
			isset( $this->options['google_api'] ) ? esc_attr( $this->options['google_api']) : ''
		);
	?>
		<h4>How to get a key:</h4>

		<ol>
			<li>Create a project (if it doesn't exists yet) and follow the steps to generate a "Geolocation" key <a target="_blank" href="https://developers.google.com/maps/documentation/geolocation/get-api-key">here</a>.</li>
			<li>Generate a key for &quot;Geocoding&quot; <a target="_blank" href="https://developers.google.com/maps/documentation/geocoding/start#get-a-key">here</a>, use the same project you created before.</li>
		</ol>

		<p>You can use <strong>any</strong> of the keys you created, just remember to generate <strong>both</strong>.</p>
		<p>To view your Google console with all your projects, click <a target="_blank" href="https://console.developers.google.com/cloud-resource-manager">here</a></p>
	<?php
	}

	/**
	 * Setting: Enable styles.
	 *
	 * Determines if a stylesheet should be output with the cookie notice.
	 *
	 * @since    1.0.0
	 */
	public function styles_callback() {

		// Retrieves styles value. If empty, defaults to all.
		$styles_value = ! empty( $this->options['styles'] ) ? (string) $this->options['styles'] : 'all';

		// Displays the styles options.
	?>
		<input type="radio" name="em_cookie_notification[styles]" value="all"<?php checked( 'all' === $styles_value ); ?> /> Layout &amp; Styles<br />
		<input type="radio" name="em_cookie_notification[styles]" value="layout"<?php checked( 'layout' === $styles_value ); ?> /> Layout Only <br />
		<input type="radio" name="em_cookie_notification[styles]" value="none"<?php checked( 'none' === $styles_value ); ?> /> None
	<?php
	}

	/**
	 * Setting: Define position of message.
	 *
	 * If styles option is output, allows users to output the message to the top or bottom of the screen.
	 *
	 * @since    1.0.0
	 */
	public function position_callback() {

		// Retrieves position value. If empty, defaults to top.
		$position_value = ! empty( $this->options['position'] ) ? (string) $this->options['position'] : 'top';

		// Displays the position options.
	?>
		<input type="radio" name="em_cookie_notification[position]" value="top"<?php checked( 'top' === $position_value ); ?> /> <?php esc_html_e( 'Top', 'em-cookie-notification' ); ?><br />
		<input type="radio" name="em_cookie_notification[position]" value="bottom"<?php checked( 'bottom' === $position_value ); ?> /> <?php esc_html_e( 'Bottom', 'em-cookie-notification' ); ?> <br />
	<?php
	}

	/**
	 * Setting: Cookie content.
	 *
	 * The message that displays to users in the cookie notification.
	 *
	 * @since    1.0.0
	 */
	public function notification_text_callback() {

		// Retrieves custom button text. If empty, defaults to fallback text.
		$content = $this->options['notification_text'];
		if ( empty( $this->options ) ) {
			$content = __( 'To give you the best possible experience, this site uses cookies and by continuing to use the site you agree that we can save them on your device.', 'em-cookie-notification' );
		}

		// Tiny MCE Settings.
		$settings = array(
			'media_buttons' => false,
			'textarea_rows' => '5',
			'textarea_name' => 'em_cookie_notification[notification_text]',
		);

		// Tiny MCE Editor ID.
		$editor_id = 'notification_text';

		// Displays the Tiny MCE Editor.
		wp_editor( $content, $editor_id, $settings );
	}

	/**
	 * Setting: Button Text.
	 *
	 * The text that displays for the button to close the cookie notification.
	 *
	 * @since    1.0.0
	 */
	public function button_text_callback() {

		// Retrieves custom button text. If empty, defaults to fallback "Continue" text.
		$button_text = ! empty( $this->options['button_text'] ) ? $this->options['button_text'] : __( 'Continue', 'em-cookie-notification' );

		// Displays button text.
		printf( '<input type="text" id="button_text" name="em_cookie_notification[button_text]" value="%s" />', esc_attr( $button_text ) );
	}

}
