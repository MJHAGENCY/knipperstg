<?php

class Em_Document_Widget extends Em_Base_Widget
{
	var $fields = array(
		array(
			'name' => 'title',
			'label' => 'Title',
			'type' => 'text',
		),
		array(
			'name' => 'display_title',
			'label' => 'Display Title',
			'type' => 'text',
		),
		array(
			'name' => 'doc_class',
			'label' => 'Document Type',
			'type' => 'select',
			'items' => array(
				'none' => 'Select',
				'case-study' => 'Case Study',
				'info-sheet' => 'Info Sheet',
				'press-release' => 'Press Release',
				'report' => 'Report',
				'video' => 'Video',
				'white-paper' => 'White Paper'
			),
		),
		array(
			'name' => 'document',
			'label' => 'Select Document',
			'type' => 'select',
			'items' => array('0' => 'Choose One'),
		),
		array(
			'name' => 'custom_link',
			'label' => 'Custom link',
			'type' => 'text',
		)
	);
	
	var $widget_id = 'document';
	
	var $widget_name = 'Document';
		
	function widget($args, $instance)
	{
		extract($args);
		
		$inst = (object) wp_parse_args($instance, array(
			'title' => '',
			'display_title' => '',
			'doc_class' => '',
			'document' => '',
			'custom_link' => '',
		));

		if ( $inst->doc_class == 'case-study' ) 
		{
			$doc_type = 'Case Study';
		}
		elseif ( $inst->doc_class == 'info-sheet' ) 
		{
			$doc_type = 'Info Sheet';
		}
		elseif ( $inst->doc_class == 'press-release' )
		{
			$doc_type = 'Press Release';
		}
		elseif ( $inst->doc_class == 'report' )
		{
			$doc_type = 'Report';
		}
		elseif ( $inst->doc_class == 'white-paper' )
		{
			$doc_type = 'White Paper';
		}
		elseif ( $inst->doc_class == 'video' )
		{
			$doc_type = 'Video';
		}		
		
		if ( $inst->document != 0 ) 
		{
			$link = get_permalink($inst->document);
		}
		else
		{
			$link = $inst->custom_link;
		}
		
		echo str_replace('widget_', $inst->doc_class . ' ', $before_widget);		
		echo '<a href="' . $link . '">';
		echo '<h3>' . $inst->display_title . '</h3>';
		echo '<h4>' . $doc_type . '</h4>';
		echo '</a>';
		echo $after_widget;
	}
	
	
	
	function form( $instance )
	{	
		$documents = new WP_Query(array(
			'post_type' => 'document',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		));
		
		while ( $documents->have_posts() )
		{
			$documents->the_post();				
			$this->fields[3]['items'][get_the_ID()] = get_the_title();
		}
		
		foreach ( $this->fields as $field )
		{
			$field['value'] = $instance[$field['name']];
			$field['id'] = $this->get_field_id($field['name']);
			$field['name'] = $this->get_field_name($field['name']);
			
			call_user_func(array('Em_Widget_Field_' . ucfirst($field['type']), 'display'), $field);
		}

	}	
}