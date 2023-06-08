<?php
if ( ! function_exists( 'flare_home_service_array' ) ) :
    /**
     * Service Section array creation
     *
     * @since flare 1.0.0
     *
     * @param string $from_service
     * @return array
     */
    function flare_home_service_array( $from_service ){
        global $flare_customizer_all_values;
        $flare_home_service_number = absint($flare_customizer_all_values['flare-home-service-number']);
        $flare_home_service_single_words = absint($flare_customizer_all_values['flare-home-page-service-single-words']);

        $flare_home_service_contents_array = array();

        $flare_home_service_contents_array[1]['flare-home-service-title'] = __('Clean Designs', 'flare');
        $flare_home_service_contents_array[1]['flare-home-service-content'] = __("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.", 'flare');
        $flare_home_service_contents_array[1]['flare-home-service-link'] = '#';
        $flare_home_service_contents_array[1]['flare-home-service-page-icon'] = 'fa-desktop';
        $flare_home_service_contents_array[1]['flare-home-service-page-link-text'] = __('Know More','flare');

        $flare_icons_array = array('flare-home-service-page-icon');
        $flare_home_service_page = array('flare-home-service-pages-ids');

        $flare_icons_arrays = salient_customizer_get_repeated_all_value(3 , $flare_icons_array);

        if ( 'from-category' ==  $from_service ){
            $flare_home_service_category = $flare_customizer_all_values['flare-home-service-category'];
            if( 0 != $flare_home_service_category ){
                $flare_home_service_args =    array(
                    'post_type' => 'post',
                    'cat' => absint($flare_home_service_category),
                    'posts_per_page' => absint($flare_home_service_numbe)
                );
            }
        }else {
                $flare_home_service_posts = salient_customizer_get_repeated_all_value(3 , $flare_home_service_page);
                $flare_home_service_posts_ids = array();
                if( null != $flare_home_service_posts ) {
                    foreach( $flare_home_service_posts as $flare_home_service_post ) {
                        if( 0 != $flare_home_service_post['flare-home-service-pages-ids'] ){
                            $flare_home_service_posts_ids[] = $flare_home_service_post['flare-home-service-pages-ids'];
                        }
                    }
                    if( !empty( $flare_home_service_posts_ids )){
                        $flare_home_service_args =    array(
                            'post_type' => 'page',
                            'post__in' => array_map( 'absint', $flare_home_service_posts_ids ),
                            'posts_per_page' => absint($flare_home_service_number),
                            'orderby' => 'post__in'
                        );
                    }
                }
            }
        // the query
        if( !empty( $flare_home_service_args )){

            $flare_home_service_contents_array = array(); /*again empty array*/
            $flare_home_service_post_query = new WP_Query( $flare_home_service_args );
            if ( $flare_home_service_post_query->have_posts() ) :
                $i = 1;
                while ( $flare_home_service_post_query->have_posts() ) : $flare_home_service_post_query->the_post();
                    $flare_home_service_contents_array[$i]['flare-home-service-title'] = get_the_title();
                    if (has_excerpt()) {
                        $flare_home_service_contents_array[$i]['flare-home-service-content'] = get_the_excerpt();
                    }
                    else {
                        $flare_home_service_contents_array[$i]['flare-home-service-content'] = flare_words_count( $flare_home_service_single_words ,get_the_content());
                    }
                    $flare_home_service_contents_array[$i]['flare-home-service-link'] = get_permalink();
                    if(isset( $flare_icons_arrays[$i] )){
                        $flare_home_service_contents_array[$i]['flare-home-service-page-icon'] = $flare_icons_arrays[$i]['flare-home-service-page-icon'];
                    }
                    else{
                        $flare_home_service_contents_array[$i]['flare-home-service-page-icon'] = 'fa-desktop';
                    }
                    $flare_home_service_contents_array[$i]['flare-home-service-page-link-text'] = __('Know More','flare');

                    $i++;
                endwhile;
                wp_reset_postdata();
            endif;
        }
        return $flare_home_service_contents_array;
    }
endif;

if ( ! function_exists( 'flare_home_service' ) ) :
    /**
     * Service Section
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_home_service() {
        global $flare_customizer_all_values;
        if( 1 != $flare_customizer_all_values['flare-home-service-enable'] ){
            return null;
        }
        $flare_home_service_selection_options = $flare_customizer_all_values['flare-home-service-selection'];
        $flare_service_arrays = flare_home_service_array( $flare_home_service_selection_options );
        if( is_array( $flare_service_arrays )){
            $flare_home_service_number = absint($flare_customizer_all_values['flare-home-service-number']);
            $flare_home_service_title = $flare_customizer_all_values['flare-home-service-title'];
            $flare_home_service_button_text = $flare_customizer_all_values['flare-home-service-button-text'];
            ?>          
            <div class="db-our-service">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="s-title">
                                <?php if(!empty( $flare_home_service_title ) ){ ?>
                                    <h2 class="wow fadeInUp" data-wow-duration="1s"><?php echo esc_html(  $flare_home_service_title); ?></h2>
                                    <div class="divider-v1 wow fadeInUp" data-wow-duration="1.5s"></div>
                                <?php } ?>
                            </div><!-- .title -->
                        </div><!-- .col-md-12 -->
                        <div class="icons-n-titles-coll clearfix">
                            
                            <?php
                            $i = 1;
                            foreach( $flare_service_arrays as $flare_service_array ){
                                if( $flare_home_service_number < $i){
                                    break;
                                }
                                ?>
                            <div class="col-sm-6 col-md-4">
                                <div class="wrapper">
                                    <div class="icon wow zoomIn">
                                        <i class="fa <?php echo esc_attr( $flare_service_array['flare-home-service-page-icon'] ); ?>"></i>
                                    </div><!-- .icon -->
                                    <section>
                                        <h3 class="fadeInUp" data-wow-duration="1s"><?php echo esc_html( $flare_service_array['flare-home-service-title'] );?></h3>
                                        <p class="wow fadeInUp" data-wow-duration="1.8s"><?php echo wp_kses_post( $flare_service_array['flare-home-service-content'] );?></p>
                                        <?php
                                        if(!empty( $flare_home_service_button_text ) ){
                                            ?>
                                                <a class="btn-c btn-blue-border wow fadeInUp" data-wow-duration="1s" href="<?php echo esc_url( $flare_service_array['flare-home-service-link'] );?>">
                                                    <?php echo esc_html( $flare_home_service_button_text );?>
                                                </a>
                                            <?php
                                        }
                                        ?>
                                    </section>
                                </div><!-- .wrapper -->
                            </div><!-- .col-md-4 -->
                            <?php
                            $i++;
                            }
                            ?>
                        </div><!-- .icons n titles coll -->
                    </div><!-- .row -->
                </div><!-- .container -->
            </div><!-- .db-our-service -->

            <?php
        }
    }
endif;
add_action( 'flare_homepage', 'flare_home_service', 15 );