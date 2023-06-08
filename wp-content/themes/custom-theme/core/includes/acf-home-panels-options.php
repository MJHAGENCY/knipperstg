<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_home-panels',
		'title' => 'Home Panels',
		'fields' => array (
			/*array (
				'key' => 'field_537a03072d54a',
				'label' => 'Panel Link',
				'name' => 'hp_link',
				'type' => 'page_link',
				'instructions' => 'The interior page the user will be taken to when clicking on the panel',
				'post_type' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),*/
			array (
				'key' => 'field_537a02b82d544',
				'label' => 'Desktop',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_537a02d72d547',
				'label' => 'Image',
				'name' => 'hp_desktop_image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_537a02c32d546',
				'label' => 'Tablet',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_537a02e82d548',
				'label' => 'Image',
				'name' => 'hp_tab_image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_537a02c12d545',
				'label' => 'Smartphone',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_537a02f12d549',
				'label' => 'Image',
				'name' => 'hp_sm_image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'home-panel',
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