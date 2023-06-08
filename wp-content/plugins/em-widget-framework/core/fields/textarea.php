<?php

class Em_Widget_Field_Textarea
{
	function display( $field )
	{
		echo '<p>';
		echo '<label for="' . $field['id'] . '">' . _($field['label']) . '</label><br />';
		echo '<textarea class="widefat" name="' . $field['name'] . '" id="' . $field['id'] .'">' . $field['value'] . '</textarea>';
		echo '</p>';
	}
	
	function save_value( $field, $instance )
	{
		return esc_html($instance[$field['name']]);
	}
}