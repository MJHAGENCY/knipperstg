<?php 
add_action( 'wp_enqueue_scripts', 'medicoz_theme_css',20);
function medicoz_theme_css() {
	wp_enqueue_style( 'medicoz-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'medicoz-style',get_stylesheet_directory_uri() . '/css/medicoz.css');
  
}