<?php
if ( ! function_exists( 'flare_set_global' ) ) :
/**
 * Setting global values for all saved customizer values
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_set_global() {
    /*Getting saved values start*/
    $GLOBALS['flare_customizer_all_values'] = flare_get_all_options(1);
}
endif;
add_action( 'flare_action_before_head', 'flare_set_global', 0 );

if ( ! function_exists( 'flare_doctype' ) ) :
/**
 * Doctype Declaration
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_doctype() {
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
<?php
}
endif;
add_action( 'flare_action_before_head', 'flare_doctype', 10 );

if ( ! function_exists( 'flare_before_wp_head' ) ) :
/**
 * Before wp head codes
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_before_wp_head() {
    ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
<?php
}
endif;
add_action( 'flare_action_before_wp_head', 'flare_before_wp_head', 10 );

if( ! function_exists( 'flare_default_layout' ) ) :
    /**
     * Flare default layout function
     *
     * @since  Flare 1.0.0
     *
     * @param int
     * @return string
     */
    function flare_default_layout( $post_id = null ){

        global $flare_customizer_all_values,$post;
        $flare_default_layout = $flare_customizer_all_values['flare-default-layout'];
        if( is_home()){
            $post_id = get_option( 'page_for_posts' );
        }
        if( null == $post_id && isset ( $post->ID ) ){
            $post_id = $post->ID;
        }
        $flare_default_layout_meta = get_post_meta( $post_id, 'flare-default-layout', true );

        if( false != $flare_default_layout_meta ) {
            $flare_default_layout = $flare_default_layout_meta;
        }
        return $flare_default_layout;
    }
endif;

if ( ! function_exists( 'flare_body_class' ) ) :
/**
 * add body class
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_body_class( $flare_body_classes ) {
    if(!is_front_page() || ( is_front_page())){
        $flare_default_layout = flare_default_layout();
        if( !empty( $flare_default_layout ) ){
            if( 'left-sidebar' == $flare_default_layout ){
                $flare_body_classes[] = 'salient-left-sidebar';
            }
            elseif( 'right-sidebar' == $flare_default_layout ){
                $flare_body_classes[] = 'salient-right-sidebar';
            }
            elseif( 'both-sidebar' == $flare_default_layout ){
                $flare_body_classes[] = 'salient-both-sidebar';
            }
            elseif( 'no-sidebar' == $flare_default_layout ){
                $flare_body_classes[] = 'salient-no-sidebar';
            }
            else{
                $flare_body_classes[] = 'salient-right-sidebar';
            }
        }
        else{
            $flare_body_classes[] = 'salient-right-sidebar';
        }
    }
    return $flare_body_classes;

}
endif;
add_action( 'body_class', 'flare_body_class', 10, 1);

if ( ! function_exists( 'flare_before_page_start' ) ) :
/**
 * intro loader
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_before_page_start() {
    global $flare_customizer_all_values;
    /*intro loader*/
}
endif;
add_action( 'flare_action_before', 'flare_before_page_start', 10 );

