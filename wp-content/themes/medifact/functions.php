<?php

/**
 * medifact functions and definitions
  @package medifact
 *
*/

/* Set the content width in pixels, based on the theme's design and stylesheet.
*/
if( ! function_exists( 'medifact_theme_setup' ) ) {

	function medifact_theme_setup() {

		$GLOBALS['content_width'] = apply_filters( 'medifact_content_width', 980 );

		load_theme_textdomain( 'medifact', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

        // Add title tag 
		add_theme_support( 'title-tag' );

		// Add default logo support		
        add_theme_support( 'custom-logo');

        add_theme_support('post-thumbnails');
        add_image_size('medifact-services-thumbnail',60,60,true);
        add_image_size('medifact-page-thumbnail',738,423, true);
        add_image_size('medifact-about-thumbnail',370,225, true);
        add_image_size('medifact-blog-front-thumbnail',370,225, true);
        add_image_size('medifact-projects-thumbnail',500,400, true);
       
        
        // Add theme support for Semantic Markup
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) ); 

		$defaults = array(
			'default-image'          => get_template_directory_uri() .'/assets/images/blog-banner.jpg',
			'width'                  => 1920,
			'height'                 => 540,
			'uploads'                => true,
			'default-text-color'     => "000",
			'wp-head-callback'       => 'medifact_header_style',
			);
		add_theme_support( 'custom-header', $defaults );

		// Menus
		register_nav_menus(
			array(
                'primary' => esc_html__('Primary Menu', 'medifact'),
            )
		);
		// add excerpt support for pages
        add_post_type_support( 'page', 'excerpt' );

        if ( is_singular() && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
        }
		// Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );
		
		
    	// To use additional css
 	    add_editor_style( 'assets/css/editor-style.css' );
		
		//About Theme		
		if ( is_admin() ) {
			require( get_template_directory() . '/home-screen.php');
		}	
	}
	add_action( 'after_setup_theme', 'medifact_theme_setup' );
}

// Register Nav Walker class_alias
   require_once('class-wp-bootstrap-navwalker.php');
   require get_template_directory(). '/include/extras.php';
   require get_template_directory(). '/premium.php';
   
function medifact_header_style()
{
	$medifact_header_text_color = get_header_textcolor();
	?>
		<style type="text/css">
			<?php
				//Check if user has defined any header image.
				if ( get_header_image() ) :
			?>
				.site-title{
					color: #<?php echo esc_attr($medifact_header_text_color); ?>;
					
				}
			<?php endif; ?>	
		</style>
	<?php

}
   
/**
 * Enqueue CSS stylesheets
 */		
if( ! function_exists( 'medifact_enqueue_styles' ) ) {
	function medifact_enqueue_styles() {	
	    
	    wp_enqueue_style('medifact-font', 'https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800');
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css');
		wp_enqueue_style('font-awesome', get_template_directory_uri() .'/assets/css/font-awesome.css');
		wp_enqueue_style('carousel', get_template_directory_uri() .'/assets/css/owl.carousel.css');
		wp_enqueue_style('theme', get_template_directory_uri() .'/assets/css/owl.theme.default.css');
		wp_enqueue_style('slick', get_template_directory_uri() .'/assets/css/slick.css');
		wp_enqueue_style('slick-theme', get_template_directory_uri() .'/assets/css/slick-theme.css');
		wp_enqueue_style('animate', get_template_directory_uri() .'/assets/css/animate.css');
		wp_enqueue_style('menubar', get_template_directory_uri() . '/assets/css/menubar.css');	
		// main style
		wp_enqueue_style( 'medifact-style', get_stylesheet_uri() );
		wp_enqueue_style('medifact-skin-blue', get_template_directory_uri() . '/assets/css/skin-blue.css');
		 
		wp_enqueue_style('medifact-responsive', get_template_directory_uri() .'/assets/css/responsive.css');
		
	 
	}
	add_action( 'wp_enqueue_scripts', 'medifact_enqueue_styles');
}
/**
 * Enqueue JS scripts
*/

if( ! function_exists( 'medifact_enqueue_scripts' ) ) {
	function medifact_enqueue_scripts() {   
		wp_enqueue_script('jquery');
		wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.js',array(),'', true);
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.js',array(),'', true);
		wp_enqueue_script('carousel', get_template_directory_uri() . '/assets/js/owl.carousel.js',array(),'', true);
		wp_enqueue_script('imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.js',array(),'', true);
		wp_enqueue_script('isotope', get_template_directory_uri() . '/assets/js/isotope.js',array(),'', true);
		wp_enqueue_script('poptrox', get_template_directory_uri() . '/assets/js/jquery.poptrox.js',array(),'', true);
		wp_enqueue_script('medifact-custom', get_template_directory_uri() . '/assets/js/custom.js',array(),'', true);
	}
	add_action( 'wp_enqueue_scripts', 'medifact_enqueue_scripts' );
}

