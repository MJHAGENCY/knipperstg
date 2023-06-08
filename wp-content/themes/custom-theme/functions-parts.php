<?php

class Em_Parts extends Em_Theme
{
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Retrieve site logo
 	 *
 	 * @echo variable $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function get_logo()
	{
		if ( $logo = get_field('opts_logo', 'options') ) :
			//! DEFINE ALL LOGO OPTIONS
			$logo_2x = get_field('opts_logo_2x', 'options');
			$mob_logo = get_field('opts_logo_mob', 'options');	
			$mob_logo_2x = get_field('opts_logo_mob_2x', 'options');

			$html .= '
				<a class="logo" href="' . home_url() . '">
					<picture>
						<!--[if IE 9]><video style="display: none;"><![endif]-->
						<source srcset="' . $logo['url'] . ( $logo_2x ? ', ' . $logo_2x['ur'] . ' 2x' : '') .'" media="(min-width: 651px)">';
						if ( $mob_logo ) $html .= '<source srcset="' . $mob_logo['url'] . ( $mob_logo_2x ? ', ' . $mob_logo_2x['ur'] . ' 2x' : '') .'" media="(min-width: 0)">';
						$html .= '<!--[if IE 9]></video><![endif]-->
						<img src="' . $logo['sizes']['lowres'] . '" alt="' .$logo['alt'] . '" />
					</picture>
				</a>';
			echo $html;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Retrieve the post title
 	 *
 	 * @param boolean $echo | determines if the result is to be echoed or returned
 	 *
 	 * @return variable $html
 	 * @echo variable $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function get_page_title( $echo = true )
	{
		if ( is_search() ) {
			$title = 'Search results: <span>' . get_query_var('s') . '</span>';
		} elseif ( is_404() ) {
			$title = 'Page Not Found';
		} else {
			$title = ( $title = get_field('int_alt_page_title') ) ? $title : get_the_title();
		}

		if ( $echo ) :
			echo '<h1>' . $title . '</h1>';
		else :
			return $title;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Retrieve the associated image for a post
 	 *
 	 * @param array $args | See $defaults for expected parameters
 	 *
 	 * @return variable $html
 	 * @echo variable $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
 	 
	function get_associated_image( $args )
	{
		//! SET DEFAULT ARGUMENTS
		$defaults = array(
			'postID' => get_the_ID(),
			'size' => 'thumbnail',
			'class' => false,
			'echo' => false
		);
		//! APPLY DEFAULT ARGUMENTS WHERE NEEDED
		$args = wp_parse_args( $args, $defaults );
		extract($args);

		$is_object = false;
		$post_type = get_post_type($postID);

		if ( has_post_thumbnail($postID) ) :
			$image = get_the_post_thumbnail( $postID, $size );	
		endif;
		
		switch ( $post_type ) :
			case 'document':
				$image = get_field('document_thumbnail', $postID);
				$is_object = true;
				break;
			case 'event' :
				$image = get_field('associated_image', $postID);
				$is_object = true;
				break;
			case 'news-item' :
				$image = get_field('associated_image', $postID);
				$is_object = true;
				break;
		endswitch;

		if ( $image ) :
			if ( $is_object ) $image = '<img src="' . $image['sizes'][$size] . '" alt="' . $image['alt'] . '" />';
			
			$html .= '<div ' . ( $class ? 'class="' . $class . '"' : '' ) . '>' . $image . '</div>';
				
			if ( $echo ) :
				echo $html;
			else :
				return $html;	
			endif;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Return or echo the social media links defined in Site Option 
 	 *
 	 * @param boolean $echo
 	 *
 	 * @return variable $html
 	 * @echo variable $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
 	 
	function get_social_media( $echo = true )
	{
		if ( $social = get_field('opts_social_media', 'options') ) :
			$html .= '<ul class="social-container">';
			foreach ( $social as $sm ) :
				$sm_type = $sm['opts_sm_type'];
				$sm_url = $sm['opts_sm_url'];
				switch ( $sm_type ) :
					case 'fb' :
						$sm_icon = '&#xf09a;';
						break;
					case 'g' :
						$sm_icon = '&#xf0d5;';
						break;
					case 'ig' :
						$sm_icon = '&#xf16d;';
						break;
					case 'li' :
						$sm_icon = '&#xf0e1;';
						break;
					case 'pt' :
						$sm_icon = '&#xf231;';
						break;
					case 'tw' :
						$sm_icon = '&#xf099;';
						break;
					case 'rss' :
						$sm_icon = '&#xf09e;';
						break;
					case 'vm' :
						$sm_icon = '&#xf194;';
						break;
					case 'yt' :
						$sm_icon = '&#xf167;';
						break;
				endswitch;

				$html .= '<li  ' . ( $sm_type == 'yt' ? 'data-yt="' . esc_url($sm_url) . '"' : '' ) . '>';
					$html .= '<a class="fa ' . $sm_type . '" target="_blank" href="' . esc_url($sm_url) . '">' . $sm_icon . '</a>';
				$html .= '</li>';
			endforeach;			
			$html .= '</ul>';
		
			if ( $echo ) :
				echo $html;
			else :
				return $html;
			endif;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Retrieve the intended excerpt per post type.   
 	 * This function is intended to be used if the post type is unknown (Ie. search results)
 	 * 
 	 * @param boolean $echo
 	 *
 	 * @return variable $em_excerpt
 	 * @echo string $em_excerpt
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function em_excerpt( $echo = false ) 
	{
		$post_type = get_post_type();
		$em_excerpt = get_the_excerpt();
		
		switch ( $post_type ) :
			case 'document' :
				$em_excerpt = get_field('document_short_description');
				break;
			case 'event' :
				//! ADD LOCATION IF APPLICABLE
				if ( $event_location = get_field('location') ) $excerpt = '<em>' . $event_location . '</em><br />';
				$em_excerpt .= get_field('excerpt');
				break;
			case 'news-item' :
				$em_excerpt = get_field('excerpt');
				break;
			case 'career' : 
				$em_excerpt = static::acf_excerpt('career_full_description');
				break;
		endswitch;

		if ( $echo ) :
			return apply_filters('the_excerpt', $em_excerpt);
		else :
			return $em_excerpt;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Retrieve the intended excerpt per post type.   
 	 * This function is intended to be used if the post type is unknown (Ie. search results)
 	 * 
 	 * @param boolean $echo
 	 *
 	 * @return variable $em_excerpt
 	 * @echo string $em_excerpt
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function em_link( $echo = false ) 
	{
		$post_type = get_post_type();
		$em_link = array(
			'url' => get_permalink(),
			'target' => '_self',
		);
		
		switch ( $post_type ) :
			case 'document' :
				$em_link['url'] = $em_link['url'] . '?dl=1';
				if ( get_field('document_require_registration') ) :
					$em_link['target'] = '_self';
				else :
					$em_link['target'] = '_blank';
				endif;
				break;
			case 'event' :
				if ( get_field('is_external_link') ) :
					$em_link['url'] = get_field('external_link');
					$em_link['target'] = '_blank';
				endif;
				break;
			case 'news-item' :
				if ( get_field('is_external_link') ) :
					$em_link['url'] = get_field('external_link');
					$em_link['target'] = '_blank';
				endif;
				break;
		endswitch;

		if ( $echo ) :
			echo $em_link;
		else :
			return $em_link;
		endif;
	}
	
	/*-----------------------------------------------------------------------------------------------------------
	 *
 	 * Turn any Text, Text Area or WYSIWYG Advanced Custom Field into an excerpt. 
 	 * 
 	 * @param string $field_name | the label of the field to retrieve
 	 * @param boolean $echo
 	 *
 	 * @return variable $html
 	 * @echo string $html
 	 *
 	 *-----------------------------------------------------------------------------------------------------------*/
	
	function acf_excerpt( $field_name, $echo = true ) {
		$content = get_field($field_name);
		if ( '' != $content ) :
			$content = strip_shortcodes($content);
			$content = wp_strip_all_tags($content);
			$content = str_replace(']]>', ']]>', $content);
			$excerpt_length = Em_Theme::$excerpt_length;
			$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
			$acf_excerpt = wp_trim_words( $content, $excerpt_length, $excerpt_more );
		endif;

		if ( $echo ) :
			return apply_filters('the_excerpt', $acf_excerpt);
		else :
			return $acf_excerpt;
		endif;
	}
	
	
	
	
	
	public static function get_careers( $email, $email_text )
	{
		$careers = json_decode(static::get_careers_data('http://www.mypharmahost.com/KnipperCareers/JobsListing.svc/getJobsData/', JSON_UNESCAPED_UNICODE));
		
		if( empty($careers) )
		{
			echo '<h2>No job postings available at this time. Please check back soon.</h2>';
			return;
		}
		
		$careers = $careers->GetJobsDataResult;
		
		$output = '';
			
		foreach( $careers as $career )
		{
			$output .= '<div class="career">';
			$output .= '<h2>' . $career->JobName . '</h2>';
			$output .= '<h4>Job Description:</h4><p>' . static::line_break($career->Description) . '</p>';
			$output .= '<h4>Openings: ' . ( $career->Openings ?: 0 ) . '</h4><br>';
			$output .= '<h4>Requirements:</h4><p>' . static::line_break($career->Requirements) . '</p>';
			$output .= '<h4>Education:</h4><p>' . static::line_break($career->Education) . '</p>';
			$output .= $email ? '<a href="mailto:' . $email . '?subject=Careers at Knipper: ' . $career->JobName . '">' . $email_text . '</a>' : '';
			$output .= '</div>';
		}
		
		return $output;
	}
	
	
	
	
	
	public static function line_break( $content )
	{
		return $content;
	}
	
	
	
	
	
	public static function get_careers_data( $url )
	{
		$cache_file = __DIR__ . '/careers.json';
				
		// Do we have a cache?
		if( file_exists($cache_file) && filemtime($cache_file) > strtotime('-5 minutes') )
			return file_get_contents($cache_file);
			
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
				
		file_put_contents($cache_file, $data);
		
		return $data;
	}
}

?>