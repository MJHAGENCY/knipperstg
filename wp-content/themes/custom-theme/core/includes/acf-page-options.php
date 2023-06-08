<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_page-options',
		'title' => 'Page Options',
		'fields' => array (
			array (
				'key' => 'field_53d7fef4182f1',
				'label' => 'Alternate Page Title',
				'name' => 'int_alt_page_title',
				'type' => 'text',
				'instructions' => 'This is used in place of the page title for the heading 1.',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53d7febb182f0',
				'label' => 'Masthead Image',
				'name' => 'int_masthead_image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_53d8001d9d3ed',
				'label' => 'Masthead Text Color',
				'name' => 'int_masthead_color',
				'type' => 'select',
				'choices' => array (
					0 => 'Choose',
					'dark' => 'Dark',
					'light' => 'Light',
				),
				'default_value' => 'light',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
			'key' => 'field_567ad086bfa1d',
				'label' => 'Masthead Background Color (mobile)',
				'name' => 'int_masthead_background',
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
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 0,
				),
				array (
					'param' => 'page_type',
					'operator' => '!=',
					'value' => 'front_page',
					'order_no' => 1,
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