<?php
/** 
 * Header Common
 * 
 * Controls the common methods, properties, and is mostly statically available
 * 
 * @since 7.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin's Security Header Generator
 * 
*/

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if( ! class_exists( 'KCP_CSPGEN_Common' ) ) {

    /** 
     * Class KCP_CSPGEN_Common
     * 
     * This class holds some common methods, properties, and is mostly statically available
     * 
     * @since 7.4
     * @access public
     * @author Kevin Pirnie <me@kpirnie.com>
     * @package Kevin's Security Header Generator
     * 
    */
    class KCP_CSPGEN_Common {


        /** 
         * get_permissions_directives
         * 
         * Generate a return an array containing the Feature/Permission Policies
         * 
         * @since 7.4
         * @access public
         * @static
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @return array Returns an array of the feature/permission policies
         * 
        */
        public static function get_permissions_directives( ) : array {

            // setup the list of policies and return them
            return array(
                'accelerometer' => array(
                    'id' => 'fp_accelerometer',
                    'title' => __( 'Accelerometer', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to gather information about the acceleration of the device through the Accelerometer interface.', 'security-header-generator' ),
                ),
                'ambient-light-sensor' => array(
                    'id' => 'fp_ambient-light-sensor',
                    'title' => __( 'Ambient Light Sensor', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to gather information about the amount of light in the environment around the device through the AmbientLightSensor interface.', 'security-header-generator' ),
                ),
                'autoplay' => array(
                    'id' => 'fp_autoplay',
                    'title' => __( 'Autoplay', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to autoplay media requested through the HTMLMediaElement interface. When this policy is disabled and there were no user gestures, the Promise returned by HTMLMediaElement.play() will reject with a DOMException. The autoplay attribute on &lt;audio&gt; and &lt;video&gt; elements will be ignored.', 'security-header-generator' ),
                ),
                'camera' => array(
                    'id' => 'fp_camera',
                    'title' => __( 'Camera', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use video input devices. When this policy is disabled, the Promise returned by getUserMedia() will reject with a NotAllowedError DOMException.', 'security-header-generator' ),
                ),
                'display-capture' => array(
                    'id' => 'fp_display-capture',
                    'title' => __( 'Display Capture', 'security-header-generator' ),
                    'desc' => __( 'Controls whether or not the current document is permitted to use the getDisplayMedia() method to capture screen contents. When this policy is disabled, the promise returned by getDisplayMedia() will reject with a NotAllowedError if permission is not obtained to capture the display\'s contents.', 'security-header-generator' ),
                ),
                'document-domain' => array(
                    'id' => 'fp_document-domain',
                    'title' => __( 'Document Domain', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to set document.domain. When this policy is disabled, attempting to set document.domain will fail and cause a SecurityError DOMException to be thrown.', 'security-header-generator' ),
                ),
                'encrypted-media' => array(
                    'id' => 'fp_encrypted-media',
                    'title' => __( 'Encrypted Media', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the Encrypted Media Extensions API (EME). When this policy is disabled, the Promise returned by Navigator.requestMediaKeySystemAccess() will reject with a DOMException.', 'security-header-generator' ),
                ),
                'execution-while-not-rendered' => array(
                    'id' => 'fp_execution-while-not-rendered',
                    'title' => __( 'Execution While Not Rendered', 'security-header-generator' ),
                    'desc' => __( 'Controls whether tasks should execute in frames while they\'re not being rendered (e.g. if an iframe is hidden or display: none).', 'security-header-generator' ),
                ),
                'execution-while-out-of-viewport' => array(
                    'id' => 'fp_execution-while-out-of-viewport',
                    'title' => __( 'Execution While Our of Viewport', 'security-header-generator' ),
                    'desc' => __( 'Controls whether tasks should execute in frames while they\'re outside of the visible viewport.', 'security-header-generator' ),
                ),
                'fullscreen' => array(
                    'id' => 'fp_fullscreen',
                    'title' => __( 'Full Screen', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use Element.requestFullScreen(). When this policy is disabled, the returned Promise rejects with a TypeError.', 'security-header-generator' ),
                ),
                'gamepad' => array(
                    'id' => 'fp_gamepad',
                    'title' => __( 'Game Pad', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the Gamepad API. When this policy is disabled, calls to Navigator.getGamepads() will throw a SecurityError DOMException, and the gamepadconnected and gamepaddisconnected events will not fire', 'security-header-generator' ),
                ),
                'geolocation' => array(
                    'id' => 'fp_geolocation',
                    'title' => __( 'Geo Location', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the Geolocation Interface. When this policy is disabled, calls to getCurrentPosition() and watchPosition() will cause those functions\' callbacks to be invoked with a GeolocationPositionError code of PERMISSION_DENIED', 'security-header-generator' ),
                ),
                'gyroscope' => array(
                    'id' => 'fp_gyroscope',
                    'title' => __( 'Gyroscope', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to gather information about the orientation of the device through the Gyroscope interface', 'security-header-generator' ),
                ),
                'magnetometer' => array(
                    'id' => 'fp_magnetometer',
                    'title' => __( 'Magnetometer', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to gather information about the orientation of the device through the Magnetometer interface', 'security-header-generator' ),
                ),
                'microphone' => array(
                    'id' => 'fp_microphone',
                    'title' => __( 'Microphone', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use audio input devices. When this policy is disabled, the Promise returned by MediaDevices.getUserMedia() will reject with a NotAllowedError.', 'security-header-generator' ),
                ),
                'midi' => array(
                    'id' => 'fp_midi',
                    'title' => __( 'MIDI', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the Web MIDI API. When this policy is disabled, the Promise returned by Navigator.requestMIDIAccess() will reject with a DOMException', 'security-header-generator' ),
                ),
                'navigation-override' => array(
                    'id' => 'fp_navigation-override',
                    'title' => __( 'Navigation Override', 'security-header-generator' ),
                    'desc' => __( 'Controls the availability of mechanisms that enables the page author to take control over the behavior of spatial navigation, or to cancel it outright', 'security-header-generator' ),
                ),
                'payment' => array(
                    'id' => 'fp_payment',
                    'title' => __( 'Payment', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the Payment Request API. When this policy is enabled, the PaymentRequest() constructor will throw a SecurityError DOMException', 'security-header-generator' ),
                ),
                'picture-in-picture' => array(
                    'id' => 'fp_picture-in-picture',
                    'title' => __( 'Picture in Picture', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to play a video in a Picture-in-Picture mode via the corresponding API', 'security-header-generator' ),
                ),
                'publickey-credentials-get' => array(
                    'id' => 'fp_publickey-credentials-get',
                    'title' => __( 'Publicket Credentials Get', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the Web Authentication API to retrieve already stored public-key credentials, i.e. via navigator.credentials.get({publicKey: ..., ...})', 'security-header-generator' ),
                ),
                'sync-xhr' => array(
                    'id' => 'fp_sync-xhr',
                    'title' => __( 'Sync XHR', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to make synchronous XMLHttpRequest requests', 'security-header-generator' ),
                ),
                'usb' => array(
                    'id' => 'fp_usb',
                    'title' => __( 'USB', 'security-header-generator' ),
                    'desc' => __( 'Controls whether the current document is allowed to use the WebUSB API', 'security-header-generator' ),
                ),
                'xr-spatial-tracking' => array(
                    'id' => 'fp_xr-spatial-tracking',
                    'title' => __( 'XR Spatial Tracking', 'security-header-generator' ),
                    'desc' => __( 'Controls whether or not the current document is allowed to use the WebXR Device API to interact with a WebXR session', 'security-header-generator' ),
                ),
            );

        }

        /** 
         * get_csp_directives
         * 
         * Generate a return an array containing the Content Security Policy directives
         * 
         * @since 7.4
         * @access public
         * @static
         * @author Kevin Pirnie <me@kpirnie.com>
         * @package Kevin's Security Header Generator
         * 
         * @return array Returns an array of the Content Security Policy directives
         * 
        */
        public static function get_csp_directives( ) : array {

            // return the array of CSP directives
            return array(
                'child-src' => array(
                    'id' => 'generate_csp_custom_child',
                    'title' => __( 'Child Source', 'security-header-generator' ),
                    'desc' => __( 'Defines the valid sources for web workers and nested browsing contexts loaded using elements such as &lt;frame&gt; and &lt;iframe&gt;.', 'security-header-generator' ),
                ),
                'connect-src' => array(
                    'id' => 'generate_csp_custom_connect',
                    'title' => __( 'Connect/Ajax/XHR Source', 'security-header-generator' ),
                    'desc' => __( 'Restricts the URLs which can be loaded using script interfaces', 'security-header-generator' ),
                ),
                'default-src' => array(
                    'id' => 'generate_csp_custom_defaults',
                    'title' => __( 'Default Source', 'security-header-generator' ),
                    'desc' => __( 'Serves as a fallback for the other fetch directives.', 'security-header-generator' ),
                ),
                'font-src' => array(
                    'id' => 'generate_csp_custom_fonts',
                    'title' => __( 'Font Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for fonts loaded using @font-face.', 'security-header-generator' ),
                ),
                'form-action' => array(
                    'id' => 'generate_csp_custom_forms',
                    'title' => __( 'Form Action', 'security-header-generator' ),
                    'desc' => __( 'Restricts the URLs which can be used as the target of a form submissions from a given context.', 'security-header-generator' ),
                ),
                'frame-src' => array(
                    'id' => 'generate_csp_custom_frames',
                    'title' => __( 'Frame Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for nested browsing contexts loading using elements such as &lt;frame&gt; and &lt;iframe&gt;.', 'security-header-generator' ),
                ),
                'frame-ancestors' => array(
                    'id' => 'generate_csp_custom_frame_ancestors',
                    'title' => __( 'Frame Ancestors', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid parents that may embed a page using &lt;frame&gt;, &lt;iframe&gt;, &lt;object&gt;, &lt;embed&gt;, or &lt;applet&gt;.', 'security-header-generator' ),
                ),
                'img-src' => array(
                    'id' => 'generate_csp_custom_images',
                    'title' => __( 'Image Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources of images and favicons.', 'security-header-generator' ),
                ),
                'manifest-src' => array(
                    'id' => 'generate_csp_custom_manifests',
                    'title' => __( 'Manifest Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources of application manifest files.', 'security-header-generator' ),
                ),
                'media-src' => array(
                    'id' => 'generate_csp_custom_media',
                    'title' => __( 'Media Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for loading media using the &lt;audio&gt; , &lt;video&gt; and &lt;track&gt; elements.', 'security-header-generator' ),
                ),
                'navigate-to' => array(
                    'id' => 'generate_csp_custom_navigates',
                    'title' => __( 'Navigate To', 'security-header-generator' ),
                    'desc' => __( 'Restricts the URLs to which a document can initiate navigation by any means, including &lt;form&gt; (if form-action is not specified), &lt;a&gt;, window.location, window.open, etc.', 'security-header-generator' ),
                ),
                'object-src' => array(
                    'id' => 'generate_csp_custom_objects',
                    'title' => __( 'Object Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for the &lt;object&gt;, &lt;embed&gt;, and &lt;applet&gt; elements.', 'security-header-generator' ),
                ),
                'prefetch-src' => array(
                    'id' => 'generate_csp_custom_prefetch',
                    'title' => __( 'Prefetch Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources to be prefetched or prerendered.', 'security-header-generator' ),
                ),
                'script-src' => array(
                    'id' => 'generate_csp_custom_scripts',
                    'title' => __( 'Script Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for JavaScript.', 'security-header-generator' ),
                ),
                'script-src-elem' => array(
                    'id' => 'generate_csp_custom_scripts_elem',
                    'title' => __( 'Script Source Elements', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for JavaScript &lt;script&gt; elements.', 'security-header-generator' ),
                ),
                'script-src-attr' => array(
                    'id' => 'generate_csp_custom_scripts_attr',
                    'title' => __( 'Script Source Attributes', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for JavaScript inline event handlers.', 'security-header-generator' ),
                ),
                'style-src' => array(
                    'id' => 'generate_csp_custom_styles',
                    'title' => __( 'Style Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for stylesheets.', 'security-header-generator' ),
                ),
                'style-src-elem' => array(
                    'id' => 'generate_csp_custom_styles_elem',
                    'title' => __( 'Style Source Elements', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for stylesheets &lt;style&gt; elements and &lt;link&gt; elements with rel="stylesheet".', 'security-header-generator' ),
                ),
                'style-src-attr' => array(
                    'id' => 'generate_csp_custom_styles_attr',
                    'title' => __( 'Style Source Attributes', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for inline styles applied to individual DOM elements.', 'security-header-generator' ),
                ),
                'worker-src' => array(
                    'id' => 'generate_csp_custom_workers',
                    'title' => __( 'Worker Source', 'security-header-generator' ),
                    'desc' => __( 'Specifies valid sources for Worker, SharedWorker, or ServiceWorker scripts.', 'security-header-generator' ),
                ),
            );

        }


    }

}
