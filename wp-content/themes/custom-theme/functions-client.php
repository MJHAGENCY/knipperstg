<?php
//! ACTIONS
add_action( 'init', array( 'Em_Client', 'tinymce_shortcode_dropdown' ) );
add_action( 'init', array( 'Em_Client', 'yoast_to_bottom') );
add_action( 'init', array( 'Em_Client', 'add_client_styles') );
add_action( 'init', array( 'Em_Client', 'add_client_scripts') );
add_action( 'pre_get_posts', array( 'Em_Client', 'mod_query' ) );
add_action( 'login_head', array( 'Em_Client', 'wp_login_client_logo' ) );
add_action( 'after_setup_theme', array( 'Em_Client', 'define_custom_image_sizes') );
//! FILTERS
add_filter( 'get_search_form', array('Em_Client', 'mod_search_form' ));

class Em_Client extends Em_Theme
{
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Extends taxonomy fields to be sorted by an Advanced Custom Field variable
 	 *
 	 * @return array $tax_sorted
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/

	function sort_taxonomy($tax_array, $order_field, $tax_slug) 
	{
		$tax_sorted = array();
		foreach ( $tax_array as $ta ) {
			$order = get_field($order_field, $tax_slug . '_' . $ta->term_id);
			$tax_sorted[$order] = $ta;
		}
		ksort($tax_sorted);	
		
		return $tax_sorted;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Build and echo the masthead for the current page
 	 *
 	 * @echo variable $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function get_masthead()
	{
		$masthead = get_field('int_masthead_image');
		$masthead_title = Em_Parts::get_page_title( false );
		$masthead_text_color = get_field('masthead_text_color');
		$masthead_color = get_field('int_masthead_color');
		$icon = get_field('header_icon');
		$masthead_background = get_field('int_masthead_background');
		
		if ( !$masthead ) :
			$ancestors = get_post_ancestors($post);
			foreach ( $ancestors as $a ) :
				if ( $has_masthead_image = get_field('int_masthead_image', $a) ) :
					$masthead = $has_masthead_image;
					$masthead_color = get_field('int_masthead_color', $a);
					$masthead_background = get_field('int_masthead_background', $a) ?: $masthead_background;
					break;
				endif;
			endforeach;
		endif;
		
		//! IF THERE IS STILL NO MASTHEAD IMAGE USE THE FALLBACK IMAGE DEFINED IN OPTIONS
		if ( !$masthead || is_search() || is_404() ) $masthead = get_field('opts_masthead', 'options');
		
		//! IF THERE IS SITLL NO MASTHEAD BACKGROUND COLOR USE THE FALLBACK FROM OPTIONS
		if ( !$masthead_background ) $masthead_background = get_field('opts_masthead_background', 'options');
		
		//! IF THE CURRENT PAGE IS A SEARCH RESULTS OR 404 PAGE SET RESET THE MASTHEAD TITLE
		if ( is_search() ) $masthead_title = 'Search Results';
		if ( is_404() ) $masthead_title = 'Page Not Found';
		
		if( get_post_type() == 'news-item' )
		{
			$terms = get_the_terms(get_the_id(), 'news-type');
			
			if( empty($terms) )
				$masthead_title = 'News';
			else
				$masthead_title = $terms[0]->name;
		}
		
		//! BUILD TITLE WITH OPTIONAL ICON
		if($icon) : 
			$full_title = '<h1 class="masthead-title hide-text icon ' . $icon . '"><span>' . $masthead_title . '</span></h1>';
		else : 
			$full_title = '<h1 class="masthead-title hide-text">' . $masthead_title . '</h1>';
		endif;
		
		//! VERIFY THERE IS A MASTHEAD IMAGE AND BUILD HTML
		if ( $masthead ) :
			$html = '
				<section style="background-image:url(' . $masthead['url'] . '); background-color: ' . $masthead_background . ';" class="masthead ' . $masthead_color . '">
					<div class="inner">' .
						$full_title .
					'</div>';
			
			if ( function_exists('yoast_breadcrumb') ) {
				$html .= yoast_breadcrumb('<div id="breadcrumbs"><div class="inner clearfix">', '</div></div>', false);
			} 
					
			$html .= '</section>';
			
			echo $html;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Modify the default WordPress search form output
 	 *
 	 * @params string $form
 	 *
 	 * Ref: https://codex.wordpress.org/Function_Reference/get_search_form
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function mod_search_form( $form )
	{
		$form = '
			<a class="hamburger" id="searchTrigger"><i class="fa fa-2x fa-search middle"></i></a>
			<form id="searchForm" role="search" method="get" class="search-form" action="' . home_url( '/' ) . '" >
				<div>
					<input type="text" value="' . get_search_query() . '" name="s" class="search-input" />
					<input type="submit" class="search-btn" value="'. esc_attr__( 'Search' ) .'" />
				</div>
			</form>';
	
		return $form;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Search results logic to ensure the correct content is displayed.
 	 *
 	 * @echo string $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function get_search_results()
	{
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
			  $search_link = Em_Parts::em_link();
				$html .= '<h2 class="search-item"><a href="' . $search_link['url'] .'"  target="' . $search_link['target'] .'">' .  get_the_title() . '</a></h2>';
				$html .= '<p>' . Em_Parts::em_excerpt() . '</p>';
				$html .= '<hr />';
			endwhile;
			wp_reset_postdata();
		else : 
			$html .= '<h2>No results found.</h2>';
		endif;
		
		echo $html;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Creates custom image sizes
 	 *
 	 * Ref: https://codex.wordpress.org/Function_Reference/add_image_size
 	 * Ref: https://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
 	 
	function define_custom_image_sizes() {
	    //! AN EXTREMELY SMALL VERSION TO BE USED IN PRELOAD CASES Ie. Em_Parts::get_logo()
	    add_image_size( 'lowres', 100 );
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Modify the main WordPress when necessary Ie. search results to exclude posts
 	 *
 	 * Ref: https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
 	 
	function mod_query( $query ) 
	{
		//! MODIFY SEARCH RESULTS TO EXCLUDE POSTS DEFINED IN OPTIONS > SEO > EXCLUDE FROM SITE SEARCH
		if ( !is_admin() && $query->is_main_query() && $query->is_search() ) :
			$post_types = get_post_types( array('exclude_from_search' => false) );
			$query->set( 'posts_per_page', '-1' );
			$query->set( 'post_type', $post_types );
			
			$exclude_ids = array();
			$exclude_posts = get_field( 'opts_exclude_posts', 'options' );		
			if ( $exclude_posts ) :		
				foreach ( $exclude_posts as $ep ) :
					$ep_id = $ep->ID;
					array_push( $exclude_ids, $ep_id );				
				endforeach;
			endif;
			$query->set( 'post__not_in', $exclude_ids );
			
	        return;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Replace the WordPress logo on the login screen with either the client or eMagine logo
 	 *
 	 * ref: https://codex.wordpress.org/Plugin_API/Action_Reference/login_head
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function wp_login_client_logo() 
	{
		$logo = get_field('opts_logo', 'options');
		
		if ( !$logo ) :
			$logo_url = Em_Theme::theme_url('/core/images/logo_emagine.png', false);
			$logo_height = '58';
			$logo_width = '85';
		else :
			$logo_url = $logo['url'];
			$logo_height = $logo['height'];
			$logo_width = $logo['width'];
		endif;
		
		echo '
			<style type="text/css">
				h1 a { 
					background-image: url('. $logo_url .') !important;
					background-size: auto !important;
					height: ' . $logo_height . 'px !important;
					width: ' . $logo_width . 'px !important;
				}
			</style>
		';	
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Add a drop down of defined shortcodes to the MCE Editor
 	 *
 	 * Ref: https://codex.wordpress.org/Plugin_API/Action_Reference/init
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function tinymce_shortcode_dropdown() 
	{
	   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
	      return;
	   }
	
	   if ( get_user_option('rich_editing') == 'true' ) {
	      add_filter( 'mce_external_plugins', array('Em_Client','add_tinymce_shortcode_dropdown'));
	      add_filter( 'mce_buttons', array('Em_Client','register_tinymce_shortcode_dropdown'));
	   }
	}
	
	function register_tinymce_shortcode_dropdown( $buttons ) {
	   array_push( $buttons, "Shortcodes" );
	   return $buttons;
	}
	
	function add_tinymce_shortcode_dropdown( $plugin_array ) {
	   $plugin_array['Shortcodes'] = Em_Theme::theme_url('/core/js/admin-global.js', false); 
	   return $plugin_array;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Move Yoast SEO metabox to the bottom of the edit screen
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/

 	function yoast_to_bottom() {
		   add_filter( 'wpseo_metabox_prio', array('Em_Client', 'yoast_priority'));
 	}

	function yoast_priority() {
		return 'low';
	}
	
	   /*--------------------------------------------------------------------------------------
    *
    * Enqueue client-specific scripts
    *
    *--------------------------------------------------------------------------------------*/
   
    function add_client_scripts() {
	    add_action('wp_enqueue_scripts', array('Em_Client', 'enqueue_client_scripts'));
    }
   
   function enqueue_client_scripts()
   {
/*
		wp_enqueue_script('jquery');
		wp_enqueue_script('modernizr', self::theme_url('core/js/modernizr.min.js', false), array(), null);
		wp_enqueue_script('jquery-plugins', self::auto_version('core/js/plugins.js', false), array('jquery'), null, true);
		wp_enqueue_script('picturefill', self::theme_url('core/js/picturefill.min.js', false), array('modernizr'), null);
		wp_enqueue_script('global', self::auto_version('core/js/global.js', false), array('jquery', 'jquery-plugins'), null, true);
*/
		
		wp_enqueue_script('google-fonts', 'https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js', array(), null, true);
		wp_enqueue_script('custom-scripts', self::auto_version('js/custom-theme-scripts.js', false), array('jquery'), null, false);
		
   }
    
   /*--------------------------------------------------------------------------------------
    *
    * Enqueue client-specific styles
    *
    *--------------------------------------------------------------------------------------*/
    function add_client_styles() {
	    add_action('wp_enqueue_scripts', array('Em_Client', 'enqueue_client_styles'));
    }

   function enqueue_client_styles()
   {
		wp_enqueue_style('client-style', self::auto_version('style.css', false), array(), null);
   }	
	
	
}



   
   

/**
* Nicely format arrays & objects.
* 
* @access public
* @param mixed $input
* @return void
*/
function print_c( $input )
{	
	// CSS colors to use
    $colors = array(
        'Teal',
        'YellowGreen',
        'LightCoral',
        'MediumOrchid',
        'MediumBlue',
        'FireBrick',
        'Olive'
    );
    
    // Check inputs and get contents
    if( is_array($input) || is_object($input) )
    {
        ob_start();
        print_r($input);
        $input = ob_get_contents();
        ob_end_clean();
        
        // Initial variables
	    $current = 0;
	    $do_nothing = true;
	    
	    // Split into single charachers and loop over, formatting
	    $chars = preg_split('//', $input, -1, PREG_SPLIT_NO_EMPTY);
	    
	    foreach( $chars as $char )
	    {
	        if( $char == '[' )
	            if( !$do_nothing )
	            	echo '</div>';
	            else
	            	$do_nothing = false;
	            	
	        if( $char == '[' )
	        	echo '<div>';
	        	
	        if( $char == ')' )
	        {
	            echo '</div></div>';
	            --$current;
	        }
	        
	        echo $char;
	        
	        if( $char == '(' )
	        {
	            echo '<div style="padding-left: 20px; color: ' . ( $colors[$current % count($colors)] ) . ';">';
	            $do_nothing = true;
	            ++$current;
	        }
	    }
    }
}



 

?>