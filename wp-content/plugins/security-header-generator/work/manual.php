<?php

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// check user capabilities
if( ! current_user_can( 'manage_options' ) ) {
    return;
}

// get the headers we're working with, by calling in our class
$_hc = new KCP_CSPGEN_Headers( );

// return them
$_headers = ( $_hc -> kp_process_headers_for_display( ) ) ?? null;

?>

<style type="text/css">
    .wrap ul {
        margin-left: 35px;
    }
    textarea{
        width:100%;
        height:400px;
    }
</style>
<span id="top"></span>
    <p><?php _e( 'Please see below for the proper code for each type of implementation if this plugin cannot implement the headers automatically.', 'security-header-generator' ); ?></p>
    <p><?php _e( 'Copy and paste the code in each section that you need.', 'security-header-generator' ); ?></p>
    <p><a href="#apache"><?php _e( 'Apache/Lightspeed', 'security-header-generator' ); ?></a> | 
    <a href="#nginx"><?php _e( 'nGinx', 'security-header-generator' ); ?></a> | 
    <a href="#iis"><?php _e( 'IIS', 'security-header-generator' ); ?></a> | 
    <a href="#php"><?php _e( 'PHP', 'security-header-generator' ); ?></a> | 
    <a href="#meta"><?php _e( 'Meta Tags', 'security-header-generator' ); ?></a></p>
    <span id="apache"></span>
    <h3><?php _e( 'Apache/Lightspeed', 'security-header-generator' ); ?></h3>
    <p><?php _e( 'Place this in your sites', 'security-header-generator' ); ?> <code>.htaccess</code></p>
    <textarea readonly>
<?php

    // make sure we actually have something
    if( isset( $_headers ) ) {

        echo '<IfModule mod_headers.c>' . PHP_EOL;

        // check if we have this header
        if( isset( $_headers['Strict-Transport-Security'] ) ) {

            // we do, so echo out the line needed
            echo '  Header always set Strict-Transport-Security "max-age=31536000; includeSubdomains; preload"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['Expect-CT'] ) ) {

            // we do, so echo out the line needed
            echo '  Header always set Expect-CT "max-age=604800, enforce"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['X-Frame-Options'] ) ) {

            // we do, so echo out the line needed
            echo '  Header set X-Frame-Options "SAMEORIGIN"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['X-Xss-Protection'] ) ) {

            // we do, so echo out the line needed
            echo '  # This header is now deprecated in most browsers, it will be removed in future versions' . PHP_EOL;
            echo '  Header set X-XSS-Protection "1; mode=block"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['X-Content-Type-Options'] ) ) {

            // we do, so echo out the line needed
            echo '  Header set X-Content-Type-Options "nosniff"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['Referrer-Policy'] ) ) {

            // we do, so echo out the line needed
            echo '  Header set Referrer-Policy "strict-origin"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['X-Download-Options'] ) ) {

            // we do, so echo out the line needed
            echo '  Header set X-Download-Options "noopen"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['X-Permitted-Cross-Domain-Policies'] ) ) {

            // we do, so echo out the line needed
            echo '  Header set X-Permitted-Cross-Domain-Policies "none"' . PHP_EOL;
        }
        // see if we're configured to include the Cross-Origin-Embedder-Policy
        if( isset( $_headers['Cross-Origin-Embedder-Policy'] ) ) {

            // we do, do write it out
            echo '  Header set Cross-Origin-Embedder-Policy "' . $_headers["Cross-Origin-Embedder-Policy"] . '"' . PHP_EOL;
            
        }
        // see if we're configured to include the Cross-Origin-Opener-Policy
        if( isset( $_headers['Cross-Origin-Opener-Policy'] ) ) {

            // we do, do write it out
            echo '  Header set Cross-Origin-Opener-Policy "' . $_headers["Cross-Origin-Opener-Policy"] . '"' . PHP_EOL;
            
        }
        // check if we have this header
        if( isset( $_headers['Content-Security-Policy'] ) ) {

            // we do, so echo out the line needed
            echo '  Header set Content-Security-Policy "' . $_headers["Content-Security-Policy"] . '"' . PHP_EOL;
            echo '  Header set Content-Security-Policy "' . $_headers["X-Content-Security-Policy"] . '"' . PHP_EOL;
        }
        // check if we have this header
        if( isset( $_headers['Permissions-Policy'] ) ) {

            // we do, so echo out the line needed
            echo '  Header always set Permissions-Policy: ' . $_headers['Permissions-Policy'] . PHP_EOL;
        }

        echo '</IfModule>' . PHP_EOL;

    }

