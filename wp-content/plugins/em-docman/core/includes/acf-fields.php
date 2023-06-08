<?php

$fields = array(
	array(
		'key' => 'field_1',
		'label' => 'File Upload',
		'name' => 'document_file_upload',
		'type' => 'file',
		'instructions' => 'Choose a file that you would like to make available for download.',
		'required' => 1,
		'conditional_logic' => array(
			'status' => 0,
			'rules' => array(
				array(
					'field' => 'field_2',
					'operator' => '==',
					'value' => 1,
				),
			),
			'allorany' => 'all',
		),
		'save_format' => 'object',
	),
	array(
		'key' => 'field_2',
		'label' => 'Short Description',
		'name' => 'document_short_description',
		'type' => 'textarea',
		'instructions' => 'Enter the short description of this document. This will be used on document overview pages.',
		'required' => 0,
		'conditional_logic' => array(
			'status' => 0,
			'rules' => array (
				array (
					'field' => 'null',
					'operator' => '==',
				),
			),
			'allorany' => 'all',
		),
	),
	array(
		'key' => 'field_3',
		'label' => 'Full Description',
		'name' => 'document_full_description',
		'type' => 'wysiwyg',
		'instructions' => 'Enter the full description of this document.',
		'required' => 0,
		'conditional_logic' => array(
			'status' => 0,
			'rules' => array (
				array (
					'field' => 'null',
					'operator' => '==',
				),
			),
			'allorany' => 'all',
		),
		'toolbar' => 'full',
		'media_upload' => 'no',
		'the_content' => 'yes',
	),	
	array(
		'key' => 'field_4',
		'label' => 'Require Registration',
		'name' => 'document_require_registration',
		'type' => 'true_false',
		'instructions' => 'Require registration before allowing download of this document?',
		'required' => 1,
		'conditional_logic' => array(
			'status' => 0,
			'rules' => array (
				array (
					'field' => 'null',
					'operator' => '==',
				),
			),
			'allorany' => 'all',
		),
		'message' => 'Yes',
	),
	array(
		'key' => 'field_5',
		'label' => 'Registration Page',
		'name' => 'document_registration_page',
		'type' => 'post_object',
		'instructions' => 'Choose a page for registration',
		'required' => 1,
		'conditional_logic' => array(
			'status' => 1,
			'rules' => array(
				array(
					'field' => 'field_4',
					'operator' => '==',
					'value' => 1,
				),
			),
			'allorany' => 'all',
		),
		'post_type' => array(
			'page',
		),
		'taxonomy' => array(
			'all',
		),
		'allow_null' => 1,
		'multiple' => 0,
	),
);