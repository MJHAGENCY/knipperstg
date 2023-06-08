<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.emagine.com
 * @since      1.0.0
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/public
 * @author     emagine <wordpress@emagine.com>
 */
class Em_Cookie_Notification_Public {


	/**
	 * Determines whether to show/hide cookie message and scripts/styles.
	 *
	 * @since    1.0.0
	 * @var      boolean $has_content
	 */
	private $has_content;

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
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = get_option( 'em_cookie_notification' );
		$this->has_content = ! empty( $this->options['notification_text'] ) ? true : false;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// Return immediately if cookie message has no content.
		if ( ! $this->has_content ) {
			return;
		}

		$styles = isset( $this->options['styles'] ) ? $this->options['styles'] : false;

		if ( 'all' === $styles ) :
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/em-cookie-notification-public.css', array(), $this->version, 'all' );
		elseif ( 'layout' === $styles ) :
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/em-cookie-notification-public-layout.css', array(), $this->version, 'all' );
		endif;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Return immediately if cookie message has no content.
		if ( ! $this->has_content ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/em-cookie-notification-public.js', array( 'jquery' ), $this->version, true );


		// Adds data to a JavaScript object so our scripts have access to data from WordPress.
		$data = array(
			'effective'  => ! empty( $this->options['effective_date'] ) ? $this->options['effective_date'] : date( 'Ymd' ),
			'expiry'     => ! empty( $this->options['expiry_days'] ) ? intval( $this->options['expiry_days'] ) : 365,
			'hide_in_us' => ( boolval( $this->options['hide_in_us'] ) ? 1 : 0 ),
			'api'        => $this->options['google_api'],
		);
		wp_localize_script( $this->plugin_name, 'EmCookieNotification', $data );

	}

	/**
	 * Output public display.
	 *
	 * @since    1.0.0
	 */
	public function output_notification() {

		// Return immediately if cookie message has no content.
		if ( ! $this->has_content ) {
			return;
		}

		if ( ! empty( $this->options['notification_text'] ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/em-cookie-notification-public-display.php';
		}
	}
}
