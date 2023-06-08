<?php
class Em_Widget_Field_Checkbox
{
	function display( $field )
	{
		echo '<p>';
		echo '<input type="checkbox" name="' . $field['name'] . '" id="' . $field['id'] .'" value="1"' . (( $field['value'] == 1 ) ? ' checked' : '') . ' />';
		echo '&nbsp;&nbsp;<label for="' . $field['id'] . '">' . _($field['label']) . '</label>';		
		echo '</p>';
	}
	
	function save_value( $field, $instance )
	{
		return esc_html($instance[$field['name']]);
	}
}