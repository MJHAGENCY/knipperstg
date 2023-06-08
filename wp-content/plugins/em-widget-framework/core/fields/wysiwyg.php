<?php

class Em_Widget_Field_Wysiwyg
{
	function display( $field )
	{
		echo '<p>';
		echo '<label for="' . $field['id'] . '">' . _($field['label']) . '</label><br />';
		echo '<textarea class="widefat wysiwyg" rows="5" name="' . $field['name'] . '" id="' . $field['id'] . '">' . $field['value'] . '</textarea>';
		echo '</p>';
	}
	
	function save_value( $field, $instance )
	{
		return stripslashes(wp_filter_post_kses(addslashes($instance[$field['name']])));
	}
}