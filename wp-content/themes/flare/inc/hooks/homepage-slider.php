<?php
if ( ! function_exists( 'flare_featured_slider_array' ) ) :
    /**
     * Featured Slider array creation
     *
     * @since Flare 1.0.0
     *
     * @param string $from_slider
     * @return array
     */
    function flare_featured_slider_array( $from_slider ){
        global $flare_customizer_all_values;
        $flare_feature_slider_number = absint( $flare_customizer_all_values['flare-featured-slider-number'] );
        $flare_feature_slider_single_words = absint( $flare_customizer_all_values['flare-fs-single-words'] );
        $flare_feature_slider_contents_array[0]['flare-feature-slider-title'] = __('Welcome to The Digital Media','flare');
        $flare_feature_slider_contents_array[0]['flare-feature-slider-content'] = __("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type",'flare');
        $flare_feature_slider_contents_array[0]['flare-feature-slider-link'] = '#';
        $flare_feature_slider_contents_array[0]['flare-feature-slider-image'] = get_template_directory_uri()."/assets/img/placeholder-banner.png";
        $repeated_page = array('flare-fs-pages-ids');
        $flare_feature_slider_args = array();

        if ( 'from-category' ==  $from_slider ){
            $flare_feature_slider_category = $flare_customizer_all_values['flare-featured-slider-category'];
            if( 0 != $flare_feature_slider_category ){
                $flare_feature_slider_args =    array(
                    'post_type' => 'post',
                    'cat' => absint($flare_feature_slider_category),
                    'ignore_sticky_posts' => true
                );
            }
        }
        else{
            $flare_feature_slider_posts = salient_customizer_get_repeated_all_value(3 , $repeated_page);
            $flare_feature_slider_posts_ids = array();
            if( null != $flare_feature_slider_posts ) {
                foreach( $flare_feature_slider_posts as $flare_feature_slider_post ) {
                    if( 0 != $flare_feature_slider_post['flare-fs-pages-ids'] ){
                        $flare_feature_section_posts_ids[] = $flare_feature_slider_post['flare-fs-pages-ids'];
                    }
                }

                if( !empty( $flare_feature_section_posts_ids )){
                    $flare_feature_slider_args =    array(
                        'post_type' => 'page',
                        'post__in' => array_map( 'absint', $flare_feature_section_posts_ids ),
                        'posts_per_page' => absint($flare_feature_slider_number),
                        'orderby' => 'post__in'
                    );
                }

            }
        }
        if( !empty( $flare_feature_slider_args )){
            // the query
            $flare_fature_section_post_query = new WP_Query( $flare_feature_slider_args );

            if ( $flare_fature_section_post_query->have_posts() ) :
                $i = 0;
                while ( $flare_fature_section_post_query->have_posts() ) : $flare_fature_section_post_query->the_post();
                    $url ='';
                    if(has_post_thumbnail()){
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'flare-main-banner' );
                        $url = $thumb['0'];
                    }
                    $flare_feature_slider_contents_array[$i]['flare-feature-slider-title'] = get_the_title();
                    if (has_excerpt()) {
                        $flare_feature_slider_contents_array[$i]['flare-feature-slider-content'] = get_the_excerpt();
                    }
                    else {
                        $flare_feature_slider_contents_array[$i]['flare-feature-slider-content'] = flare_words_count( $flare_feature_slider_single_words ,get_the_content());
                    }
                    $flare_feature_slider_contents_array[$i]['flare-feature-slider-link'] = get_permalink();
                    $flare_feature_slider_contents_array[$i]['flare-feature-slider-image'] = $url;
                    if ($i == 0) {
                        $flare_feature_slider_contents_array[$i]['flare-slider-class'] = 'active';
                    }
                    else{
                        $flare_feature_slider_contents_array[$i]['flare-slider-class'] = 'inactive';
                    }
                    $i++;
                endwhile;
                wp_reset_postdata();
            endif;
        }
        return $flare_feature_slider_contents_array;
    }
