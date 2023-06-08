<?php
/*
 * @package   GFP_Salesforce_WebTo\GFP_Salesforce_WebTo_Addon
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
 * Class GFP_Salesforce_WebTo_Addon
 *
 * Gravity Forms Add-On
 *
 * @since  4.0.0
 *
 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
 */
class GFP_Salesforce_WebTo_Addon extends GFFeedAddOn {

	/**
	 * @var string Version number of the Add-On
	 */
	protected $_version = GFP_SALESFORCE_WEBTO_CURRENT_VERSION;
	/**
	 * @var string Gravity Forms minimum version requirement
	 */
	protected $_min_gravityforms_version = '2.2';
	/**
	 * @var string URL-friendly identifier used for form settings, add-on settings, text domain localization...
	 */
	protected $_slug = GFP_SALESFORCE_WEBTO_SLUG;
	/**
	 * @var string Relative path to the plugin from the plugins folder
	 */
	protected $_path = GFP_SALESFORCE_WEBTO_PATH;
	/**
	 * @var string Full path to the plugin. Example: __FILE__
	 */
	protected $_full_path = GFP_SALESFORCE_WEBTO_FILE;
	/**
	 * @var string URL to the App website.
	 */
	protected $_url = 'https://gravityplus.pro/gravity-forms-salesforce';
	/**
	 * @var string Title of the plugin to be used on the settings page, form settings and plugins page.
	 */
	protected $_title = 'Gravity Forms Salesforce Web-To Add-On';
	/**
	 * @var string Short version of the plugin title to be used on menus and other places where a less verbose string
	 *      is useful.
	 */
	protected $_short_title = 'Salesforce Web-To';
	/**
	 * @var array Members plugin integration. List of capabilities to add to roles.
	 */
	protected $_capabilities = array(
		'gravityforms_salesforce_webto_plugin_settings',
		'gravityforms_salesforce_webto_form_settings',
		'gravityforms_salesforce_webto_uninstall'
	);

	// ------------ Permissions -----------
	/**
	 * @var string|array A string or an array of capabilities or roles that have access to the settings page
	 */
	protected $_capabilities_settings_page = array( 'gravityforms_salesforce_webto_plugin_settings' );

