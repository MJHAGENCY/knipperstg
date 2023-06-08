<?php

class Em_CTA_Button extends WP_Widget
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
			'name' => 'cta_link',
			'label' => 'Internal Link',
			'type' => 'select',
			'items' => array('' => 'Choose One'),
		),
		array(
			'name' => 'cta_external_link',
			'label' => 'External Link',
			'type' => 'text',
		),
	);
	
	var $widget_id = 'cta';
	
	var $widget_name = 'Call to Action Button';
	
	var $widget_ops = array(
		'description' => '',
	);
	
	var $control_ops = array(
		'width' => 430,
		'height' => 420,
	);
			
   function __construct()
	{
		$this->WP_Widget($this->widget_id, $this->widget_name, $this->widget_ops, $this->control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		echo $before_widget . '<h3><a ' . ( !empty($instance['cta_external_link']) ? 'target="_Blank"' : '' ) . ' class="btn" href="' . ( !empty($instance['cta_external_link']) ? $instance['cta_external_link'] : get_permalink($instance['cta_link']) ) . '">' . $instance['display_title'] . '</a></h3>';
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		foreach ( $this->fields as $field )
		{
			$new_instance[$field['name']] = call_user_func_array(array('Em_Widget_Field_' . ucfirst($field['type']), 'save_value'), array($field, $new_instance));
		}
		
		return $new_instance;
	}
	
	function form( $instance )
	{	
		$pages = get_pages(array(
			'sort_order' => 'ASC',
			'sort_column' => 'menu_order',
			'post_type' => 'page',
			'post_status' => 'publish'
		)); 
		foreach ( $pages as $page ) 
		{
			if ( $page->post_parent != 0 ) 
			{
				$page_title = '-' . $page->post_title;
			}
			else 
			{
				$page_title = $page->post_title;
			}
			$this->fields[2]['items'][$page->ID] = $page_title;
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