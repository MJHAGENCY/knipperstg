<?php
/**
 * The template for displaying 404 pages (not found).
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
<!-- ====== page-404 starts ====== -->
<section class="page-404 pt-110 pb-140">
    <div class="container">
        <h3>
            <span><?php echo esc_html__( 'Oops....', 'medifact' ); ?></span><?php echo esc_html__( 'Page Not Found!', 'medifact' ); ?>
        </h3>
        <h4><?php echo esc_html__( '404', 'medifact' ); ?></h4>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn host-btn"> <?php echo esc_html__( 'BACK TO HOME', 'medifact' ); ?></a>
    </div>
</section>
<!-- ====== page-404 ends ====== -->
<?php get_footer(); ?>	