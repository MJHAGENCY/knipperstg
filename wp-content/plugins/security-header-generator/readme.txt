=== Security Header Generator ===
Contributors: kevp75
Donate link: https://paypal.me/kevinpirnie
Tags: security, security headers, content security policy, permissions, permissions policy
Requires at least: 5.5
Tested up to: 6.0.2
Requires PHP: 7.4
Stable tag: 3.4.28
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
 
This plugin generates the proper security HTTP response headers to keep your site secured.
 
== Description ==
 
This plugin generates the proper security HTTP response headers, attempts to generate a valid Content Security Policy, and sets browser permissions if configured. 
 
== Installation ==
 
1. Download the plugin, unzip it, and upload to your sites `/wp-content/plugins/` directory
    1. You can also upload it directly to your Plugins admin
2. Activate the plugin through the 'Plugins' menu in WordPress
 
== Frequently Asked Questions ==
 
= What is a Content Security Policy? =
 
A Content Security Policy is an added layer of security that helps to detect and mitigate certain types of attacks, including Cross Site Scripting (XSS) and data injection attacks.
 
= Will this detect every external source in my site? =
 
Unfortunately, no.   While it will make every best effort to do so, it cannot capture external resources inside already external resources.

= How do I automatically generate a Content Security Policy using your plugin? =

Login to shell for your site, change directory to your websites root folder, and run `wp csp generate`.  Have some patience because it can take some time to run. Please make sure to run it a few times, I cannot guarantee that it will get everything, but, in my tests on my own sites it did.
 
== Screenshots ==
 
1. Standard Header Settings
2. Content Security Policy Settings
3. Permissions Settings
4. Implementation
5. Documentation
6. Import/Export Settings
7. Headers Set
 
== Changelog ==

= 3.4.28 =
* Fix: Typo in versioning

= 3.4.27 =
* Test: Up to 6.0.2 compliant
* Tech: force PHP 7.4 minimum
* Remove: Upgrader hook
    * this is no longer needed
* Remove: X-XSS-Protection Header
    * was depracated in version 2.2.13. Only compatible browsersas of 7/14/2022 are Edge and and Safari
      Use CSP to mitigate XSS

= 3.3.01 =
* Test: Up to 6.0 compliant
* Test: Up to PHP 8.1 Compliant
* New: Plugin Icon =)
* Updated: Settings Field Framework
    * Added: Number field "min", "max", "step" options.
    * Updated: Google Web Fonts array added new fonts.
    * Updated: JS libraries (codemirror, leaflet, etc).
    * Improved: Group field "custom title and prefix" option (samples added).
    * Improved: Some JS and CSS coding.

= 3.2.37 =
* Fix: Eval and Inline for empty directives

= 3.2.34 =
* Fix: Forgot a debugging var_dump... SMH

= 3.2.33 =
* Fix: Include blank directives:
    * Even if the directives are blank for the CSP, they should still be included with the 'self' flag
* Test: Up to 5.9.2 compliant
* Fix: CLI performance.  
    * Was timing out, then skipping some directives on larger sites.

= 3.1.02 =
* Fix: Default WP CSP headers not being set
* Fix: Implementation now includes Default WP
* Feature: Implement debug check to queue unminified style and scripts
* Fix: Implementation from the CLI pulls

= 3.0.77 = 
* Update: Settings framework

= 3.0.68 =
* Fix: OR to ||
    * forgot about it in the main plugin file
* Update: translatable resources
    * New: /languages/security-header-generator.pot

= 3.0.10 =
* Fix: Array issue
* Fix: Strict typing issue

= 3.0.09 =
* Feature: Implement post update hook to try to properly migrate existing settings to the new format
* Update: Change exportable/importable settings names, more legible
    * While I will do my best to automate this, please note it may not be perfect... I am only human after all ;)
    * If you export your settings before updating, you can import them again after updating and the below will be 
      taken care of for you.
    * Just in case it does not work 100%, please export your settings before updating to this version and 
      perform a search and replace for the string to remove it:
        * Search: "kp_cspgen_"
        * Replace: null|nothing|empty
    * NOTE: If you do not export your settings I will not guarantee that you will not have to reconfigure the plugin.
      Although... I did take a backup ;) You will need to hop into your database to grab it though, it will be in your
      options table, and it is called: `wpsh_TEMP_settings`.  I will have this automatically removed in a future update
* Add: Option to remove server advertising.
* Add: Expect-CT header
    * The Expect-CT header lets sites opt in to reporting and/or enforcement of Certificate Transparency requirements, 
      to prevent the use of misissued certificates for that site from going unnoticed.
    * Doc: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT
    * Hook: `wpsh_expectct_header`
* Updated: Feature Policies.
    * Removed the following: battery, layout-animations, legacy-image-formats, oversized-images, screen-wake-lock, 
      unoptimized-images, unsized-media, web-share
    * The above no longer have any browser support.
    * Added: Descriptive descriptions for each directive
* Updated: Content Security Policy
    * Added: the following fetch directives: 
        * child-src, manifest-src, object-src, prefetch-src, script-src-elem, 
          script-src-attr, style-src-elem, style-src-attr, worker-src, navigate-to
    * Added: Unsafe Inline and Unsafe Eval settings on each CSP directive
    * Added: Descriptive descriptions for each directive
    * Reworked: Settings for the entire section, which of course caused me to rewrite the way they are implemented.

= 2.2.15 =
* Implement: The 2 new headers documentation and implementation tabs SMH

= 2.2.13 =
* Deprecated: The X-XSS-Protection header has been deprecated by modern browsers. 
    * As a result we are marking the setting, hook, and functionality to set it as deprecated and 
      will be removed in future versions of this plugin.
    * Doc: https://owasp.org/www-project-secure-headers/#x-xss-protection
    * Set to log as deprecated
