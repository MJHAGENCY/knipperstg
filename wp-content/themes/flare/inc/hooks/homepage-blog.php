<?php
if ( ! function_exists( 'flare_home_blog' ) ) :
    /**
     * Blog Section
     *
     * @since Flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_home_blog() {
        global $flare_customizer_all_values;
        global $post;
        $author_id=$post->post_author;
        $flare_home_blog_title = $flare_customizer_all_values['flare-home-blog-title'];
        $flare_home_blog_button_text = $flare_customizer_all_values['flare-home-blog-button-text'];
        $flare_home_blog_button_link = $flare_customizer_all_values['flare-home-blog-button-link'];
        $flare_home_blog_single_words = absint( $flare_customizer_all_values['flare-home-blog-single-words'] );
        
        $flare_home_blog_category = $flare_customizer_all_values['flare-home-blog-category'];
        if( 1 != $flare_customizer_all_values['flare-home-blog-enable'] ){
            return null;
        }
        ?>
        <div class="db-latest-news">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="s-title">
                            <h2 class="wow fadeInUp" data-wow-duration="1.5s"><?php echo esc_html( $flare_home_blog_title );?></h2>
                            <div class="divider-v1 wow fadeInUp" data-wow-duration="1.5s"></div>
                        </div><!-- .title -->
                    </div><!-- .col-md-12 -->
                    <div class="news-coll">
                        <?php
                        $flare_home_blog_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'cat'           => absint($flare_home_blog_category),
                        );
                        $flare_home_about_post_query = new WP_Query($flare_home_blog_args);
                        if ($flare_home_about_post_query->have_posts()) :
                            while ($flare_home_about_post_query->have_posts()) : $flare_home_about_post_query->the_post();
                                if(has_post_thumbnail()){
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'flare-blog' );
                                    $url = $thumb['0'];
                                }
                                else{
                                    $url = get_template_directory_uri().'/assets/img/placeholder-banner.png';
                                } ?>

                                <div class="col-sm-6 col-md-4">
                                    <section class="wow fadeInLeft">
                                        <div class="img-n-des clearfix">
                                            <div class="img-wrapper"> 
                                                <div class="overlay">  
                                                   <!-- date block -->
                                                        <div class="blog-date">
                                                            <span>
                                                                <?php
                                                                $archive_year  = get_the_time('Y'); 
                                                                $archive_month = get_the_time('m'); 
                                                                $archive_day   = get_the_time('d'); 
                                                                ?>
                                                                <a href="<?php echo get_day_link($archive_year, $archive_month, $archive_day); ?>"><i class="fa fa-calendar"></i> <?php echo get_the_date('M j');?></a>
                                                            </span>
                                                        </div>                                            
                                                    <img class="blog-img img-responsive" src="<?php echo esc_url( $url); ?>" alt="<?php the_title();?>">                                                   
                                                </div><!-- overlay-->
                                            </div><!-- .img-wrapper -->
                                            <h2><a href="<?php the_permalink();?>">
                                                <?php the_title(); ?>
                                            </a></h2>
                                            <p>
                                                <?php
                                                if ( has_excerpt() ) {
                                                    the_excerpt();
                                                } else {
                                                    $content_blog = get_the_content();
                                                    echo wp_kses_post(flare_words_count( $flare_home_blog_single_words, $content_blog ));
                                                } ?>
                                            </p>
                                            <div class="detail">
                                                    <div class="user">
                                                       <a href="<?php echo esc_url( get_author_posts_url( $author_id ) )?>" ><i class="fa fa-user"></i><span><?php echo esc_html( get_the_author_meta( 'user_nicename', $author_id )); ?></span> </a>
                                                    </div>                                                        
                                                        <!-- category div -->
                                                    <div class = "f-blog-cat">
                                                        <span class="cat-links">
                                                        <?php echo wp_kses_post(get_the_category_list( ",", "", get_the_id())); ?>
                                                        </span>
                                                   </div>
                                            </div><!-- .detail --> 
                                        </div><!-- .img-n-des -->
                                       
                                    </section><!-- section -->
                                </div><!-- .col-md-4 -->

                            <?php endwhile; 
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div><!-- .news-coll -->

                    <div class="clearfix"></div>
                    <?php
                        if( !empty ( $flare_home_blog_button_text ) ){
                            ?><div class="more-blog">
                                    <a class="btn-c btn-blue white-border" href="<?php echo esc_url( $flare_home_blog_button_link );?>">
                                        <?php echo esc_html( $flare_home_blog_button_text );?>
                                    </a>
                                </div>
                            <?php
                        }
                    ?>
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .db premium media -->
        <?php
    }
endif;
add_action( 'flare_homepage', 'flare_home_blog', 50 );