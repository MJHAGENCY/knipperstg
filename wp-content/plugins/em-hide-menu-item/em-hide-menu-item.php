<?php

/*
Plugin Name: EM Hide Menu Item
Description: Allows admins to easily make a menu item hidden (e.g. not visible on the frontend)
Version: 1.0.0
Author: eMagine
Author URI: http://www.emagineusa.com
License: GPL2
*/

Em_Hide_Menu_Item::_init();

class Em_Hide_Menu_Item
{
	// ! @static string $classname The name of the class
	static $classname = 'Em_Hide_Menu_item';
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Initialization function
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function _init()
	{
		if ( is_admin() )	// admin hooks
		{
			// save any custom menu parameters in the admin menu editor
			add_action('wp_update_nav_menu', array(self::$classname, 'save_custom_menu_params'));
			
			// use custom class for displaying admin menu items
			add_filter('wp_edit_nav_menu_walker', array(self::$classname, 'register_nav_menu_walker_class'));
		}
		else	// frontend hooks
		{
			// remove hidden menu items before they are output
			add_filter('wp_get_nav_menu_items', array(self::$classname, 'remove_hidden_menu_items'), 10, 3);
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Remove hidden menu items
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function remove_hidden_menu_items( $items, $menu, $args )
	{
		global $wpdb;
		
		$all_ids = $hidden_ids = array();
		
		// put all item IDs into an array
		foreach ( $items as $key => $item )
		{
			$all_ids[] = $item->ID;
		}
		
		// get any items that are flagged as hidden
		$hidden_items = $wpdb->get_results('
			SELECT post_id AS ID
			FROM ' . $wpdb->postmeta . '
			WHERE meta_value = "1"
				AND meta_key = "_menu_item_is_hidden"
				AND post_id IN (' . implode(',', $all_ids) . ')
		');
		
		// put hidden item IDs into an array for easier use
		foreach ( $hidden_items as $hidden_item )
		{
			$hidden_ids[] = $hidden_item->ID;
		}
		
		// loop through items and remove any items that are in the hidden_ids array
		foreach ( $items as $key => $item )
		{
			if ( in_array($item->ID, $hidden_ids) )
			{
				unset($items[$key]);
			}
		}
		
		return $items;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Override the default WP nav menu class (admin)
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function register_nav_menu_walker_class()
	{
		include_once 'class-em-walker-nav-menu-edit.php';
		return 'Em_Walker_Nav_Menu_Edit';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Save custom menu parameters
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function save_custom_menu_params()
	{
		foreach ( (array) $_POST['menu-item-title'] as $item_id => $value )
		{
			$value = isset($_POST['menu-item-is-hidden'][$item_id]) ? 1 : 0;
			update_post_meta($item_id, '_menu_item_is_hidden', $value);
		}
	}
}