<?php
include_once('functions-client.php');
include_once('functions-parts.php');

add_filter('gform_entry_field_value', 'gform_show_fields', 10, 4 );
function gform_show_fields($display_value, $field, $lead, $form ) {
	if ( $field->type == 'checkbox') {
		$display_value = "";
		$labels = $field->inputs;
		foreach ( $labels as $label) {
			if ( $lead[ $label['id'] ] ) {
				$display_value .= '<p>' . $label['label'] . '</p>';
			}
		}
	}
  return $display_value;
}
add_filter('gform_addon_field_value', 'gform_addon_field_value_sf', 10, 4 );
function gform_addon_field_value_sf( $field_value, $form, $entry, $field_id, $slug ) {
	if ( $entry['fields'][$field_id]->type = 'checkbox' ) {
		return 1;
	}
	return $field_value;

	// $fh = fopen( WP_CONTENT_DIR . '/sf.log', 'a' );
	// fwrite( $fh, $field_value . PHP_EOL );
	// fwrite( $fh, $field_id . PHP_EOL );
	// ob_start();
	// print_r($form);
	// $form = ob_get_clean();
	// ob_start();
	// print_r($entry);
	// $entry = ob_get_clean();
	// fwrite( $fh, $entry . PHP_EOL );
	// fwrite( $fh, $form . PHP_EOL );
	// fclose( $fh );
	// return $field_value;

}

Em_Theme::_init();

class Em_Theme
{
	// ! @static string $path The base path to the theme
	static $path;

	// ! @static int $excerpt_length How long excerpts should be
	static $excerpt_length = 25;

   // ! @static string $excerpt_more The trailing string for excerpts
   static $excerpt_more = '...';

   // ! @static bool $show_toolbar Whether or not to show the toolbar on the front end
   static $show_toolbar = false;

   // ! @static array $nav_menus The unique menus for this theme
   static $nav_menus = array(
    	array(
    		'slug' => 'main-menu',
    		'name' => 'Main Menu',
    	),
    	array(
    		'slug' => 'utilities-menu',
    		'name' => 'Utilities Menu',
    	),
    	array(
    		'slug' => 'footer-menu',
    		'name' => 'Footer Menu',
    	),
   );

	// @static array _dynamic_sidebars The dynamic sidebars for this theme
   static $dynamic_sidebars = array(
    	array(
    		'description' => '',
    		'name' => 'Home Callouts',
    		'id' => 'home-callouts',
    		'before_widget' => '<dd>',
    		'after_widget' => '</dd>',
    		'before_title' => '<h3>',
    		'after_title' => '</h3>',
    	),
    	array(
    		'description' => '',
    		'name' => 'Callouts',
    		'id' => 'callouts',
    		'before_widget' => '<div class="widget %2$s">',
    		'after_widget' => '</div>',
    		'before_title' => '<h3>',
    		'after_title' => '</h3>',
    	),
   );

   // ! @static array $default_widgets The default widgets that we want to unregister
   static $default_widgets = array(
 		'WP_Widget_Pages',                  // Pages Widget
		'WP_Widget_Calendar',        		// Calendar Widget
		'WP_Widget_Archives',         		// Archives Widget
		'WP_Widget_Links',         			// Links Widget
		'WP_Widget_Meta',         			// Meta Widget
		'WP_Widget_Search',         		// Search Widget
		'WP_Widget_Text',         			// Text Widget
		'WP_Widget_Categories',         	// Categories Widget
		'WP_Widget_Recent_Posts',         	// Recent Posts Widget
		'WP_Widget_Recent_Comments',        // Recent Comments Widget
		'WP_Widget_RSS',         			// RSS Widget
		'WP_Widget_Tag_Cloud',         		// Tag Cloud Widget
		'WP_Nav_Menu_Widget',         		// Menus Widget
	);

	// ! @static string $url The theme's base url
   static $url = '';

   // ! @static array $shortcodes A list of shortcodes to register
   static $shortcodes = array(
    	array(
     		'name' => 'careers',
     		'callback' => 'do_careers'
     	)
   );

