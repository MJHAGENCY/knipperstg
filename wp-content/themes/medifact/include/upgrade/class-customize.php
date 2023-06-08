<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Medifact_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		require_once( trailingslashit( get_template_directory() ) . '/include/upgrade/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'medifact_Customize_Section_Pro' );

		// doc sections.
		$manager->add_section(
			new medifact_Customize_Section_Pro(
				$manager,
				'medifact',
				array(
					'title'    => esc_html__( 'Theme Documentation', 'medifact' ),
					'pro_text' => esc_html__( 'Click Here', 'medifact' ),
					'pro_url'  => 'http://hubblethemes.com/docs/medifact-doc/index.html',
					'priority'  => 1
				)
			)
		);
	 
		// upgrade sections.
		$manager->add_section(
			new medifact_Customize_Section_Pro(
				$manager,
				'upgrade-pro',
				array(
					'title'    => esc_html__( 'Upgrade To Pro', 'medifact'),
					'pro_text' => esc_html__( 'View Pro', 'medifact'),
					'pro_url'  => 'http://www.hubblethemes.com/recommends/medifact-pro-details/',
					'priority'  => 2
				)
			)
		);
		
		// upgrade sections.
		$manager->add_section(
			new medifact_Customize_Section_Pro(
				$manager,
				'upgrade-prof',
				array(
					'title'    => esc_html__( 'All features', 'medifact'),
					'pro_text' => esc_html__( 'Watch Here', 'medifact'),
					'pro_url'  => 'http://www.hubblethemes.com/recommends/medifact-pro-details/',
					'priority'  => 600
				)
			)
		);
	}


	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'medifact-customize-controls', trailingslashit( get_template_directory_uri() ) . '/include/upgrade/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'medifact-customize-controls', trailingslashit( get_template_directory_uri() ) . '/include/upgrade/customize-controls.css' );
	}
}

// Doing this customizer thang!
Medifact_Customize::get_instance();
