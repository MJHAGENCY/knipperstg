<?php
/** 
 * Common CLI Methods
 * 
 * Set of common methods for the CLI
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
if( ! class_exists( 'KCP_CSPGEN_CLI_Common' ) ) {

    /** 
     * Class KCP_CSPGEN_CLI_Common
     * 
     * The actual class for generating the common methods
     * 
     * @since 7.4
     * @access public
     * @author Kevin Pirnie <me@kpirnie.com>
     * @package Kevin's Security Header Generator
     * 
    */
    class KCP_CSPGEN_CLI_Common {

        /** 
         * cli_success
         * 
         * The method is responsible for displaying a success message in CLI
         * 
         * @since 7.4
         * @access public
         * @static
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_msg The message to display
         * 
         * @return void This method does not return anything
         * 
        */
        public static function cli_success( string $_msg ) : void {

            // throw the error
            WP_CLI::success( __( $_msg ) );

        }

        /** 
         * cli_error
         * 
         * The method is responsible for displaying a error message in CLI
         * 
         * @since 7.4
         * @access public
         * @static
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_msg The message to display
         * 
         * @return void This method does not return anything
         * 
        */
        public static function cli_error( string $_msg ) : void {

            // throw the error
            WP_CLI::error( __( $_msg ) );

        }

        /** 
         * cli_warning
         * 
         * The method is responsible for displaying a warning message in CLI
         * 
         * @since 7.4
         * @access public
         * @static
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_msg The message to display
         * 
         * @return void This method does not return anything
         * 
        */
        public static function cli_warning( string $_msg ) : void {

            // throw the error
            WP_CLI::warning( __( $_msg ) );

        }

    }

}