   // ! @static array $admin_menus_to_hide A list of admin menus to hide
   static $admin_menus_to_hide = array(
      'edit.php'
      /*
    	'index.php',						// Dashboard
    	'edit.php',							// Posts
    	'upload.php',						// Media
    	'link-manager.php',					// Links
    	'edit.php?post_type=page',			// Pages
    	'edit-comments.php',				// Comments
    	'themes.php',						// Themes
    	'plugins.php',						// Plugins
    	'users.php',						// Users
    	'tools.php',						// Tools
    	'options-general.php'			// Settings
      */
   );

   // ! @static array $dashboard_widgets_to_hide A list of dashboard widgets to hide
   static $dashboard_widgets_to_hide = array(
 		'side' => array(
 			'dashboard_quick_press',
 			'dashboard_recent_drafts',
 			'dashboard_primary',
 			'dashboard_secondary',
 		),
 		'normal' => array(
 			'dashboard_incoming_links',
 			'dashboard_right_now',
 			'dashboard_plugins',
 			'dashboard_recent_comments',
 		),
   );

   /*--------------------------------------------------------------------------------------
    *
    * Initialize
    *
    *--------------------------------------------------------------------------------------*/

	function _init()
   {

		// Set the theme's bath path
      self::$path = get_stylesheet_directory();

      // Set the theme's base url
      self::$url = get_stylesheet_directory_uri();

    	/**
    	 * Global hooks
    	 */

		// Register custom ACF fields
		if ( function_exists('register_field') )
		{
		   register_field('acf_Post_Type_Selector', self::$path . '/core/includes/class-acf-post-type-selector.php');
		}

		// Add Typical Advanced Custom Fields
		if( function_exists('acf_add_options_page') )
		{
			acf_add_options_page();
			include_once self::$path . '/core/includes/acf-site-options.php';
		}

		include_once self::$path . '/core/includes/acf-page-options.php';
		include_once self::$path . '/core/includes/acf-home-panels-options.php';

		// Add theme support for menus
		self::register_nav_menus();

		// Register custom post types
		add_action('init', array(__CLASS__, 'register_post_types'));

		// Register custom post types
		add_action('init', array(__CLASS__, 'register_taxonomies'));
	    add_action('init' , array(__CLASS__, 'add_categories_to_media'));

		// Register dynamic sidebars
		add_action('init', array(__CLASS__, 'register_sidebars'));

		// Unregister default widgets
		add_action('widgets_init', array(__CLASS__, 'unregister_widgets'));

		// Setup shortcodes
		self::setup_shortcodes();

		// Setup admin columns
		add_action('admin_init', array(__CLASS__, 'setup_admin_columns'));

		// Add featured image support
		add_theme_support( 'post-thumbnails' );

		// Add HTML5 support for the search form.
		add_theme_support( 'html5', array( 'search-form' ) );

		// Disable updates
		//add_filter('pre_site_transient_update_core', create_function('$a', "return null;"));
		//remove_action('load-update-core.php', 'wp_update_plugins');
    	//add_filter('pre_site_transient_update_plugins', create_function('$a', "return null;"));

    	/*
    	AJAX hooks
    	*/

    	if ( defined('DOING_AJAX') && DOING_AJAX )
    	{
    		return;
    	}

    	/*
    	Admin hooks
    	*/

      if ( is_admin() )
      {
	    	add_action('wp_dashboard_setup', array(__CLASS__, 'hide_dashboard_widgets'));
	    	add_action('admin_menu', array(__CLASS__, 'hide_admin_menu_items'));
	    	add_filter('tiny_mce_before_init', array(__CLASS__, 'tiny_mce_before_init'), 10, 1);
	    	add_filter('mce_buttons_2', array(__CLASS__, 'tiny_mce_buttons_2'));
	    	add_action('init', array(__CLASS__, 'setup_rewrites'));
	    	add_editor_style('core/css/editor-style.css');
	    	add_action('init', array(__CLASS__, 'remove_seo_dropdown'));
	    	add_filter('pre_get_posts', array(__CLASS__, 'admin_change_sort_order'), 1);
	    	add_action('admin_enqueue_scripts', array(__CLASS__, 'load_admin_style'));
	    	return;
      }

		/*
		Frontend hooks
		*/

		include_once self::$path . '/core/includes/class-walker-custom-nav-menu.php';

	 	show_admin_bar(self::$show_toolbar);

	 	self::remove_head_stuff();

	 	add_filter('body_class', array(__CLASS__, 'body_class'));
	 	add_filter('excerpt_length', array(__CLASS__, 'change_excerpt_length'));
	 	add_filter('excerpt_more', array(__CLASS__, 'change_excerpt_more'));
	 	add_filter('wp_nav_menu_objects', array(__CLASS__, 'add_menu_classes'));
	 	add_filter('page_css_class', array(__CLASS__, 'add_parent_class'), 10, 4);
	 	add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
	 	add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_styles'));
/* 	 	add_action('wp_head', array(__CLASS__, 'bugherd_script')); */
	 	add_action('wp_head', array(__CLASS__, 'ga_tracking_script'));
	}