?>
    </textarea>
    <a href="#wpwrap"><?php _e( 'TOP OF PAGE', 'security-header-generator' ); ?></a>
    <span id="nginx"></span>
    <h3><?php _e( 'nGinx', 'security-header-generator' ); ?></h3>
    <p><?php _e( 'Place this in your main location block for your site.', 'security-header-generator' ); ?></p>
    <textarea readonly>
<?php

// make sure we actually have something
if( isset( $_headers ) ) {
    
    // check if we have this header
    if( isset( $_headers['Strict-Transport-Security'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header Strict-Transport-Security "max-age=31536000; includeSubdomains; preload";' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Expect-CT'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header Expect-CT "max-age=604800, enforce";' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Frame-Options'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header X-Frame-Options "SAMEORIGIN" always;' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Xss-Protection'] ) ) {

        // we do, so echo out the line needed
        echo '# This header is now deprecated in most browsers, it will be removed in future versions' . PHP_EOL;
        echo 'add_header X-Xss-Protection "1; mode=block";' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Content-Type-Options'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header X-Content-Type-Options "nosniff" always;' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Referrer-Policy'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header Referrer-Policy "strict-origin";' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Download-Options'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header X-Download-Options "noopen";' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Permitted-Cross-Domain-Policies'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header X-Permitted-Cross-Domain-Policies "none";' . PHP_EOL;
    }
    // see if we're configured to include the Cross-Origin-Embedder-Policy
    if( isset( $_headers['Cross-Origin-Embedder-Policy'] ) ) {

        // we do, do write it out
        echo 'add_header Cross-Origin-Embedder-Policy "' . $_headers["Cross-Origin-Embedder-Policy"] . '";' . PHP_EOL;
        
    }
    // see if we're configured to include the Cross-Origin-Opener-Policy
    if( isset( $_headers['Cross-Origin-Opener-Policy'] ) ) {

        // we do, do write it out
        echo 'add_header Cross-Origin-Opener-Policy "' . $_headers["Cross-Origin-Opener-Policy"] . '";' . PHP_EOL;
        
    }
    // check if we have this header
    if( isset( $_headers['Content-Security-Policy'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header Content-Security-Policy "' . $_headers['Content-Security-Policy'] . '" always;' . PHP_EOL;
        echo 'add_header X-Content-Security-Policy "' . $_headers['Content-Security-Policy'] . '" always;' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Permissions-Policy'] ) ) {

        // we do, so echo out the line needed
        echo 'add_header Permissions-Policy ' . $_headers['Permissions-Policy'] . ';' . PHP_EOL;
    }

}

?>
</textarea>
    <a href="#wpwrap"><?php _e( 'TOP OF PAGE', 'security-header-generator' ); ?></a>
    <span id="iis"></span>
    <h3><?php _e( 'IIS', 'security-header-generator' ); ?></h3>
    <p><?php _e( 'Place this in your sites', 'security-header-generator' ); ?> <code>web.config</code> <?php _e( 'file inside the', 'security-header-generator' ); ?> <code>system.webServer</code> <?php _e( 'node', 'security-header-generator' ); ?>.</p>
    <textarea readonly>
<?php

// make sure we actually have something
if( isset( $_headers ) ) {

    echo '<httpProtocol>' . PHP_EOL;

    // check if we have this header
    if( isset( $_headers['Strict-Transport-Security'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="Strict-Transport-Security" />' . PHP_EOL;
        echo '  <add name="Strict-Transport-Security" value="max-age=31536000; includeSubdomains; preload" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Expect-CT'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="Expect-CT" />' . PHP_EOL;
        echo '  <add name="Expect-CT" value="max-age=604800, enforce" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Frame-Options'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="X-Frame-Options" />' . PHP_EOL;
        echo '  <add name="X-Frame-Options" value="SAMEORIGIN" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Xss-Protection'] ) ) {

        // we do, so echo out the line needed
        echo '  <!-- This header is now deprecated in most browsers, it will be removed in future versions -->' . PHP_EOL;
        echo '  <remove name="X-XSS-Protection" />' . PHP_EOL;
        echo '  <add name="X-XSS-Protection" value="1; mode=block" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Content-Type-Options'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="X-Content-Type-Options" />' . PHP_EOL;
        echo '  <add name="X-Content-Type-Options" value="nosniff" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Referrer-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="Referrer-Policy" />' . PHP_EOL;
        echo '  <add name="Referrer-Policy" value="strict-origin" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Download-Options'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="X-Download-Options" />' . PHP_EOL;
        echo '  <add name="X-Download-Options" value="noopen" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Permitted-Cross-Domain-Policies'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="X-Permitted-Cross-Domain-Policies" />' . PHP_EOL;
        echo '  <add name="X-Permitted-Cross-Domain-Policies" value="none" />' . PHP_EOL;
    }
    // see if we're configured to include the Cross-Origin-Embedder-Policy
    if( isset( $_headers['Cross-Origin-Embedder-Policy'] ) ) {

        // we do, do write it out
        echo '  <remove name="Cross-Origin-Embedder-Policy" />' . PHP_EOL;
        echo '  <add name="Cross-Origin-Embedder-Policy" value="' . $_headers['Cross-Origin-Embedder-Policy'] . '" />' . PHP_EOL;
        
    }
    // see if we're configured to include the Cross-Origin-Opener-Policy
    if( isset( $_headers['Cross-Origin-Opener-Policy'] ) ) {

        // we do, do write it out
        echo '  <remove name="Cross-Origin-Opener-Policy" />' . PHP_EOL;
        echo '  <add name="Cross-Origin-Opener-Policy" value="' . $_headers['Cross-Origin-Opener-Policy'] . '" />' . PHP_EOL;
        
    }
    // check if we have this header
    if( isset( $_headers['Content-Security-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="Content-Security-Policy" />' . PHP_EOL;
        echo '  <remove name="X-Content-Security-Policy" />' . PHP_EOL;
        echo '  <add name="Content-Security-Policy" value="' . $_headers['Content-Security-Policy'] . '" />' . PHP_EOL;
        echo '  <add name="X-Content-Security-Policy" value="' . $_headers['Content-Security-Policy'] . '" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Permissions-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '  <remove name="Permissions-Policy" />' . PHP_EOL;
        echo '  <add name="Permissions-Policy" value="' . esc_attr( str_replace( '"', '\"', $_headers['Permissions-Policy'] ) ) . '" />' . PHP_EOL;
    }
    

    echo '</httpProtocol>' . PHP_EOL;

}

?>
</textarea>
    <a href="#wpwrap"><?php _e( 'TOP OF PAGE', 'security-header-generator' ); ?></a>
    <span id="php"></span>
    <h3><?php _e( 'PHP', 'security-header-generator' ); ?></h3>
    <p><?php _e( 'Place this in your themes', 'security-header-generator' ); ?> <code>functions.php</code> <?php _e( 'file', 'security-header-generator' ); ?>.</p>
    <textarea readonly>
<?php

// make sure we actually have something
if( isset( $_headers ) ) {

    echo '&lt;?php' . PHP_EOL;
    echo '  // to implement in admin, change the action to \'admin_init\'' . PHP_EOL;
    echo '  add_action( \'send_headers\', function( ) {' . PHP_EOL;

    // check if we have this header
    if( isset( $_headers['Strict-Transport-Security'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "Strict-Transport-Security : ' . $_headers['Strict-Transport-Security'] . '", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Expect-CT'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "Expect-CT : max-age=604800, enforce", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Frame-Options'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "X-Frame-Options : ' . $_headers['X-Frame-Options'] . '", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Xss-Protection'] ) ) {

        // we do, so echo out the line needed
        echo '      // This header is now deprecated in most browsers, it will be removed in future versions' . PHP_EOL;
        echo '      header( "X-Xss-Protection : ' . $_headers['X-Xss-Protection'] . '", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Content-Type-Options'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "X-Content-Type-Options : ' . $_headers['X-Content-Type-Options'] . '", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Referrer-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "Referrer-Policy : ' . $_headers['Referrer-Policy'] . '", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Download-Options'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "X-Download-Options : ' . $_headers['X-Download-Options'] . '", true );' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Permitted-Cross-Domain-Policies'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "X-Permitted-Cross-Domain-Policies : ' . $_headers['X-Permitted-Cross-Domain-Policies'] . '", true );' . PHP_EOL;
    }
    // see if we're configured to include the Cross-Origin-Embedder-Policy
    if( isset( $_headers['Cross-Origin-Embedder-Policy'] ) ) {

        // we do, do write it out
        echo '      header( "Cross-Origin-Embedder-Policy : ' . $_headers["Cross-Origin-Embedder-Policy"] . '", true );' . PHP_EOL;
        
    }
    // see if we're configured to include the Cross-Origin-Opener-Policy
    if( isset( $_headers['Cross-Origin-Opener-Policy'] ) ) {

        // we do, do write it out
        echo '      header( "Cross-Origin-Opener-Policy : ' . $_headers["Cross-Origin-Opener-Policy"] . '", true );' . PHP_EOL;
        
    }
    // check if we have this header
    if( isset( $_headers['Content-Security-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "Content-Security-Policy : ' . $_headers['Content-Security-Policy'] . '", true );' . PHP_EOL;
        echo '      header( "X-Content-Security-Policy : ' . $_headers['Content-Security-Policy'] . '", true );' . PHP_EOL;
    }

    // check if we have this header
    if( isset( $_headers['Permissions-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '      header( "Permissions-Policy : ' . esc_attr( str_replace( '"', '\"', $_headers['Permissions-Policy'] ) ) . '", true );' . PHP_EOL;
    }
    

    echo '  }, PHP_INT_MAX );' . PHP_EOL;

}

?>
</textarea>
<a href="#wpwrap"><?php _e( 'TOP OF PAGE', 'security-header-generator' ); ?></a>
    <span id="meta"></span>
    <h3><?php _e( 'Meta Tags', 'security-header-generator' ); ?></h3>
    <p><?php _e( 'Place this in between your sites', 'security-header-generator' ); ?> <code>&lt;head&gt;</code> <?php _e( 'tags', 'security-header-generator' ); ?>.</p>
    <textarea readonly>
<?php

// make sure we actually have something
if( isset( $_headers ) ) {

    // check if we have this header
    if( isset( $_headers['Strict-Transport-Security'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="Strict-Transport-Security" content="max-age=31536000; includeSubdomains; preload" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Expect-CT'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="Expect-CT" content="max-age=604800, enforce" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Frame-Options'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Xss-Protection'] ) ) {

        // we do, so echo out the line needed
        echo '<!-- This header is now deprecated in most browsers, it will be removed in future versions -->' . PHP_EOL;
        echo '<meta http-equiv="X-Xss-Protection" content="1; mode=block" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Content-Type-Options'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="X-Content-Type-Options" content="nosniff" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Referrer-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="Referrer-Policy" content="strict-origin" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Download-Options'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="X-Download-Options" content="noopen" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['X-Permitted-Cross-Domain-Policies'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="X-Permitted-Cross-Domain-Policies" content="none" />' . PHP_EOL;
    }
    // see if we're configured to include the Cross-Origin-Embedder-Policy
    if( isset( $_headers['Cross-Origin-Embedder-Policy'] ) ) {

        // we do, do write it out
        echo '<meta http-equiv="Cross-Origin-Embedder-Policy" content="' . $_headers["Cross-Origin-Embedder-Policy"] . '" />' . PHP_EOL;
        
    }
    // see if we're configured to include the Cross-Origin-Opener-Policy
    if( isset( $_headers['Cross-Origin-Opener-Policy'] ) ) {

        // we do, do write it out
        echo '<meta http-equiv="Cross-Origin-Opener-Policy" content="' . $_headers["Cross-Origin-Opener-Policy"] . '" />' . PHP_EOL;
        
    }
    // check if we have this header
    if( isset( $_headers['Content-Security-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="Content-Security-Policy" content="' . $_headers['Content-Security-Policy'] . '" />' . PHP_EOL;
        echo '<meta http-equiv="X-Content-Security-Policy" content="' . $_headers['Content-Security-Policy'] . '" />' . PHP_EOL;
    }
    // check if we have this header
    if( isset( $_headers['Permissions-Policy'] ) ) {

        // we do, so echo out the line needed
        echo '<meta http-equiv="Permissions-Policy" content="' . esc_attr( str_replace( '"', '\"', $_headers['Permissions-Policy'] ) ) . '" />' . PHP_EOL;
    }

}

?>
</textarea>
<a href="#wpwrap"><?php _e( 'TOP OF PAGE', 'security-header-generator' ); ?></a>
