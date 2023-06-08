<?php

class Em_Base_Widget extends WP_Widget
{
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
		foreach ( $this->fields as $field )
		{
			$field['value'] = isset($instance[$field['name']]) ? $instance[$field['name']] : '';
			$field['id'] = $this->get_field_id($field['name']);
			$field['name'] = $this->get_field_name($field['name']);
			
			call_user_func(array('Em_Widget_Field_' . ucfirst($field['type']), 'display'), $field);
		}
	}	
}