if ( ! function_exists( 'flare_page_start' ) ) :
/**
 * page start
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_page_start() {
?>
    <div id="page" class="site">
<?php
}
endif;
add_action( 'flare_action_before', 'flare_page_start', 15 );

if ( ! function_exists( 'flare_skip_to_content' ) ) :
/**
 * Skip to content
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_skip_to_content() {
    ?>
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'flare' ); ?></a>
<?php
}
endif;
add_action( 'flare_action_before_header', 'flare_skip_to_content', 10 );

if ( ! function_exists( 'flare_header' ) ) :
/**
 * Main header
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
function flare_header() {
    global $flare_customizer_all_values;
    global $wp_version;
    global $post;
    ?>
        <?php if ($flare_customizer_all_values['flare-enable-top-header'] == 1) { ?>
            <div class="header-accessories">
             <div class="container">
                <div class="top-nav-wrapper">
                    <div class="col-md-7 col-sm-5 col-xs-12 address-section">
                        <ul class="clearfix">
                        <?php if (!empty($flare_customizer_all_values['flare-top-header-email'])) { ?>
                            <li class="email"> <a href="mailto:<?php echo esc_html($flare_customizer_all_values['flare-top-header-email']); ?>"><?php echo esc_html($flare_customizer_all_values['flare-top-header-email']); ?></a></li>
                        <?php } ?>
                        <?php if (!empty($flare_customizer_all_values['flare-top-header-number'])) { ?>
                            <li class="phone"> <a href="tel:<?php echo preg_replace( '/\D+/', '', esc_attr( $flare_customizer_all_values['flare-top-header-number']) ); ?>"><?php echo esc_attr( $flare_customizer_all_values['flare-top-header-number'] ); ?></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($flare_customizer_all_values['flare-top-header-address'])) { ?>
                            <li class="address"> <span class="flare-address"><?php echo esc_html($flare_customizer_all_values['flare-top-header-address']); ?> </span></li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>    
                                </div>

                    <?php if( has_nav_menu( 'top-menu' ) ){ ?>
                        <div class="col-md-5 col-sm-5 col-xs-12 flare-top-nav">
                                <div class="social-widget salient-social-section social-icon-only top-tooltip salient-top-menu clearfix">
                                    <?php
                                        wp_nav_menu( array( 
                                            'theme_location' => 'top-menu', 
                                            'link_before' => '<span>',
                                            'link_after'=>'</span>' , 
                                            'menu_id' => 'top-menu',
                                            'fallback_cb' => false,
                                            'depth'     => 1,
                                             ) );
                                    ?>
                                </div>
                        </div>
                    <?php } ?>
            </div>
        <?php } ?>
        
        <header id="masthead" class="wrapper site-header" role="banner">            
                <div class="container main-menu">
                    <div class="row">
                        <!-- site branding -->
                        <div class="col-xs-9 col-sm-10 col-md-4">
                            <div class="site-branding">
                                        <?php flare_the_custom_logo(); ?>
                                        <?php if ( is_front_page() && is_home() ) : ?>
                                            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                        <?php else : ?>
                                            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                                        <?php endif;

                                        $description = get_bloginfo( 'description', 'display' );
                                        if ( $description || is_customize_preview() ) : ?>
                                            <h2 class="site-description"><?php echo esc_html($description); ?></h2>
                                        <?php endif;
                                ?>
                            </div><!-- .site-branding -->
                        </div><!-- .col-md-3 -->

                        <div class="col-xs-3 col-sm-2 col-md-8">
                            <nav id="site-navigation" class="main-navigation" role="navigation">
                            <button id="toggleNav" class="menu-toggle"><span></span><span></span><span></span></button>
                                <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
                            </nav><!-- #site-navigation -->                        
                        </div><!-- .col-md-9 -->
                    </div>
            </div>
        </header>
    <?php if (  is_front_page() && !is_home() ) { ?>
    <div id="content" class="site-content">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
    <?php } 
    else {
        do_action('flare-page-inner-title');
    }
    ?>

<?php 
}
endif;
add_action( 'flare_action_header', 'flare_header', 10 );

if( ! function_exists( 'flare_add_breadcrumb' ) ) :

/**
 * Breadcrumb
 *
 * @since Flare 1.0.0
 *
 * @param null
 * @return null
 *
 */
    function flare_add_breadcrumb(){
        global $flare_customizer_all_values;
        // Bail if Breadcrumb disabled
        $breadcrumb_enable_breadcrumb = $flare_customizer_all_values['flare-enable-breadcrumb' ];
        if ( 1 != $breadcrumb_enable_breadcrumb ) {
            return;
        }
        // Bail if Home Page
        if ( is_front_page() || is_home() ) {
            return;
        }
        echo '<div id="breadcrumb" class="breadcrumb-wrap">';
         flare_simple_breadcrumb();
        echo '</div><!-- #breadcrumb -->';
    }
endif;
add_action( 'flare_action_after_title', 'flare_add_breadcrumb', 10 );


