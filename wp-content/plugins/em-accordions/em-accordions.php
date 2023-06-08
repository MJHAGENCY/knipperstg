<?php

/*
Plugin Name: EM Accordions
Description: Easily create accordions in your content
Version: 0.0.1
Author: eMagine
Author URI: http://www.emagineusa.com/
License: GPL2
	
Copyright 2013 eMagine (email : info@emagineusa.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

Em_Accordions::init();

class Em_Accordions
{
	//! @static string $base_url The base url of the plugin
	static $base_url;
	
	//! @static string $version
	static $version = '0.0.1';
	

	function init()
	{
		self::$base_url = plugins_url('', __FILE__);
		
		if ( !is_admin() )
		{	// front end hooks
			add_shortcode('accordion', array(__CLASS__, 'do_accordion'));
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_styles'));
		}
	}
	
	function do_accordion( $atts, $content = null )
	{
		extract(shortcode_atts(array('title' => ''), $atts));
		$id =  sanitize_title_with_dashes($title);
		return '<div id="accordion-'. $id .'" class="accordion">' .
				 '<a class="accordion-label" href="#'. $id .'">'. $title .'</a>' .
				 '<div class="accordion-content">'. do_shortcode($content) . '</div>' .
				 '</div>';
	}
	
	function enqueue_scripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('em-accordions', self::$base_url .'/em-accordions.js', array('jquery'), self::$version);
	}
	
	function enqueue_styles()
	{
		wp_enqueue_style('em-accordions', self::$base_url .'/em-accordions.css', array(), self::$version);	
	}
}