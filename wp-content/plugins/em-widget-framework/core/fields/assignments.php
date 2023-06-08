<?php

class Em_Widget_Field_Assignments
{
	function display( $field )
	{
		echo '<p>';
		echo '<a class="button assignments-link" href="javascript:;">Assignments</a>';
		echo '<input type="hidden" class="assignments-type" name="' . $field['name'] . '[type]" id="' . $field['id'] .'-type" value="' . $field['values']['type'] . '" />';
		echo '<input type="hidden" class="assignments-id" name="' . $field['name'] . '[id]" id="' . $field['id'] .'-id" value="' . $field['values']['id'] . '" />';
		echo '</p>';
	}
}