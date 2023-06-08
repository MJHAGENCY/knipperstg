<?php
/** 
 * CLI Functionality
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
if( ! class_exists( 'KCP_CSPGEN_CLI' ) ) {

    /** 
     * Class KCP_CSPGEN_CLI
     * 
     * The actual class for tying everything together
     * 
     * @since 7.4
     * @access public
     * @author Kevin Pirnie <me@kpirnie.com>
     * @package Kevin's Security Header Generator
     * 
    */
    class KCP_CSPGEN_CLI {

        // fire us up
        public function __construct( ) {

            if( class_exists( '\WP_CLI' ) ) { 

                // create our generate command
                WP_CLI::add_command( 'csp generate', function( ) : void {

                    // fire up the processor
                    $_processor = new KCP_CSPGEN_CLI_Process( );
                    $_processor -> cli_process( );

                    // no need to hold on to this, so get rid of it
                    unset( $_processor ); 

                } );

            }

        }

    }

}
