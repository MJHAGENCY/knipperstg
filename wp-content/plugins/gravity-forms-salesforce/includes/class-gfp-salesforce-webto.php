<?php
/* @package   GFP_Salesforce_WebTo\GFP_Salesforce_WebTo
 * @author    Naomi C. Bush for gravity+ <support@gravityplus.pro>
 * @copyright 2017 gravity+
 * @license   GPL-2.0+
 * @since     4.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GFP_Salesforce_WebTo
 *
 * Main plugin class
 *
 * @since  4.0.0
 *
 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
 */
class GFP_Salesforce_WebTo {

	/**
	 * Constructor
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function construct() {
	}

	/**
	 * Register WordPress hooks
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function run() {

		add_action( 'gform_loaded', array( $this, 'gform_loaded' ) );

	}

	/**
	 * Create GF Add-On
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function gform_loaded() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {

			return;

		}

		GFForms::include_addon_framework();

		GFForms::include_feed_addon_framework();

		require_once( GFP_SALESFORCE_WEBTO_PATH . 'includes/class-addon.php' );

		GFAddOn::register( 'GFP_Salesforce_WebTo_Addon' );

	}

	/**
	 * Return GF Add-On object
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return GFP_Salesforce_WebTo_Addon
	 */
	public function get_addon_object() {

		return GFP_Salesforce_WebTo_Addon::get_instance();

	}

}