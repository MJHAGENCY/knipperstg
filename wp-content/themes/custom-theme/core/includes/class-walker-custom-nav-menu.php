<?php

class Walker_Custom_Nav_Menu extends Walker_Nav_Menu
{
	var $is_hidden = false;
	
	function start_el( &$output, $item, $depth, $args )
	{
		if ( in_array('hidden', $item->classes) ) {
			$this->is_hidden = true;
			return;
		}
			
		parent::start_el($output, $item, $depth, $args);
	}
	
	function end_el( &$output, $item, $depth )
	{
		if ( $this->is_hidden ) {
			$this->is_hidden = false;
			return;
		}
		
		parent::end_el($output, $item, $depth);
	}
}