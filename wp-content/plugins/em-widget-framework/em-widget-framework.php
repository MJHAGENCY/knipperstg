<?php

/*
Plugin Name: EM Widget Framework
Description: A framework for easily creating custom widgets and assigning them to posts and pages
Version: 1.2.3
Author: eMagine
Author URI: http://www.emagineusa.com/
License: GPL2
	
Copyright 2012 eMagine (email : info@emagineusa.com)

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

Em_Widget_Framework::init();

class Em_Widget_Framework
{
	/* ! @static string $version */
	static $version = '1.2.2';
	
	/* ! @static string $base_url The base url of the plugin */
	static $base_url;
	
	/* ! @static string $base_path The base path of the plugin */
	static $base_path;
	
	// ! @static array $settings An array of the plugin's settings
	static $settings = array();
	
	// ! @static $message Holds any form messages
	static $message;
	
	// ! @static string $screen The current screen
	static $screen;
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Initialization function
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function init()
	{
		global $wp_version;
		
		// This plugin only works on WP version 3.3 or higher
		if ( version_compare($wp_version, '3.3', '<') )
		{
			add_action('admin_notices', array(__CLASS__, 'admin_notices'));
			return;
		}
		
		self::$base_url = plugins_url('', __FILE__);
		self::$base_path = plugin_dir_path(__FILE__);
		self::$settings = get_option('em_widget_framework');
		
		// ! Global hooks
		add_action('widgets_init', array(__CLASS__, 'register_widgets'));
		
		// Include base widget
		include_once self::$base_path .'core/includes/class-em-base-widget.php';
			
		// ! Frontend hooks
		if ( ! is_admin() && strpos($_SERVER['REQUEST_URI'], 'wp-login.php') === false )
		{
			add_filter('sidebars_widgets', array(__CLASS__, 'filter_widgets'));
			return;
		}
		
		// ! Admin hooks
		add_action('admin_head-media-upload-popup', array(__CLASS__, 'popup_head'));
		add_action('admin_menu', array(__CLASS__, 'admin_menu_items'));
		add_action('in_widget_form', array(__CLASS__, 'add_assignments_field'), 10, 3);
		add_filter('widget_update_callback', array(__CLASS__, 'widget_update_callback'), 10, 4);
		add_action('admin_notices', array(__CLASS__, 'maybe_display_widget_status'));
		add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'load_scripts'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'load_styles'));
		add_action('save_post', array(__CLASS__, 'save_post'));
		
		// Include widget fields
		include_once self::$base_path .'core/fields/assignments.php';
		include_once self::$base_path .'core/fields/checkbox.php';
		include_once self::$base_path .'core/fields/radio.php';
		include_once self::$base_path .'core/fields/select.php';
		include_once self::$base_path .'core/fields/text.php';
		include_once self::$base_path .'core/fields/textarea.php';
		include_once self::$base_path .'core/fields/upload.php';
		include_once self::$base_path .'core/fields/wysiwyg.php';
		
		// ! Hooks for widgets screen
		if ( self::get_current_screen() == 'widgets.php' )
		{
			add_action('admin_print_footer_scripts', array(__CLASS__, 'load_footer_scripts'));
		}
		
		// ! Hooks for settings screen
		if ( self::get_current_screen() == 'tools.php' )
		{
			add_action('init', array(__CLASS__, 'save_settings'));
			include self::$base_path . 'core/includes/class-em-widget-framework-list-table.php';
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Get current screen
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function get_current_screen()
	{
		if ( empty(self::$screen) )
		{
			$url = parse_url(network_home_url($_SERVER['REQUEST_URI']));
			$path = pathinfo($url['path']);
			self::$screen = $path['basename'];
		}
		
		return self::$screen;		
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Save post
	 * @param int $post_id
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function save_post( $post_id )
	{
		// Check if the user intended to change this value.
		if ( !isset($_POST['em_widget_framework_save_page_widgets_nonce']) || !wp_verify_nonce($_POST['em_widget_framework_save_page_widgets_nonce'], 'em-widget-framework') ) return;
		
		// Check if the user wants custom sorting
		if ( empty($_POST['em_widget_framework_use_custom_sorting']) )
		{
			delete_post_meta($post_id, 'widgets');
		}
		else
		{
			update_post_meta($post_id, 'widgets', $_POST['em_widget_framework_widgets']);
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Add meta boxes
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function add_meta_boxes()
	{
		if ( self::get_current_screen() != 'post.php') return;
		
		$post_types = get_post_types();
		
		foreach ( $post_types as $post_type )
		{
			add_meta_box('em-page-widgets', 'Callout Sorting', array(__CLASS__, 'page_widget_meta_box'), $post_type, 'side', 'default');
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Displays the page widget meta box
	 * @param object $post
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function page_widget_meta_box( $post )
	{
		include_once self::$base_path .'core/includes/page-widget-metabox.php';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Add assignments field to all widgets
	 * @param object $widget The current widget (e.g. $this)
	 * @param @return
	 * @param $instance The current widget's instance
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function add_assignments_field( $widget, $return, $instance )
	{
		$inst = wp_parse_args($instance, array(
			'assignments' => array(
				'type' => '',
				'id' => '',
			),
		));
		
		call_user_func(array('Em_Widget_Field_Assignments', 'display'), array(
			'name' => $widget->get_field_name('assignments'),
			'id' => $widget->get_field_id('assignments'),
			'label' => 'Assignments',
			'type' => 'assignments',
			'values' => array(
				'type' => $inst['assignments']['type'],
				'id' => $inst['assignments']['id'],
			),
		));
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Add menu items to the admin
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function admin_menu_items()
	{
		add_management_page('Widget Framework', 'Widget Framework', 'update_core', 'em-widget-framework', array(__CLASS__, 'display_settings_page'));
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Display admin notices
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function admin_notices()
	{
		echo '<div class="error"><p>Em Widget Framework could be initialized becauses it requires WordPress version 3.3 or higher.</p></div>';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Display the settings screen HTML
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function display_settings_page()
	{
		if ( empty($_GET['action']) )
		{
			include_once self::$base_path .'core/includes/settings.php';
			return;
		}
		
		switch ( $_GET['action'] )
		{
			case 'add' :
				include_once self::$base_path .'core/includes/settings-add-new.php';
			break;
			
			case 'edit' :
				include_once self::$base_path .'core/includes/settings-edit.php';
			break;
			
			case 'delete' :
				include_once self::$base_path .'core/includes/settings-delete.php';
			break;
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Filter widgets that are not assigned to this post, post type or post tree
	 * @param array $widgets
	 * @return array $widgets
	 * @uses $post
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function filter_widgets( $widgets )
	{
		global $post;
		
		$post_id = 0;
		
		if ( is_object($post) )
		{
			$post_id = $post->ID;
		}
		
		// check if this post is using custom sorting
		$widgets_sorted = get_post_meta($post_id, 'widgets', true);
		
		if ( !empty($widgets_sorted) )
		{
			$widgets = $widgets_sorted;
		}
		
		foreach ( $widgets as $sidebar => $sidebar_widgets )
		{
			if ( !is_array($sidebar_widgets) ) continue;
			
			foreach ( $sidebar_widgets as $k => $widget )
			{
				// determine the option name from the widget name
				preg_match('/([^0-9]+)-([0-9]+)/', $widget, $matches);
				
				if ( ! isset($matches[1]) || ! isset($matches[2]) )
				{
					continue;
				}
				
				// get the widget from the options table
				$the_widget = get_option('widget_' . $matches[1]);
				
				// get this particular widget's settings
				$widget_settings = $the_widget[$matches[2]];
				
				// set defaults
				$widget_settings = wp_parse_args($widget_settings, array(
					'assignments' => array(
						'id' => '',
						'type' => '',
					),
				));
				
				// get widget assignments
				$ids = $widget_settings['assignments']['id'];
				$types = $widget_settings['assignments']['type'];
				$pt = get_post_type($post_id);
				
				// if nothing is set then don't filter
				if ( empty($ids) && empty($types) ) { continue; }
				
				if ( ! empty($types) )
				{
					$types = explode(',', $types);
					
					// check if widget is assigned to this post's type
					if ( in_array($pt, $types) )
					{
						continue;
					}
					
					// check if widget is assigned to all special pages
					if ( (is_404() || is_search()) && in_array('em-special', $types) )
					{
						continue;
					}
				}
				
				if ( ! empty($ids) )
				{
					$ids = explode(',', $ids);
					
					// check if widget is assigned to this post's id
					if ( in_array($post_id, $ids) )
					{
						continue;
					}
					
					// check if widget is assigned to 404 page
					if ( is_404() && in_array('notfound', $ids) )
					{
						continue;
					}
					
					// check if widget is assigned to search page
					if ( is_search() && in_array('search', $ids) )
					{
						continue;
					}
				}
				
				// widget is not assigned to this post - remove it from widgets array
				unset($widgets[$sidebar][$k]);
			}
		}
		
		return $widgets;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Get a widget's settings
	 * @param string $setting The name of the setting
	 * @param string $id The ID of the widget
	 * @return mixed
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function get_widget_setting( $setting, $id = null )
	{
		if ( is_null($id) )
		{
			$id = isset($_GET['ID']) ? $_GET['ID'] : 0;
		}
		
		if ( ! empty($_POST) )
		{
			return $_POST[$setting];
		}
		
		return isset(self::$settings['widgets'][$id][$setting]) ? self::$settings['widgets'][$id][$setting] : false;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Insert styles & scripts for the media uploader popup
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function popup_head()
	{
		if ( ! isset($_REQUEST['em-widget-upload-field']) ) { return; }
		
		echo '<link rel="stylesheet" href="' . self::$base_url . '/core/css/media-uploader.css" />';
		echo '<script type="text/javascript">var EM_WIDGET_FIELD = "' . $_GET['em-widget-upload-field'] . '";</script>';
		echo '<script src="' . self::$base_url . '/core/js/media-uploader.js"></script>';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Enqueue scripts
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function load_scripts()
	{
		global $wp_version;
		
		if ( self::get_current_screen() == 'widgets.php' )
		{
			add_thickbox();
			wp_enqueue_script('editor');
			wp_enqueue_script('wplink');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('wpdialogs-popup');
			
			if ( version_compare($wp_version, '3.5', '>=') )
			{
				wp_enqueue_media();
			}
			
			wp_enqueue_script('em-widget-framework', self::$base_url . '/core/js/widgets.js', array(), self::$version);
		}
		
		if ( self::get_current_screen() == 'post.php' )	
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('em-widget-framework-post', self::$base_url . '/core/js/post.js', array(), self::$version);
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Enqueue stylesheets
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function load_styles()
	{
		if ( self::get_current_screen() == 'widgets.php' )
		{
			wp_enqueue_style('thickbox');
			wp_enqueue_style('em-widget-framework', self::$base_url . '/core/css/widgets.css', array(), self::$version);
		}
		
		if ( self::get_current_screen() == 'post.php' )	
		{
			wp_enqueue_style('em-widget-framework-post', self::$base_url . '/core/css/post.css', array(), self::$version);
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Hook into admin footer scripts
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function load_footer_scripts()
	{
		wp_editor('', 'em-wysiwyg-widget-field', array(
			'tinymce' => array(
				'height' => '480',
			),
		));
		
		include_once self::$base_path .'core/includes/assignments-popup.php';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Check if widget file exists and classname is correct and display status
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function maybe_display_widget_status()
	{
		$status = '';
		
		if ( self::get_current_screen() != 'tools.php' ) { return; }
		
		foreach ( (array) self::$settings['widgets'] as $widget )
		{
			$filepath = str_replace('//', '/', get_stylesheet_directory() . '/' . $widget['filepath']);
			
			if ( ! file_exists($filepath) )
			{
				$status .= '<div class="error"><p><strong>Error:</strong> The file "' . $filepath . '" could not be found.</p></div>';
				continue;
			}
			
			include_once $filepath;
			
			if ( ! class_exists($widget['classname']) ) {
				$status .= '<div class="error"><p><strong>Error:</strong> A class of' . $widget['classname'] . ' could not be found.</p></div>';
			}
		}
		
		echo $status;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Register widgets
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function register_widgets()
	{
		if ( ! is_array(self::$settings['widgets']) ) { return; }
		
		foreach ( self::$settings['widgets'] as $widget )
		{
			$filepath = str_replace('//', '/', get_stylesheet_directory() . '/' . $widget['filepath']);
			
			if ( ! file_exists($filepath) ) { continue; }
			
			include_once $filepath;
			
			if ( ! class_exists($widget['classname']) ) { continue; }
			
			register_widget($widget['classname']);
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Save settings
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function save_settings()
	{
		
		if ( ! empty($_POST) && $_GET['action'] == 'add' )
		{
			// Add new widget
			check_admin_referer('add-widget', '_em_widget_framework_nonce');
			
			if ( empty($_POST['filepath']) || empty($_POST['classname']) )
			{
				self::$message = array(
					'type' => 'error',
					'text' => 'All fields are required',
				);
				return;
			}
			
			$id = empty(self::$settings['next_widget_id']) ? 1 : self::$settings['next_widget_id'];
			$next_id = $id + 1;
			
			self::$settings['widgets'][$id] = array(
				'filepath' => $_POST['filepath'],
				'classname' => $_POST['classname'],
			);
			self::$settings['next_widget_id'] = $next_id;
			
			// Save settings to db
			update_option('em_widget_framework', self::$settings);
			
			// Redirect back to overview page
			$url = remove_query_arg('action');
			$url = add_query_arg('message', 'added', $url);
			wp_redirect($url);
			exit;
		}
		
		if ( ! empty($_POST) && $_GET['action'] == 'edit' )
		{
			// Edit widget
			check_admin_referer('edit-widget', '_em_widget_framework_nonce');
			
			if ( empty($_POST['filepath']) || empty($_POST['classname']) )
			{
				self::$message = array(
					'type' => 'error',
					'text' => 'All fields are required',
				);
				return;
			}
			
			self::$settings['widgets'][$_GET['ID']] = array(
				'filepath' => $_POST['filepath'],
				'classname' => $_POST['classname'],
			);
			
			// Save settings to db
			update_option('em_widget_framework', self::$settings);
			
			// Redirect back to overview page
			$url = remove_query_arg('action');
			$url = remove_query_arg('ID', $url);
			$url = add_query_arg('message', 'edited', $url);
			wp_redirect($url);
			exit;
		}
		
		if ( ! empty($_POST) && $_GET['action'] == 'delete' )
		{
			// Delete widget
			check_admin_referer('delete-widget', '_em_widget_framework_nonce');
			
			unset(self::$settings['widgets'][$_GET['ID']]);
						
			// Save settings to db
			update_option('em_widget_framework', self::$settings);
			
			// Redirect back to overview page
			$url = remove_query_arg('action');
			$url = remove_query_arg('ID', $url);
			$url = add_query_arg('message', 'deleted', $url);
			wp_redirect($url);
			exit;
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Update non-custom widgets to allow for the assignments field
	 * @param array $instance The current instance
	 * @param array $new_instance The new instance
	 * @param array $old_instance The old instance
	 * @param object $widget The current widget class (e.g. $this)
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function widget_update_callback( $instance, $new_instance, $old_instance, $widget )
	{
		$instance['assignments']['type'] = $new_instance['assignments']['type'];
		$instance['assignments']['id'] = $new_instance['assignments']['id'];
		return $instance;
	}
}