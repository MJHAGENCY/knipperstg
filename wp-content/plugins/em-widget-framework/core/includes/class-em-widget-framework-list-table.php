<?php

/**
 * Load the base class
 */

if ( ! class_exists('WP_List_Table') )
{
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Em_Widget_Framework_List_Table extends WP_List_Table
{
    /*--------------------------------------------------------------------------------------
	 *
	 * Constructor function
	 *
	 * @param array $args
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function __construct()
	{
		parent::__construct(array(
			'singular' => 'Custom Widget',
			'plural' => 'Custom Widgets',
			'ajax' => false,
		));
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Displays the title column
	 *
	 * @param array $item The current row's data
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function column_title($item)
	{
		// build row actions
		$actions = array(
			'edit' => '<a href="?page=' . $_REQUEST['page'] . '&action=edit&ID=' . $item['ID'] . '">Edit</a>',
			'delete' => '<a href="?page=' . $_REQUEST['page'] . '&action=delete&ID=' . $item['ID'] . '">Delete</a>',
		);
		
		// return the column content
		return '<a href="?page=' . $_REQUEST['page'] . '&action=edit&ID=' . $item['ID'] . '">' . $item['title'] . '</a>' . $this->row_actions($actions);
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Displays the checkbox column
	 *
	 * @param array $item The current row's data
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function column_cb($item)
	{
		return '<input type="checkbox" name="' . $this->_args['singular'] . '[]" value="' . $item['ID'] . '" />';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Displays the checkbox column
	 *
	 * @param array $item The current row's data
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function column_filepath( $item )
	{
		return str_replace(home_url(), '', get_stylesheet_directory_uri()) . '/' . $item['filepath'];
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Define the table's columns
	 *
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function get_columns()
	{
		return array(
			'title' => 'Class Name',
			'filepath' => 'File Path',
		);
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Define the bulk actions for the actions dropdown
	 * 
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function get_bulk_actions()
	{
		return array();
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Prepare the table data
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function prepare_items()
	{
		/**
		 * Define column headers
		 */
		 
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		
		/**
		 * Setup column headers
		 */
		
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		/**
		 * Fetch data
		 */
		
		$settings = get_option('em_widget_framework');
		
		$this->items = array();
		
		if ( isset($settings['widgets']) )
		{
			foreach ( (array) $settings['widgets'] as $k => $widget )
			{
				$this->items[] = array(
					'ID' => $k,
					'title' => $widget['classname'],
					'filepath' => $widget['filepath'],
				);
			}
		}
	}
}