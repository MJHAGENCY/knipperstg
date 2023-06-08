<?php
/**
 * The template for displaying search results pages.
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
<!-- Page banner starts-->
<div class="top-banner" <?php if ( get_header_image() ){ ?> style="background-image:url('<?php header_image(); ?>')"  <?php } ?>>
	<div class="banner-<?php echo esc_attr($overlay); ?>">
		<?php if ( have_posts() ) : ?>
			<h2><?php 
			/* translators: %s: search term */
			printf( esc_html__( 'Search Results for: %s', 'medifact' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
		<?php else : ?>
		    <h2>
            <?php
			/* translators: %s: nothing found term */
			printf( esc_html__( 'Nothing Found for: %s', 'medifact' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
		<?php endif; ?>
	</div>
</div>
<!-- Page banner ends -->
    <div class="list pt-110 pb-140 off-blue">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="blog-cont">
                            <?php if(have_posts()) : ?>
                                <?php while(have_posts()) : the_post(); ?>
                                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                        <?php get_template_part('content-parts/content','search') ?>
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