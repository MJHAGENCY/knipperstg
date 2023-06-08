<?php
/**
 * Template part - Service Section of FrontPage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Medifact
*/
$medifact_services_title = get_theme_mod('medifact-services_title');
$medifact_services_section     = get_theme_mod( 'medifact_services_section_hideshow','hide');
  
$services_no        = 6;
$services_pages      = array();
$serviceicon     = array();
  
for( $i = 1; $i <= $services_no; $i++ ) {
   $services_pages[]    =  get_theme_mod( "medifact_services_page_$i", 1 );
   $serviceicon[]    = get_theme_mod( "medifact_page_service_icon_$i", '' );
}

$services_args  = array(
    'post_type' => 'page',
    'post__in' => array_map( 'absint', $services_pages ),
    'posts_per_page' => absint($services_no),
    'orderby' => 'post__in'
); 
    
$services_query = new   wp_Query( $services_args );
if( $medifact_services_section == "show" ) :
?>
    <!-- ====== service starts ====== -->
    <section class="service-2 bg-w section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if($medifact_services_title != "")   {  ?> 
                        <div class="all-title">
                            <h3><?php echo esc_html(get_theme_mod('medifact-services_title')); ?></h3>
                            <div class="title-border">
                                <span class="fa fa-stethoscope" aria-hidden="true"></span>
                            </div>
                            <p class="tit-bot-30">
                                <?php echo esc_html(get_theme_mod('medifact-services_subtitle')); ?>
                            </p>
                        </div>
                    <?php }?>
                </div>
            </div>
			<div class="row">
				<?php
				$count=0;	
				if($services_query->have_posts()):
                        while($services_query->have_posts()) :
                          $services_query->the_post();
                    ?>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="choose">
								<div class="choose-icon">
									<i class="fa <?php echo esc_attr($serviceicon[$count]); ?>" aria-hidden="true"></i>
								</div>
								<div class="choose-content">
									<h4> <?php the_title(); ?></h4>
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					 <?php
					 $count++;
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?> 	
			</div>	
        </div>
    </section>
    <!-- ====== service ends ====== -->
<?php  endif; ?>