<?php
/**
 * Template part - Features Section of FrontPage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Medifact
*/
   
    
$slider_no        = 3;
$slider_pages      = array();
for( $i = 1; $i <= $slider_no; $i++ ) {
    $slider_pages[]    =  get_theme_mod( "medifact_slider_page_$i", 1 );
    $medifact_slider_btntxt[]    =  get_theme_mod( "medifact_slider_btntxt_$i", '' );
    $medifact_slider_btnurl[]    =  get_theme_mod( "medifact_slider_btnurl_$i", '');
     
}

$slider_args  = array(
    'post_type' => 'page',
    'post__in' => array_map( 'absint', $slider_pages ),
    'posts_per_page' => absint($slider_no),
    'orderby' => 'post__in'
   
); 

$slider_query = new   wp_Query( $slider_args );

if ($slider_query->have_posts() ) { 
?>
    <!-- ====== top-section starts ====== -->
    <section class="slider3">
        <div class="host-blue-warp">
            <div class="host-slider1 owl-carousel owl-theme">
                <?php
                $count = 0;
                while($slider_query->have_posts()) :
                    $slider_query->the_post();
                ?>
                    <div class="item">
                        <?php if(has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail(); ?>
                        <?php endif; ?>
                        <div class="slide-overlay">
                            <div class="slide-table">
                                <div class="slide-table-cell">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="slide-text text">
                                                    <h2>
                                                        <?php the_title(); ?>
                                                    </h2>
                                                    <div class="slide-para">
                                                        <?php the_content(); ?>
                                                    </div>
                                                    <?php if($medifact_slider_btntxt[$count] != ""){ ?>
                                                        <div class="host-pakage">
                                                            <a class="btn host-btn" href="<?php echo esc_url($medifact_slider_btnurl[$count]); ?>"><?php echo esc_html($medifact_slider_btntxt[$count]); ?></a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                $count = $count + 1;
                endwhile;
                wp_reset_postdata();
                ?>  
            </div>
        </div>
    </section>
    <!-- ====== header ends ====== -->
<?php } ?>