   /*--------------------------------------------------------------------------------------
	 *
 	 * Add useful classes to menu items that WP doesn't do by default
 	 *
 	 * @param array $items
 	 * @return array
 	 *
 	 *--------------------------------------------------------------------------------------*/

   function add_menu_classes( $items )
   {
		foreach ( $items as &$item )
		{
	   	  if ( self::has_children( $item->ID, $items ))
	      {
	      	$item->classes[] = 'menu-item-parent';
	      }
	   }

		return $items;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Adds a class of "parent" to page items that have children
	 * Used in conjuction with wp_list_pages()
	 *
	 * @param array $css_class
	 * @param int $page
	 * @param int $depth
	 * @param array $args
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/

	function add_parent_class( $css_class, $page, $depth, $args )
	{
		if ( ! empty($args['has_children']) )
		{
			$css_class[] = 'parent';
		}

		return $css_class;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Change the default sort order in wp-admin to menu order ascending
	 * @param object $query The current WP_Query object
	 *
	 *--------------------------------------------------------------------------------------*/

	function admin_change_sort_order( $query )
	{
		global $pagenow;

		if ( $pagenow == 'edit.php' && isset($_GET['post_type']) && ! isset($_GET['orderby']) && ! isset($_GET['order']) )
		{
			$query->set('orderby', 'menu_order');
			$query->set('order', 'ASC');
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Output a file name based upon the file's last modified date - used in conjuction
	 * with the .htaccess file
	 *
	 * @param string $path
	 *
	 *--------------------------------------------------------------------------------------*/

	function auto_version( $path, $echo = true )
	{
		$path = str_replace('//', '/', self::$path . '/' . $path);
		$pathinfo = pathinfo($path);
		$ver = '.' . $pathinfo['extension'] . '?v=' . filemtime($path);

		if ( $echo )
		{
			echo self::$url . str_replace(self::$path, '', $pathinfo['dirname']) . '/' . str_replace('.' . $pathinfo['extension'], $ver, $pathinfo['basename']);
		}
		else
		{
			return self::$url . str_replace(self::$path, '', $pathinfo['dirname']) . '/' . str_replace('.' . $pathinfo['extension'], $ver, $pathinfo['basename']);
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Customize the classes output by the body_class() function
	 *
	 * @param array $classes
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/

	function body_class( $classes )
	{
		foreach ( $classes as &$class )
		{
			$class = str_replace(array('-php', 'page-template-'), '', $class);
		}

		if ( ! is_front_page() )
		{
			$classes[] = 'interior';
		}

		return $classes;
	}

   /*--------------------------------------------------------------------------------------
    *
    * Change the default excerpt length
    *
    * @param int $length The desired excerpt length
    * @return int
    *
    *--------------------------------------------------------------------------------------*/

   function change_excerpt_length( $length )
   {
   	return self::$excerpt_length;
	}

   /*--------------------------------------------------------------------------------------
    *
    * Change the default more [...]
    * @param string $more The default more text
    * @return string The new more text
    *
    *--------------------------------------------------------------------------------------*/

   function change_excerpt_more( $more )
   {
		return self::$excerpt_more;
   }

   /*--------------------------------------------------------------------------------------
    *
    * Allow media to be assigned to categories
    *
    *--------------------------------------------------------------------------------------*/

   function add_categories_to_media()
   {
		$labels = array(
	        'name'              => 'Categories',
	        'singular_name'     => 'Category',
	        'search_items'      => 'Search Categories',
	        'all_items'         => 'All Categories',
	        'parent_item'       => 'Parent Category',
	        'parent_item_colon' => 'Parent Category:',
	        'edit_item'         => 'Edit Category',
	        'update_item'       => 'Update Category',
	        'add_new_item'      => 'Add New Category',
	        'new_item_name'     => 'New Category Name',
	        'menu_name'         => 'Categories',
	    );

	    $args = array(
	        'labels' => $labels,
	        'hierarchical' => true,
	        'query_var' => 'true',
	        'rewrite' => 'true',
	        'show_admin_column' => 'true',
	    );

	    register_taxonomy( 'media-category', 'attachment', $args );
   }

   /*--------------------------------------------------------------------------------------
    *
    * Enqueue admin styles and scripts
    *
    *--------------------------------------------------------------------------------------*/

   function load_admin_style()
   {
		wp_enqueue_style('em-editor', self::auto_version('core/css/editor.css', false), array(), null);
   }

   /*--------------------------------------------------------------------------------------
    *
    * Enqueue scripts
    *
    *--------------------------------------------------------------------------------------*/

   function enqueue_scripts()
   {
		wp_enqueue_script('jquery');
		wp_enqueue_script('modernizr', self::theme_url('core/js/modernizr.min.js', false), array(), null);
		wp_enqueue_script('jquery-plugins', self::auto_version('core/js/plugins.js', false), array('jquery'), null, true);
		wp_enqueue_script('picturefill', self::theme_url('core/js/picturefill.min.js', false), array('modernizr'), null);
		wp_enqueue_script('global', self::auto_version('core/js/global.js', false), array('jquery', 'jquery-plugins'), null, true);
		wp_enqueue_script('picturefill-background', self::theme_url('core/js/picturefill-background-mod.js', false), array(), null, true);

   }

   /*--------------------------------------------------------------------------------------
    *
    * Enqueue styles
    *
    *--------------------------------------------------------------------------------------*/
   function enqueue_styles()
   {
      wp_enqueue_style('font-awesome', self::auto_version('core/css/font-awesome.min.css', false), array(), null);
      wp_enqueue_style('source-sans-pro', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,700,900&subset=latin,latin-ext', array(), null);
      wp_enqueue_style('roboto', 'https://fonts.googleapis.com/css?family=Roboto:400,100,300,400italic,500,700,900&subset=latin,latin-ext', array(), null);
      wp_enqueue_style('global', self::auto_version('core/css/global.css', false), array('font-awesome', 'source-sans-pro'), null);
      wp_enqueue_style('owlslidestyle', self::auto_version('core/css/owl.css', false), array(), null);
      wp_enqueue_style('owlslidestyle2', self::auto_version('core/css/owl.theme.css', false), array(), null); /* TEMPORARY */

      //! CHECK FOR INTERNET EXPLORER
		preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
		if ( count($matches) > 1 )
		{
			$version = $matches[1];
			switch(true)
			{
			    case ( $version == 9 ):
            wp_enqueue_style('ie-nine', self::auto_version('core/css/ie9.css', false), array(), null);
			    break;
			}
		}

   }

	/*--------------------------------------------------------------------------------------
	 *
	 * Outputs the BugHerd script for QA testing
	 *
	 *--------------------------------------------------------------------------------------*/

	function bugherd_script()
	{
		if ( false === ($bh_apikey = self::get_field('opts_bh_apikey', 'options')) ) return false;
	?>
<script type='text/javascript'>
(function (d, t) {
  var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
  bh.type = 'text/javascript';
  bh.src = '//www.bugherd.com/sidebarv2.js?apikey=<?php echo $bh_apikey; ?>';
  s.parentNode.insertBefore(bh, s);
  })(document, 'script');
</script>
	<?php
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Outputs the Google Analytics tracking code
	 *
	 *--------------------------------------------------------------------------------------*/

	function ga_tracking_script()
	{
		if ( false === ($ga_id = self::get_field('opts_ga_id', 'options')) ) return false;

		if ( true === ($ga_classic = self::get_field('opts_ga_classic', 'options')) ) :
	?>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo $ga_id; ?>']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
	<?php
		else : $ga_domain = self::get_field('opts_ga_domain', 'options');
	?>
<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $ga_id; ?>', '<?php echo $ga_domain; ?>');
  ga('send', 'pageview');
</script>
	<?php
		endif;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Wrapper for the ACF get_field() function
	 *
	 * @param string $field The field slug
	 * @param mixed $post The post id or "options"
	 * @return mixed
	 * @author jcowher
	 * @since 1.0.4
	 *
	 *--------------------------------------------------------------------------------------*/

	function get_field( $field, $post = null )
	{
		if ( function_exists('get_field') )
		{
			return get_field($field, $post);
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Determine if the specified menu item has children
	 *
	 * @return bool
	 * @author jcowher
	 * @since 1.0.0
	 *
	 *--------------------------------------------------------------------------------------*/

	function has_children( $menu_item_id, $items )
	{
		foreach ( $items as $item )
		{
            if ( $item->menu_item_parent && $item->menu_item_parent == $menu_item_id ) return true;
        }

        return false;
	}

   /*--------------------------------------------------------------------------------------
    *
    * Hide admin menu items
    * @uses $menu
    *
    *--------------------------------------------------------------------------------------*/

   function hide_admin_menu_items()
   {
   	global $menu;

		if ( empty(self::$admin_menus_to_hide) ) return;

      foreach ( $menu as $key => $item )
      {
      	if ( in_array($item[2], self::$admin_menus_to_hide) )
      	{
      		unset($menu[$key]);
      	}
      }
   }

   /*--------------------------------------------------------------------------------------
    *
    * Hide dashboard widgets
    * @uses $wp_meta_boxes
    *
    *--------------------------------------------------------------------------------------*/

   function hide_dashboard_widgets()
   {
    	global $wp_meta_boxes;

    	foreach ( (array) self::$dashboard_widgets_to_hide as $context => $boxes )
    	{
    		foreach ( $boxes as $boxname )
    		{
    			unset($wp_meta_boxes['dashboard'][$context]['core'][$boxname]);
    		}
    	}
   }

   /*--------------------------------------------------------------------------------------
    *
    * Register navigation menus for this theme
    *
    * @author jcowher
    * @since 1.0.0
    *
    *--------------------------------------------------------------------------------------*/

   function register_nav_menus()
   {
   	if ( empty(self::$nav_menus) ) return false;

   	add_theme_support('menus');

   	foreach ( self::$nav_menus as $menu )
   	{
   	    register_nav_menu($menu['slug'], $menu['name']);
   	}
   }

   /*--------------------------------------------------------------------------------------
    *
    * Register the custom post types for this theme
    *
    * @author jcowher
    * @since 1.0.0
    *
    *--------------------------------------------------------------------------------------*/

	function register_post_types()
	{
		include_once self::$path . '/core/includes/post-types.php';
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Register this theme's dynamic sidebars
	 *
    * @author jcowher
    * @since 1.0.0
    *
	 *--------------------------------------------------------------------------------------*/

	function register_sidebars()
	{
		array_map('register_sidebar', self::$dynamic_sidebars);
	}

   /*--------------------------------------------------------------------------------------
    *
    * Register the custom taxonomies for this theme
    *
    * @author jcowher
    * @since 1.0.0
    *
    *--------------------------------------------------------------------------------------*/

	function register_taxonomies()
	{
		include_once self::$path . '/core/includes/taxonomies.php';
	}

    /*--------------------------------------------------------------------------------------
     *
     * Remove extra output from wp_head()
     *
     * @author jcowher
     * @since 1.0.0
     *
     *--------------------------------------------------------------------------------------*/

    function remove_head_stuff()
    {
    	remove_action('wp_head', 'feed_links', 2);
    	remove_action('wp_head', 'feed_links_extra', 3);
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    	remove_action('wp_head', 'index_rel_link');
    	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    	remove_action('wp_head', 'start_post_rel_link', 10, 0);
    	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    	remove_action('wp_head', 'wp_generator', 10, 0);
    	remove_action('wp_head', 'rel_canonical', 10, 0);
    }

   /*--------------------------------------------------------------------------------------
    *
    * Remove the SEO Score dropdown on list pages
    *
    *--------------------------------------------------------------------------------------*/

   function remove_seo_dropdown()
   {
   	global $pagenow;

   	if ( $pagenow == 'edit.php' && isset($_GET['post_type']) )
   	{
	   	add_filter('wpseo_use_page_analysis', create_function('', 'return false;'));
   	}
   }

    /*--------------------------------------------------------------------------------------
     *
     * Setup admin columns
     *
     * @author jcowher
     * @since 1.0.6
     *
     *--------------------------------------------------------------------------------------*/

	function setup_admin_columns()
	{
		include_once self::$path . '/core/includes/class-em-admin-column-manager.php';

		$post_types = get_post_types();
		$taxonomies = get_taxonomies();

		foreach ( $post_types as $pt )
		{
			Em_Admin_Column_Manager::setup($pt);
		}

		foreach ( $taxonomies as $tax )
		{
			Em_Admin_Column_Manager::setup_taxonomy($tax);
		}
	}


	/*--------------------------------------------------------------------------------------
	 *
	 * Setup theme shortcodes
	 *
	 *--------------------------------------------------------------------------------------*/

	function setup_shortcodes()
	{
		if ( empty(self::$shortcodes) ) return false;

		include_once self::$path . '/core/includes/class-em-shortcode.php';

		foreach ( self::$shortcodes as $shortcode )
		{
			new Em_Shortcode($shortcode);
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Setup rewrites for auto versioning
	 *
	 *--------------------------------------------------------------------------------------*/

	function setup_rewrites()
	{
		$base1 = str_replace(ABSPATH, '', get_stylesheet_directory());
		$base2 = str_replace($_SERVER['DOCUMENT_ROOT'], '', get_stylesheet_directory());
		add_rewrite_rule($base1 . '/core/css/([^\d]*)\.[\d]*\.css$', $base2 . '/core/css/$1.css', 'top');
		add_rewrite_rule($base1 . '/core/js/([^\d]*)\.[\d]*\.js$', $base2 . '/core/js/$1.js', 'top');
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Outputs the tertiary menu
	 * @param array|string $args See wp_nav_menu()
	 *
	 *--------------------------------------------------------------------------------------*/

	/*--------------------------------------------------------------------------------------
	 *
	 * Outputs the tertiary menu
	 * @param array|string $args See wp_nav_menu()
	 *
	 *--------------------------------------------------------------------------------------*/

	function tertiary_menu( $args )
	{
		include_once self::$path . '/core/includes/class-walker-tertiary-menu.php';
		$uri_parts = explode('/', $_SERVER['REQUEST_URI']);
		$newargs = wp_parse_args($args, '');
		if ( is_multisite() )
		{
			global $blog_id;
			if ( $blog_id > 1 )
			{
				$newargs['ancestor'] = ( ! isset($newargs['ancestor']) ) ? get_page_by_path($uri_parts[2]) : (( is_object($newargs['ancestor']) ) ? $newargs['ancestor'] : get_post($newargs['ancestor']));
			}
			else
			{
				$newargs['ancestor'] = ( ! isset($newargs['ancestor']) ) ? get_page_by_path($uri_parts[1]) : (( is_object($newargs['ancestor']) ) ? $newargs['ancestor'] : get_post($newargs['ancestor']));
			}
		}
		else
		{
			$newargs['ancestor'] = ( ! isset($newargs['ancestor']) ) ? get_page_by_path($uri_parts[1]) : (( is_object($newargs['ancestor']) ) ? $newargs['ancestor'] : get_post($newargs['ancestor']));
		}

		if ( $newargs['ancestor']->ID != $post->ID )
		{
			$newargs['walker'] = new Walker_Tertiary_Menu();
			wp_nav_menu($newargs);
		}
	}

  /*--------------------------------------------------------------------------------------
	*
	* Apply custom settings to the tiny mce
	* @param array $settings Contains all of settings for the tiny mce
	* @return array
	*
	*--------------------------------------------------------------------------------------*/

	function tiny_mce_before_init( $settings )
	{
		include self::$path . '/core/includes/mce-settings.php';
		return $settings;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Add a style select dropdown to the MCE editor
	 * @param array $buttons
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/

	function tiny_mce_buttons_2( $buttons )
	{
		array_unshift($buttons, 'styleselect');
		$buttons[] = 'subscript';
		$buttons[] = 'superscript';
		return $buttons;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Gets the template url including an optional path
	 * @param string $path
	 * @param bool $echo
	 *
	 *--------------------------------------------------------------------------------------*/

	function theme_url( $path = '', $echo = true )
	{
		if ( $echo )
		{
			echo self::$url . str_replace('//', '/', '/' . $path);
		}
		else
		{
			return self::$url . str_replace('//', '/', '/' . $path);
		}
	}

   /*--------------------------------------------------------------------------------------
    *
    * Unregister the default widgets
    *
    *--------------------------------------------------------------------------------------*/

	function unregister_widgets()
	{
		array_map('unregister_widget', self::$default_widgets);
	}
}
