<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
<!-- ====== page starts ====== -->
<div class="why-we section-padding pt-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                        <?php if(has_post_thumbnail()) : ?>
				            <div class="page-img">
				                <?php the_post_thumbnail('medifact-page-thumbnail', array('class' => 'img-responsive')); ?>
				            </div>
			            <?php endif; ?>
                        <div class="page-content">
			                <?php the_content(); ?>
                        </div>
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
<!-- ====== page ends ====== -->
	
<?php get_footer(); ?>