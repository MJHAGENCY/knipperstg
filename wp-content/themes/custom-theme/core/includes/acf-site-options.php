<?php

/**
 *  Install Add-ons
 *  
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *  
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme/plugin as outlined in the terms and conditions.
 *  For more information, please read:
 *  - http://www.advancedcustomfields.com/terms-conditions/
 *  - http://www.advancedcustomfields.com/resources/getting-started/including-lite-mode-in-a-plugin-theme/
 */ 

// Add-ons 
// include_once('add-ons/acf-repeater/acf-repeater.php');
// include_once('add-ons/acf-gallery/acf-gallery.php');
// include_once('add-ons/acf-flexible-content/acf-flexible-content.php');
// include_once( 'add-ons/acf-options-page/acf-options-page.php' );


/**
 *  Register Field Groups
 *
 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_site-options',
		'title' => 'Site Options',
		'fields' => array (
			array (
				'key' => 'field_53c8318878dab',
				'label' => 'General',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_526ac4acd1b15',
				'label' => 'Footer Copy',
				'name' => 'opts_footer',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_561d71e57bbd1',
				'label' => 'Press Release Footer',
				'name' => 'opts_pr_footer',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
			array (
				'key' => 'field_526ac367d1b0c',
				'label' => 'Fallback Masthead Image',
				'name' => 'opts_masthead',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'full',
				'library' => 'all',
			),
			array (
			'key' => 'field_535ce562d1b0c',
				'label' => 'Fallback Masthead Background Color (mobile)',
				'name' => 'opts_masthead_background',
				'type' => 'color_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
			),
/*
			array (
				'key' => 'field_5384d5484cnkw',
				'label' => 'BugHerd API Key',
				'name' => 'opts_bh_apikey',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
*/
			array (
				'key' => 'field_53c831887ut1l',
				'label' => 'Utilities',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5384d5484cntc',
				'label' => 'Contact Icon URL',
				'name' => 'util_contact_url',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_526ac45bd1b12',
				'label' => 'Social Media',
				'name' => 'opts_social_media',
				'type' => 'repeater',
				'sub_fields' => array (
					array (
						'key' => 'field_526ac471d1b13',
						'label' => 'Social Media Type',
						'name' => 'opts_sm_type',
						'type' => 'select',
						'column_width' => '',
						'choices' => array (
							'fb' => 'FaceBook',
							'g' => 'Google+',
							'ig' => 'Instagram',
							'li' => 'LinkedIn',
							'rss' => 'RSS',
							'pt' => 'Pintrest',
							'tw' => 'Twitter',
							'vm' => 'Vimeo',
							'yt' => 'YouTube',
						),
						'default_value' => '',
						'allow_null' => 0,
						'multiple' => 0,
					),
					array (
						'key' => 'field_526ac492d1b14',
						'label' => 'Social Media URL',
						'name' => 'opts_sm_url',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => 0,
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Social Media Service',
			),
			array (
				'key' => 'field_53c8318879ebc',
				'label' => 'Responsive',
				'name' => '',
				'type' => 'tab',
			),
			
			array (
				'key' => 'field_526ac342d1b0a',
				'label' => 'Desktop Logo',
				'name' => 'opts_logo',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'full',
				'library' => 'all',
			),
			array (
				'key' => 'field_526ac342d2c1b',
				'label' => 'Desktop Logo High-Resolution',
				'name' => 'opts_logo_2x',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'full',
				'library' => 'all',
			),
			array (
				'key' => 'field_526ac342d3d2c',
				'label' => 'Mobile Logo',
				'name' => 'opts_logo_mob',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'full',
				'library' => 'all',
			),
			array (
				'key' => 'field_526ac342d4e3d',
				'label' => 'Mobile Logo High-Resolution',
				'name' => 'opts_logo_mob_2x',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'full',
				'library' => 'all',
			),
			array (
				'key' => 'field_53c8318870fcd',
				'label' => 'SEO',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5384d56a4f1ae',
				'label' => 'Use Google Analytics Classic',
				'name' => 'opts_ga_classic',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_5384d5484f1ad',
				'label' => 'Google Analytics ID',
				'name' => 'opts_ga_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5384d5844f1af',
				'label' => 'Google Analytics Domain',
				'name' => 'opts_ga_domain',
				'type' => 'text',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5384d56a4f1ae',
							'operator' => '!=',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53ebb59a109eb',
				'label' => 'Exclude from Site Search',
				'name' => 'opts_exclude_posts',
				'type' => 'relationship',
				'instructions' => 'Select the pages and posts to hide from Wordpress search engine.',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'post',
					1 => 'page',
					2 => 'career',
					3 => 'document',
					4 => 'news-item',
					5 => 'event',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'filters' => array (
					0 => 'search',
					1 => 'post_type',
				),
				'result_elements' => array (
					0 => 'post_type',
					1 => 'post_title',
				),
				'max' => '',
			),
			array (
				'key' => 'field_526e60825bbcf',
				'label' => 'Search Navigation',
				'name' => 'opts_search_nav',
				'type' => 'post_object',
				'instructions' => 'The tertiary navigation to be displayed on search results page',
				'post_type' => array (
					0 => 'page',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_526e60b55bbd0',
				'label' => 'Page Not Found Navigation',
				'name' => 'opts_404_nav',
				'type' => 'post_object',
				'instructions' => 'The tertiary navigation displayed when a page cannot be found',
				'post_type' => array (
					0 => 'page',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_526e60d65bbd1',
				'label' => 'Page Not Found Text',
				'name' => 'opts_404_text',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

?>