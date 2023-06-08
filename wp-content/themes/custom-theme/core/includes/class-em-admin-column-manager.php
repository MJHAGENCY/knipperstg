<?php

Em_Admin_Column_Manager::setup('media');

class Em_Admin_Column_Manager
{
	/*--------------------------------------------------------------------------------------
	 *
	 * Setup
	 *
	 * @param string $post_type The post type slug
	 * @author jcowher
	 * @since 1.0.0
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function setup( $post_type )
	{
		$pt = str_replace('-', '_', $post_type);
		$col_func = "manage_{$pt}_columns";
		$col_val_func = "manage_{$pt}_column_value";
		
		if ( ! method_exists(__CLASS__, $col_func) || ! method_exists(__CLASS__, $col_val_func) ) return false;
		
		if ( $post_type != 'media' )
		{
			add_filter('manage_edit-' . $post_type . '_columns', array(__CLASS__, $col_func));
			add_action('manage_' . $post_type . '_posts_custom_column', array(__CLASS__, $col_val_func), 10, 2);
		}
		else
		{
			add_filter('manage_media_columns', array(__CLASS__, 'manage_media_columns'));
			add_action('manage_media_custom_column', array(__CLASS__, 'manage_media_column_value'), 10, 2);
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Setup Taxonomy
	 *
	 * @param string $taxonomy The taxonomy slug
	 * @author jcowher
	 * @since 1.1.7
	 *
	 *--------------------------------------------------------------------------------------*/

	function setup_taxonomy( $taxonomy )
	{
		$tax = str_replace('-', '_', $taxonomy);
		$col_func = "manage_{$tax}_columns";
		$col_val_func = "manage_{$tax}_column_value";
		
		if ( ! method_exists(__CLASS__, $col_func) || ! method_exists(__CLASS__, $col_val_func) ) return false;
		
		add_filter("manage_edit-{$taxonomy}_columns", array(__CLASS__, $col_func));
		add_filter("manage_{$taxonomy}_custom_column", array(__CLASS__, $col_val_func), 10, 3);
	}
	
	function manage_media_columns( $columns )
	{
		return $columns;
	}
	
	function manage_media_column_value( $column, $post_id )
	{
		$mypost = get_post($post_id);
		
		switch ( $column )
		{
		}
	}
	
	function manage_page_columns( $columns )
	{
		unset(
			$columns['wpseo-score'],
			$columns['wpseo-title'],
			$columns['wpseo-metadesc'],
			$columns['wpseo-focuskw'],
			$columns['comments'],
			$columns['author'],
			$columns['date']
		);

		$columns['menu_order'] = 'Order';
		$columns['template'] = 'Template';
		return $columns;		
	}
	
	function manage_page_column_value( $column, $post_id )
	{
		$mypost = get_post($post_id);
		
		switch ( $column )
		{
			case 'menu_order' :
				echo $mypost->menu_order;
			break;
			
			case 'template' :
				$templates = get_page_templates();
				$template = array_search(get_post_meta($post_id, '_wp_page_template', true), $templates);
				echo empty($template) ? 'Default' : $template;
			break;
		}	
	}
}