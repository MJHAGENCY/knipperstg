<?php

/**
	Each post type array should contain the following:
	
	array(
		"slug" => The slug of the post type - should always be singular
		"display_plural" => Plural version of display text
		"display_singular" => Singular version of display text
		"description" => A short description of what the post type is
		"supports" => An array of fields that this post type supports
		"taxonomies" => An array of taxonomies that are linked to this post type
	)
	
	Supports Values:
		title
		editor
		author
		thumbnail
		excerpt
		trackbacks
		custom-fields
		comments
		revisions
		page-attributes
		post-formats
*/

$post_types = array();

/*--------------------------------------------------------------------------------------
 *
 * Stop editing!
 *
 *--------------------------------------------------------------------------------------*/

foreach ( $post_types as $pt )
{
	$defaults = array(
		'labels' => array(
			'name' => ucwords($pt['display_plural']),
			'singular_name' => ucwords($pt['display_singular']),
			'add_new' => sprintf('Add %s', $pt['display_singular']),
			'add_new_item' => sprintf('Add %s', $pt['display_singular']),
			'edit_item' => sprintf('Edit %s', $pt['display_singular']),
			'new_item' => sprintf('New %s', $pt['display_singular']),
			'view_item' => sprintf('View %s', $pt['display_singular']),
			'search_items' => sprintf('Search %s', $pt['display_plural']),
			'not_found' => sprintf('No %s found', $pt['display_plural']),
			'not_found_in_trash' => sprintf('No %s in trash', $pt['display_plural']),
			'parent_item_colon' => sprintf('Parent %s:', $pt['display_singular']),
		),
		'description' => ! empty($pt['description']) ? $pt['description'] : '',
		'public' => true,
		'menu_position' => 5,
		'supports' => ! empty($pt['supports']) ? $pt['supports'] : array(),
		'taxonomies' => ! empty($pt['taxonomies']) ? $pt['taxonomies'] : array(),
		'has_archive' => true,
	);
	
	register_post_type($pt['slug'], array_merge($defaults, $pt));
}