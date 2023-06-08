<?php
/** 
 * Common Functionality
 * 
 * Control and process the frameworks plugin updates from GitLab
 * 
 * @since 7.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin's Security Header Generator
 * 
*/

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// Plugin Activation
register_activation_hook( WPSH_PATH . '/' . WPSH_FILENAME, function( $_network ) : void {

    // check the PHP version, and deny if lower than 7.3
    if ( version_compare( PHP_VERSION, '7.4', '<=' ) ) {

        // it is, so throw and error message and exit
        wp_die( __( '<h1>PHP To Low</h1><p>Due to the nature of this plugin, it cannot be run on lower versions of PHP.</p><p>Please contact your hosting provider to upgrade your site to at least version 7.3.</p>', 'security-header-generator' ), 
            __( 'Cannot Activate: PHP To Low', 'security-header-generator' ),
            array(
                'back_link' => true,
            ) );

    }

    // check if we tried to network activate this plugin
    if( is_multisite( ) && $_network ) {

        // we did, so... throw an error message and exit
        wp_die( 
            __( '<h1>Cannot Network Activate</h1><p>Due to the nature of this plugin, it cannot be network activated.</p><p>Please go back, and activate inside your subsites.</p>', 'security-header-generator' ), 
            __( 'Cannot Network Activate', 'security-header-generator' ),
            array(
                'back_link' => true,
            ) 
        );
    }

} );

// Plugin De-Activation
register_deactivation_hook( WPSH_PATH . '/' . WPSH_FILENAME, function( ) : void {

} );

// make sure this plugin is actually active before we do anything
if( in_array( WPSH_DIRNAME . '/' . WPSH_FILENAME, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    // make sure this function doesn't already exist
    if( ! function_exists( 'get_our_option' ) ) {

        /** 
         * get_our_option
         * 
         * The method is responsible for getting our options
         * 
         * @since 7.4
         * @access public
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param string $_opt The name of the option to retrieve
         * 
         * @return var Returns the value from the option
         * 
        */
        function get_our_option( string $_opt ) {

            // get the entire option set
            $_opts = ( get_option( 'wpsh_settings' ) ) ?? array( );

            // return the option, or null if it does not exist
            return $_opts[ $_opt ] ?? null;

        }  
        
    }

    // setup our autoload
    spl_autoload_register( function( $_cls ) : void {

        // reformat the class name to match the file name for inclusion
        $_class = strtolower( str_ireplace( '_', '-', $_cls ) );

        // check if we're in CLI or not
        if( defined( 'WP_CLI' ) ) {

            // we are, so let's pull the CLI classes
            $_path = WPSH_PATH . '/cli/work/' . $_class . '.php';
        } else {

            // we're not, so let's pull the non-cli classes
            $_path = WPSH_PATH . '/work/inc/' . $_class . '.php';
        }

        // if the file exists
        if( is_readable( $_path ) ) {

            // include it once
            include $_path;
        }

    } );

    // let's see if we're in CLI or not
    if( ! defined( 'WP_CLI' ) ) {

        // let's see if the main framework class exists
        if( ! class_exists( 'KPSHG' ) ) {

            // it does not, include the setup class
            require_once plugin_dir_path( WPSH_PATH . '/' . WPSH_FILENAME ) .'/vendor/custom-fields/classes/setup.class.php';
            
        }

        // hook into the plugin loaded
        add_action( 'kpshg_loaded', function( ) : void {

            // create the settings as well as the menu pages
            $_settings = new KCP_CSPGEN_Settings( );

            // run it
            $_settings -> kp_cspgen_settings( );

            // clean it up
            unset( $_settings );
            
        }, PHP_INT_MAX );

        // hack in some styling
        add_action( 'admin_enqueue_scripts', function( ) : void {

            // if WP_DEBUG is set and true
            if( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

                // register the unminified stylesheet
                wp_register_style( 'kpsh_css', plugins_url( '/assets/css/style.css', WPSH_PATH . '/' . WPSH_FILENAME ), null, null );

                // register the unminified script
                wp_register_script( 'kpsh_js', plugins_url( '/assets/js/script.js', WPSH_PATH . '/' . WPSH_FILENAME ), null, null );

            } else {

                // register the minifiedstylesheet
                wp_register_style( 'kpsh_css', plugins_url( '/assets/css/style.min.css', WPSH_PATH . '/' . WPSH_FILENAME ), null, null );

                // register the minified script
                wp_register_script( 'kpsh_js', plugins_url( '/assets/js/script.min.js', WPSH_PATH . '/' . WPSH_FILENAME ), null, null );

            }

            // enqueue it
            wp_enqueue_style( 'kpsh_css' );

            // enqueue it
            wp_enqueue_script( 'kpsh_js' );
            
        }, PHP_INT_MAX );

        // bring in our header functionality and apply them
        $_headers = new KCP_CSPGEN_Headers( );

        // run it
        $_headers -> kp_process_headers( );

        // dump it
        unset( $_headers );

    } else {

        // fire off our CLI implementation
        new KCP_CSPGEN_CLI( );

    }

}
