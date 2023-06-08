<?php

class Em_Widget_Field_Text
{
	function display( $field )
	{
		echo '<p>';
		echo '<label for="' . $field['id'] . '">' . _($field['label']) . '</label><br />';
		echo '<input class="widefat" type="text" name="' . $field['name'] . '" id="' . $field['id'] .'" value="' . $field['value'] . '" />';
		echo '</p>';
	}
	
	function save_value( $field, $instance )
	{
		return esc_html($instance[$field['name']]);
	}
}