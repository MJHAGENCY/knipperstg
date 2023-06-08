<?php
/**
 * salient themes Theme Customizer
 *
 * @package salient themes
 * @subpackage flare
 * @since flare 1.0.0
 */
add_filter('salient_customizer_framework_url', 'flare_customizer_framework_url');

if( ! function_exists( 'flare_customizer_framework_url' ) ):

    function flare_customizer_framework_url(){
        return trailingslashit( get_template_directory_uri() ) . 'inc/frameworks/salient-customizer/';
    }

endif;

add_filter('salient_customizer_framework_path', 'flare_customizer_framework_path');

if( ! function_exists( 'flare_customizer_framework_path' ) ):
    function flare_customizer_framework_path(){
        return trailingslashit( get_template_directory() ) . 'inc/frameworks/salient-customizer/';
    }
endif;

/*define constant for coder-customizer-constant*/
if(!defined('salient_CUSTOMIZER_NAME')){
    define('salient_CUSTOMIZER_NAME','flare-options');
}


/**
 * reset options
 * @param  array $reset_options
 * @return void
 *
 * @since flare 1.0
 */
if ( ! function_exists( 'flare_reset_options' ) ) :
    function flare_reset_options( $reset_options ) {
        set_theme_mod( salient_CUSTOMIZER_NAME, $reset_options );
    }
endif;
/**
 * Customizer framework added.
 */
require get_template_directory().'/inc/frameworks/salient-customizer/salient-customizer.php';

global $flare_panels;
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/******************************************
Featured Slider options
 *******************************************/
require get_template_directory().'/inc/customizer/featured-slider/slider-panel.php';

/******************************************
Home Service options
 *******************************************/
require get_template_directory().'/inc/customizer/home-service/service-panel.php';


/******************************************
Home Testimonial options 
 *******************************************/
require get_template_directory().'/inc/customizer/home-testimonial/testimonial-panel.php';

/******************************************
Home Call back options 
 *******************************************/
require get_template_directory().'/inc/customizer/home-callback/callback-section.php';

/******************************************
Home Blog options 
 *******************************************/
require get_template_directory().'/inc/customizer/home-blog/home-blog-panel.php';


/******************************************
Theme options panel
 *******************************************/
require get_template_directory().'/inc/customizer/theme-options/option-panel.php';
/******************************************
Removing section setting control
 *******************************************/

$flare_customizer_args = array(
    'panels'            => $flare_panels, /*always use key panels */
    'sections'          => $flare_sections,/*always use key sections*/
    'settings_controls' => $flare_settings_controls,/*always use key settings_controls*/
    'repeated_settings_controls' => $flare_repeated_settings_controls,/*always use key sections*/
);

/*registering panel section setting and control start*/
function flare_add_panels_sections_settings() {
    global $flare_customizer_args;
    return $flare_customizer_args;
}
add_filter( 'salient_customizer_panels_sections_settings', 'flare_add_panels_sections_settings' );
/*registering panel section setting and control end*/

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function flare_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'flare_customize_register' );
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function flare_customize_preview_js() {
    wp_enqueue_script( 'flare-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20160105', true );
}
add_action( 'customize_preview_init', 'flare_customize_preview_js' );



/**
 * get all saved options
 * @param  null
 * @return array saved options
 *
 * @since flare 1.0
 */
if ( ! function_exists( 'flare_get_all_options' ) ) :
    function flare_get_all_options( $merge_default = 0 ) {
        $flare_customizer_saved_values = salient_customizer_get_all_values( );
        if( 1 == $merge_default ){
            global $flare_customizer_defaults;
            if(is_array( $flare_customizer_saved_values )){
                $flare_customizer_saved_values = array_merge($flare_customizer_defaults, $flare_customizer_saved_values );
            }
            else{
                $flare_customizer_saved_values = $flare_customizer_defaults;
            }
        }
        return $flare_customizer_saved_values;
    }
endif;