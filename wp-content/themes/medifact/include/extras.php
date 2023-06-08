<?php
/**
 * Functions hooked to core hooks.
 *
 */

if ( ! function_exists( 'medifact_customize_search_form' ) ) :

	/** Customize search form.
	 **/
	function medifact_customize_search_form() {
        
        $form = '<div class="widget search-widget"><form role="search" method="get" class=" input-group" action="' . esc_url( home_url( '/' ) ) . '">
			
			<span class="screen-reader-text">' . esc_html( '', 'label', 'medifact' ) . '</span>
			<input type="search" class="form-group form-control" placeholder="' . esc_attr_x( 'Search', 'placeholder', 'medifact' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label', 'medifact' ) . '" />
			
			<button type="submit" class="btn btn-widget">
                <i class="fa fa-search"></i>
            </button>
            </form></div>';
        return $form;
    }
endif;
add_filter( 'get_search_form', 'medifact_customize_search_form', 15 );
?>
