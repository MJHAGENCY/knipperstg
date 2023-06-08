<?php
/** 
 * CLI Requests
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
if( ! class_exists( 'KCP_CSPGEN_CLI_Requests' ) ) {

    /** 
     * Class KCP_CSPGEN_CLI_Requests
     * 
     * The actual class for performing the "remote" requests necessary to process
     * 
     * @since 7.4
     * @access public
     * @author Kevin Pirnie <me@kpirnie.com>
     * @package Kevin's Security Header Generator
     * 
     * @property string $ba_user A string to hold the basic auth username
     * @property string @ba_pass A string to hold the basic auth password
     * 
    */
    class KCP_CSPGEN_CLI_Requests {

        // setup a couple internal properties
        private string $ba_user;
        private string $ba_pass;

        // fire us up
        public function __construct( ) {

            // populate the internal properties
            $this -> ba_user = ( get_our_option( 'auth_un' ) ) ?? null;
            $this -> ba_pass = ( get_our_option( 'auth_pw' ) ) ?? null;

        }

        /** 
         * cli_requests
         * 
         * The method is responsible for requesing the public pages in the site
         * 
         * @since 7.4
         * @access public
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param array $_pages An array of the public "pages" to be requested
         * 
         * @return void This method does not return anything
         * 
        */
        public function cli_requests( array $_pages ) : array {

            // hold our return
            $_ret = array( );

            // loop over the pages and pull them
            foreach( $_pages as $_url ) {

                // perform the request
                $_page_body = $this -> cli_curl( $_url );

                // now that we have the body we need to parse it, so do just that
                $_parser = new KCP_CSPGEN_CLI_Parser( );

                // setup the return property, make sure they are unique
                $_ret[] = $_parser -> parse( $_page_body );

            }

            // return the array
            return $_ret;
        }

        /** 
         * cli_curl
         * 
         * The method is responsible for performing the actual remote requests
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_url The URL to be requested
         * 
         * @return string Returns a nullable string of the HTML content of the page/post requested
         * 
        */
        private function cli_curl( string $_url ) : ?string {

            // hold our response string
            $_resp = '';

            // setup our arguments
            $_args = array(
                'timeout' => 20,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0',
            );

            // if we need to authenticate, merge that header in
            if( $this -> ba_user && $this -> ba_pass ) {

                // merge the auth header
                $_args = array_merge( $_args, array(
                    'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode( $this -> ba_user . ':' . $this -> ba_pass )
                    )
                ) );
            }

            // make the request
            $_req = wp_remote_get( $_url, $_args );

            // make sure we have a valid response code before proceeding
            if( wp_remote_retrieve_response_code( $_req ) == 200 ) {

                // we do, so toss in our response
                $_resp = wp_remote_retrieve_body( $_req );
            }

            // return our response
            return $_resp;

        }

    }

}
