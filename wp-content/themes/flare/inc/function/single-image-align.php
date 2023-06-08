<?php 
/*image alignment single post*/

if( ! function_exists( 'flare_single_post_image_align' ) ) :
    /**
     * Flare default layout function
     *
     * @since  Flare 1.0.0
     *
     * @param int
     * @return string
     */
    function flare_single_post_image_align( $post_id = null ){
        global $flare_customizer_all_values,$post;
        if( null == $post_id && isset ( $post->ID ) ){
            $post_id = $post->ID;
        }
        $flare_single_post_image_align = $flare_customizer_all_values['flare-single-post-image-align'];
        $flare_single_post_image_align_meta = get_post_meta( $post_id, 'flare-single-post-image-align', true );

        if( false != $flare_single_post_image_align_meta ) {
            $flare_single_post_image_align = $flare_single_post_image_align_meta;
        }
        return $flare_single_post_image_align;
    }
endif;