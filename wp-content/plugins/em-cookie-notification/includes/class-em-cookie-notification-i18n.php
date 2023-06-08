<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.emagine.com
 * @since      1.0.0
 *
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Em_Cookie_Notification
 * @subpackage Em_Cookie_Notification/includes
 * @author     emagine <wordpress@emagine.com>
 */
class Em_Cookie_Notification_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'em-cookie-notification',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
