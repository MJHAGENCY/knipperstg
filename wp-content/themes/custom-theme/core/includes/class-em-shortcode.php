<?php

class Em_Shortcode
{
	/*--------------------------------------------------------------------------------------
	 *
	 * Constructor
	 *
	 * @author jcowher
	 * @since 1.0.0
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	public function __construct( $shortcode )
	{
		add_shortcode($shortcode['name'], array($this, $shortcode['callback']));
	}
	
	
	
	
	
	public function do_careers( $atts )
	{
		extract(shortcode_atts(array(
			'email' => '',
			'email_text' => 'Email Us'
		), $atts));
		
		return Em_Parts::get_careers($email, $email_text);
	}
}