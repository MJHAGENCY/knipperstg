<?php
if (!function_exists('flare_home_testimonial_array')) :
    /**
     * Testimonial array creation
     *
     * @since Flare 1.0.0
     *
     * @param string $from_testimonial
     * @return array
     */
    function flare_home_testimonial_array($from_testimonial){
        global $flare_customizer_all_values;
        $flare_home_testimonial_number = absint( $flare_customizer_all_values['flare-home-testimonial-number'] );
        $flare_home_testimonial_single_words = absint( $flare_customizer_all_values['flare-home-testimonial-single-words'] );

        $flare_home_testimonial_contents_array = array();
        $flare_home_testimonial_contents_array[0]['flare-home-testimonial-title'] = __('John Doe','flare');
        $flare_home_testimonial_contents_array[0]['flare-home-testimonial-content'] = __("The set doesn't moved. Deep don't fru it fowl gathering heaven days moving creeping under from i air. Set it fifth Meat was darkness. every bring in it.",'flare');
        $flare_home_testimonial_contents_array[0]['flare-home-testimonial-image'] = get_template_directory_uri()."/assets/img/no-img.png";
        $flare_home_testimonial_contents_array[0]['flare-home-testimonial-link'] = '#';
        $flare_home_testimonial_contents_array[0]['flare-testimonial-class'] = 'active';
        $flare_home_testimonial_contents_array[0]['flare-testimonial-slider-number'] = 0;
        $repeated_page = array('flare-home-testimonial-pages-ids');

        if ('from-category' == $from_testimonial) {
            $flare_home_testimonial_category = $flare_customizer_all_values['flare-home-testimonial-category'];
            if( 0 != $flare_home_testimonial_category ){
                $flare_home_testimonial_args = array(
                    'post_type' => 'post',
                    'cat' => absint($flare_home_testimonial_category),
                    'posts_per_page' => absint($flare_home_testimonial_number),
                );
            }

        }
        else {
            $flare_home_testimonial_posts = salient_customizer_get_repeated_all_value(3 , $repeated_page);
            $flare_home_testimonial_posts_ids = array();
            if (null != $flare_home_testimonial_posts) {
                foreach ($flare_home_testimonial_posts as $flare_home_testimonial_post) {
                    if (0 != $flare_home_testimonial_post['flare-home-testimonial-pages-ids']) {
                        $flare_home_testimonial_posts_ids[] = $flare_home_testimonial_post['flare-home-testimonial-pages-ids'];
                    }
                }
                if( !empty( $flare_home_testimonial_posts_ids )){
                    $flare_home_testimonial_args = array(
                        'post_type' => 'page',
                        'post__in' => array_map( 'absint', $flare_home_testimonial_posts_ids ),
                        'posts_per_page' => absint($flare_home_testimonial_number),
                        'orderby' => 'post__in'
                    );
                }
            }
        }
        // the query
        if( !empty( $flare_home_testimonial_args )){
            $flare_home_testimonial_contents_array = array();
            $flare_home_testimonial_post_query = new WP_Query($flare_home_testimonial_args);
            if ($flare_home_testimonial_post_query->have_posts()) :
                $i = 0;
                while ($flare_home_testimonial_post_query->have_posts()) : $flare_home_testimonial_post_query->the_post();
                    $thumb_image ='';
                    if(has_post_thumbnail()){
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                        $thumb_image = $thumb['0'];
                    }

                    $flare_home_testimonial_contents_array[$i]['flare-home-testimonial-title'] = get_the_title();
                    if (has_excerpt()) {
                        $flare_home_testimonial_contents_array[$i]['flare-home-testimonial-content'] = get_the_excerpt();
                    }
                    else {
                        $flare_home_testimonial_contents_array[$i]['flare-home-testimonial-content'] = flare_words_count( $flare_home_testimonial_single_words ,get_the_content());
                    }
                    $flare_home_testimonial_contents_array[$i]['flare-home-testimonial-image'] = $thumb_image;
                    $flare_home_testimonial_contents_array[$i]['flare-home-testimonial-link'] = get_permalink();
                    if ($i == 0) {
                        $flare_home_testimonial_contents_array[$i]['flare-testimonial-class'] = 'active';
                    }
                    else{
                        $flare_home_testimonial_contents_array[$i]['flare-testimonial-class'] = '';
                    }
                        $flare_home_testimonial_contents_array[$i]['flare-testimonial-slider-number'] = $i;
                    $i++;
                endwhile;
                wp_reset_postdata();
            endif;
        }
        return $flare_home_testimonial_contents_array;
    }
endif;

if ( ! function_exists( 'flare_home_testimonial' ) ) :
    /**
     * About Section
     *
     * @since Flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_home_testimonial() {
        global $flare_customizer_all_values;
        if( (1 != $flare_customizer_all_values['flare-home-testimonial-enable']) ){
            return;
        }
        $flare_home_testimonial_selection_options = $flare_customizer_all_values['flare-home-testimonial-selection'];
        $flare_testimonial_arrays = flare_home_testimonial_array($flare_home_testimonial_selection_options);
        if(1 == $flare_customizer_all_values['flare-home-testimonial-enable']) {
            if (is_array($flare_testimonial_arrays)) {
                $flare_home_testimonial_title = $flare_customizer_all_values['flare-home-testimonial-main-title'];
                $flare_home_testimonial_number = absint( $flare_customizer_all_values['flare-home-testimonial-number'] );
                $flare_home_testimonial_icon_no = ($flare_home_testimonial_number - 1) ;
                ?>
                <div class="db-testimonials">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="s-title">
                                    <h2 class="wow fadeInUp" data-wow-duration="1s"><?php echo esc_html(  $flare_home_testimonial_title); ?></h2>
                                    <div class="divider-v1 wow fadeInUp" data-wow-duration="1.5s"></div>
                                </div><!-- .title -->
                            </div><!-- .col-md-12 -->

                            <div class="col-md-offset-2 col-md-8">
                                <div class="center">
                                <?php
                                $i = 1;
                                foreach( $flare_testimonial_arrays as $flare_testimonial_array ){
                                    if( $flare_home_testimonial_number < $i){
                                        break;
                                    }
                                    if(empty($flare_testimonial_array['flare-home-testimonial-image'])){
                                        $flare_feature_testimonial_slider_image = get_template_directory_uri().'/assets/img/no-img.png';
                                    }
                                    else{
                                        $flare_feature_testimonial_slider_image =$flare_testimonial_array['flare-home-testimonial-image'];
                                    }
                                    ?>                              
                                  <div class="testimonials-contain">
                                     <div class="clients-image">
                                        <img src="<?php echo esc_url( $flare_feature_testimonial_slider_image)?>" class="img-response">
                                         <?php if (!empty($flare_testimonial_array['flare-home-testimonial-title'])) {?>
                                           <h3  class="wow fadeInUp"> <a href="<?php echo esc_url($flare_testimonial_array['flare-home-testimonial-link']);?>">
                                                <?php echo esc_html( $flare_testimonial_array['flare-home-testimonial-title'] ); ?>
                                            </a> </h3>
                                        <?php } ?>
                                     </div>
                                     <div class="clients-text">
                                        <?php echo wp_kses_post( $flare_testimonial_array['flare-home-testimonial-content'] ); ?>
                                     </div>
                                  </div>
                                  <?php } ?>
                                </div>
                            </div><!-- .col-md-12 -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </div><!-- .db-testimoials -->
            <?php
            }
        }
    }
endif;
add_action( 'flare_homepage', 'flare_home_testimonial', 35 );