endif;

if ( ! function_exists( 'flare_featured_home_slider' ) ) :
    /**
     * Featured Slider
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_featured_home_slider() {
        global $flare_customizer_all_values;

        if( 1 != $flare_customizer_all_values['flare-feature-slider-enable'] ){
            return null;
        }
        $flare_feature_slider_selection_options = $flare_customizer_all_values['flare-featured-slider-selection'];
        $flare_slider_arrays = flare_featured_slider_array( $flare_feature_slider_selection_options );


        if( is_array( $flare_slider_arrays )){
        $flare_feature_slider_number = absint( $flare_customizer_all_values['flare-featured-slider-number'] );
        $flare_feature_enable_arrow = $flare_customizer_all_values['flare-fs-enable-arrow'];
        $flare_feature_enable_button = $flare_customizer_all_values['flare-fs-enable-button'];
        $flare_feature_button_text = $flare_customizer_all_values['flare-fs-button-text'];
    ?>
    <div class="eb-banner-section">
        <div id="eb-banner-slider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php for ($i=0; $i < $flare_feature_slider_number ; $i++) { ?>
                <li data-target="#eb-banner-slider" data-slide-to="<?php echo absint($i); ?>"></li>
            <?php } ?>
        </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <?php
                $i = 1;
                foreach( $flare_slider_arrays as $flare_slider_array ){
                    if( $flare_feature_slider_number < $i){
                        break;
                    }
                    if(empty($flare_slider_array['flare-feature-slider-image'])){
                        $flare_feature_slider_image = get_template_directory_uri().'/assets/img/placeholder-banner.png';
                    }
                    else{
                        $flare_feature_slider_image =$flare_slider_array['flare-feature-slider-image'];
                    }
                    ?>
                        <div class="item <?php echo esc_attr($flare_slider_array['flare-slider-class']); ?>" style="background-image: url('<?php echo esc_url( $flare_feature_slider_image )?>');" >
                            <div class="carousel-caption wow fadeInUp" data-wow-duration="1.8s">
                                <h1><a href="<?php echo esc_url( $flare_slider_array['flare-feature-slider-link'] );?>"><?php echo esc_html( $flare_slider_array['flare-feature-slider-title'] );?></a></h1>
                                <p><?php echo wp_kses_post( $flare_slider_array['flare-feature-slider-content'] );?> </p>
                                <?php if ( 1 == $flare_feature_enable_button){ ?>
                                    <a href="<?php echo esc_url( $flare_slider_array['flare-feature-slider-link'] );?>" class="btn-c btn-blue white-border">
                                        <?php echo esc_html( $flare_customizer_all_values['flare-fs-button-text'] );?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    $i++;
                    }
                    ?>
            </div><!-- .carousel inner -->
            <!-- Controls -->
            <?php if( 1 == $flare_feature_enable_arrow){ ?>
                <a class="left control" href="#eb-banner-slider" role="button" data-slide="prev">
                    <svg width="30" height="56" viewBox="0 0 30 56">
                    <path class="left-arrow" data-name="Rectangle 1 copy" class="cls-1" d="M28.443-.386l1.115,1.12-28,27.886L0.441,27.5Zm-0.558,56.4L29,54.891,1.115,27,0,28.125Z"/>
                    </svg>
                </a>
                <a class="right control" href="#eb-banner-slider" role="button" data-slide="next">
                    <svg width="30" height="56" viewBox="0 0 30 56">
                    <path id="Rectangle_1_copy" data-name="Rectangle 1 copy" class="cls-1" d="M1.136-.011L0,1.1,28.42,28.8l1.132-1.113ZM1.7,56.011L0.575,54.9,28.869,27.2,30,28.31Z"/>
                    </svg>
                </a>
            <?php }  ?>
            
        </div><!-- .dm banner slider -->
    </div><!-- .dm banner section -->
    <?php
        }
    }
endif;
add_action( 'flare_homepage', 'flare_featured_home_slider', 10 );