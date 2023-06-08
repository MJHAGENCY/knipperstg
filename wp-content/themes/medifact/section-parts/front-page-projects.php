<?php
/**
 * Template part - Projects Section of FrontPage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Medifact
 */
$medifact_projects_title = get_theme_mod('medifact-projects_title');
$medifact_projects_section     = get_theme_mod( 'medifact_projects_section_hideshow','hide');
  
$projects_no        = 6;
$projects_pages      = array();
  
for( $i = 1; $i <= $projects_no; $i++ ) {
   $projects_pages[]    =  get_theme_mod( "medifact_projects_page_$i", 1 );
     
}

$projects_args  = array(
    'post_type' => 'page',
    'post__in' => array_map( 'absint', $projects_pages ),
    'posts_per_page' => absint($projects_no),
    'orderby' => 'post__in'
); 
    
$projects_query = new   wp_Query( $projects_args );
if( $medifact_projects_section == "show" ) :
?>
    <!-- ====== portfolio starts ====== -->
    <section class="portfolio section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if($medifact_projects_title != "")   {  ?> 
                        <div class="all-title">
                            <h3><?php echo esc_html(get_theme_mod('medifact-projects_title')); ?></h3>
                            <div class="title-border">
                               <span class="fa fa-stethoscope" aria-hidden="true"></span>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="row portfolio-gallary">
                <?php
                if($projects_query->have_posts()):
                    while($projects_query->have_posts()) :
                      $projects_query->the_post();
                ?>
                    <div class="col-md-4 col-sm-6 port-item web dedicated">
                        <div class="portfolio-inner">
                            <?php if(has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medifact-projects-thumbnail', array('class' => 'img-responsive')); 
                            endif; ?>
                            <div class="dimmer">
                                <h4>
                                    <a href="<?php the_permalink();?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="portfolio-overlay">
                                    <a href="<?php the_post_thumbnail_url(); ?>" class="popup trox">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a href="<?php the_permalink();?>" class="link">
                                        <i class="fa fa-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>      
            </div>
        </div>
    </section>
    <!-- ====== portfolio ends ====== -->
<?php
  endif;
?>