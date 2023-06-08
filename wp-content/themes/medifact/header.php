<?php 
/**
 * The header for our theme.
 *
 * Displays all of the <head> section 
 *
 * @package Medifact
*/
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="wrapper">
        <!-- ====== scroll to top ====== -->
        <a id="goTop" title="Go to top" href="">
           <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
        </a>
       <!-- ====== header starts ====== -->
        <header class="header-1">
            <div class="header-top">
				<div class="container">
                    <div class="row">
					<?php
                        
                        $medifact_header_faq_url = get_theme_mod('medifact_header_faq_url');  
                        $medifact_header_helpdesk = get_theme_mod('medifact_header_helpdesk');
                        $medifact_header_support = get_theme_mod('medifact_header_support');
                        $medifact_header_openting_hours = get_theme_mod('medifact_header_openting_hours');
						$medifact_header_social_link_1 = get_theme_mod('medifact_header_social_link_1');
                        $medifact_header_social_link_2 = get_theme_mod('medifact_header_social_link_2');
                        $medifact_header_social_link_3 = get_theme_mod('medifact_header_social_link_3');
                        $medifact_header_social_link_4 = get_theme_mod('medifact_header_social_link_4');
                       
                    ?>
                        <div class="col-md-8">
                            <ul class="top-links">
								<?php if (!empty($medifact_header_faq_url)) { ?>
									<li>
										<a href="<?php echo esc_url(get_theme_mod('medifact_header_faq_url')); ?>"><?php echo esc_html__( 'FAQ', 'medifact' );?></a>
									</li>
								<?php } ?>	
								<?php if (!empty($medifact_header_helpdesk)) { ?>
                                <li>
									<a href="<?php echo esc_url(get_theme_mod('medifact_header_helpdesk')); ?>"><?php echo esc_html__( 'Help Desk', 'medifact' );?></a>
                                </li>
								<?php } ?>	
								<?php if (!empty($medifact_header_support)) { ?>
                                <li>
                                    <a href="<?php echo esc_url(get_theme_mod('medifact_header_support')); ?>"><?php echo esc_html__( 'Support', 'medifact' );?></a>
                                </li>
								<?php } ?>
								<?php if (!empty($medifact_header_openting_hours)) { ?>
                                <li>
                                    <span><?php echo esc_html($medifact_header_openting_hours); ?></span>
                                </li>
								<?php } ?>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="top-socials">
								<?php if (!empty($medifact_header_social_link_1)) { ?>
                                <li>
                                    <a href="<?php echo esc_url(get_theme_mod('medifact_header_social_link_1')); ?>">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
								<?php } ?>
								<?php if (!empty($medifact_header_social_link_2)) { ?>
                                <li>
                                    <a href="<?php echo esc_url(get_theme_mod('medifact_header_social_link_2')); ?>">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
								<?php } ?>
								<?php if (!empty($medifact_header_social_link_3)) { ?>
                                <li>
                                    <a href="<?php echo esc_url(get_theme_mod('medifact_header_social_link_3')); ?>">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                </li>
								<?php } ?>
								<?php if (!empty($medifact_header_social_link_4)) { ?>
                                <li>
                                    <a href="<?php echo esc_url(get_theme_mod('medifact_header_social_link_4')); ?>">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
								<?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
			</div>
			<div class="header-wrap header-blue">	
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="logo">
                                <?php   
                                    if (has_custom_logo()) :
                                ?> 
                                <h1>
                                    <?php the_custom_logo(); ?>
                                </h1>
                                <?php  
                                  else : ?>
                                <h1>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
                                        <span class="site-title" ><?php bloginfo( 'title' ); ?>
                                        </span>
                                    </a>
                                </h1>
                                <?php 
                                endif;?>
                            </div>
						</div>	
						<div class="col-lg-9 d-none d-lg-block">
                            <div class="menubar">
                                
                                 <nav class="navbar">
                                    <?php wp_nav_menu(
                                        array(
                                            'container'        => 'ul', 
                                            'theme_location'    => 'primary', 
                                            'menu_class'        => 'navbar-nav', 
                                            'items_wrap'        => '<ul class="navbar-nav">%3$s</ul>',
                                            'fallback_cb'       => 'medifact_wp_bootstrap_navwalker::fallback',
                                            'walker'            => new medifact_wp_bootstrap_navwalker()
                                        )
                                    ); 
                                    ?>         
                                </nav>   
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 d-none d-lg-block">
                       
                    </div>
                </div>
            </div>
        </header>
        <!-- ====== header ends ====== -->
        