	/**
	 * @var string|array A string or an array of capabilities or roles that have access to the form settings
	 */
	protected $_capabilities_form_settings = array( 'gravityforms_salesforce_webto_form_settings' );
	/**
	 * @var string|array A string or an array of capabilities or roles that can uninstall the plugin
	 */
	protected $_capabilities_uninstall = array( 'gravityforms_salesforce_webto_uninstall' );

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @var bool|null
	 */
	private $_valid_organization_id = null;

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @var bool
	 */
	private $_processing_feed = false;

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return GFP_Salesforce_WebTo_Addon|null
	 */
	public static function get_instance() {

		if ( self::$_instance == null ) {

			self::$_instance = new self();

		}

		return self::$_instance;

	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $previous_version
	 */
	public function upgrade( $previous_version ) {

		do_action( "gform_{$this->_slug}_upgrade", $previous_version, $this );


		return;

	}

	/**
	 * @see    GFAddOn::plugin_settings_fields
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {

		$settings_fields = array();

		$settings_fields[] = array(
			'title'  => __( 'Account Information', 'gravity-forms-salesforce' ),
			'fields' => array(
				array(
					'name'                => 'organization_id',
					'label'               => __( 'Organization ID', 'gravity-forms-salesforce' ),
					'type'                => 'text',
					'class'               => 'medium code',
					'required'            => true,
					'validation_callback' => array( $this, 'validate_organization_id' ),
					'feedback_callback' => array( $this, 'get_organization_id_validation' )
				),
				array(
					'name'          => 'date_format',
					'label'         => __( 'Date Format', 'gravity-forms-salesforce' ),
					'type'          => 'radio',
					'horizontal'    => 'true',
					'choices'       => array(
						array(
							'value' => 'm/d/Y',
							'label' => 'mm/dd/yyyy',
						),
						array(
							'value' => 'd/m/Y',
							'label' => 'dd/mm/yyyy',
						),
					),
					'default_value' => $this->get_default_date_format()
				)
			)
		);

		return apply_filters( "gform_{$this->_slug}_plugin_settings_fields", $settings_fields, $this );
	}

	/**
	 * Get the default date format by guessing where you are.
	 *
	 * Taken from Zack Katz
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return string
	 */
	private function get_default_date_format() {

		switch ( get_locale() ) {

			case 'en_US':
			case 'en_CA':

				$format = 'm/d/Y';

				break;

			default:

				$format = 'd/m/Y';

				break;
		}

		return $format;
	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $field
	 * @param $field_setting
	 */
	public function validate_organization_id( $field, $field_setting ) {

		if ( ! empty( $field_setting ) ) {

			$validation = $this->send_request( 'https://test.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8', array(
				'oid'    => $field_setting,
				'retURL' => add_query_arg( array() )
			) );

			$this->_valid_organization_id = $validation['success'];

			if ( ! $validation[ 'success' ] ) {

				if ( empty( $validation['response'] ) ) {

					$this->set_field_error( $field, __( 'Unable to validate Organization ID: Error connecting to Salesforce. Please try again.', 'gravity-forms-salesforce' ) );

				} else {

					$this->set_field_error( $field, sprintf( __( 'Error validating Organization ID: %s', 'gravity-forms-salesforce' ), $validation[ 'response' ] ) );

				}

				$settings = $this->get_plugin_settings();

				$settings['organization_id'] = '';

				parent::update_plugin_settings( $settings );

			}

		} else if ( empty( $field_setting ) ) {

			$this->set_field_error( $field, __( 'Please enter your Salesforce Organization ID', 'gravity-forms-salesforce' ) );

			$this->_valid_organization_id = false;

		}

	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $value
	 * @param $field
	 *
	 * @return bool|null
	 */
	public function get_organization_id_validation( $value, $field ) {

		if ( ! is_null( $this->_valid_organization_id ) ) {

			$is_valid = $this->_valid_organization_id;

		}
		else {

			$this->validate_organization_id( $field, $value );

			$is_valid = $this->get_organization_id_validation( $value, $field );

		}

		return $is_valid;
	}

	/**
	 * @since 4.1.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 */
	public function render_uninstall() {

		do_action( "gform_{$this->_slug}_render_uninstall", $this );

		parent::render_uninstall();

	}

	/**
	 * @see    GFFeedAddOn::can_create_feed
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return bool
	 */
	public function can_create_feed() {

		$organization_id = $this->get_plugin_setting( 'organization_id' );


		return ! empty( $organization_id );
	}

	/**
	 * @see    GFFeedAddOn::feed_list_message
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return bool | string
	 */
	public function feed_list_message() {

		$message = parent::feed_list_message();

		if ( $message !== false ) {

			return $message;

		}

		$organization_id = $this->get_plugin_setting( 'organization_id' );

		if ( empty( $organization_id ) ) {

			$settings_label = __( 'enter your Salesforce Organization ID', 'gravity-forms-salesforce' );

			$settings_link = sprintf( '<a href="%s">%s</a>', $this->get_plugin_settings_url(), $settings_label );

			return sprintf( __( 'To get started, please %s.', 'gravity-forms-salesforce' ), $settings_link );
		}

		return false;
	}

	/**
	 * @see    GFFeedAddOn::can_duplicate_feed
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param array|int $id
	 *
	 * @return bool
	 */
	public function can_duplicate_feed( $id ) {

		return true;

	}

	/**
	 * @see    GFFeedAddOn::feed_list_columns
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return array
	 */
	public function feed_list_columns() {

		return array(
			'feedName' => __( 'Name', 'gravity-forms-salesforce' ),
			'webto_object'  => __( 'Web-To', 'gravity-forms-salesforce' )
		);

	}

	/**
	 * @see    GFFeedAddOn::feed_settings_fields
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return array
	 */
	public function feed_settings_fields() {

		$feed_field_name = array(
			'label'    => __( 'Name', 'gravity-forms-salesforce' ),
			'type'     => 'text',
			'name'     => 'feedName',
			'tooltip'  => __( 'Name for this feed', 'gravity-forms-salesforce' ),
			'class'    => 'medium',
			'required' => true
		);

		$feed_field_webto_object = array(
			'label'    => __( 'Salesforce Object', 'gravity-forms-salesforce' ),
			'type'     => 'select',
			'name'     => 'webto_object',
			'tooltip'  => __( 'Select the Salesforce Web-To object you want to create when someone submits this form', 'gravity-forms-salesforce' ),
			'choices'  => array(
				array(
					'label' => __( 'Select', 'gravity-forms-salesforce' ),
					'value' => ''
				),
				array(
					'label' => __( 'Lead', 'gravity-forms-salesforce' ),
					'value' => 'lead'
				),
				array(
					'label' => __( 'Case', 'gravity-forms-salesforce' ),
					'value' => 'case'
				)
			),
			'onchange' => "jQuery(this).parents('form').submit();jQuery( this ).parents( 'form' ).find(':input').prop('disabled', true );",
			'required' => true
		);

		$fields_to_map = $this->get_fields_to_map();

		$feed_field_required_fields = array(
			'label'           => __( 'Required Fields', 'gravity-forms-salesforce' ),
			'type'            => 'field_map',
			'name'            => 'required_fields',
			'tooltip'         => __( 'Select the form field with the value for the Salesforce required field', 'gravity-forms-salesforce' ),
			'field_map'       => $fields_to_map[ 'required' ],
			'disabled_custom' => true
		);

		$feed_field_other_fields = array(
			'label'     => __( 'Other Fields', 'gravity-forms-salesforce' ),
			'type'      => 'dynamic_field_map',
			'name'      => 'other_fields',
			'tooltip'   => __( 'Select or enter your Salesforce field name, then select the form field with the value for that field', 'gravity-forms-salesforce' ),
			'field_map' => $fields_to_map[ 'other' ]
		);

		$feed_field_conditional_logic = array(
			'name'    => 'conditionalLogic',
			'label'   => __( 'Conditional Logic', 'gravity-forms-salesforce' ),
			'type'    => 'feed_condition',
			'tooltip' => '<h6>' . __( 'Conditional Logic', 'gravity-forms-salesforce' ) . '</h6>' . __( 'When conditions are enabled, form submissions will only be sent to Salesforce when the conditions are met. When disabled, all form submissions will be sent to Salesforce.', 'gravity-forms-salesforce' )
		);

		$sections = array(
			array(
				'title'  => __( 'Feed Name', 'gravity-forms-salesforce' ),
				'fields' => array(
					$feed_field_name
				)
			),
			array(
				'title'  => __( 'Web-To', 'gravity-forms-salesforce' ),
				'fields' => array(
					$feed_field_webto_object
				)
			),
			array(
				'title'      => __( 'Salesforce Fields', 'gravity-forms-salesforce' ),
				'dependency' => 'webto_object',
				'fields'     => array(
					$feed_field_required_fields,
					$feed_field_other_fields
				)
			),
			array(
				'title'      => __( 'Conditional Logic', 'gravity-forms-salesforce' ),
				'dependency' => 'webto_object',
				'fields'     => array(
					$feed_field_conditional_logic
				)
			)
		);

		return $sections;
	}

	/**
	 * Get Salesforce Web-to-Lead required fields
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return array
	 */
	private function get_webto_object_required_fields( $webto_object ) {

		$this->log_debug( __METHOD__ );

		switch ( $webto_object ) {

			case 'lead':
			case 'case':

				$required_fields = array( array(
					'label'    => __( 'Email', 'gravity-forms-salesforce' ),
					'name'    => 'email',
					'required' => true
				));

				break;

		}


		return $required_fields;

	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $webto_object
	 *
	 * @return array
	 */
	private function get_webto_object_other_fields( $webto_object ) {

		$this->log_debug( __METHOD__ );

		$other_fields = array(
			array(
				'label' => __( 'Select', 'gravity-forms-salesforce' ),
				'value' => ''
			)
		);

		switch ( $webto_object ) {

			case 'lead':

				$lead_fields = array(
					array( 'label' => __( 'Salutation', 'gravity-forms-salesforce' ), 'value' => 'salutation' ),
					array( 'label' => __( 'First Name', 'gravity-forms-salesforce' ), 'value' => 'first_name' ),
					array( 'label' => __( 'Last Name', 'gravity-forms-salesforce' ), 'value' => 'last_name' ),
					array( 'label' => __( 'Street', 'gravity-forms-salesforce' ), 'value' => 'street' ),
					array( 'label' => __( 'Street Line 2', 'gravity-forms-salesforce' ), 'value' => 'street2' ),
					array( 'label' => __( 'City', 'gravity-forms-salesforce' ), 'value' => 'city' ),
					array( 'label' => __( 'State/Province', 'gravity-forms-salesforce' ), 'value' => 'state' ),
					array( 'label' => __( 'Zip', 'gravity-forms-salesforce' ), 'value' => 'zip' ),
					array( 'label' => __( 'Country', 'gravity-forms-salesforce' ), 'value' => 'country' ),
					array( 'label' => __( 'Phone', 'gravity-forms-salesforce' ), 'value' => 'phone' ),
					array( 'label' => __( 'Mobile', 'gravity-forms-salesforce' ), 'value' => 'mobile' ),
					array( 'label' => __( 'Fax', 'gravity-forms-salesforce' ), 'value' => 'fax' ),
					array( 'label' => __( 'Company', 'gravity-forms-salesforce' ), 'value' => 'company' ),
					array( 'label' => __( 'Title', 'gravity-forms-salesforce' ), 'value' => 'title' ),
					array( 'label' => __( 'Website', 'gravity-forms-salesforce' ), 'value' => 'URL' ),
					array( 'label' => __( 'Description', 'gravity-forms-salesforce' ), 'value' => 'description' ),
					array( 'label' => __( 'Lead Source', 'gravity-forms-salesforce' ), 'value' => 'lead_source' ),
					array( 'label' => __( 'Industry', 'gravity-forms-salesforce' ), 'value' => 'industry' ),
					array( 'label' => __( 'Rating', 'gravity-forms-salesforce' ), 'value' => 'rating' ),
					array( 'label' => __( 'Annual Revenue', 'gravity-forms-salesforce' ), 'value' => 'revenue' ),
					array( 'label' => __( 'Employees', 'gravity-forms-salesforce' ), 'value' => 'employees' ),
					array( 'label' => __( 'Campaign', 'gravity-forms-salesforce' ), 'value' => 'Campaign_ID' ),
					array( 'label' => __( 'Member Status', 'gravity-forms-salesforce' ), 'value' => 'member_status' ),
					array( 'label' => __( 'Email Opt Out', 'gravity-forms-salesforce' ), 'value' => 'emailOptOut' ),
					array( 'label' => __( 'Fax Opt Out', 'gravity-forms-salesforce' ), 'value' => 'faxOptOut' ),
					array( 'label' => __( 'Do Not Call', 'gravity-forms-salesforce' ), 'value' => 'doNotCall' )
				);

				$other_fields = array_merge( $other_fields, $lead_fields );

				break;

			case 'case':

				$case_fields = array(
					array( 'label' => __( 'Name', 'gravity-forms-salesforce' ), 'value' => 'name' ),
					array( 'label' => __( 'Phone', 'gravity-forms-salesforce' ), 'value' => 'phone' ),
					array( 'label' => __( 'Subject', 'gravity-forms-salesforce' ), 'value' => 'subject' ),
					array( 'label' => __( 'Description', 'gravity-forms-salesforce' ), 'value' => 'description' ),
					array( 'label' => __( 'Company', 'gravity-forms-salesforce' ), 'value' => 'company' ),
					array( 'label' => __( 'Type', 'gravity-forms-salesforce' ), 'value' => 'type' ),
					array( 'label' => __( 'Status', 'gravity-forms-salesforce' ), 'value' => 'status' ),
					array( 'label' => __( 'Reason', 'gravity-forms-salesforce' ), 'value' => 'reason' ),
					array( 'label' => __( 'Priority', 'gravity-forms-salesforce' ), 'value' => 'priority' )
				);

				$other_fields = array_merge( $other_fields, $case_fields );

				break;

		}


		return $other_fields;
	}


	/**
	 * Get required fields for field mapping
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @return array
	 */
	private function get_fields_to_map() {

		$this->log_debug( __METHOD__ );

		$fields_to_map[ 'required' ] = array();

		$fields_to_map[ 'other' ] = array(
			array(
				'label' => 'Select',
				'value' => ''
			)
		);

		$webto_object = $this->get_setting( 'webto_object' );

		if ( ! empty( $webto_object ) ) {

			$fields_to_map[ 'required' ] = $this->get_webto_object_required_fields( $webto_object );

			$fields_to_map[ 'other' ] = $this->get_webto_object_other_fields( $webto_object );

		}


		return $fields_to_map;

	}

	/**
	 * @see    parent
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param array  $form
	 * @param array  $entry
	 * @param string $field_id
	 *
	 * @return mixed|null|string
	 */
	public function get_field_value( $form, $entry, $field_id ) {

		if ( $this->_processing_feed ) {

			$gf_field_type = GFFormsModel::get_input_type( GFFormsModel::get_field( $form, $field_id ) );

			if ( 'date_created' == strtolower( $field_id ) ) {

				$field_value = $this->format_date( rgar( $entry, strtolower( $field_id ) ) );

				$field_value = gf_apply_filters( array(
					'gform_addon_field_value',
					$form[ 'id' ],
					$field_id
				), $field_value, $form, $entry, $field_id, $this->_slug );

				$field_value = $this->maybe_override_field_value( $field_value, $form, $entry, $field_id );

			} else if ( 'date' == $gf_field_type ) {

				$field = GFFormsModel::get_field( $form, $field_id );

				if ( is_object( $field ) ) {

					$input_type = $field->get_input_type();

					if ( is_callable( array( $this, "get_{$input_type}_field_value" ) ) ) {

						$field_value = call_user_func( array(
							$this,
							"get_{$input_type}_field_value"
						), $entry, $field_id, $field );

					} else {

						$field_value = $field->get_value_export( $entry, $field_id );

					}

				} else {

					$field_value = rgar( $entry, $field_id );

				}

				$field_value = $this->format_date( $field_value );

				$field_value = gf_apply_filters( array(
					'gform_addon_field_value',
					$form[ 'id' ],
					$field_id
				), $field_value, $form, $entry, $field_id, $this->_slug );

				$field_value = $this->maybe_override_field_value( $field_value, $form, $entry, $field_id );

			} else {

				if ( ($gf_field_type == 'checkbox') && ($entry[$field_id]) ) {
					$entry[$field_id] = 1;
				}
				$field_value = $entry[$field_id];
				// $field_value = parent::get_field_value( $form, $entry, $field_id );

			}

			if ( is_array( $field_value ) ) {

				$field_value = implode( ';', array_filter( $field_value, 'rgblank' ) );

			}

		}

		return GFCommon::trim_all( $field_value );

	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $date_value
	 *
	 * @return string
	 */
	private function format_date( $date_value ) {

		$date_format = $this->get_plugin_setting( 'date_format' );

		$date_created = new DateTime( $date_value );

		$formatted_date = $date_created->format( $date_format );


		return $formatted_date;
	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $webto_object
	 *
	 * @return string
	 */
	private function get_webto_post_url( $webto_object ) {

		$url = '';

		switch ( $webto_object ) {

			case 'case':

				$url = 'https://webto.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8';

				break;

			default:

				$url = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';

				break;
		}

		return $url;
	}

	/**
	 * @see    GFFeedAddOn::process_feed
	 *
	 * Performs the Salesforce action when the form is submitted
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $feed
	 * @param $entry
	 * @param $form
	 */
	public function process_feed( $feed, $entry, $form ) {

		$this->log_debug( __METHOD__ );

		$this->_processing_feed = true;

		$organization_id = $this->get_plugin_setting( 'organization_id' );

		if ( empty( $organization_id ) ) {

			$this->log_error( 'Empty organization ID. Nothing to do.' );

		} else {

			$webto_object = (string) $this->get_setting( 'webto_object', '', $feed[ 'meta' ] );

			foreach ( $this->get_field_map_fields( $feed, 'required_fields' ) as $name => $value ) {

				$required_field_data[ $name ] = $this->get_mapped_field_value( "required_fields_{$name}", $form, $entry, $feed[ 'meta' ] );

			}

			unset( $name, $value );

			$other_field_data = $this->get_dynamic_field_map_values( 'other_fields', $feed, $entry, $form );

			if ( ! empty( $other_field_data[ 'street2' ] ) ) {

				$other_field_data[ 'street' ] .= " {$other_field_data['street2']}";

				unset( $other_field_data[ 'street2' ] );
			}

			unset( $name, $value );

			$field_data = array_merge( $required_field_data, $other_field_data );

			foreach ( $field_data as $field_name => $value ) {

				if ( in_array( $field_name, array( 'emailOptOut', 'faxOptOut', 'doNotCall' ) ) ) {

					$field_data[ $field_name ] = $value = (string) intval( (bool) $value );

				}

				if ( empty( $value ) && '0' !== $value ) {

					unset( $field_data[ $field_name ] );

				}

			}

			unset( $field_name, $value );

			$organization_key = ( 'lead' == $webto_object ) ? 'oid' : 'orgid';

			$response = $this->send_request(
				apply_filters( "{$this->_slug}_webto_post_url", $this->get_webto_post_url( $webto_object ), $feed, $entry, $form ),
				apply_filters( "{$this->_slug}_field_data", array_merge( $field_data, array( $organization_key => $organization_id, 'retURL' => add_query_arg( array() ) ) ), $feed, $entry, $form )
			);

		}

		$this->_processing_feed = false;

	}


	/**
	 * Get field values from entry, for a dynamic field map
	 *
	 * TODO Note: this doesn't work for image or signature fields
	 *
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $field_name
	 * @param $feed
	 * @param $entry
	 * @param $form
	 *
	 * @return array
	 */
	private function get_dynamic_field_map_values( $field_name, $feed, $entry, $form ) {

		$field_map_values = array();


		$field_map_field_ids = $this->get_dynamic_field_map_fields( $feed, $field_name );


		foreach ( $field_map_field_ids as $name => $field_id ) {

			$field_map_values[ $name ] = $this->get_field_value( $form, $entry, $field_id );

		}


		return $field_map_values;

	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $url
	 * @param $body
	 *
	 * @return array
	 */
	public function send_request( $url, $body ) {

		$this->log_debug( __METHOD__ );

		$arguments = array(
			'timeout'   => 30,
			'sslverify' => false,
			'headers'   => array(
				'User-Agent' => 'Gravity Forms + Salesforce ' . GFP_SALESFORCE_WEBTO_CURRENT_VERSION . '/' . get_bloginfo( 'url' ),
			),
			'body'      => $body
		);

		$this->log_debug( 'Arguments: ' . print_r( $arguments, true ) );

		$raw_response = wp_remote_post( $url, $arguments );


		return $this->handle_response( $raw_response );
	}

	/**
	 * @since  4.0.0
	 *
	 * @author Naomi C. Bush for gravity+ <support@gravityplus.pro>
	 *
	 * @param $raw_response
	 *
	 * @return array
	 */
	private function handle_response( $raw_response ) {

		$response = array();

		$success = false;

		if ( is_wp_error( $raw_response ) || ( 200 != wp_remote_retrieve_response_code( $raw_response ) ) ) {

			$this->log_error( 'Error: ' . print_r( $raw_response, true ) );

		} else if ( strpos( $raw_response[ 'headers' ][ 'is-processed' ], 'Exception' ) ) {

			$response = $raw_response[ 'headers' ][ 'is-processed' ];

			$this->log_error( 'Error: ' . print_r( $raw_response, true ) );

		} else {

			$success = true;

			$response = $raw_response;

			$this->log_debug( "Success." . print_r( $response, true ) );

		}

		return array( 'success' => $success, 'response' => $response );

	}

}
