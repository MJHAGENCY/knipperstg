<?php

class Em_Widget_Field_Select
{
	function display( $field )
	{
		echo '<p>';
		echo '<label for="' . $field['id'] . '">' . _($field['label']) . '</label><br />';
		echo '<select name="' . $field['name'] . '" id="' . $field['id'] . '">';
				
		foreach ( (array) $field['items'] as $val => $label )
		{
			if ( !is_array($label) )
			{
				echo '<option value="' . $val . '"' . ($val == $field['value'] ? ' selected' : '') . '>' . _($label) . '</option>';
			} else {
				echo '<optgroup label="' . $val . '">';
				foreach ( $label as $value => $text ) {
					echo '<option value="' . $value . '"' . ($value == $field['value'] ? ' selected' : '') . '>' . _($text) . '</option>';
				}
				echo '</optgroup>';
			}
		}
		
		echo '</select>';
		echo '</p>';
	}
	
	function save_value( $field, $instance )
	{
		return esc_html($instance[$field['name']]);
	}
}