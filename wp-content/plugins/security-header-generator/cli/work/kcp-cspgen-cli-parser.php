<?php
/** 
 * CLI Site Parser
 * 
 * Parses HTML
 * 
 * @since 7.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin's Security Header Generator
 * 
*/

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// we also only want to allow CLI access
defined( 'WP_CLI' ) || die( 'Only CLI access allowed' );

// make sure the class doesn't already exist
if( ! class_exists( 'KCP_CSPGEN_CLI_Parser' ) ) {

    /** 
     * Class KCP_CSPGEN_CLI_Parser
     * 
     * The actual class for parsing HTML content
     * 
     * @since 7.4
     * @access public
     * @author Kevin Pirnie <me@kpirnie.com>
     * @package Kevin's Security Header Generator
     * 
    */
    class KCP_CSPGEN_CLI_Parser {

        /** 
         * parse
         * 
         * The method is responsible for parsing HTML documents
         * 
         * @since 7.4
         * @access public
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_body The html content to parse
         * 
         * @return array Returns a categorized array of external resources
         * 
        */
        public function parse( string $_body ) : array {

            // if we have an empty body string, there's nothing to parse, so just skip it
            if( empty( $_body ) ) {

                // return an empty array here, since we are declaring the return type of this method
                return array( );
            }

            // setup the returnable array
            $_tmp = array( );

            // setup an array of html tags we want to look for
            $_tags = array( 'form', 'link', 'script', 'frame', 'iframe', 'img', 'object', 'video', 'audio', 'embed', 'source' );

            // lets hold a few more
            $_tmp[ 'generate_csp_custom_styles' ] = array( );
            $_tmp[ 'generate_csp_custom_scripts' ] = array( );
            $_tmp[ 'generate_csp_custom_connect' ] = array( );
            $_tmp[ 'generate_csp_custom_frames' ] = array( );
            $_tmp[ 'generate_csp_custom_media' ] = array( );
            $_tmp[ 'generate_csp_custom_fonts' ] = array( );
            $_tmp[ 'generate_csp_custom_images' ] = array( );
            $_tmp[ 'generate_csp_custom_defaults' ] = array( );
            $_tmp[ 'generate_csp_custom_forms' ] = array( );
            
            // let's utilize the built-in PHP DomDocument to parse
            $_doc = new DOMDocument( );

            // silence
            libxml_use_internal_errors( true );

            // load our html into the parser
            $_doc -> loadHTML( $_body );

            // clear the libxml errors
            libxml_clear_errors( );

            // we need to loop the tags array and process it
            foreach( $_tags as $_tag) {

                // get the element
                $_elements = $_doc -> getElementsByTagName( $_tag );

                // make sure it actually exists before proceeding
                if( $_elements ) {

                    // loop over the elements
                    foreach( $_elements as $_element ) {

                        // switch for the proper URL's to gather based on the tag we're looking at
                        switch( $_tag ) {
                            case 'img': // images
                                array_push( $_tmp['generate_csp_custom_images'], $_element -> getAttribute( 'src' ) );
                                break;
                            case 'form': // forms
                                array_push( $_tmp['generate_csp_custom_forms'], $_element -> getAttribute( 'action' ) );
                                break;
                            case 'script': // scripts
                                array_push( $_tmp['generate_csp_custom_scripts'], $_element -> getAttribute( 'src' ) );
                                break;
                            case 'link':

                                // check if the link is a stylesheet or something else
                                if( $_element -> getAttribute( 'rel' ) == 'stylesheet' ) {

                                    // it is, so push or to the styles array
                                    array_push( $_tmp['generate_csp_custom_styles'], $_element -> getAttribute( 'href' ) );

                                    // lets parse through the CSS and see if we have any fonts
                                    $this -> parse_css_for_fonts( $_element -> getAttribute( 'href' ), $_tmp );

                                } elseif( ( $_element -> getAttribute( 'rel' ) == 'icon' ) || ( $_element -> getAttribute( 'rel' ) == 'apple-touch-icon-precomposed' ) || ( $_element -> getAttribute( 'rel' ) == 'apple-touch-icon' ) ) {

                                    // it's a favicon
                                    array_push( $_tmp['generate_csp_custom_images'], $_element -> getAttribute( 'href' ) );
                                } elseif( ( $_element -> getAttribute( 'rel' ) != 'shortlink' ) && ( $_element -> getAttribute( 'rel' ) != 'sitemap' ) ) {

                                    // it's something to connect
                                    array_push( $_tmp['generate_csp_custom_connect'], $_element -> getAttribute( 'href' ) );
                                }
                                break;
                            case 'frame':
                            case 'iframe': //frames
                                array_push( $_tmp['generate_csp_custom_frames'], $_element -> getAttribute( 'src' ) );
                                break;
                            case 'video':
                            case 'audio':
                            case 'source':
                                array_push( $_tmp['generate_csp_custom_media'], $_element -> getAttribute( 'src' ) );
                                break;
                            case 'embed':
                                // we'll need to check what type of file is being included in the source
                                $_src = $_element -> getAttribute( 'src' );

                                // parse the embed
                                $this -> parse_objectembed( $_src, $_tmp );
                                break;
                            case 'object':
                                // we'll need to check what type of file is being included in the source
                                $_src = $_element -> getAttribute( 'data' );

                                // parse the emdbed
                                $this -> parse_objectembed( $_src, $_tmp );
                                break;
                        }

                    }

                }

            }

            // release the dom parser
            unset( $_doc );

            // return it
            return $_tmp;
        }

        /** 
         * parse_css_for_fonts
         * 
         * The method is responsible for attempting to parse CSS for Fonts
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_src The CSS to be parsed
         * @param array &$_arr The parsed array of external fonts
         * 
         * @return void This method does not return anything
         * 
        */
        private function parse_css_for_fonts( string $_src, array &$_arr ) : void {

            // make sure the URL has the protocol
            if( ! parse_url( $_src, PHP_URL_SCHEME ) ) {
                $_src = 'https:' . $_src;
            }

            // request the file
            $_resp = wp_safe_remote_get( $_src, array( 'timeout' => 45, ) );

            // make sure we have a resposne
            if( is_array( $_resp ) && ! is_wp_error( $_resp ) ) {

                // populate the body so we can parse the CSS
                $_body = $_resp['body'];

                // hold the matches
                $_matches = array( );

                // try to get all our font-faces
                preg_match_all( '/^(.*):\s*(.*)\s*;$/m', $_body, $_matches, PREG_SET_ORDER );

                // hold our parsed out info
                $_parsed = array( );

                // setup a count
                $_ct = 0;

                // loop over the matches
                foreach( $_matches as $_k => $_match ) {

                    // if we've got the first item, setup something to hold a temporary array
                    if($_ct === 0) {
                        // here
                        $tmp = array();
                    }

                    $tmp[ trim( $_match[ 1 ] ) ] = trim( $_match[ 2 ] );

                    if ( $_ct === 3 ) {

                        $_parsed[] = $tmp;
                        $_ct = 0;

                    } else {

                        $_ct ++;

                    }
                }

                // parse through the parsed out fonts
                if( count( $_parsed ) > 0 ) {

                    // loop over them
                    foreach( $_parsed as $_p ) {

                        // force numeric indeces
                        $_p = array_values( $_p );

                        // the temp source
                        $_tmp_source = ( $_p[3] ) ?? null;

                        // parse the hostname out
                        $_host = parse_url( $_tmp_source, PHP_URL_HOST );

                        // append the host to the array
                        array_push( $_arr['generate_csp_custom_fonts'], $_host );

                    }
                }

            }

        }

        /** 
         * parse_objectembed
         * 
         * The method is responsible for attempting to parse external objects
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_src The CSS to be parsed
         * @param array &$_arr The parsed array of external objects
         * 
         * @return void This method does not return anything
         * 
        */
        protected function parse_objectembed( string $_src, array &$_arr ) : void {

            // set a default
            $_content_type = 'plain/text';

            // set a couple arrays to hold mimetypes
            $_img = array( 'image/png', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/gif', 'image/tiff', 'image/svg+xml', 'image/webp', 'image/apng', 'image/x-icon' );
            $_media = array( 'audio/wave', 'audio/wav', 'audio/x-wav', 'audio/x-pn-wav', 'audio/webm', 'audio/ogg', 'application/ogg', 'video/ogg', 'video/webm', 'video/x-flv', 'video/mp4', 'application/x-mpegURL', 'video/MP2T', 'video/3gpp', 'video/quicktime', 'video/mkv', 'video/x-msvideo', 'video/x-ms-wmv' );
            $_css = array( 'text/css' );
            $_script = array( 'application/javascript', 'application/ecmascript', 'application/x-ecmascript', 'application/x-javascript', 'text/javascript', 'text/ecmascript', 'text/javascript1.0', 'text/javascript1.1', 'text/javascript1.2', 'text/javascript1.3', 'text/javascript1.4', 'text/javascript1.5', 'text/jscript', 'text/livescript', 'text/x-ecmascript', 'text/x-javascript' );

            // make a request for just the headers on this item.  we only need the content-type returned
            $_r = wp_remote_head( $_src );

            // as long as its valid response
            if ( is_array( $_r ) && ! is_wp_error( $_r ) ) {

                // get the content type
                $_content_type = wp_remote_retrieve_header( $_r, 'content-type' );

                // check if we're in the image array
                if( in_array( $_content_type, $_img ) ) {

                    // yep, we have an image
                    array_push( $_arr['generate_csp_custom_images'], $_src );
                } elseif( in_array( $_content_type, $_media ) ) { // check if we're in the media array

                    // yep, we have media
                    array_push( $_arr['generate_csp_custom_media'], $_src );
                } elseif( in_array( $_content_type, $_css ) ) { // check if we're in the css array

                    // yep, we have css
                    array_push( $_arr['generate_csp_custom_styles'], $_src );
                } elseif( in_array( $_content_type, $_script ) ) { // check if we're in the script array

                    // yep, we have script
                    array_push( $_arr['generate_csp_custom_scripts'], $_src );
                } else {

                    // throw it in the default
                    array_push( $_arr['generate_csp_custom_defaults'], $_src );
                }

            }

        }

    }

}
