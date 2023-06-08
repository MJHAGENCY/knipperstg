<?php

$field_groups = array(
	array(
		'name' => 'News Settings',
		'post_type' => self::$news_slug,
		'fields' => array(
			array(
				'key' => 'field_1',
				'label' => 'Associated Image',
				'name' => 'associated_image',
				'type' => 'image',
				'order_no' => 0,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_6',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'object',
				'preview_size' => 'large',
			),
			array (
				'key' => 'field_2',
				'label' => 'Excerpt',
				'name' => 'excerpt',
				'type' => 'textarea',
				'order_no' => 1,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_6',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_3',
				'label' => 'Is this an external link?',
				'name' => 'is_external_link',
				'type' => 'true_false',
				'order_no' => 2,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_6',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
			),
			array (
				'key' => 'field_4',
				'label' => 'Full Text',
				'name' => 'full_text',
				'type' => 'wysiwyg',
				'order_no' => 4,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 1,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_3',
							'operator' => '!=',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
				'the_content' => 'yes',
			),
			array (
				'key' => 'field_5',
				'label' => 'External Link',
				'name' => 'external_link',
				'type' => 'text',
				'order_no' => 5,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 1,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_3',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
		),
	),
	array(
		'name' => 'Event Settings',
		'post_type' => self::$event_slug,
		'fields' => array (
			array (
				'key' => 'field_1',
				'label' => 'Start Date',
				'name' => 'start_date',
				'type' => 'date_picker',
				'order_no' => 0,
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'date_format' => 'yymmdd',
				'display_format' => 'yy-mm-dd',
			),
			array (
				'key' => 'field_2',
				'label' => 'End Date',
				'name' => 'end_date',
				'type' => 'date_picker',
				'order_no' => 1,
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'date_format' => 'yymmdd',
				'display_format' => 'yy-mm-dd',
			),
			array (
				'key' => 'field_3',
				'label' => 'Associated Image',
				'name' => 'associated_image',
				'type' => 'image',
				'order_no' => 2,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_17',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'object',
				'preview_size' => 'large',
			),
			array (
				'key' => 'field_4',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'textarea',
				'order_no' => 3,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_5',
				'label' => 'Excerpt',
				'name' => 'excerpt',
				'type' => 'textarea',
				'order_no' => 4,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_17',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_6',
				'label' => 'Is this an external link?',
				'name' => 'is_external_link',
				'type' => 'true_false',
				'order_no' => 5,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 0,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_17',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
			),
			array (
				'key' => 'field_7',
				'label' => 'Full Text',
				'name' => 'full_text',
				'type' => 'wysiwyg',
				'order_no' => 7,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 1,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_6',
							'operator' => '!=',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
				'the_content' => 'yes',
			),
			array (
				'key' => 'field_8',
				'label' => 'External Link',
				'name' => 'external_link',
				'type' => 'text',
				'order_no' => 8,
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 
				array (
					'status' => 1,
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_6',
							'operator' => '==',
							'value' => 1,
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
		),
	),
);