/**
 * Register sidebars for medifact
*/
function medifact_sidebars() {

	// Blog Sidebar
	
	register_sidebar(array(
		'name' => esc_html__( 'Blog Sidebar', "medifact"),
		'id' => 'blog-sidebar',
		'description' => esc_html__( 'Sidebar on the blog layout.', "medifact"),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="border-arrow">',
		'after_title' => '</h3>',
	));
  	

	// Footer Sidebar
	register_sidebar(array(
		'name' => esc_html__( 'Footer Widget Area 1', "medifact"),
		'id' => 'medifact-footer-widget-area-1',
		'description' => esc_html__( 'The footer widget area 1', "medifact"),
		'before_widget' => '<div class="widget %2$s"> ',
		'after_widget' => '</div> ',
		'before_title' => '<h4 class="foot-heading">',
		'after_title' => '</h4>',
	));	
	
	register_sidebar(array(
		'name' => esc_html__( 'Footer Widget Area 2', "medifact"),
		'id' => 'medifact-footer-widget-area-2',
		'description' => esc_html__( 'The footer widget area 2', "medifact"),
		'before_widget' => '<div class="widget %2$s">  ',
		'after_widget' => ' </div>',
		'before_title' => '<h4 class="foot-heading">',
		'after_title' => '</h4>',
	));	
	
	register_sidebar(array(
		'name' => esc_html__( 'Footer Widget Area 3', "medifact"),
		'id' => 'medifact-footer-widget-area-3',
		'description' => esc_html__( 'The footer widget area 3', "medifact"),
		'before_widget' => '<div class="widget %2$s"> ',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="foot-heading">',
		'after_title' => '</h4>',
	));	
	
	register_sidebar(array(
		'name' => esc_html__( 'Footer Widget Area 4', "medifact"),
		'id' => 'medifact-footer-widget-area-4',
		'description' => esc_html__( 'The footer widget area 4', "medifact"),
		'before_widget' => '<div class="widget %2$s"> ',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="foot-heading">',
		'after_title' => '</h4>',
	));		
}

add_action( 'widgets_init', 'medifact_sidebars' );

 /**
 * Comment layout
 */
function medifact_comments( $comment, $args, $depth ) { 
    $GLOBALS['comment'] = $comment; ?>
	<div <?php comment_class('comments'); ?> id="<?php comment_ID() ?>">
	    <?php if ($comment->comment_approved == '0') : ?>
	        <div class="alert alert-info">
	          <p><?php esc_html_e( 'Your comment is awaiting moderation.', 'medifact' ) ?></p>
	        </div>
	    <?php endif; ?>
	    <div class="comment-inner">
            <a class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></a>
	        <div class="com-img">
	           <?php echo get_avatar( $comment,'80', null,'User', array( 'class' => array( 'media-object','' ) )); ?>
	        </div>
	        <div class="comm-content">
	            <h4>
	                <?php 
	                        /* translators: '%1$s %2$s: edit term */
	                printf(esc_html__( '%1$s %2$s', 'medifact' ), get_comment_author_link(), edit_comment_link(esc_html__( '(Edit)', 'medifact' ),'  ','') ) ?> </h4>
	                <h5 class="fw-600"><time datetime="<?php echo comment_time('Y-m-j'); ?>"><?php comment_time(esc_html__( 'F jS, Y', 'medifact' )); ?></time></h5>
	           
	            <?php comment_text() ;?>
	        </div>
        </div>
	</div>
<?php
} 


/**
 * Customizer additions.
*/
require get_template_directory(). '/include/customizer.php';

	/**
 * Load Upsell Button In Customizer
 * 2016 &copy; [Justin Tadlock](http://justintadlock.com).
 */

require_once( trailingslashit( get_template_directory() ) . '/include/upgrade/class-customize.php' );

add_action( 'admin_init', 'medifact_detect_button' );
	function medifact_detect_button() {
	wp_enqueue_style( 'medifact-info-button', get_template_directory_uri() . '/assets/css/import-button.css' );
}
 
/**
 * admin  JS scripts
 */
function medifact_admin_enqueue_scripts( $hook ) { 
	wp_enqueue_style( 
		'font-awesome', 
		get_template_directory_uri() . '/assets/css/font-awesome.css', 
		array(), 
		'4.7.0', 
		'all' 
	);
	wp_enqueue_style( 
		'medifact-admin', 
		get_template_directory_uri() . '/assets/admin/css/admin.css', 
		array(), 
		'1.0.0', 
		'all' 
	);
 
}
add_action( 'admin_enqueue_scripts', 'medifact_admin_enqueue_scripts' );
?>