* Rework: Settings Fields
    * found it was causing a conflict with another plugin
* Add: 2 new Headers
    * Cross-Origin-Embedder-Policy
        * This response header (also named COEP) prevents a document from loading any cross-origin resources that don’t 
          explicitly grant the document permission
        * https://owasp.org/www-project-secure-headers/#cross-origin-embedder-policy
        * Hook: `wpsh_coep_header`
    * Cross-Origin-Opener-Policy
        * This response header (also named COOP) allows you to ensure a top-level document does not share a browsing 
          context group with cross-origin documents. COOP will process-isolate your document and potential attackers can’t 
          access to your global object if they were opening it in a popup, preventing a set of cross-origin attacks dubbed XS-Leaks
        * https://owasp.org/www-project-secure-headers/#cross-origin-opener-policy
        * Hook: `wpsh_coop_header`
* NOTE: There is no full browser support yet for Cross-Origin-Resource-Policy, so for now it is going to be left out.  As a result,
  the only option for the Embedder Policy is "unsafe-none".  Once it is fully cross-browser this header will be implemented.

= 2.1.09 =
* Fixed: Admin bar menu show in for non-admin capabilities.
* Improved: Usage anywhere framework fields.
* Updated: JS libraries (codemirror, leaflet, etc).
* Improved: Some js and css coding.

= 2.0.97 =
* Verify: Core 5.9 Compatibility
* Feature: Add some hooks.  All pretty self-explanatory, so here is the list and arguments if there are any:
    * `wpsh_pre_headers`, `wpsh_send_frontend_headers`, `wpsh_send_admin_headers`, `wpsh_post_headers`
    * `wpsh_sts_header` - Arg: The "Strict-Transport-Security" header content for the generated options
    * `wpsh_ofs_header` - Arg: The "X-Frame-Options" header content for the generated options
    * `wpsh_xss_header` - Arg: The "X-Xss-Protection" header content for the generated options
    * `wpsh_mimesniffing_header` - Arg: The "X-Content-Type-Options" header content for the generated options
    * `wpsh_referrer_header` - Arg: The "Referrer-Policy" header content for the generated options
    * `wpsh_dlopt_header` - Arg: The "X-Download-Options" header content for the generated options
    * `wpsh_crossdomain_header` - Arg: The "X-Permitted-Cross-Domain-Policies" header content for the generated options
    * `wpsh_csp_header` - Arg: The "Content-Security-Policy" header content for the generated options
    * `wpsh_upgradesecure_header` - Arg: The "Content-Security-Policy" header content for the generated options
    * `wpsh_floc_header` - Arg: The "Permissions-Policy" header content for the generated options
    * `wpsh_permissions_header` - Arg: The "Permissions-Policy" header content for the generated options
    * `wpsh_pre_csp_generate` - fires off before the Content Security Policy is generated
    * `wpsh_post_csp_generate` - Arg: The generated Content Security Policy string

= 2.0.36 =
* Tweak admin permissions
    * found an issue where a subsite admin could not administer
      the settings if the super-admin disabled Plugins in settings

= 2.0.08 =
* Convert all major comments to phpdoc
* Fix `OR DIE` on direct file access
* Removed double PHP version check (whoops!)
* move the plugins stylesheet
* Core 5.8.1 Compliance

= 1.9.51 =
* Update: Rewrite Field Framework

= 1.9.47 =
* Update: Field Framework

= 1.9.44 =
* fixed minor issue setting FLoC header

= 1.9.43 =
* Removed sticky header for settings
    * it was not working properly in all browsers anyways
* Implemented Permissions-Policy header
    * See Here for more information: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy
* Organize header settings into separate tabs
* Fixed issue where setting would automatically collapse
* Re-implement uninstall

= 1.9.27 =
* Wordpress Core 5.8 compliance
    * Tested
* **feature** re-implement WP CLI functionality
    * Attempts to spider the site gathering up only external resources like imagery, stylesheets, scripts, fonts, frames, etc...
    * Creates a custom post type to hold the output of the parsed external resources
    * Adds the domains only to the Content-Security-Policy header

= 1.9.23 =
* Fix issue where admin menu would not show for some
* Force PHP 7.3 minimum on actviation

= 1.9.18 =
* Publishing
    * slight issue in readme was preventing updating to 1.9.17

= 1.9.17 =
* Remove the remote header checks implemented in v1.9.11
* Update Documentation

= 1.9.11 =
* New class to attempt server-side header Implementation
* If server-side headers do exist, show a note in Settings
* Attempt to override existing headers
    * only headers set by plugin
* Cache the above check for 1 day
* Temprorarily remove the CLI functionality

= 1.8.23 =
* Update settings framework
* Updated minimum PHP requirement to PHP 7.3

= 1.8.14 =
* Update for WP Core 5.7.2
* Update methods to force output type

= 1.8.11 =
* implement FLoC decline setting and headers

= 1.7.03 =
* full field framework update

= 1.7.02 = 
* field framework update
* little styling tweak for it

= 1.6.10 = 
* fix path issue

= 1.6.09 =
* replace field framework
    * big thanks to Codestar: https://codecanyon.net/user/codestar
* rebuild the settings and retrieval
* rework admin sending of headers
* clear cache on settings save

= 1.5.22 =
* update field framework
* 5.7 compliance
* check for existing functionality

= 1.4.11 =
* fix uninstall to remove all options, including for multisite

= 1.4.09 =
* Implement true autoloader
    * rename class files accordingly
* Implement true autoloader for CLI
    * rename class files accordingly

= 1.3.13 =
* First public release
