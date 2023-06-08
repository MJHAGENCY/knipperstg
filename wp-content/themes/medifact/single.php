 <?php
/**
 * The template for displaying all single posts.
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
<!-- ====== blog starts ====== -->
<div class="blog pt-70 section-padding bg-w">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                        <?php  get_template_part( 'content-parts/content', 'single' ); ?>
                    <?php endwhile; ?>
                <?php else :  
                    get_template_part( 'content-parts/content', 'none' );
                endif; ?>
                <!-- Comments -->
                <div class="spacer-80"></div>
                <?php 
                    if ( comments_open() || get_comments_number() ) :
                            comments_template();
                    endif; 
                ?> 
                <!--End Comments -->
            </div>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>
<!-- ====== blog ends ====== -->
<?php get_footer(); ?>