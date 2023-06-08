<?php
/**
* Returns word count of the sentences.
*
* @since @since flare 1.0.0
*/
if ( ! function_exists( 'flare_words_count' ) ) :
	function flare_words_count( $length = 25, $flare_content = null ) {
		$length = absint( $length );
		$source_content = preg_replace( '`\[[^\]]*\]`', '', $flare_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '...' );
		return $trimmed_content;
	}
endif;