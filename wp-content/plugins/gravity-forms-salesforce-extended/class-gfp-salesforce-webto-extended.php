<?php
/* @package   GFP_Salesforce_WebTo_Extended\GFP_Salesforce_WebTo_Extended
 * @author    Naomi C. Bush for gravity+ <support@gravityplus.pro>
 * @copyright 2017 gravity+
 * @license   GPL-2.0+
 * @since     1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class GFP_Salesforce_WebTo_Extended
 *
 * Main plugin class
 *
 * @since  1.0.0
 *
 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
 */
class GFP_Salesforce_WebTo_Extended {

	/**
	 * GFP_Salesforce_WebTo_Extended constructor.
	 * 
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	function __construct() {
	}

	/**
	 * Let's kick things off!
	 * 
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function run() {

		add_action( 'gform_loaded', array( $this, 'gform_loaded' ) );

	}

	/**
	 * Add hooks
	 *
	 * @since  1.1.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function gform_loaded() {
		
		if ( defined( 'GFP_SALESFORCE_WEBTO_SLUG' ) ) {

			add_action( 'init', array( $this, 'init' ) );

			add_filter( 'gform_' . GFP_SALESFORCE_WEBTO_SLUG . '_plugin_settings_fields', array( $this, 'plugin_settings_fields' ) );

			add_filter( 'gform_' . GFP_SALESFORCE_WEBTO_SLUG . '_feed_settings_fields', array( $this, 'feed_settings_fields' ) );

			add_filter( GFP_SALESFORCE_WEBTO_SLUG . '_webto_post_url', array( $this, 'webto_post_url' ), 10, 4 );

			add_filter( GFP_SALESFORCE_WEBTO_SLUG . '_field_data', array( $this, 'field_data' ), 10, 4 );

		}

	}

	/**
	 * Add delayed payment support
	 *
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function init() {

		if ( class_exists( 'GFP_Salesforce_WebTo_Addon' ) ) {

			global $gravityformssalesforce_webto;

			$gravityformssalesforce_webto->get_addon_object()->add_delayed_payment_support( array(
				'option_label' => __( 'Take Salesforce action only when a payment is received.', 'gravity-forms-salesforce-extended' )
			) );

		}

	}

	/**
	 * Add plugin settings
	 *
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $plugin_settings_fields
	 *
	 * @return array
	 */
	public function plugin_settings_fields( $plugin_settings_fields ) {

		$plugin_settings_fields[] = array(
			'title'  => __( 'Extended Options', 'gravity-forms-salesforce-extended' ),
			'fields' => array(
				array(
					'name'     => 'sandbox_organization_id',
					'label'    => __( 'Sandbox Organization ID', 'gravity-forms-salesforce-extended' ),
					'type'     => 'text',
					'class'    => 'medium code'
				)
			)
		);


		return $plugin_settings_fields;
	}

	/**
	 * Add feed options
	 * 
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $feed_settings_fields
	 *
	 * @return mixed
	 */
	public function feed_settings_fields( $feed_settings_fields ) {

		array_splice( $feed_settings_fields, 3, 0, array( array(
			'title'      => __( 'Extended Options', 'gravity-forms-salesforce-extended' ),
			'dependency' => 'webto_object',
			'fields'     => array(
				array(
					'label'   => __( 'Use sandbox?', 'gravity-forms-salesforce-extended' ),
					'type'    => 'checkbox',
					'name'    => 'sandbox_checkbox',
					'choices' => array(
						array(
							'name'          => 'use_sandbox',
							'label'         => '',
							'default_value' => '0',
						)
					)
				),
				array(
					'label'   => __( 'Debugging emails?', 'gravity-forms-salesforce-extended' ),
					'type'    => 'checkbox',
					'name'    => 'debug_checkbox',
					'tooltip'    => __( 'Receive Salesforce debugging emails when this feed is processed', 'gravity-forms-salesforce-extended' ),
					'choices' => array(
						array(
							'name'          => 'debug',
							'label'         => '',
							'default_value' => '0',
						)
					)
				),
				array(
					'label'      => __( 'Debug Email', 'gravity-forms-salesforce-extended' ),
					'type'       => 'text',
					'name'       => 'debug_email',
					'tooltip'    => __( 'Email where Salesforce will send debugging emails', 'gravity-forms-salesforce-extended' ),
					'class'      => 'medium',
					'required'   => true,
					'dependency' => array( 'field' => 'debug', 'values' => array( '1' ) )
				)
			)
		) ) );


		return $feed_settings_fields;

	}

	/**
	 * Use sandbox URL
	 *
	 * @since  1.1.1
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $url
	 * @param $feed
	 * @param $entry
	 * @param $form
	 *
	 * @return string
	 */
	public function webto_post_url( $url, $feed, $entry, $form ) {

		global $gravityformssalesforce_webto;

		$addon_object = $gravityformssalesforce_webto->get_addon_object();


		$use_sandbox = $addon_object->get_setting( 'use_sandbox', '', $feed[ 'meta' ] );

		$sandbox_organization_id = $addon_object->get_plugin_setting( 'sandbox_organization_id' );


		if ( ! empty( $use_sandbox ) && ! empty( $sandbox_organization_id ) ) {

			$url = str_replace( 'webto', 'test', $url );

		}


		return $url;
	}

	/**
	 * Add field data
	 * 
	 * @since  1.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $field_data
	 * @param $feed
	 * @param $entry
	 * @param $form
	 *
	 * @return mixed
	 */
	public function field_data( $field_data, $feed, $entry, $form ) {

		global $gravityformssalesforce_webto;

		$addon_object = $gravityformssalesforce_webto->get_addon_object();


		$debug = $addon_object->get_setting( 'debug', '', $feed[ 'meta' ] );

		if ( ! empty( $debug ) ) {

			$field_data[ 'debug' ] = 1;

			$debug_email = $addon_object->get_setting( 'debug_email', '', $feed[ 'meta' ] );

			$field_data[ 'debugEmail' ] = $debug_email;

		}

		$webto_object = ucwords( (string) $addon_object->get_setting( 'webto_object', '', $feed[ 'meta' ] ) );

		$use_sandbox = $addon_object->get_setting( 'use_sandbox', '', $feed[ 'meta' ] );

		$sandbox_organization_id = $addon_object->get_plugin_setting( 'sandbox_organization_id' );


		if ( ! empty( $use_sandbox ) && ! empty( $sandbox_organization_id ) ) {

			$organization_key = ( 'Lead' == $webto_object ) ? 'oid' : 'orgid';

			$field_data[ $organization_key ] = $sandbox_organization_id;
		}


		return $field_data;
	}

}