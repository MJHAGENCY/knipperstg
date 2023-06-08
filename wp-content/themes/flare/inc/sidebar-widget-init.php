<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function flare_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'flare' ),
        'id'            => 'sidebar-1',
        'description'   => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    $flare_get_all_options = flare_get_all_options(1);
    $flare_footer_widgets_number = $flare_get_all_options['flare-footer-sidebar-number'];

    if( $flare_footer_widgets_number > 0 ){
        register_sidebar(array(
            'name' => __('Footer Column One', 'flare'),
            'id' => 'footer-col-one',
            'description' => __('Displays items on footer section.','flare'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title'  => '<h1 class="widget-title">',
            'after_title'   => '</h1>',
        ));
        if( $flare_footer_widgets_number > 1 ){
            register_sidebar(array(
                'name' => __('Footer Column Two', 'flare'),
                'id' => 'footer-col-two',
                'description' => __('Displays items on footer section.','flare'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
            ));
        }
    }
}
add_action( 'widgets_init', 'flare_widgets_init' );