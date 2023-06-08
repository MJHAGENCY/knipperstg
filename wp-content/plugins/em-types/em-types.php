<?php

/*
Plugin Name: EM Types
Description: A framework for easily creating custom post types and taxonomies
Version: 0.0.6
Author: eMagine
Author URI: http://www.emagineusa.com/
License: GPL2
	
Copyright 2013 eMagine (email : info@emagineusa.com)

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

Em_Types::init();

class Em_Types
{
	//! @static string $base_url The base url of the plugin
	static $base_url;
	
	//! @static array $settings An array of the plugin's settings
	static $settings = array();
	
	//! @static string $message Holds any form messages
	static $message;
	
	//! @static string $version
	static $version = '0.0.6';
	
	//! @static array $supports
	static $supports = array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'excerpt',
		'trackbacks',
		'custom-fields',
		'comments',
		'revisions',
		'page-attributes',
		'post-formats',
	);
	
	//! @static array $menu_positions
	static $menu_positions = array(
		5 => 'Below Posts',
		10 => 'Below Media',
		15 => 'Below Links',
		20 => 'Below Pages',
		25 => 'Below Comments',
		60 => 'Below First Separator',
		65 => 'Below Plugins',
		70 => 'Below Users',
		75 => 'Below Tools',
		80 => 'Below Settings',
		100 => 'Below Second Separator',
	);
	
	//! @static array $messages
	static $messages = array(
		'1' => 'Type added successfully',
		'2' => 'Type edited successfully',
		'3' => 'Type deleted successfully',
	);
	
	//! @static array $data
	static $data = array();
	

	/*---------------------------------------------------------
	 * init()
	 * Initialiation function
	 *---------------------------------------------------------*/
	
	function init()
	{
		self::$base_url = plugins_url('', __FILE__);
		
		// Get post types
		self::$data['custom_post_types'] = get_option('custom_post_types');
		
		// Get taxonomies
		self::$data['custom_taxonomies'] = get_option('custom_taxonomies');
		
		// Register posts
		self::add_action('init', 'register_post_types');

		// Admin hooks
		if ( is_admin() )
		{
			self::add_action('admin_menu', 'admin_menus');
			self::add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');
			self::add_action('admin_enqueue_scripts', 'admin_enqueue_styles');
			self::add_action('init', 'maybe_save_type');
			self::add_action('init', 'delete_type');
			self::add_action('wp_ajax_em_types_check_slug', 'check_slug');
			self::add_action('admin_notices', 'admin_notices');
		}
		// Front end hooks
		else
		{
			self::add_filter('wp_nav_menu_objects', 'add_menu_classes');
			self::add_filter('posts_results', 'posts_results', 10, 2);
		}
	}
	
	/*---------------------------------------------------------
	 * posts_results()
	 * Modifies the global posts array when on single page templates
	 *
	 * @param array $posts
	 * @param object $query
	 * @return array
	 *---------------------------------------------------------*/
	
	function posts_results( $posts, $query )
	{
		if ( !$query->is_main_query() || count($posts) == 0 || !$query->is_single() )
		{
			return $posts;
		}
		
		$post =& $posts[0];
		$slug = get_post_type($post->ID);
		
		if ( !isset(self::$data['custom_post_types'][$slug]) )
		{
			return $posts;
		}
		
		$data = self::$data['custom_post_types'][$slug];
		
		if ( empty($data['parent_page']) )
		{
			return $posts;
		}
		
		$post->post_parent = $data['parent_page'];
		
		return $posts;
	}
	
	/*---------------------------------------------------------
	 * add_menu_classes()
	 * Adds classes to menu items when on single templates
	 *
	 * @param array $items
	 * @return array
	 *---------------------------------------------------------*/
	
	function add_menu_classes( $items )
	{
		if ( !is_single() )
		{
			return $items;
		}
		
		$slug = get_post_type();
		
		if ( !isset(self::$data['custom_post_types'][$slug]) )
		{
			return $items;
		}
		
		$data = self::$data['custom_post_types'][$slug];
		
		if ( empty($data['parent_page']) )
		{
			return $items;
		}
		
		$menu_parent = FALSE;
		
		foreach ( $items as &$item )
		{
			if ( $item->object_id == $data['parent_page'] )
			{
				$item->classes[] = 'current-menu-item';
				$menu_parent = $item->menu_item_parent;
			}
		}
		
		if ( $menu_parent != FALSE )
		{
			foreach ( $items as &$item )
			{
				if ( $item->ID == $menu_parent )
				{
					if ( !empty($item->menu_item_parent) )
					{
						$item->classes[] = 'current-menu-ancestor';
					}
					else
					{
						$item->classes[] = 'current-menu-parent';
					}
				}
			}
		}
		
		return $items;
	}
	
	/*---------------------------------------------------------
	 * register_post_types()
	 * Register custom post types
	 *---------------------------------------------------------*/
	
	function register_post_types()
	{
		if ( is_array(self::$data['custom_post_types']) )
		{
			foreach ( self::$data['custom_post_types'] as $slug => $pt )
			{
				$defaults = array(
					'labels' => array(
						'name' => ucwords($pt['name']),
						'singular_name' => ucwords($pt['singular_name']),
						'add_new' => 'Add '. $pt['singular_name'],
						'add_new_item' => 'Add '. $pt['singular_name'],
						'edit_item' => 'Edit '. $pt['singular_name'],
						'new_item' => 'New '. $pt['singular_name'],
						'view_item' => 'View '. $pt['singular_name'],
						'search_items' => 'Search '. $pt['name'],
						'not_found' => 'No %s found'. $pt['name'],
						'not_found_in_trash' => 'No '. $pt['name'] .'in trash',
						'parent_item_colon' => 'Parent '. $pt['singular_name'] . ':',
					),
					'has_archive' => true,
					'menu_icon' => 'dashicons-admin-post',
				);
				
				$pt = array_map(create_function('$val', 'return $val === 0 || $val == 1 ? (bool) $val : $val;'), $pt);
								
				if ( !empty($pt['parent_page']) )
				{
					$pt['rewrite'] = array(
						'slug' => get_page_uri($pt['parent_page']) . '/' . $slug,
						'with_front' => FALSE,
					);
				}
				register_post_type($slug, array_merge($defaults, $pt));
			}
		}

		if ( is_array(self::$data['custom_taxonomies']) )
		{
			foreach ( self::$data['custom_taxonomies'] as $slug => $tax )
			{
				$defaults = array(
					'labels' => array(
						'name' => ucwords($tax['name']),
						'singular_name' => ucwords($tax['singular_name']),
						'menu_name' => ucwords($tax['name']),
						'all_items' => 'All '. $tax['name'],
						'edit_item' => 'Edit '. $tax['singular_name'],
						'view_item' => 'View '. $tax['singular_name'],
						'update_item' => 'Update '. $tax['singular_name'],
						'add_new_item' => 'Add New '. $tax['singular_name'],
						'new_item_name' => 'New '. $tax['singular_name'],
						'parent_item' => 'Parent '. $tax['singular_name'],
						'parent_item_colon' => 'Parent: '. $tax['singular_name'],
						'search_items' => 'Search '. $tax['name'],
						'popular_items' => 'Popular '. $tax['name'],
						'separate_items_with_commas' => 'Separate '. $tax['name'] . ' with commas',
						'add_or_remove_items' => 'Add or remove '. $tax['name'],
						'choose_from_most_used' => 'Choose from most used  '. $tax['name'],
						'not_found' => 'No '. $tax['name'] . ' found',
					),
				);
				
				if ( !empty($tax['tax_rewrite']) )
				{
					$tax['rewrite'] = array(
						'slug' => $tax['tax_rewrite'],
						'with_front' => FALSE,
					);
				}
				
				$tax = array_map(create_function('$val', 'return $val == 0 || $val == 1 ? (bool) $val : $val;'), $tax);
				
				register_taxonomy($slug, $tax['post_type'], array_merge($defaults, $tax));
				
				foreach ( $tax['post_type'] as $pt )
				{
					register_taxonomy_for_object_type($slug, $pt);
				}
			}
		}
		
		
		if ( get_option('em_flush_rewrites') )
		{
			flush_rewrite_rules();
			delete_option('em_flush_rewrites');
		}
	}
	
	/*---------------------------------------------------------
	 * admin_notices()
	 * Display admin notices
	 *---------------------------------------------------------*/
	
	function admin_notices()
	{
		$get = (object) wp_parse_args($_GET, array('page' => '', 'message' => ''));
		if ( $get->page == 'em-types' && isset(self::$messages[$get->message]) )
		{
			echo '<div class="updated"><p>' . self::$messages[$get->message] . '</p></div>';
		}
	}
	
	/*---------------------------------------------------------
	 * check_slug()
	 * Checks a slug to see if it's already been registered
	 *---------------------------------------------------------*/
	
	function check_slug()
	{
		$post = (object) wp_parse_args($_POST, array('nonce' => '', 'slug' => '', 'type' => ''));
		
		switch ( $post->type )
		{
			case 'post-type' :
				if ( isset(self::$data['custom_post_types'][$post->slug]) )
				{
					die(json_encode(array(0 => 'A post type with this slug already exists.')));
				}
				else
				{
					die('true');
				}
				break;
			
			case 'taxonomy' :
				if ( isset(self::$data['custom_taxonomies'][$post->slug]) )
				{
					die(json_encode(array(0 => 'A taxonomy with this slug already exists.')));
				}
				else
				{
					die('true');
				}
				break;
		}

		die('false');
	}
	
	/*---------------------------------------------------------
	 * maybe_save_type()
	 * Determines if there is post data to save for a type and
	 * saves it to the db
	 *---------------------------------------------------------*/
	
	function maybe_save_type()
	{
		$get = (object) wp_parse_args($_GET, array('page' => '', 'action' => ''));
		
		if ( !isset($_POST['em_types_nonce']) || $get->page != 'em-types' )
		{
			return FALSE;
		}
		
		check_admin_referer('em-types-new', 'em_types_nonce');
		
		$post = $_POST['em_types'];
		
		switch ( $post['type'] )
		{
			case 'post-type' :
				$option_name = 'custom_post_types';
				break;
				
			case 'taxonomy' :
				$option_name = 'custom_taxonomies';
				break;
				
			default :
				// a type was not selected, stop processing
				return FALSE;
				break;
		}
		
		// pull slug out of the $post array.
		// we don't need it because it becomes the data key
		$slug = $post['slug'];
		unset($post['slug']);
		
		if ( isset(self::$data[$option_name][$slug]) && $get->action == 'new' )
		{	// an item with this slug already exists
			// this should've been caught with client side validation so stop processing
			return FALSE;
		}
		
		self::$data[$option_name][$slug] = $post;
		update_option($option_name, self::$data[$option_name]);
		update_option('em_flush_rewrites', 1);
		
		$url = admin_url('tools.php?page=em-types');
		
		if ( $get->action == 'new' )
		{
			wp_redirect(add_query_arg('message', 1, $url));
		}
		else
		{
			wp_redirect(add_query_arg('message', 2, $url));
		}
		
		exit;
	}
	
	/*---------------------------------------------------------
	 * maybe_delete_type()
	 * Determines if there is post data to save for a type and
	 * saves it to the db
	 *---------------------------------------------------------*/
	
	function delete_type()
	{
		$get = (object) wp_parse_args($_GET, array('page' => '', 'action' => ''));
		
		if ( $get->action != 'delete' )
		{
			return FALSE;
		}
		
		$post = $_POST['em_types'];
		
		switch ( $_GET['type'] )
		{
			case 'post_type' :
				$option_name = 'custom_post_types';
				break;
				
			case 'taxonomy' :
				$option_name = 'custom_taxonomies';
				break;
				
			default :
				// a type was not selected, stop processing
				return FALSE;
				break;
		}
		
		// pull slug out of the $post array.
		// we don't need it because it becomes the data key
		$slug = $_GET['slug'];
		
		unset(self::$data[$option_name][$slug]);
		update_option($option_name, self::$data[$option_name]);
		update_option('em_flush_rewrites', 1);
		
		$url = admin_url('tools.php?page=em-types');
		wp_redirect(add_query_arg('message', 3, $url));
		
		exit;
	}
	
	/*---------------------------------------------------------
	 * add_action()
	 * Wrapper function for the WP add_action() function
	 *
	 * @param string $hook
	 * @param string $callback
	 * @param int $priority
	 * @param int $args
	 *---------------------------------------------------------*/
	
	function add_action( $hook, $callback, $priority = 10, $args = 1 )
	{
		add_action($hook, array(__CLASS__, $callback), $priority, $args);
	}
	
	/*---------------------------------------------------------
	 * add_filter()
	 * Wrapper function for the WP add_filter() function
	 *
	 * @param string $hook
	 * @param string $callback
	 * @param int $priority
	 * @param int $args
	 *---------------------------------------------------------*/
	
	function add_filter( $hook, $callback, $priority = 10, $args = 1 )
	{
		add_filter($hook, array(__CLASS__, $callback), $priority, $args);
	}
	
	/*---------------------------------------------------------
	 * admin_enqueue_scripts()
	 * Enqueue admin scripts
	 *---------------------------------------------------------*/
	
	function admin_enqueue_scripts()
	{
		$get = (object) wp_parse_args($_GET, array('page' => ''));
		if ( $get->page == 'em-types' )
		{
			wp_enqueue_script('post');
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-validate', self::base_url('core/js/jquery.validate.min.js'), array('jquery'), '1.11.1');
			wp_enqueue_script('em-types', self::base_url('core/js/global.js'), array('jquery', 'jquery-validate'), self::$version);
		}
	}
	
	/*---------------------------------------------------------
	 * base_url()
	 * Returns the base url to the plugin with optional path
	 *
	 * @param string $path (optional)
	 * @return string
	 *---------------------------------------------------------*/
	
	function base_url( $path = '' )
	{
		return self::$base_url . '/' . ltrim($path, '/');
	}
	
	/*---------------------------------------------------------
	 * admin_enqueue_styles()
	 * Enqueue admin styles
	 *---------------------------------------------------------*/
	
	function admin_enqueue_styles()
	{
		$get = (object) wp_parse_args($_GET, array('page' => ''));
		if ( $get->page == 'em-types' )
		{
			wp_enqueue_style('em-types', self::base_url('core/css/global.css'), array(), self::$version);
		}
	}
	
	/*---------------------------------------------------------
	 * admin_menus()
	 * Adds menu items to the admin menu
	 *---------------------------------------------------------*/
	
	function admin_menus()
	{
		add_management_page('Types', 'Types', 'activate_plugins', 'em-types', array(__CLASS__, 'show_types_page'));
	}
	
	/*---------------------------------------------------------
	 * show_types_page()
	 * Displays the HTML for the types page
	 *---------------------------------------------------------*/
	
	function show_types_page()
	{
		$get = (object) wp_parse_args($_GET, array('action' => '', 'slug' => '', 'type' => ''));
		switch ( $get->action )
		{
			case 'new' :
				$type = FALSE;
				if ( isset($_POST['type']['type']) )
				{
					$type = $_POST['type']['type'];
				}
				
				include_once 'core/pages/page-new.php';
				break;
			
			case 'edit' :
				$option_name = '';
				switch ( $get->type )
				{
					case 'post_type' :
						$option_name = 'custom_post_types';
						break;
						
					case 'taxonomy' :
						$option_name = 'custom_taxonomies';
						break;
				}
				
				if ( !isset(self::$data[$option_name][$get->slug]) )
				{
					wp_die('Invalid type');
				}
				
				include_once 'core/pages/page-edit.php';
				break;
			default :
				include_once 'core/includes/class-em-types-list-table.php';
				include_once 'core/pages/page-list.php';
				break;
		}
	}
	
	/*---------------------------------------------------------
	 * get_value()
	 * Safely gets a value
	 *
	 * @param string $key
	 * @param string $default (optional)
	 * @return mixed
	 *---------------------------------------------------------*/
	
	function get_value( $key, $default = '' )
	{
		if ( isset($_POST['em_types'][$key]) )
		{
			return $_POST['em_types'][$key];
		}
		
		$get = (object) wp_parse_args($_GET, array('slug' => '', 'type' => ''));
		
		if ( $key == 'type' )
		{
			return str_replace('_', '-', $get->type);
		}
		
		if ( $key == 'slug' )
		{
			return $get->slug;
		}
		
		switch ( $get->type )
		{
			case 'post_type' :
				return isset(self::$data['custom_post_types'][$get->slug][$key]) ? self::$data['custom_post_types'][$get->slug][$key] : $default;
				break;
				
			case 'taxonomy' :
				return isset(self::$data['custom_taxonomies'][$get->slug][$key]) ? self::$data['custom_taxonomies'][$get->slug][$key] : $default;
				break;
		}
			
		return $default;
	}
}