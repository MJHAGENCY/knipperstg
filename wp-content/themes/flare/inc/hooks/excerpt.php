<?php
if( ! function_exists( 'flare_excerpt_length' ) ) :

    /**
     * Excerpt length
     *
     * @since  Flare 1.0.0
     *
     * @param null
     * @return int
     */
    function flare_excerpt_length( $length ){
        global $flare_customizer_all_values;
        $excerpt_length = $flare_customizer_all_values['flare-number-of-words'];
        if ( empty( $excerpt_length) ) {
            $excerpt_length = $length;
        }
        return absint( $excerpt_length );

    }

endif;
add_filter( 'excerpt_length', 'flare_excerpt_length', 999 );