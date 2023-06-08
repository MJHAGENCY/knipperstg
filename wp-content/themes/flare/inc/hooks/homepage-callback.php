<?php

if ( ! function_exists( 'flare_home_callback_section' ) ) :
    /**
     * Callback
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_home_callback_section() {
        global $flare_customizer_all_values;
        if( 1 != $flare_customizer_all_values['flare-callback-enable'] ){
            return null;
        }
        $flare_home_callback_single_words = absint( $flare_customizer_all_values['flare-home-callback-single-words'] );
        $flare_home_callback_posts = absint($flare_customizer_all_values['flare-callback-page']);
        $flare_home_callback_button = esc_html($flare_customizer_all_values['flare-home-callback-button-text'] );
        $flare_home_callback_button_link = esc_url_raw($flare_customizer_all_values['flare-home-callback-button-link'] );

    ?>
    <?php
    if( !empty( $flare_home_callback_posts )){
        $flare_feature_callback_args =    array(
            'post_type' => 'page',
            'p' => $flare_home_callback_posts,
            'posts_per_page' => 1

        );
        $flare_fature_section_post_query = new WP_Query( $flare_feature_callback_args );
        if ( $flare_fature_section_post_query->have_posts() ) :
        while ( $flare_fature_section_post_query->have_posts() ) : $flare_fature_section_post_query->the_post();
        if(has_post_thumbnail()){
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
        }
        else{
            $thumb[0] = get_template_directory_uri() .'/assets/img/no-image-570-370.png';
        }
        $callback_image = $thumb[0];
        ?>               
        <!-- call back section start -->
        <div class="eb-callback-section">
            <div id="eb-callback-background" class="callback-image" style="background: url( <?php echo esc_url($callback_image); ?>)" >
                <div class="overlay">
                    <!-- Wrapper for banner -->
                    <div class="container">
                       <div class="col-md-12 col-xs-12 col-sm-12 eb-callback-caption">
                          <div class="s-title">
                            <h2 class="wow fadeInUp" data-wow-duration="1s"><?php the_title(); ?></h2>
                            <div class="divider-v1 wow fadeInUp" data-wow-duration="1.5s"></div>
                        </div><!-- .title -->
                           <?php
                           if (has_excerpt()) {
                               $flare_home_callback_content = get_the_excerpt();
                           }
                           else {
                               $flare_home_callback_content = flare_words_count( $flare_home_callback_single_words ,get_the_content());
                           } ?>
                           <?php echo wp_kses_post($flare_home_callback_content); ?>
                           <?php if( 1 == $flare_customizer_all_values['flare-home-callback-remove-button'] ){ ?><br /><br />
                                <a href="<?php the_permalink(); ?>" class="btn-c btn-blue white-border callback"> <?php echo esc_html($flare_home_callback_button);?>
                                </a>
                               
                           <?php } ?>
                       </div>
                   </div>
                </div><!-- .dm banner slider -->
            </div>
        </div><!-- .dm banner section -->
        <?php
            wp_reset_postdata();
            endwhile;
        endif;
    }
}
endif;
add_action( 'flare_homepage', 'flare_home_callback_section', 40 );