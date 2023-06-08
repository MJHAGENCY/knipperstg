<?php

class Em_Custom_Widget extends Em_Base_Widget
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
			'name' => 'assoc_image',
			'label' => 'Image',
			'type' => 'upload',
		),
		array(
			'name' => 'content',
			'label' => 'Content',
			'type' => 'wysiwyg',
		),
	);
	
	var $widget_id = 'custom';
	
	var $widget_name = 'Custom HTML';
		
	function widget($args, $instance)
	{
		extract($args);
		
		$inst = (object) wp_parse_args($instance, array(
			'title' => '',
			'display_title' => '',
			'content' => '',
			'assoc_image' => ''
		));
		
		echo str_replace('widget_', '', $before_widget);
		
		if ( $inst->assoc_image ) :
		  echo '<img class="widget-img" src="' . $inst->assoc_image . '" alt="" />';
		endif;
		
		echo '<div class="inner-widget">';
		echo $before_title;
		echo $inst->display_title;
		echo $after_title;
		echo apply_filters('the_content', $inst->content);
		echo $after_widget;
		echo '</div>';
	}	
}