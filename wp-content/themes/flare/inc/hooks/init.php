<?php
/**
 * Implement Editor Styles
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
add_action( 'init', 'flare_add_editor_styles' );

if ( ! function_exists( 'flare_add_editor_styles' ) ) :
    function flare_add_editor_styles() {
        add_editor_style( 'editor-style.css' );
    }
endif;