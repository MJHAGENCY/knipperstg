<?php

/*
Plugin Name: EM Add To Menu
Description: Easily add posts and pages to an existing nav menu
Version: 1.0.0
Author: eMagine
Author URI: www.emagineusa.com
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

Em_Add_To_Menu::init();

class Em_Add_To_Menu
{
	function init()
	{
		if ( ! is_admin() )
		{
			// This plugin is designed to work on the backend only
			return false;
		}
		
		// Add metaboxes to post admin screens
		add_action('load-post.php', array(__CLASS__, 'init_metabox'));
		add_action('load-post-new.php', array(__CLASS__, 'init_metabox'));
	}
	
	function add_metabox()
	{
		$pts = get_post_types();
		
		foreach ( $pts as $pt )
		{
			if ( $pt == 'acf' )
			{
				continue;
			}
			
			add_meta_box(
				'em-add-to-menu',
				__('Menuing Options', 'emagine'),
				array(__CLASS__, 'display_metabox'),
				$pt,
				'side',
				'default'
			);
		}
	}
	
	function display_metabox( $post )
	{
		$menus = get_registered_nav_menus();
		wp_nonce_field(basename(__FILE__), 'em_add_to_menu_nonce');
?>
		<select name="em_menu_id" id="em-menu-id">
			<option value=""><?php _e('(no menu)', 'emagine'); ?></option>
<?php
		foreach ( $menus as $k => $v )
		{
			$menu_obj = wp_get_nav_menu_object($k);
			echo '<option value="' . $menu_obj->term_id . '">' . __($v, 'emagine') . '</option>';
		}
?>
		</select>
<?php
	}
	
	function init_metabox()
	{
		add_action('add_meta_boxes', array(__CLASS__, 'add_metabox'));
		add_action('save_post', array(__CLASS__, 'save_metabox'), 10, 2);
	}
	
	function save_metabox( $post_id, $post )
	{
		/*
		Make sure this is not a post revision
		*/
		
		if ( wp_is_post_revision($post_id) )
		{
			return $post_id;
		}
		
		/*
		Validate nonce
		*/
		
		if ( ! isset($_POST['em_add_to_menu_nonce']) || ! wp_verify_nonce($_POST['em_add_to_menu_nonce'], basename(__FILE__)) )
		{
			return $post_id;
		}
		
		/*
		Check if the current user has permission to edit the post.
		*/
		
		$post_type = get_post_type_object($post->post_type);
		
		if ( ! current_user_can($post_type->cap->edit_post, $post_id) )
		{
			return $post_id;
		}
		
		/*
		Save menu item
		*/
		
		$parent_id = ! empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
		$menu_id = ! empty($_POST['em_menu_id']) ? (int) $_POST['em_menu_id'] : 0;
		
		if ( empty($menu_id) )
		{
			return $post_id;
		}
		
		$menu_item_data = array(
			'menu-item-type' => 'post_type',
			'menu-item-object-id' => $post_id,
			'menu-item-object' => $post->post_type,
			'menu-item-position' => ! empty($_POST['menu_order']) ? (int) $_POST['menu_order'] : 1,
			'menu-item-status' => 'publish',
		);
		
		if ( ! empty($parent_id) )
		{
			$all_items = wp_get_nav_menu_items($menu_id);
			
			foreach ( $all_items as $k => $menu_item )
			{
				if ( $menu_item->object_id == $parent_id )
				{
					$menu_item_data['menu-item-parent-id'] = $menu_item->ID;
					break;
				}
			}
		}
		
		remove_action('save_post', array(__CLASS__, 'save_metabox'), 10, 2);
		wp_update_nav_menu_item($menu_id, 0, $menu_item_data);
		
		return $post_id;
	}
}