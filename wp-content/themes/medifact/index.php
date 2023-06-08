<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Medifact
 */
 get_header(); 
if ( get_header_image() ){      
    $overlay = "overlay";
}
else{
    $overlay = "overlay2";
}
?>
<!-- ====== top-banner starts ====== -->
<div class="top-banner" <?php if ( get_header_image() ){ ?> style="background-image:url('<?php header_image(); ?>')"  <?php } ?>>
    <div class="banner-<?php echo esc_attr($overlay); ?>">
        <h2>
            <?php wp_title(''); ?>
        </h2>
    </div>
</div>
<!-- ====== top-banner ends ====== -->

<div class="list pt-110 pb-140 off-blue">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="blog-cont">
                        <?php if(have_posts()) : ?>
                            <?php while(have_posts()) : the_post(); ?>
                                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                    <?php get_template_part('content-parts/content', get_post_format()); ?>
                                </div>
                            <?php endwhile; ?>
                        <?php else :  
                            get_template_part( 'content-parts/content', 'none' );
                        endif; ?>
                    </div>
                </div>
                <?php the_posts_pagination(
                    array(
                      'prev_text' =>__('<i class="fa fa-long-arrow-left"></i> Prev','medifact'),
                      'next_text' =>__('Next <i class="fa fa-long-arrow-right"></i>','medifact')
                    )
                ); ?>
            </div>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>