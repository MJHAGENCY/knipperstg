<?php

/**
	Each taxonomy array should contain the following:
	
	array(
		"taxonomy" => The slug of the taxonomy - should always be plural
		"display_plural" => Plural version of display text
		"display_singular" => Singular version of display text
		"post_types" => An array of post types to associate this taxonomy with
		"hierarchical" => If set to true taxonomy will act like a category. If false will mimic tag functionality.
	)
*/

$taxonomies = array();

/*--------------------------------------------------------------------------------------
 *
 * Stop editing!
 *
 *--------------------------------------------------------------------------------------*/

foreach ( $taxonomies as $tax )
{
	$defaults = array(
		'labels' => array(
			'name' => ucwords($tax['display_plural']),
			'singular_name' => ucwords($tax['display_singular']),
			'search_items' => sprintf('Search %s', $tax['display_plural']),
			'popular_items' => sprintf('Popular %s', $tax['display_plural']),
			'all_items' => sprintf('All %s', $tax['display_plural']),
			'parent_item' => sprintf('Parent %s', $tax['display_singular']),
			'parent_item_colon' => sprintf('Parent %s:', $tax['display_singular']),
			'edit_item' => sprintf('Edit %s', $tax['display_singular']),
			'update_item' => sprintf('Update %s', $tax['display_singular']),
			'add_new_item' => sprintf('Add new %s', $tax['display_singular']),
			'new_item_name' => sprintf('New %s name', $tax['display_singular']),
			'separate_items_with_commas' => sprintf('Separate %s with commas', $tax['display_plural']),
			'add_or_remove_items' => sprintf('Add or remove %s', $tax['display_plural']),
			'choose_from_most_used' => sprintf('Choose from the most used %s', $tax['display_plural']),
		),
		'public' => true,
		'post_types' => ! empty($tax['post_types']) ? $tax['post_types'] : array(),
		'hierarchical' => ! empty($tax['hierarchical']) ? $tax['hierarchical'] : true,
	);
	
	register_taxonomy($tax['taxonomy'], $tax['post_types'], array_merge($defaults, $tax));
}