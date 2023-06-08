<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.emagine.com
 * @since             1.0.0
 * @package           Em_Cookie_Notification
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Cookie Notification
 * Plugin URI:        http://www.emagine.com
 * Description:       Displays cookie notification. Customized for J. Knipper and Co. 
 * Version:           1.0.1
 * Author:            emagine
 * Author URI:        http://www.emagine.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       em-cookie-notification
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-em-cookie-notification-activator.php
 */
function activate_em_cookie_notification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-em-cookie-notification-activator.php';
	Em_Cookie_Notification_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-em-cookie-notification-deactivator.php
 */
function deactivate_em_cookie_notification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-em-cookie-notification-deactivator.php';
	Em_Cookie_Notification_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_em_cookie_notification' );
register_deactivation_hook( __FILE__, 'deactivate_em_cookie_notification' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-em-cookie-notification.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_em_cookie_notification() {

	$plugin = new Em_Cookie_Notification();
	$plugin->run();

}
run_em_cookie_notification();
