<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Exit_Notifier {

	/**
	 * The single instance of Exit_Notifier.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.9.1' ) {
		$this->_version = $version;
		$this->_token = 'exit_notifier';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Exit_Notifier_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );

		// Register exit_notifier_page shortcode
		add_shortcode('exit-notifier-page',array('Exit_Notifier','exit_notifier_page'));

	} // End __construct ()

	// exit_notifier_page shortcode implementation
	public static function exit_notifier_page ($atts = [], $content = null) {
		$content  = "<div style='height: 100%; display: flex; align-items: center; justify-content: center;'>";
		$content .= "<div style='background-color: #000; width: 50%; height: 50%;'>";
		$content .= "<div>";
		$content .= do_shortcode(get_option('exitbox_title_field','Thank you for visiting our website'));
		$content .= "</div>";
		$content .= "<div>";
		$content .= do_shortcode(get_option('exitbox_body_text','<p>The link you have selected is located on another server.  The linked site contains information that has been created, published, maintained, or otherwise posted by institutions or organizations independent of this organization.  We do not endorse, approve, certify, or control any linked websites, their sponsors, or any of their policies, activities, products, or services.  We do not assume responsibility for the accuracy, completeness, or timeliness of the information contained therein.  Visitors to any linked websites should not use or rely on the information contained therein until they have consulted with an independent financial professional.</p> <p>Please click “Go to URL…” to leave this website and proceed to the selected site.</p>'));
		$content .= "</div>";
		$content .= "</div>";
		$content .= "</div>";
		return $content;
	}

	/**
	 * Wrapper function to register a new post type
	 * @param  string $post_type   Post type name
	 * @param  string $plural      Post type item plural name
	 * @param  string $single      Post type item single name
	 * @param  string $description Description of post type
	 * @return object              Post type class object
	 */
	public function register_post_type ( $post_type = '', $plural = '', $single = '', $description = '' ) {

		if ( ! $post_type || ! $plural || ! $single ) return;

		$post_type = new Exit_Notifier_Post_Type( $post_type, $plural, $single, $description );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy
	 * @param  string $taxonomy   Taxonomy name
	 * @param  string $plural     Taxonomy single name
	 * @param  string $single     Taxonomy plural name
	 * @param  array  $post_types Post types to which this taxonomy applies
	 * @return object             Taxonomy class object
	 */
	public function register_taxonomy ( $taxonomy = '', $plural = '', $single = '', $post_types = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) return;

		$taxonomy = new Exit_Notifier_Taxonomy( $taxonomy, $plural, $single, $post_types );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
		wp_register_style( $this->_token . '-jAlert', esc_url( $this->assets_url ) . 'css/jAlert.min.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-jAlert' );
/*
		wp_register_style( $this->_token . '-animate', esc_url( $this->assets_url ) . 'css/animate.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-animate' );
*/
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
		$siteurl = explode('//',get_option('siteurl'));
		$exitboxsettings=array(
			// System
			'exitbox_version' => $this->_version,
			'siteroot' => $siteurl[1],
			'siteurl' => get_option('siteurl'),
			// Content
			'title' => do_shortcode(get_option('exitbox_title_field','Thank you for visiting our website')),
			'body' => do_shortcode(get_option('exitbox_body_text','<p>The link you have selected is located on another server.  The linked site contains information that has been created, published, maintained, or otherwise posted by institutions or organizations independent of this organization.  We do not endorse, approve, certify, or control any linked websites, their sponsors, or any of their policies, activities, products, or services.  We do not assume responsibility for the accuracy, completeness, or timeliness of the information contained therein.  Visitors to any linked websites should not use or rely on the information contained therein until they have consulted with an independent financial professional.</p> <p>Please click “Go to URL…” to leave this website and proceed to the selected site.</p>')),
			'GoButtonText' => do_shortcode(get_option('exitbox_go_button_text','Go to URL...')),
			'Include_URL' => get_option('exitbox_include_url','on'),
			'CancelButtonText' => do_shortcode(get_option('exitbox_cancel_button_text','Cancel')),
			// Alternate Content
			'alt_title' => do_shortcode(get_option('exitbox_alt_title_field','Thank you for visiting our website')),
			'alt_body' => do_shortcode(get_option('exitbox_alt_body_text','<p>The link you have selected is located on another server.  The linked site contains information that has been created, published, maintained, or otherwise posted by institutions or organizations independent of this organization.  We do not endorse, approve, certify, or control any linked websites, their sponsors, or any of their policies, activities, products, or services.  We do not assume responsibility for the accuracy, completeness, or timeliness of the information contained therein.  Visitors to any linked websites should not use or rely on the information contained therein until they have consulted with an independent financial professional.</p> <p>Please click “Go to URL…” to leave this website and proceed to the selected site.</p>')),
			'alt_GoButtonText' => do_shortcode(get_option('exitbox_alt_go_button_text','Go to URL...')),
			'alt_Include_URL' => get_option('exitbox_alt_include_url','on'),
			'alt_CancelButtonText' => do_shortcode(get_option('exitbox_alt_cancel_button_text','Cancel')),
			'alt_classname' => get_option('exitbox_alt_classname','altExitNotifier'),
			// Custom Content
			'activate_custom_content' => get_option('exitbox_activate_custom_content','on'),
			// Behavior
			'apply_to_all_offsite_links' => get_option('exitbox_apply_to_all_offsite_links','on'),
			'jquery_selector_field' => get_option('exitbox_jquery_selector_field','a[href*="//"]:not([href*="' . $siteurl[1] . '"])'),
			'new_window' => get_option('exitbox_new_window',''),
			'css_exclusion_class' => get_option('exitbox_css_exclusion_class','noExitNotifier'),
			'relnofollow' => get_option('exitbox_relnofollow',''),
			// Form Behavior
			'enable_notifier_for_forms' => get_option('exitbox_enable_notifier_for_forms',''),
			'apply_to_all_offsite_forms' => get_option('exitbox_apply_to_all_offsite_forms','on'),
			'jquery_form_selector_field' => get_option('exitbox_jquery_form_selector_field','form[action*="//"]:not([action*="' . $siteurl[1] . '"])'),
			// Display
			'sa2_or_jAlert' => get_option('exitbox_sa2_or_jAlert','jAlert'),
			'theme' => get_option('exitbox_theme','default'),
			'backgroundcolor' => get_option('exitbox_backgroundcolor','black'),
			'blurbackground' => get_option('exitbox_blurbackground',''),
			'size' => get_option('exitbox_size','md'),
			'showAnimation' => get_option('exitbox_showAnimation','fadeIn'),
			'hideAnimation' => get_option('exitbox_hideAnimation','fadeOut'),
			'visual' => get_option('exitbox_visual_indication',''),
			// Timeout
			'enable_timeout' => get_option('exitbox_enable_timeout',''),
			'timeout_text_continue' => get_option('exitbox_timeout_text_continue','Continue in {seconds} seconds.'),
			'timeout_text_cancel' => get_option('exitbox_timeout_text_cancel','Cancel in {seconds} seconds.'),
			'continue_or_cancel' => get_option('exitbox_continue_or_cancel','continue'),
			'timeout_seconds' => get_option('exitbox_timeout_seconds','10'),
			'enable_progressbar' => get_option('exitbox_enable_progressbar',''),
			'timeout_statement' => get_option('exitbox_timeout_statement','on'),
			// Custom CSS
			'custom_css' => get_option('exitbox_custom_css',"
background: #0684ce;
background: -moz-linear-gradient(top,  #0684ce 0%, #1e5799 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0684ce), color-stop(100%,#1e5799));
background: -webkit-linear-gradient(top,  #0684ce 0%,#1e5799 100%);
background: -o-linear-gradient(top,  #0684ce 0%,#1e5799 100%);
background: -ms-linear-gradient(top,  #0684ce 0%,#1e5799 100%);
background: linear-gradient(to bottom,  #0684ce 0%,#1e5799 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0684ce', endColorstr='#1e5799',GradientType=0 );
border: 3px solid #1e5799;
			"),
			'advanced_custom_css' => get_option('exitbox_advanced_custom_css',''),
			'addclasses' => get_option('exitbox_addclasses',''),
			'classestoadd' => get_option('exitbox_classestoadd',''),
			// Debug
			'debugtoconsole' => get_option('exitbox_debugtoconsole','')
		);
		wp_localize_script( $this->_token . '-frontend', "ExitBoxSettings", $exitboxsettings);
		wp_register_script( $this->_token . '-jAlert.min', esc_url( $this->assets_url ) . 'js/jAlert.min.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-jAlert.min' );
		wp_register_script( $this->_token . '-sa2.min', esc_url( $this->assets_url ) . 'js/sweetalert2.all.min.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-sa2.min' );
	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'exit-notifier', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'exit-notifier';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Exit_Notifier Instance
	 *
	 * Ensures only one instance of Exit_Notifier is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Exit_Notifier()
	 * @return Main Exit_Notifier instance
	 */
	public static function instance ( $file = '', $version = '1.9.1' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
