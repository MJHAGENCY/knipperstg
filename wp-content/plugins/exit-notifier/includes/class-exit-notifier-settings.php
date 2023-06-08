<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Exit_Notifier_Settings {

	/**
	 * The single instance of Exit_Notifier_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'exitbox_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'Exit Notifier', 'exit-notifier' ) , __( 'Exit Notifier', 'exit-notifier' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
/*
		wp_enqueue_style( 'farbtastic' );
    	wp_enqueue_script( 'farbtastic' );
*/

    	// We're including the WP media scripts here because they're needed for the image upload field
    	// If you're not including an image upload then you can leave this function call out
    	wp_enqueue_media();

    	wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings.js', array('jquery' ), '1.1.1' );
    	wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'exit-notifier' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {
		$siteurl = explode('//',get_option('siteurl'));
		$settings['content'] = array(
			'title'					=> __( 'Content', 'exit-notifier' ),
			'description'			=> __( 'Define the parameters of the notification. Use {target} to include the destination URL in the text of title or body.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'title_field',
					'label'			=> __( 'Title' , 'exit-notifier' ),
					'description'	=> __( 'This will be the title of your notification box.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Thank you for visiting our website',
					'placeholder'	=> __( 'Notifier Title', 'exit-notifier' )
				),
				array(
					'id' 			=> 'body_text',
					'label'			=> __( 'Body' , 'exit-notifier' ),
					'description'	=> __( 'This defines the body of the notification box. You can includ {target} in the body text and Exit Notifier will replace it with the destination link URL.', 'exit-notifier' ),
					'type'			=> 'textarea',
					'default'		=> '<p>The link you have selected ({target}) is located on another server.  The linked site contains information that has been created, published, maintained, or otherwise posted by institutions or organizations independent of this organization.  We do not endorse, approve, certify, or control any linked websites, their sponsors, or any of their policies, activities, products, or services.  We do not assume responsibility for the accuracy, completeness, or timeliness of the information contained therein.  Visitors to any linked websites should not use or rely on the information contained therein until they have consulted with an independent financial professional.</p> <p>Please click “Go to URL…” to leave this website and proceed to the selected site.</p>',
					'placeholder'	=> __( 'Notifier Body', 'exit-notifier' )
				),
				array(
					'id' 			=> 'go_button_text',
					'label'			=> __( 'Continue Button Text' , 'exit-notifier' ),
					'description'	=> __( 'This is the prefix of the "Go to URL..." button.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Go to URL...',
					'placeholder'	=> __( 'Go to URL...', 'exit-notifier' )
				),
				array(
					'id' 			=> 'include_url',
					'label'			=> __( 'Include the URL on the Go button?' , 'exit-notifier' ),
					'description'	=> __( 'Turn this option off to leave the URL off of the button text.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'cancel_button_text',
					'label'			=> __( 'Cancel Button Text' , 'exit-notifier' ),
					'description'	=> __( 'This is the text of the cancel button.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Cancel',
					'placeholder'	=> __( 'Cancel', 'exit-notifier' )
				)
			)
		);
		$settings['alternatecontent'] = array(
			'title'					=> __( 'Alternate Content', 'exit-notifier' ),
			'description'			=> __( 'Define the parameters of the alternate notification. Use {target} to include the destination URL in the text of title or body.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'alt_classname',
					'label'			=> __( 'Class to activate alternate content' , 'exit-notifier' ),
					'description'	=> __( '<br>This will be the class you add to a link to activate the alternate notification box. "<a href="https://somewhere.else" class="altExitNotifier">', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'altExitNotifier',
					'placeholder'	=> __( 'Alternate Notifier Class', 'exit-notifier' )
				),
				array(
					'id' 			=> 'alt_title_field',
					'label'			=> __( 'Alternate Title' , 'exit-notifier' ),
					'description'	=> __( 'This will be the title of your alternate notification box.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Thank you for visiting our website',
					'placeholder'	=> __( 'Notifier Title', 'exit-notifier' )
				),
				array(
					'id' 			=> 'alt_body_text',
					'label'			=> __( 'Alternate Body' , 'exit-notifier' ),
					'description'	=> __( 'This defines the body of the alternate notification box.', 'exit-notifier' ),
					'type'			=> 'textarea',
					'default'		=> '<p>The link you have selected is located on another server.  The linked site contains information that has been created, published, maintained, or otherwise posted by institutions or organizations independent of this organization.  We do not endorse, approve, certify, or control any linked websites, their sponsors, or any of their policies, activities, products, or services.  We do not assume responsibility for the accuracy, completeness, or timeliness of the information contained therein.  Visitors to any linked websites should not use or rely on the information contained therein until they have consulted with an independent financial professional.</p> <p>Please click “Go to URL…” to leave this website and proceed to the selected site.</p>',
					'placeholder'	=> __( 'Notifier Body', 'exit-notifier' )
				),
				array(
					'id' 			=> 'alt_go_button_text',
					'label'			=> __( 'Alternate Continue Button Text' , 'exit-notifier' ),
					'description'	=> __( 'This is the prefix of the "Go to URL..." button on the alternate notification box.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Go to URL...',
					'placeholder'	=> __( 'Go to URL...', 'exit-notifier' )
				),
				array(
					'id' 			=> 'alt_include_url',
					'label'			=> __( 'Include the URL on the alternate Go button?' , 'exit-notifier' ),
					'description'	=> __( 'Turn this option off to leave the URL off of the button text on the alternate notification box.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'alt_cancel_button_text',
					'label'			=> __( 'Alternate Cancel Button Text' , 'exit-notifier' ),
					'description'	=> __( 'This is the text of the cancel button on the alternate notification box.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Cancel',
					'placeholder'	=> __( 'Cancel', 'exit-notifier' )
				)
			)
		);
		$settings['customcontent'] = array(
			'title'					=> __( 'Custom Content', 'exit-notifier' ),
			'description'			=> __( 'With Custom Content enabled, you can customize the title and body for each link by adding exit-notifier-title and exit-notifier-body parameters in the &lt;a&gt; tag for your links. Like this:<br><br><em>&lt;a href="link url" exit-notifier-title="Custom Exit Notifier Title" exit-notifier-body="This is a Custom Exit Notifier Body. You can include whatever you want here and it will only show up for this link."&gt;</em><br><br>FYI: Shortcodes will not work in this context, however, {target} will work."', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'activate_custom_content',
					'label'			=> __( 'Activate Custom Content?', 'exit-notifier' ),
					'description'	=> __( 'You can uncheck this box to ignore the custom content parameters. If it is checked, however, the configured content will only be overridden if the parameters exist. so it should be safe to leave this on.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				)
			)
		);
		$settings['behavior'] = array(
			'title'					=> __( 'Anchor (&lt;a&gt;) Behavior', 'exit-notifier' ),
			'description'			=> __( 'Choose when and how the notification is issued for &lt;a&gt; tags.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'apply_to_all_offsite_links',
					'label'			=> __( 'Apply to all offsite links?', 'exit-notifier' ),
					'description'	=> __( 'The default behavior is to apply the exit notification to all offsite links. Uncheck this item to use the jQuery selector expression below instead.  <p>*** NOTICE *** If your liks are not working, check this box, save and reload your site. If the links then work, the problem is in your custom selector. I will gladly assist in tailoring a selctor for you, simply contact me at <a href="mailto:wpplugins@cvstech.com?subject=Custom jQuery selector in Exit Notifier">wpplugins@cvstech.com.</a></p>', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'jquery_selector_field',
					'label'			=> __( 'jQuery Selector' , 'exit-notifier' ),
					'description'	=> __( '<br>Uncheck above and use this jQuery selector to determine what links get notification instead of automatically selecting all external links. <br>The default is: a[href*="//"]:not([href*="yourwordpressinstallation.url"])', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'a[href*="//"]:not([href*="' . $siteurl[1] . '"])',
					'placeholder'	=> __( 'a[href*="//"]:not([href*="' . $siteurl[1] . '"])', 'exit-notifier' )
				),
				array(
					'id' 			=> 'new_window',
					'label'			=> __( 'Open selected links in a new window/tab.', 'exit-notifier' ),
					'description'	=> __( 'If this option is on, all selected links will open in a new window or tab, according to the browser settings', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'css_exclusion_class',
					'label'			=> __( 'Exclude &lt;a&gt; links with this CSS class' , 'exit-notifier' ),
					'description'	=> __( '<br>Apply this class to manually exclude links from Exit Notifier', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'noExitNotifier',
					'placeholder'	=> __( 'noExitNotifier' )
				),
				array(
					'id' 			=> 'relnofollow',
					'label'			=> __( 'Add rel="nofollow" to all external links.', 'exit-notifier' ),
					'description'	=> __( 'If this option is on, all selected links will have rel="nofollow" added.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				)
			)
		);
		$settings['formbehavior'] = array(
			'title'					=> __( 'Form (&lt;form&gt;) Behavior', 'exit-notifier' ),
			'description'			=> __( 'Choose when and how the notification is issued for &lt;form&gt;s. This may not work if javascript is employed to submit your form.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'enable_notifier_for_forms',
					'label'			=> __( 'Enable Exit Notifier for forms?', 'exit-notifier' ),
					'description'	=> __( 'Exit Notifier can now alert for forms. Disabled by default due to some unexpected interactions.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'apply_to_all_offsite_forms',
					'label'			=> __( 'Apply to all offsite forms?', 'exit-notifier' ),
					'description'	=> __( 'The default behavior is to apply the exit notification to all offsite forms. Uncheck this item to use the jQuery selector expression below instead.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'jquery_form_selector_field',
					'label'			=> __( 'jQuery form Selector' , 'exit-notifier' ),
					'description'	=> __( '<br>Uncheck above and use this jQuery selector to determine what forms get notification instead of automatically selecting all external forms. <br>The default is: form[action*="//"]:not([action*="yourwordpressinstallation.url"])', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'form[action*="//"]:not([action*="' . $siteurl[1] . '"])',
					'placeholder'	=> __( 'form[action*="//"]:not([action*="' . $siteurl[1] . '"])', 'exit-notifier' )
				)
			)
		);
		$settings['display'] = array(
			'title'					=> __( 'Display', 'exit-notifier' ),
			'description'			=> __( 'Choose how the notification should look.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'sa2_or_jAlert',
					'label'			=> __( 'SweetAlert2 or jAlert?' , 'exit-notifier' ),
					'description'	=> __( '<ul><li>Choose whether to use:<ul><li>SweetAlert2</li><ul><li>Current and supported and accessible (NOTE: ** NONE ** of the options below apply. This will be more fully supported in the future.)</li></ul></li><li>or jAlert<ul><li>What you are already used to. More config options.</li></ul></li></ul></ul>', 'exit-notifier' ),
					'type'			=> 'radio',
					'options'		=> array('sa2' => 'SweetAlert2', 'jAlert' => 'jAlert'),
					'default'		=> 'jAlert'
				),
				// array(
				// 	'id' 			=> 'sa2_theme',
				// 	'label'			=> __( 'SweetAlert2 Theme', 'exit-notifier' ),
				// 	'description'	=> __( 'Select the visual theme for the SweetAlert2 window.', 'exit-notifier' ),
				// 	'type'			=> 'select',
				// 	'options'		=> array( 'default' => 'Default',
				// 								'custom' => 'Dark',
				// 								'green' => 'Bootstrap 4',
				// 								'yellow' => 'Bulma',
				// 								'black' => 'Minimal',
				// 								'blue' => 'Borderless',
				// 								'blue' => 'Material UI',
				// 								'red' => 'Wordpress Admin' ),
				// 	'default'		=> 'default'
				// ),
				array(
					'id' 			=> 'theme',
					'label'			=> __( 'Theme', 'exit-notifier' ),
					'description'	=> __( 'Select the visual theme for the jAlert window.', 'exit-notifier' ),
					'type'			=> 'select',
					'options'		=> array( 'default' => 'Default',
												'red' => 'Red',
												'dark_red' => 'Dark Red',
												'green' => 'Green',
												'dark_green' => 'Dark Green',
												'blue' => 'Blue',
												'dark_blue' => 'Dark Blue',
												'yellow' => 'Yellow',
												'brown' => 'Brown',
												'gray' => 'Gray',
												'dark_gray' => 'Dark Gray',
												'black' => 'Black',
												'custom' => 'Custom'),
					'default'		=> 'default'
				),
				array(
					'id' 			=> 'backgroundcolor',
					'label'			=> __( 'Background Color', 'exit-notifier' ),
					'description'	=> __( 'Controls the color of the background behind the alert (the color that covers up the rest of the page) for the jAlert window.', 'exit-notifier' ),
					'type'			=> 'select',
					'options'		=> array( 'black' => 'Black', 'white' => 'White' ),
					'default'		=> 'black'
				),
				array(
					'id' 			=> 'blurbackground',
					'label'			=> __( 'Blur the background.', 'exit-notifier' ),
					'description'	=> __( 'Setting this option will blur the content of the page behind the dialog box for the jAlert window.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'size',
					'label'			=> __( 'Dialog Box Size', 'exit-notifier' ),
					'description'	=> __( 'How large should the box be for the jAlert window?', 'exit-notifier' ),
					'type'			=> 'select',
					'options'		=> array(  'xsm' => 'Extra Small', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xlg' => 'Extra Large', 'full' => 'Full' ),
					'default'		=> 'md'
				),
				array(
					'id' 			=> 'showAnimation',
					'label'			=> __( 'Animation on show', 'exit-notifier' ),
					'description'	=> __( 'Animation revealing the box for the jAlert window.', 'exit-notifier' ),
					'type'			=> 'select',
					'options'		=> array(  'none' => 'none',
												'bounce' => 'bounce',
												'flash' => 'flash',
												'pulse' => 'pulse',
												'rubberBand' => 'rubberBand',
												'shake' => 'shake',
												'swing' => 'swing',
												'tada' => 'tada',
												'wobble' => 'wobble',
												'jello' => 'jello',
												'bounceIn' => 'bounceIn',
												'bounceInDown' => 'bounceInDown',
												'bounceInLeft' => 'bounceInLeft',
												'bounceInRight' => 'bounceInRight',
												'bounceInUp' => 'bounceInUp',
												'fadeIn' => 'fadeIn',
												'fadeInDown' => 'fadeInDown',
												'fadeInDownBig' => 'fadeInDownBig',
												'fadeInLeft' => 'fadeInLeft',
												'fadeInLeftBig' => 'fadeInLeftBig',
												'fadeInRight' => 'fadeInRight',
												'fadeInRightBig' => 'fadeInRightBig',
												'fadeInUp' => 'fadeInUp',
												'fadeInUpBig' => 'fadeInUpBig',
												'flipInX' => 'flipInX',
												'flipInY' => 'flipInY',
												'lightSpeedIn' => 'lightSpeedIn',
												'rotateIn' => 'rotateIn',
												'rotateInDownLeft' => 'rotateInDownLeft',
												'rotateInDownRight' => 'rotateInDownRight',
												'rotateInUpLeft' => 'rotateInUpLeft',
												'rotateInUpRight' => 'rotateInUpRight',
												'hinge' => 'hinge',
												'rollIn' => 'rollIn',
												'zoomIn' => 'zoomIn',
												'zoomInDown' => 'zoomInDown',
												'zoomInLeft' => 'zoomInLeft',
												'zoomInRight' => 'zoomInRight',
												'zoomInUp' => 'zoomInUp',
												'slideInDown' => 'slideInDown',
												'slideInLeft' => 'slideInLeft',
												'slideInRight' => 'slideInRight',
												'slideInUp' => 'slideInUp' ),
					'default'		=> 'fadeIn'
				),
				array(
					'id' 			=> 'hideAnimation',
					'label'			=> __( 'Animation on hide', 'exit-notifier' ),
					'description'	=> __( 'Animation hiding the box for the jAlert window.', 'exit-notifier' ),
					'type'			=> 'select',
					'options'		=> array(  'none' => 'none',
												'bounce' => 'bounce',
												'flash' => 'flash',
												'pulse' => 'pulse',
												'rubberBand' => 'rubberBand',
												'shake' => 'shake',
												'swing' => 'swing',
												'tada' => 'tada',
												'wobble' => 'wobble',
												'jello' => 'jello',
												'bounceOut' => 'bounceOut',
												'bounceOutDown' => 'bounceOutDown',
												'bounceOutLeft' => 'bounceOutLeft',
												'bounceOutRight' => 'bounceOutRight',
												'bounceOutUp' => 'bounceOutUp',
												'fadeOut' => 'fadeOut',
												'fadeOutDown' => 'fadeOutDown',
												'fadeOutDownBig' => 'fadeOutDownBig',
												'fadeOutLeft' => 'fadeOutLeft',
												'fadeOutLeftBig' => 'fadeOutLeftBig',
												'fadeOutRight' => 'fadeOutRight',
												'fadeOutRightBig' => 'fadeOutRightBig',
												'fadeOutUp' => 'fadeOutUp',
												'fadeOutUpBig' => 'fadeOutUpBig',
												'flipOutX' => 'flipOutX',
												'flipOutY' => 'flipOutY',
												'lightSpeedOut' => 'lightSpeedOut',
												'rotateOut' => 'rotateOut',
												'rotateOutDownLeft' => 'rotateOutDownLeft',
												'rotateOutDownRight' => 'rotateOutDownRight',
												'rotateOutUpLeft' => 'rotateOutUpLeft',
												'rotateOutUpRight' => 'rotateOutUpRight',
												'hinge' => 'hinge',
												'rollIn' => 'rollIn',
												'rollOut' => 'rollOut',
												'zoomOut' => 'zoomOut',
												'zoomOutDown' => 'zoomOutDown',
												'zoomOutLeft' => 'zoomOutLeft',
												'zoomOutRight' => 'zoomOutRight',
												'zoomOutUp' => 'zoomOutUp',
												'slideOutDown' => 'slideOutDown',
												'slideOutLeft' => 'slideOutLeft',
												'slideOutRight' => 'slideOutRight',
												'slideOutUp' => 'slideOutUp' ),
					'default'		=> 'fadeOut'
				),
				array(
					'id' 			=> 'visual_indication',
					'label'			=> __( 'Visually identify selected links?', 'exit-notifier' ),
					'description'	=> __( 'Enabling this option will add a visual indication to all selected links.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				)
			)
		);
		$settings['timeout'] = array(
			'title'					=> __( 'Timeout', 'exit-notifier' ),
			'description'			=> __( 'Set a timeout for an automatic action.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'enable_timeout',
					'label'			=> __( 'Enable Timeout?', 'exit-notifier' ),
					'description'	=> __( 'Enable the timeout feature.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'off'
				),
				array(
					'id' 			=> 'continue_or_cancel',
					'label'			=> __( 'Continue or Cancel?' , 'exit-notifier' ),
					'description'	=> __( 'Choose whether the timeout will continue to the clicked link or cancel.', 'exit-notifier' ),
					'type'			=> 'radio',
					'options'		=> array('continue' => 'Continue', 'cancel' => 'Cancel'),
					'default'		=> 'continue'
				),
				array(
					'id' 			=> 'timeout_text_continue',
					'label'			=> __( 'Customize the text in the continue timeout message.', 'exit-notifier' ),
					'description'	=> __( 'Customize the timeout text. Use {seconds} as a placeholder for the countdown.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Continue in {seconds} seconds.'
				),
				array(
					'id' 			=> 'timeout_text_cancel',
					'label'			=> __( 'Customize the text in the cancel timeout message.', 'exit-notifier' ),
					'description'	=> __( 'Customize the timeout text. Use {seconds} as a placeholder for the countdown.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> 'Cancel in {seconds} seconds.'
				),
				array(
					'id' 			=> 'timeout_seconds',
					'label'			=> __( 'Timeout in seconds.', 'exit-notifier' ),
					'description'	=> __( 'Set the timeout for the chosen action in seconds.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> '10'
				),
				array(
					'id' 			=> 'enable_progressbar',
					'label'			=> __( 'Enable Progress Bar?', 'exit-notifier' ),
					'description'	=> __( 'Show a progress bar on the notifier window.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'timeout_statement',
					'label'			=> __( 'Show timeout statement at bottom of dialog?', 'exit-notifier' ),
					'description'	=> __( 'Inform the user what happens at the end of the timeout.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
			)
		);
		$settings['customcss'] = array(
			'title'					=> __( 'Custom CSS', 'exit-notifier' ),
			'description'			=> __( 'Modify the CSS for the dialog.', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'custom_css',
					'label'			=> __( 'Custom CSS ', 'exit-notifier' ),
					'description'	=> __( 'This field is simply the content of the .ja_custom {} CSS and can only affect the ja_custom class. The default is a copy of ja_blue. Edit to suit and choose "Custom" for the theme on the Display page.', 'exit-notifier' ),
					'type'			=> 'textarea',
					'default'		=> "
background: #0684ce;
background: -moz-linear-gradient(top,  #0684ce 0%, #1e5799 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0684ce), color-stop(100%,#1e5799));
background: -webkit-linear-gradient(top,  #0684ce 0%,#1e5799 100%);
background: -o-linear-gradient(top,  #0684ce 0%,#1e5799 100%);
background: -ms-linear-gradient(top,  #0684ce 0%,#1e5799 100%);
background: linear-gradient(to bottom,  #0684ce 0%,#1e5799 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0684ce', endColorstr='#1e5799',GradientType=0 );
border: 3px solid #1e5799;
"
				),
				array(
					'id' 			=> 'advanced_custom_css',
					'label'			=> __( 'Advanced Custom CSS:<br>DO NOT USE IF YOU ARE NOT CONFIDENT IN YOUR CSS KNOWLEDGE!<br>This CSS field can affect your whole site! ', 'exit-notifier' ),
					'description'	=> __( 'This CSS can affect your entire site. It is in effect whether or not the "Custom" theme is chosen on the Display page. Style class exitNotifierLink to change links affected by Exit Notifier.', 'exit-notifier' ),
					'type'			=> 'textarea',
					'default'		=> "
.my-special-class {
	color: #ff0000;
}"
				),
				array(
					'id' 			=> 'addclasses',
					'label'			=> __( 'Add custom CSS classes to links?', 'exit-notifier' ),
					'description'	=> __( 'Enabling this option will add the CSS classes below to all selected links.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'classestoadd',
					'label'			=> __( 'CSS classes to add to external links' , 'exit-notifier' ),
					'description'	=> __( 'Add one or more CCS class names. Separate classes with a space.', 'exit-notifier' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'offsite warn', 'exit-notifier' )
				)
			)
		);
		$settings['debug'] = array(
			'title'					=> __( 'Debug', 'exit-notifier' ),
			'description'			=> __( 'Debug settings', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'debugtoconsole',
					'label'			=> __( 'Send diagnostic information to console', 'exit-notifier' ),
					'description'	=> __( 'If this option is on, helpful diagnostic information will print to the console, ' .
						'and an alert will pause right before activating the link when you call exit_notifier_js().', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				)
			)
		);
		$settings['calljsdirect'] = array(
			'title'					=> __( 'Call from JS', 'exit-notifier' ),
			'description'			=> __( 'Call Exit Notifier directly from your JavaScript. Here is an example call:</p>' .
				'<p><code>exit_notifier_js({
					href: \'&ltdestinationURL&gt\', target: \'&lttarget&gt\'
				})</code></p>' .
				'<p>href is the destination URL<br>' .
				'target should be \'_self\', \'_blank\', \'_top\', or \'_parent\' and will ' .
				'override the default Exit Notifier setting on the Anchor (&lta&gt) Behavior page.</p>', 'exit-notifier' )
		);
		$settings['advanced'] = array(
			'title'					=> __( 'Advanced', 'exit-notifier' ),
			'description'			=> __( 'Advanced settings', 'exit-notifier' ),
			'fields'				=> array(
				array(
					'id' 			=> 'remove_settings_on_uninstall',
					'label'			=> __( 'Remove all settings?', 'exit-notifier' ),
					'description'	=> __( 'With this box checked (the default) all Exit Notifier settings will be erased when the plugin is uninstalled. Uncheck to preserve settings.', 'exit-notifier' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				)
			)
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base, 'label-for' => '50'));
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'Exit Notifier Settings' , 'exit-notifier' ) . '</h2>' . "\n";

			$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

				$c = 0;
				foreach ( $this->settings as $section => $data ) {

					// Set tab class
					$class = 'nav-tab';
					if ( ! isset( $_GET['tab'] ) ) {
						if ( 0 == $c ) {
							$class .= ' nav-tab-active';
						}
					} else {
						if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
							$class .= ' nav-tab-active';
						}
					}

					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}

					// Output tab
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'exit-notifier' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<p>Thank you for using Exit Notifier by Curtis V. Schleich. If you have found it useful, you can show your appreciation at <a target=blank href="http://cvstech.com/donate">http://cvstech.com/donate</a>. Thanks!</p>';
		$html .= 'I welcome any suggestions for improvement of this plugin. Please feel free to contact me at <a href="mailto:wpplugins@cvstech.com">wpplugins@cvstech.com</a> with feature requests, bug reports, critiques, or anything else!';
		$html .= '<p><em>Credit where credit is due:</em></p>';
		$html .= 'I have made liberal use of the excellent Wordpress Plugin Template by Hugh Lashbrooke found at <a href="https://github.com/hlashbrooke/WordPress-Plugin-Template">https://github.com/hlashbrooke/WordPress-Plugin-Template</a>. Thanks, Hugh!';
		$html .= '<br>Also, to <a href="https://htmlguy.com">HTMLGuy</a>, the maker of <a href="https://htmlguyllc.github.io/jAlert/">jAlert</a>, a very versatile and simple alert component! Thanks!';


		echo $html;
	}

	/**
	 * Main Exit_Notifier_Settings Instance
	 *
	 * Ensures only one instance of Exit_Notifier_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Exit_Notifier()
	 * @return Main Exit_Notifier_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
