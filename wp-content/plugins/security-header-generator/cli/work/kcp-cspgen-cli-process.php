<?php
/** 
 * CLI Site Processor
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
if( ! class_exists( 'KCP_CSPGEN_CLI_Process' ) ) {

    /** 
     * Class KCP_CSPGEN_CLI_Process
     * 
     * The actual class for processing the site
     * 
     * @since 7.4
     * @access public
     * @author Kevin Pirnie <me@kpirnie.com>
     * @package Kevin's Security Header Generator
     * 
    */
    class KCP_CSPGEN_CLI_Process {

        /** 
         * cli_process
         * 
         * The method is responsible for processing the site
         * 
         * @since 7.4
         * @access public
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @return void This method does not return anything
         * 
        */
        public function cli_process( ) : void {

            // empty line
            echo PHP_EOL;

            // show a starting processing message
            KCP_CSPGEN_CLI_Common::cli_warning( 'We have started processing the site.  This can take awhile to complete, please keep this window open until it has finished.' );

            // we need a CPT to hold the output, so create it
            $this -> cli_create_cpt( );

            // process the site's requests
            $_data = $this -> cli_process_site( );

            // create or update the CPT's post with the data obtained from the parsing
            $this -> cli_create_post( $_data );

            // show an ending message
            KCP_CSPGEN_CLI_Common::cli_success( 'We have finished processing the site and have processed all the external resources we could find.' );
            KCP_CSPGEN_CLI_Common::cli_success( 'Please test your site.' );
            KCP_CSPGEN_CLI_Common::cli_success( 'Please note that you may need to run this a few times before everything is gathered.' );
        }

        /** 
         * cli_process_site
         * 
         * The method is responsible for the true processing the site
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @return array Returns an array of unique external resource domains
         * 
        */
        private function cli_process_site( ) : array {

            // setup the returnable array
            $_ret = array( );

            // hold a pages array
            $_pages = array( );

            // all we're concerned with is internal posts and pages, so let's use WP_Query to grab us an array of the URL's
            $_post_types = get_post_types( 
                array(
                    'public' => true,
                ), 
            'names' );

            // now run a query to get all posts with these post types and are published
            $_qry = new WP_Query( array( 'post_type' => $_post_types, 'posts_per_page' => -1, 'post_status' => 'publish' ) );

            // now make sure we get a post object for each item returned
            $_rs = $_qry -> get_posts( );

            // if we do not have anything throw an error
            if( ! $_rs ) {

                // throw an error message and exit
                KCP_CSPGEN_CLI_Common::cli_error( 'Your site does not have any public pages, no need to proceed' );
            } else {

                // loop over the return
                foreach( $_rs as $_post ) {

                    // we only need the permalink for this
                    $_pages[] = get_permalink( $_post -> ID );

                }

                // reset the query
                wp_reset_query( );
            }

            // now we have something we can work with.  pull in our request and parsing manager
            $_requests = new KCP_CSPGEN_CLI_Requests( );
            $_ret = $_requests -> cli_requests( $_pages );

            // process the returned array so all nodes are unique, and external to the site
            $_ret = $this -> cli_process_resources( $_ret );

            // return it
            return $_ret;

        }

        /** 
         * cli_create_post
         * 
         * The method is responsible for creating our CPT to store the processed data
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param array $_data The array of data to be processed
         * 
         * @return void This method does not return anything
         * 
        */
        private function cli_create_post( array $_data ) : void {

            // let's wp encode our data
            $_data = maybe_serialize( $_data );

            // let's see if the post already exists
            $_post = get_page_by_title( 'KCP_GENERATED_CSP', OBJECT, 'kcp_csp' );

            // there is no post for this yet
            if( ! $_post ) {

                // setup the data array for creating our post
                $_args = array( 
                    'post_title' => 'KCP_GENERATED_CSP',
                    'post_content' => $_data,
                    'post_type' => 'kcp_csp',
                );

                // create the post
                $_id = wp_insert_post( $_args, true );

                // check if there was an error creating it
                if( is_wp_error( $_id ) ) {

                    // show a message
                    KCP_CSPGEN_CLI_Common::cli_error( 'There was an error gathering the resources: ' . $_id -> get_error_message( ) );
                }

            } else {

                // we do already have a record for this, update it
                $_args = array(
                    'ID' => $_post -> ID,
                    'post_content' => $_data,
                );

                // update it
                $_id = wp_update_post( $_args, true );

                // check if there was an error updating it
                if( is_wp_error( $_id ) ) {

                    // show a message
                    KCP_CSPGEN_CLI_Common::cli_error( 'There was an error gathering the resources: ' . $_id -> get_error_message( ) );
                }

            }

        }

        /** 
         * cli_process_resources
         * 
         * The method is responsible for processing all external and unique returned data
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @param array $_data The array of data to be processed
         * 
         * @return array Returns the categorized array of unique external only resources
         * 
        */
        private function cli_process_resources( array $_data ) : array {

            // get our sites url
            $_local_url = get_site_url( );

            // parse it for just the FQDN
            $_local_domain = parse_url( $_local_url, PHP_URL_HOST );

            // setup our returnable array
            $_ret = array( );

            // loop over the array and force the individual keys to have unique values
            foreach( $_data as $_item ) {

                // loop over the arrays inside and process the external FQDN
                foreach( $_item as $_key => $_val ) {

                    // hold the domain array
                    $_dom = array( );

                    // section name
                    $_name = $_key;

                    // it's value
                    $_value = array_unique( $_val );

                    // loop over the values, and reset an array for just the domains
                    foreach( $_value as $_url ) {

                        // get just the FQDN
                        $_domain = parse_url( $_url, PHP_URL_HOST );

                        // make sure we actually have a value here
                        if( $_domain ) {

                            // now, if this isn't something local
                            if( $_domain !== $_local_domain ) {

                                // set it to the return domain array
                                $_dom[] = $_domain;

                            }

                        }

                    }

                    // set the unique array of domains
                    $_ret[$_key] = array_unique( $_dom );

                }

            }

            // return the processed array
            return $_ret;

        }

        /** 
         * cli_create_cpt
         * 
         * The method is responsible for creating the CPT to store our data
         * 
         * @since 7.4
         * @access private
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @return void This method does not return anything
         * 
        */
        private function cli_create_cpt( ) : void {

            // setup the arguments for this CPT
            $_args = array(
                'supports' => array( 'title', 'editor' ), 
                'hierarchical' => false, 
                'public' => false, 
                'show_ui' => false, 
                'show_in_menu' => false, 
                'show_in_admin_bar' => false, 
                'show_in_nav_menus' => false, 
                'can_export' => false, 
                'has_archive' => false, 
                'exclude_from_search' => true, 
                'publicly_queryable' => false, 
                'capability_type' => 'post', 
                'query_var' => 'kcp_csp', );

            // register the post type, catching any exceptions
            try { 

                // register it here
                register_post_type( 'kcp_csp', $_args );
            } catch ( Exception $_e ) { 

                // houston, we have a problem
                KCP_CSPGEN_CLI_Common::cli_error( "We encountered an error registering the post type we need for this.\n" . $_e -> getMessage( ) );
            }

        }

    }

}