<?php

/*	Plugin Name: EM News &amp; Events
	Description: Easily manage your site's news and events
	Version: 0.1.2
	Author: eMagine
	Author URL: http://www.emagineusa.com
	License: GPL2

	Copyright 2013  eMagine  (email : info@emagineusa.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

Em_News_Events::init();

class Em_News_Events
{
	static $version = '0.1.1';
	static $base_url;
	static $base_path;
	static $news_slug = 'news-item';
	static $event_slug = 'event';
	static $news_tax_slug = 'news-type';
	static $event_tax_slug = 'event-type';
	static $settings = FALSE;
	
	/*---------------------------------------------------------
	 * init()
	 *---------------------------------------------------------*/
	
	function init()
	{
		//! Setup class variables
		self::$base_url = plugin_dir_url(__FILE__);
		self::$base_path = plugin_dir_path(__FILE__);
		self::get_settings();
		
		//! Include ACF Custom Fields
		include_once self::$base_path . 'core/includes/acf-news-events-fields.php';
		
		//! Global hooks
		add_action('init', array(__CLASS__, 'register_types'));
		add_action('init', array(__CLASS__, 'add_shortcodes'));
		
		if ( is_admin() )
		{	//! Admin-only functions
			register_activation_hook(__FILE__, array(__CLASS__, 'activate'));
			add_action('admin_menu', array(__CLASS__, 'admin_menu'));
			add_action('init', array(__CLASS__, 'maybe_save_settings'));
			add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_settings_scripts'));
			add_action('init', array(__CLASS__, 'maybe_flush_rewrites'));
			add_filter('manage_edit-' . self::$event_slug. '_columns', array(__CLASS__, 'manage_event_column_headers'));
			add_action('manage_' . self::$event_slug . '_posts_custom_column', array(__CLASS__, 'manage_event_column_values'), 10, 2);
			add_filter('manage_edit-' . self::$news_slug. '_columns', array(__CLASS__, 'manage_news_column_headers'));
			add_action('manage_' . self::$news_slug . '_posts_custom_column', array(__CLASS__, 'manage_news_column_values'), 10, 2);			
		}
		else
		{	//! Front end functions
			add_filter('posts_results', array(__CLASS__, 'posts_results'), 10, 2);
			add_filter('wp_nav_menu_objects', array(__CLASS__, 'add_menu_classes'));
		}		
	}
	
	/*---------------------------------------------------------
	 * add_menu_classes()
	 * Adds classes to menu items (e.g. current-menu-parent)
	 *
	 * @param array $items
	 * @uses array $post
	 * @return array
	 *---------------------------------------------------------*/
	
	function add_menu_classes( $items )
	{
		global $post;
		
		$news_parent = self::get_setting_value('news_parent');
		$event_parent = self::get_setting_value('event_parent');
		
		foreach ( $items as &$item )
		{
			if ( $item->object_id == $news_parent && is_single() && get_post_type($post->ID) == self::$news_slug )
			{
				$item->classes[] = 'current-menu-item';
				$parent = $item->menu_item_parent;
			}
			
			if ( $item->object_id == $event_parent && is_single() && get_post_type($post->ID) == self::$event_slug )
			{
				$item->classes[] = 'current-menu-item';
				$parent = $item->menu_item_parent;
			}
	   }
	   
	   if ( !empty($parent) )
	   {
	   	foreach ( $items as &$item )
	   	{
		   	if ( $item->ID == $parent )
		   	{
			   	$item->classes[] = 'current-menu-parent';
		   	}
	   	}
	   }
	    
		return $items;    
	}
	
	/*---------------------------------------------------------
	 * posts_results()
	 * Modifies the posts array for single news items and events
	 *
	 * @param array $posts
	 * @param object $query
	 * @return array
	 *---------------------------------------------------------*/

	 function posts_results( $posts, $query )
	 {
		if ( ! $query->is_main_query() || count($posts) == 0 )
		{
			return $posts;
		}
		
		$content = '';
		$post = $posts[0];
		$pt = get_post_type($post->ID);
		$template = 'event_detail_template';
		
		if ( is_single() && ($pt == self::$news_slug || $pt == self::$event_slug) )
		{
			if ( $pt == self::$news_slug )
			{
				$parent = self::get_setting_value('news_parent');
				$posts[0]->post_parent = $parent;
			}
			elseif ( $pt == self::$event_slug )
			{
				$parent = self::get_setting_value('event_parent');
				$posts[0]->post_parent = $parent;
			}
		}
		else
		{
			return $posts;
		}
		
		if ( $pt == self::$news_slug )
		{
			$template = 'news_detail_template';
			$cats = get_the_terms($post->ID, self::$news_tax_slug);
		
			if ( is_array($cats) )
			{
				$cat = array_shift($cats);
				$tmpl_name = '_' . $cat->term_id;
				$value = trim(self::get_setting_value($tmpl_name));
				
				if ( !empty($value) )
				{
					$template = $tmpl_name;
				}
			}
		}
		
		$content .= self::parse_template($template, $post->ID);
		
		if ( !empty($content) )
		{
			$posts[0]->post_content .= $content;
		}
		
		return $posts;
	}
	
	/*---------------------------------------------------------
	 * manage_news_column_headers()
	 * Sets admin columns for news items
	 *
	 * @param array $columns
	 * @return array
	 *---------------------------------------------------------*/
	
	function manage_news_column_headers( $columns )
	{
		unset($columns['date']);
		$columns['type'] = 'Type';
		$columns['news-date'] = 'Date';
		return $columns;
	}

	/*---------------------------------------------------------
	 * manage_news_column_values()
	 * Sets admin column values for news items
	 *
	 * @param array $columns
	 * @param int $post_id
	 *---------------------------------------------------------*/
	
	function manage_news_column_values( $column, $post_id )
	{
		switch ( $column )
		{
			case 'type' :
				$terms = get_the_terms($post_id, self::$news_tax_slug);
				$term_names = array();
				if ( is_array($terms) )
				{
					foreach ( $terms as $term )
					{
						$term_names[] = $term->name;
					}
				}
				echo implode(', ', $term_names);
			break;
			
			case 'date' :
				echo get_the_time('Y-m-d');
			break;
		}
	}

	/*---------------------------------------------------------
	 * manage_event_column_headers()
	 * Sets admin columns for events
	 *
	 * @param array $columns
	 * @return array
	 *---------------------------------------------------------*/
	
	function manage_event_column_headers( $columns )
	{
		unset($columns['date']);
		$columns['start-date'] = 'Start Date';
		$columns['end-date'] = 'End Date';
		$columns['location'] = 'Location';
		return $columns;
	}

	/*---------------------------------------------------------
	 * manage_event_column_values()
	 * Sets admin column values for events
	 *
	 * @param array $columns
	 * @param int $post_id
	 *---------------------------------------------------------*/
		
	function manage_event_column_values( $column, $post_id )
	{
		switch ( $column )
		{
			case 'start-date' :
				echo date('Y-m-d', strtotime(get_field('start_date')));
			break;
			
			case 'end-date' :
				echo date('Y-m-d', strtotime(get_field('end_date')));
			break;
			
			case 'location' :
				the_field('location');
			break;
		}
	}
	
	/*---------------------------------------------------------
	 * add_shortcodes()
	 * Adds custom shortcodes
	 *---------------------------------------------------------*/
	
	function add_shortcodes()
	{
		add_shortcode('events', array(__CLASS__, 'shortcode_events'));
		add_shortcode('news_items', array(__CLASS__, 'shortcode_news_items'));
	}
	
	/*---------------------------------------------------------
	 * shortcode_events()
	 * Handles the [events] shortcode
	 *---------------------------------------------------------*/
	
	function shortcode_events( $atts, $content = null )
	{
		global $post;
		
		$atts = (object) shortcode_atts(array(
			'is_archive' => FALSE,
			'type' => FALSE,
			'number' => -1,
			'paged' => FALSE,
		), $atts);
		
		$args = array(
			'post_type' => self::$event_slug,
			'posts_per_page' => $atts->number,
		);
		
		if ( $atts->type !== FALSE )
		{
			$args['tax_query'][] = array(
				'taxonomy' => self::$event_tax_slug,
				'field' => 'id',
				'terms' => $atts->type,
			);
		}
		
		if ( $atts->is_archive != 1 )
		{
			$args['meta_query']['relation'] = 'OR';
			$args['meta_query'][] = array(
				'key' => 'start_date',
				'value' => date('Ymd'),
				'compare' => '>=',
				'type' => 'DATE',
			);
			$args['meta_query'][] = array(
				'key' => 'end_date',
				'value' => date('Ymd'),
				'compare' => '>=',
				'type' => 'DATE',
			);
		}
		
		$args['paged'] = get_query_var('paged');
		$events = new WP_Query($args);
		$sorted = array();
		$html = '';
		$template = 'event_list_template';
		
		while ( $events->have_posts() ) : $events->the_post();
			$date = DateTime::createFromFormat('m/d/Y', get_field('start_date'));
      $date_year = $date->format('Y');
			$sorted[$date_year][get_field('start_date')][] = $post;
		endwhile;
	  
		ksort($sorted);
		foreach ( $sorted as $year_group )
		{
  		ksort($year_group);
      foreach ( $year_group as $posts )
			{
        foreach ( $posts as $post )
        {
  				setup_postdata($post);
  				$html .= self::parse_template($template, $post->ID);
        }
			}
		}
		
		if( $atts->paged )
		{
			$html .= self::display_pagination(array('query' => $events));
		}
		
		wp_reset_postdata();
		
		return $html;
	}
	
	/*---------------------------------------------------------
	 * shortcode_news_items()
	 * Handles the [news_items] shortcode
	 *---------------------------------------------------------*/
	
	function shortcode_news_items( $atts, $content = null )
	{
		global $post;
		
		$atts = (object) shortcode_atts(array(
			'type' => 0,
			'number' => -1,
			'paged' => FALSE,
		), $atts);
		
		$args = array(
			'post_type' => self::$news_slug,
			'posts_per_page' => $atts->number,
			'tax_query' => array(
				array(
					'taxonomy' => self::$news_tax_slug,
					'field' => 'id',
					'terms' => $atts->type,
				),
			),
			'paged' => get_query_var('paged'),
		);
		$news = new WP_Query($args);
		
		$html = '';
		$template = 'news_list_template';
		
		while ( $news->have_posts() ) : $news->the_post();
			$terms = get_the_terms($post->ID, self::$news_tax_slug);
			$term = array_shift($terms);
			
			if ( isset($term->term_id) )
			{
				$tmpl = trim(self::get_setting_value($template . '_' . $term->term_id));
				
				if ( !empty($tmpl) )
				{
					$template .= '_' . $term->term_id;
				}
			}
			
			$html .= self::parse_template($template, $post->ID);
			
		endwhile;
		
		if( $atts->paged )
		{
			$html .= self::display_pagination(array('query' => $news));
		}
		
		wp_reset_postdata();
		
		return $html;
	}
	
	/*---------------------------------------------------------
	 * maybe_flush_rewrites()
	 * Flush the rewrites if we need to
	 *---------------------------------------------------------*/
	
	function maybe_flush_rewrites()
	{
		if ( get_transient('flush_rewrites') )
		{
			flush_rewrite_rules();
			delete_transient('flush_rewrites');
		}
	}
	
	/*---------------------------------------------------------
	 * parse_template()
	 * Parses a template for output
	 *
	 * @param string $template
	 * @param int $post_id
	 * @return string
	 *---------------------------------------------------------*/
	
	function parse_template( $template , $post_id )
	{
		$is_news = ( get_post_type($post_id) == self::$news_slug ) ? TRUE : FALSE;
		
		// Get url
		$link_url = get_permalink($post_id);
		$link_target = '_self';
		
		if ( get_field('is_external_link', $post_id) == 'Yes' )
		{
			$link_url = esc_url(get_field('external_link', $post_id));
			$link_target = '_blank';
		}
		
		// Get event date
		$event_date = '';
		if ( !$is_news )
		{
			$event_date = self::get_event_date($post_id);
		}
		
		// Get event location
		$event_location = '';
		if ( !$is_news )
		{
			$event_location = get_field('location', $post_id);
		}
		
		// Get image
		$img_html = '';
		if ( $image = get_field('associated_image', $post_id) )
		{
			$img_html = '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';
		}
		
		$args = array(
			'{title}' => get_the_title($post_id),
			'{excerpt}' => get_field('excerpt', $post_id),
			'{full_text}' => get_field('full_text', $post_id),
			'{url}' => $link_url,
			'{link_target}' => $link_target,
			'{event_date}' => $event_date,
			'{event_location}' => $event_location,
			'{image}' => $img_html,
			'{publish_date}' => get_the_time('F j, Y', $post_id),
		);
		
		$template = str_replace(array_keys($args), array_values($args), stripslashes(self::$settings[$template]));
		
		if ( $image )
		{
			$template = preg_replace('/{image class="(.*)"}/', '<img class="$1" alt="' . $image['alt'] . '" src="' . $image['url'] . '" />', $template);
		}
		else {
  		$template = preg_replace('/{image class="(.*)"}/', '', $template);
		}
				
		return $template;
	}

	
	/*---------------------------------------------------------
	 * enqueue_scripts()
	 * Enqueue scripts for news settings page
	 *---------------------------------------------------------*/
	
	function enqueue_settings_scripts()
	{
		if ( isset($_GET['page']) && $_GET['page'] == 'em-news-settings' )
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('em-news-events', self::$base_url . 'core/js/settings.js', array('jquery'), self::$version);
		}
	}
	
	/*---------------------------------------------------------
	 * maybe_save_settings()
	 * If on settings page and there is post data then update
	 * settings
	 *---------------------------------------------------------*/
	
	function maybe_save_settings()
	{
		if ( isset($_POST['_em_news_events_nonce']) && isset($_POST['settings']) && check_admin_referer('save_settings', '_em_news_events_nonce') )
		{
			self::update_settings($_POST['settings']);
		}
	}
	
	/*---------------------------------------------------------
	 * update_settings()
	 * Saves plugin settings to db
	 *
	 * @param array $data
	 *---------------------------------------------------------*/
	
	function update_settings( $data )
	{
		if ( isset($_POST['news_parent']) && $_POST['news_parent'] != self::get_setting_value('news_parent') )
		{
			set_transient('flush_rewrites', 1);
		}
		
		if ( isset($_POST['event_parent']) && $_POST['event_parent'] != self::get_setting_value('event_parent') )
		{
			set_transient('flush_rewrites', 1);
		}
		
		if ( is_array(self::$settings) )
		{
			$data = array_merge(self::$settings, $data);
		}

		update_option('em_news_events_settings', $data);
		self::$settings = $data;
	}
	
	/*---------------------------------------------------------
	 * get_event_date()
	 * Gets an event's date
	 *
	 * @param int $event_id
	 * @return string
	 *---------------------------------------------------------*/
	
	function get_event_date( $event_id )
	{
		$start_date = strtotime(get_field('start_date', $event_id));
		$end_date = strtotime(get_field('end_date', $event_id));
		$event_date = '';
		
		if ( $start_date == $end_date )
		{
			$event_date = date('M j, Y', $start_date);
		}
		else
		{
			if ( date('mY', $start_date) != date('mY', $end_date) )
			{
				$event_date = date('M j, Y', $start_date) . '-' . date('M j, Y', $end_date);
			}
			else
			{
				$event_date = date('M j', $start_date) . '-' . date('j, Y', $end_date);
			}
		}
		
		return $event_date;
	}
	
	/*---------------------------------------------------------
	 * get_setting_value()
	 * Gets a setting value
	 *
	 * @param string $index
	 * @param mixed $default
	 * @return string
	 *---------------------------------------------------------*/
	
	function get_setting_value( $index, $default = FALSE )
	{
		return ( isset(self::$settings[$index]) ) ? stripslashes(self::$settings[$index]) : $default;
	}
	
	/*---------------------------------------------------------
	 * get_settings()
	 * Initializes plugin settings from the db
	 *---------------------------------------------------------*/
	
	function get_settings()
	{
		self::$settings = get_option('em_news_events_settings');
	}
	
	/*---------------------------------------------------------
	 * admin_menu()
	 * Add options to the admin menus
	 *---------------------------------------------------------*/
	
	function admin_menu()
	{
		add_submenu_page('edit.php?post_type=' . self::$news_slug, 'News Settings', 'Settings', 'activate_plugins', 'em-news-settings', array(__CLASS__, 'settings_page'));
		add_submenu_page('edit.php?post_type=' . self::$event_slug, 'Events Settings', 'Settings', 'activate_plugins', 'em-events-settings', array(__CLASS__, 'settings_page'));		
		add_submenu_page('edit.php?post_type=' . self::$news_slug, 'News Shortcodes', 'Shortcodes', 'activate_plugins', 'em-newsevents-shortcodes', array(__CLASS__, 'shortcodes_page'));
		add_submenu_page('edit.php?post_type=' . self::$event_slug, 'Events Shortcodes', 'Shortcodes', 'activate_plugins', 'em-newsevents-shortcodes', array(__CLASS__, 'shortcodes_page'));		
	}
	
	/*---------------------------------------------------------
	 * shortcodes_page
	 * Displays the shortcodes setting page
	 *---------------------------------------------------------*/
	
	function shortcodes_page()
	{
		include_once 'core/includes/shortcodes.php';
	}
	
	/*---------------------------------------------------------
	 * settings_page()
	 * Display settings pages
	 *---------------------------------------------------------*/
	
	function settings_page()
	{
		include_once 'core/includes/settings.php';
	}
	
	/*---------------------------------------------------------
	 * activate()
	 * Initializes ACF fields and some database values
	 *---------------------------------------------------------*/
	
	function activate()
	{
		if ( ! class_exists('Acf') )
		{
			wp_die(__('<p><strong>Could not activate!</strong> Please install and activate Advanced Custom Fields before activating this plugin.<br /><a href="plugins.php">&laquo; Return to plugins</a></p>', 'em-docman'));
		}
		
		if ( get_option('em_news_events_initialized') ) { return false; }
		
		include_once self::$base_path . '/core/includes/acf-fields.php';
		
		foreach ( $field_groups as $group )
		{
			// create acf post for news
			$post_id = wp_insert_post(array(
				'post_title' => $group['name'],
				'post_type' => 'acf',
				'post_status' => 'publish',		
			));
			
			// basic meta
			update_post_meta($post_id, 'allorany', 'all');
			update_post_meta($post_id, 'hide_on_screen', '');
			update_post_meta($post_id, 'layout', 'default');
			update_post_meta($post_id, 'position', 'normal');
			update_post_meta($post_id, 'rule', array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => $group['post_type'],
				'order_no' => 0,
			));
			
			// insert fields into db
			$next_field_id = $starting_field_id = get_option('acf_next_field_id', 1);
			foreach ( $group['fields'] as $k => &$field )
			{
				if ( $k > 0 )
				{
					$next_field_id = (int) $next_field_id + 1;
				}
	
				$field['order_no'] = $k;
				$field['key'] = 'field_' . $next_field_id;
				
				if ( $field['conditional_logic']['status'] == 1 )
				{
					/*
					if field uses conditional logic we need to recalculate the field id that is used.
					this is because our acf-fields.php file doesn't take into account any fields that may
					already exist in the db
					*/
				
					foreach ( $field['conditional_logic']['rules'] as $k => &$rule )
					{
						$id = (int) str_replace('field_', '', $rule['field']);
						$rule['field'] = 'field_' . ($starting_field_id + $id - 1);
					}
				}
				
				update_post_meta($post_id, $field['key'], $field);
			}
			
			update_option('acf_next_field_id', $next_field_id + 1);
		}
		
		// insert default templates
		$settings = array();
		$settings['event_list_template'] = ''
			. '<div class="event">' . "\r\n"
			. '    <h2 class="event-title"><a target="{link_target}" href="{url}">{title}</a></h2>' . "\r\n"
			. '    {event_date}' . "\r\n"
			. '    {event_location}' . "\r\n"
			. '    {excerpt}' . "\r\n"
			. '</div>';
		$settings['event_detail_template'] = ''
			. '<div class="event">' . "\r\n"
			. '    <h1 class="event-title">{title}</h1>' . "\r\n"
			. '    {event_date}' . "\r\n"
			. '    {event_location}' . "\r\n"
			. '    {full_text}' . "\r\n"
			. '</div>';
		$settings['news_list_template'] = ''
			. '<div class="news-item">' . "\r\n"
			. '    {publish_date}' . "\r\n"
			. '    <h2 class="news-title"><a target="{link_target}" href="{url}">{title}</a></h2>' . "\r\n"
			. '    {excerpt}' . "\r\n"
			. '</div>';
		$settings['news_detail_template'] = ''
			. '<div class="news-item">' . "\r\n"
			. '    {publish_date}' . "\r\n"
			. '    <h2 class="news-title">{title}</h2>' . "\r\n"
			. '    {full_text}' . "\r\n"
			. '</div>';
			
		self::update_settings($settings);
		update_option('em_news_events_initialized', '1');		
	}
	
	/*---------------------------------------------------------
	 * register_types()
	 * Registers custom post types and taxonomies
	 *---------------------------------------------------------*/
	
	function register_types()
	{
		register_post_type(self::$news_slug, array(
			'labels' => array(
				'name' => 'News Items',
				'singular_name' => 'News Item',
				'add_new_item' => 'Add new News Item',
				'edit_item' => 'Edit News Item',
				'new_item' => 'New News Item',
				'view_item' => 'View News Item',
				'search_items' => 'Search News Items',
				'not_found' => 'No News Items found',
				'not_found_in_trash' => 'No News Items found in trash',
			),
			'public' => TRUE,
			'show_in_nav_menus' => FALSE,
			'supports' => array('title'),
			'taxonomies' => array(self::$news_tax_slug),
			'rewrite' => array(
				'slug' => ( $parent = self::get_setting_value('news_parent') ) ? get_page_uri($parent) . '/' . self::$news_slug : self::$news_slug,
				'with_front' => FALSE,
			),
		));
		
		register_post_type(self::$event_slug, array(
			'labels' => array(
				'name' => 'Events',
				'singular_name' => 'Event',
				'add_new_item' => 'Add new Event',
				'edit_item' => 'Edit Event',
				'new_item' => 'New Event',
				'view_item' => 'View Event',
				'search_items' => 'Search Events',
				'not_found' => 'No Events found',
				'not_found_in_trash' => 'No Events found in trash',
			),
			'public' => TRUE,
			'show_in_nav_menus' => FALSE,
			'supports' => array('title'),
			'rewrite' => array(
				'slug' => ( $parent = self::get_setting_value('event_parent') ) ? get_page_uri($parent) . '/' . self::$event_slug : self::$event_slug,
				'with_front' => FALSE,
			),
		));
		
		register_taxonomy(self::$news_tax_slug, self::$news_slug, array(
			'labels' => array(
				'name' => 'News Types',
				'singular_name' => 'News Type',
				'all_items' => 'All News Types',
				'edit_item' => 'Edit News Type',
				'view_item' => 'View News Type',
				'update_item' => 'Update News Type',
				'add_new_item' => 'Add New News Type',
				'new_item_name' => 'New News Type',
				'parent_item' => 'Parent News Type',
				'parent_item_colon' => 'Parent News Type:',
				'search_items' => 'Search News Types',
				'popular_items' => 'Popular News Types',
			),
			'hierarchical' => TRUE,
		));
		register_taxonomy_for_object_type(self::$news_tax_slug, self::$news_slug);
		
		register_taxonomy(self::$event_tax_slug, self::$event_slug, array(
			'labels' => array(
				'name' => 'Event Types',
				'singular_name' => 'Event Type',
				'all_items' => 'All Event Types',
				'edit_item' => 'Edit Event Type',
				'view_item' => 'View Event Type',
				'update_item' => 'Update Event Type',
				'add_new_item' => 'Add New Event Type',
				'new_item_name' => 'New Event Type',
				'parent_item' => 'Parent Event Type',
				'parent_item_colon' => 'Parent Event Type:',
				'search_items' => 'Search Event Types',
				'popular_items' => 'Popular Event Types',
			),
			'hierarchical' => TRUE,
		));
		register_taxonomy_for_object_type(self::$event_tax_slug, self::$event_slug);
	}
	
	/***********************************************************
	*
	* Outputs pager links
	*
	* @params: first - [string] first page text
	*		   last - [string] last page text
	*		   prev - [string] previous page text
	*		   next - [string] next page text
	*		   single_prev - [string] single page previous text
	*		   single_next - [string] single page next text
	*		   single_class - [string] class to add to single page links
	*		   query - [wp_query object] custom wp_query (if not in main loop)
	*		   first_last - [bool] show first and last page links
	*		   current - [bool] override the current page
	*
	* @return: pager links
	*
	************************************************************/
	
	function display_pagination( $args = '' ) {
		
		$defaults = array(
						'first' => '&laquo;',
						'last' => '&raquo;',
						'prev' => '&lsaquo;',
						'next' => '&rsaquo;',
						'single_prev' => '&lsaquo; Previous Post',
						'single_next' => 'Next Post &rsaquo;',
						'single_class' => 'prev-next-posts',
						'query' => null,
						'first_last' => false,
						'current' => null,
						);
						
		// Pull the WP_Query first, as wp_parse_args kills it
		$query = $args['query'];
		$args = wp_parse_args($args, $defaults);
		
		global $wp_query;				
		if( is_null($query) )
		{
			$query =& $wp_query;
		}
		
		$output_before = '<nav class="pagination"><div class="pagination-inner clearfix">';
		$output_after = '</div></nav>';
		
		// If we have multiple pages of posts
		if( $query->max_num_pages > 1 )
		{
			$big = 999999999;
			$current = ( is_null($args['current']) ) ? max(1, get_query_var('paged')) : $args['current'];
			
			$output =  paginate_links(array(
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => '/page/%#%/',
				'current' => $current,
				'total' => $query->max_num_pages,
				'prev_text' => $args['prev'],
				'next_text' => $args['next'],
			));
			
			// Print first and last page links if not on the first or last page
			if( $args['first_last'] )
			{
				if( $current != 1 )
				{
					$output = '<a class="prev first-page" href="' . get_permalink(get_option('page_for_posts')) . '">' . $args['first'] . '</a>' . $output;
				}
				if( $current != $wp_query->max_num_pages )
				{
					$output .= '<a class="next last-page" href="' . get_permalink(get_option('page_for_posts')) . 'page/' . $query->max_num_pages . '/">' . $args['last'] . '</a>';
				}
			}
			return $output_before . $output . $output_after;
		}
		
		// Format the single page links
		if( is_single() )
		{
			// post_link only echos, so turn on the output buffer and capture
			ob_start();
			previous_post_link('%link', $args['single_prev']);
			next_post_link('%link', $args['single_next']);
			
			// Capture the buffer
			$links = ob_get_contents();
			
			// Clean the buffer
			ob_end_clean();
			
			// Add class and return
			return $output_before . str_replace('<a', '<a class="' . $args['single_class'] . '"', $links) . $output_after;
		}
	}	    	
}