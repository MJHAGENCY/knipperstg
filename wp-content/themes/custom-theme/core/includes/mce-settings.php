<?php

/*--------------------------------------------------------------------------------------
 *
 * The valid elements
 *
 *--------------------------------------------------------------------------------------*/
 
$settings['extended_valid_elements'] = '*[*]';

/*--------------------------------------------------------------------------------------
 *
 * Populates the formats dropdown menu
 *
 *--------------------------------------------------------------------------------------*/

$settings['style_formats'] = json_encode(array(
	array(
		'selector' => '*',
		'title' => 'Float Left',
		'classes' => 'alignleft',
	),
	array(
		'selector' => '*',
		'title' => 'Float Right',
		'classes' => 'alignright',
	),
	array(
		'selector' => '*',
		'title' => 'Display Inline',
		'classes' => 'force-inline',
	),
	array(
		'selector' => '*',
		'title' => 'Display Inline-Block',
		'classes' => 'force-inline-block',
	),
	array(
		'selector' => 'a',
		'title' => 'More Link',
		'classes' => 'more',
	),
	array(
		'selector' => 'a',
		'title' => 'Primary Button',
		'classes' => 'button',	
	),
	array(
		'selector' => 'a',
		'title' => 'Secondary Button',
		'classes' => 'alt-button',	
	),
	array(
		'selector' => '*',
		'title' => 'Footer Text',
		'classes' => 'small',
	),
	array(
		'selector' => 'ul, ol',
		'title' => 'No List Style',
		'classes' => 'no-list-style',
	),
	array(
		'selector' => 'img',
		'title' => 'No Border',
		'classes' => 'no-border',
	),
	array(
		'selector' => 'ul',
		'title' => 'Two Column List',
		'classes' => 'two-column',
	),
	array(
		'selector' => 'ul',
		'title' => 'Three Column List',
		'classes' => 'three-column',
	),
	array(
		'selector' => 'table',
		'title' => 'Tabular Data',
		'classes' => 'data',
	),
	array(
		'selector' => '*',
		'title' => 'No Image Wrap',
		'styles' => array(
			'overflow' => 'hidden',
		),
	),
	array(
		'selector' => 'h2, h3, p, ul, ol',
		'title' => 'Clear Floats',
		'styles' => array(
			'clear' => 'both',
		),
	),
	array(
		'selector' => 'h1, h2, h3, p, ul, ol',
		'title' => 'Blue Text',
		'styles' => array(
			'color' => '#00529c',
		),
	),
));