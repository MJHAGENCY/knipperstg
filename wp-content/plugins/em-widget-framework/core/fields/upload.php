<?php

class Em_Widget_Field_Upload
{
	function display( $field )
	{
		echo '<p>';
		echo '<label for="' . $field['id'] . '">' . _($field['label']) . '</label><br />';
		echo '<input type="text" class="upload-field" name="' . $field['name'] . '" id="' . $field['id'] .'" value="' . $field['value'] . '" />';
		echo '&nbsp;<input type="button" class="button upload-button" value="Select File" />';
		echo '</p>';
	}
	
	function save_value( $field, $instance )
	{
		return esc_url($instance[$field['name']]);
	}
}