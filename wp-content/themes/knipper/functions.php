<?php
function my_theme_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script('knipper_js', get_theme_file_uri() . '/js/knipper.js', array('jquery'), '1.0.0', true);
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


if ( ! function_exists( 'et_get_safe_localization' ) ) {

	function et_get_safe_localization( $string ) {
		return wp_kses( $string, et_get_allowed_localization_html_elements() );
	}

}

if ( ! function_exists( 'et_get_safe_localization' ) ) {
    
    function et_get_safe_localization( $string ) {
    	return wp_kses( $string, et_get_allowed_localization_html_elements() );
    }
    
}

if ( ! function_exists( 'et_get_allowed_localization_html_elements' ) ) {
    
    function et_get_allowed_localization_html_elements() {
    	$whitelisted_attributes = array(
    		'id'    => array(),
    		'class' => array(),
    		'style' => array(),
    	);
    
    	$whitelisted_attributes = apply_filters( 'et_allowed_localization_html_attributes', $whitelisted_attributes );
    
    	$elements = array(
    		'a'      => array(
    			'href'   => array(),
    			'title'  => array(),
    			'target' => array(),
    		),
    		'b'      => array(),
    		'em'     => array(),
    		'p'      => array(),
    		'span'   => array(),
    		'div'    => array(),
    		'strong' => array(),
    	);
    
    	$elements = apply_filters( 'et_allowed_localization_html_elements', $elements );
    
    	foreach ( $elements as $tag => $attributes ) {
    		$elements[ $tag ] = array_merge( $attributes, $whitelisted_attributes );
    	}
    
    	return $elements;
    }
    
}

if ( ! function_exists( 'et_load_core_options' ) ) {
    
    function et_load_core_options() {
        $options = require_once( get_stylesheet_directory() . esc_attr( "/panel_options.php" ) );
    }
    add_action( 'init', 'et_load_core_options', 999 );
    
}

if ( ! function_exists( 'et_load_core_options' ) ) {
    
    function et_load_core_options() {
        $options = require_once( get_stylesheet_directory() . esc_attr( "/panel_options.php" ) );
    }
    add_action( 'init', 'et_load_core_options', 999 );
    
}

/**
* Gravity Forms: Form Validation.
*
* The below looks for the words 'http' and 'https' upon after the user tries to submit the form.
* During form validation, if the words appear within the form, the form will not submit.
* The user will then be presented with a custom, informative, and actionable message.
*
* @link https://docs.gravityforms.com/gform_field_validation
* @param array  $result The validation result to be filtered.
* @param string $value The field value to be validated. Multi-input fields like Address will pass an array of values.
* @param object $form Current form object.
* @param object $field Current field object.
*/
function em_disable_urls_validation( $result, $value, $form, $field ) {

	$pattern = '(http|https|www)'; // Looks for http and https.
	if ( preg_match( $pattern, $value ) ) {
		$result['is_valid'] = false;
		$result['message']  = 'Field cannot contain website addresses.';
	}
	
	if ( 'name' === $field->type ) {
		$name_pattern = '(http|https|www|.ru|.net|.com|.edu)'; // Looks for a pattern.
	
		// Input values.
		$prefix = rgar( $value, $field->id . '.2' );
		$first  = rgar( $value, $field->id . '.3' );
		$middle = rgar( $value, $field->id . '.4' );
		$last   = rgar( $value, $field->id . '.6' );
		$suffix = rgar( $value, $field->id . '.8' );
	
		if ( ! empty( $prefix ) && ! $field->get_input_property( '2', 'isHidden' ) && preg_match( $name_pattern, $prefix )
			|| ! empty( $first ) && ! $field->get_input_property( '3', 'isHidden' ) && preg_match( $name_pattern, $first )
			|| ! empty( $middle ) && ! $field->get_input_property( '4', 'isHidden' ) && preg_match( $name_pattern, $middle )
			|| ! empty( $last ) && ! $field->get_input_property( '6', 'isHidden' ) && preg_match( $name_pattern, $last )
			|| ! empty( $suffix ) && ! $field->get_input_property( '8', 'isHidden' ) && preg_match( $name_pattern, $suffix )
		) {
			$result['is_valid'] = false;
			$result['message']  = empty( $field->errorMessage ) ? __( 'Fields cannot contain website addresses.', 'gravityforms' ) : $field->errorMessage;
		} else {
			$result['is_valid'] = true;
			$result['message']  = '';
		}
	}
	
	return $result;
	}
	add_filter( 'gform_field_validation', 'em_disable_urls_validation', 10, 4 );
	
