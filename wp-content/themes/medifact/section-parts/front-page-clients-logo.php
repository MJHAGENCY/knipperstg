 <?php 
// To display Clients-logo section on front page
//error_reporting(0);

$medifact_clients_section_hideshow = get_theme_mod('medifact_clients_section_hideshow','hide');

$clients_no        = 6;
$clients_logo      = array();

for( $i = 1; $i <= $clients_no; $i++ ) {
    $clients_logo[]    =  get_theme_mod( "medifact_client_logo_$i", 1 );
}

$client_args  = array(
  'post_type' => 'page',
  'post__in' => array_map( 'absint', $clients_logo ),
  'posts_per_page' => absint($clients_no),
  'orderby' => 'post__in'
);

$client_query = new   wp_Query( $client_args );
if ($medifact_clients_section_hideshow =='show') { 
?> 
 <!-- ====== partner starts ====== -->
    <div class="partner">
        <div class="partner-slider owl-carousel">
            <?php
            if($client_query->have_posts() ):                   
                while($client_query->have_posts()) :
                $client_query->the_post();
            ?>     
                <div class="item">
                    <div class="partner-item">
                        <?php 
                        if(has_post_thumbnail()): 
                            the_post_thumbnail();
                        endif; 
                        ?>
                    </div>
                </div>
            <?php     
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
    <!-- ====== partner ends ====== -->
<?php } ?>  