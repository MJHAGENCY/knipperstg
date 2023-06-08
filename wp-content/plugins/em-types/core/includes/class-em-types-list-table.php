<?php

class Em_Types_List_Table extends WP_List_Table
{
	function __construct()
	{
		//Set parent defaults
		parent::__construct(array(
		   'singular'  => 'type',     // singular name of the listed records
		   'plural'    => 'types',    // plural name of the listed records
		   'ajax'      => false       // does this table support ajax?
		));
   }
   
   function column_title( $item )
   {
   	$actions = array(
      	'edit' => '<a href="?page='. $_GET['page'] .'&action=edit&slug='. $item['slug'] .'&type='. $item['type'] .'">Edit</a>',
         'delete' => '<a href="?page='. $_GET['page'] . '&action=delete&slug='. $item['slug'] .'&type='. $item['type'] .'">Delete</a>',
      );
      
      return '<strong><a class="row-title" href="?page='. $_GET['page'] .'&action=edit&slug='. $item['slug'] .'&type='. $item['type'] .'">'. $item['title'] .'</a></strong>'. $this->row_actions($actions);
   }
   
   function column_type( $item )
   {
	   return ucwords(str_replace('_', ' ', $item['type']));
   }
   
   function column_default( $item, $column )
   {
	   return $item[$column];
   }
   
   function get_columns()
   {
		$columns = array(
		   'title' => 'Name',
		   'type' => 'Type',
		   'slug' => 'Slug',
		);
		return $columns;
   }
   
   function prepare_items()
   {
		/**
		* First, lets decide how many records per page to show
		*/
		$per_page = 25;
		
		
		/**
		* REQUIRED. Now we need to define our column headers. This includes a complete
		* array of columns to be displayed (slugs & titles), a list of columns
		* to keep hidden, and a list of columns that are sortable. Each of these
		* can be defined in another method (as we've done here) before being
		* used to build the value for our _column_headers property.
		*/
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		
		
		/**
		* REQUIRED. Finally, we build an array to be used by the class for column 
		* headers. The $this->_column_headers property takes an array which contains
		* 3 other arrays. One for all columns, one for hidden columns, and one
		* for sortable columns.
		*/
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		
		/**
		* Instead of querying a database, we're going to fetch the example data
		* property we created for use in this plugin. This makes this example 
		* package slightly different than one you might build on your own. In 
		* this example, we'll be using array manipulation to sort and paginate 
		* our data. In a real-world implementation, you will probably want to 
		* use sort and pagination data to build a custom query instead, as you'll
		* be able to use your precisely-queried data immediately.
		*/
		$post_types = get_option('custom_post_types');
		$taxonomies = get_option('custom_taxonomies');
		$data = array();
		$types = array();
		
		if ( is_array($post_types) )
		{
			$types += $post_types;
		}
		
		if ( is_array($taxonomies) )
		{
			$types += $taxonomies;
		}
		
		ksort($types);
		
		foreach ( $types as $slug => $pt )
		{
			$data[] = array(
				'slug' => $slug,
				'title' => $pt['name'],
				'type' => array_key_exists($slug, $post_types) ? 'post_type' : 'taxonomy',
			);
		}
		       
		       
		/**
		* REQUIRED for pagination. Let's figure out what page the user is currently 
		* looking at. We'll need this later, so you should always include it in 
		* your own package classes.
		*/
		$current_page = $this->get_pagenum();
		
		/**
		* REQUIRED for pagination. Let's check how many items are in our data array. 
		* In real-world use, this would be the total number of items in your database, 
		* without filtering. We'll need this later, so you should always include it 
		* in your own package classes.
		*/
		$total_items = count($data);
		
		
		/**
		* The WP_List_Table class does not handle pagination for us, so we need
		* to ensure that the data is trimmed to only the current page. We can use
		* array_slice() to 
		*/
		$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		
		
		
		/**
		* REQUIRED. Now we can add our *sorted* data to the items property, where 
		* it can be used by the rest of the class.
		*/
		$this->items = $data;
		
		
		/**
		* REQUIRED. We also have to register our pagination options & calculations.
		*/
		$this->set_pagination_args(array(
		   'total_items' => $total_items,                  //WE have to calculate the total number of items
		   'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
		   'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
		));
   }
}