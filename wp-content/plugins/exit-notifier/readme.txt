=== Exit Notifier ===
Contributors: cvs@cvstech.com
Donate link: https://cvstech.com/donate
Tags: exit link, speed bump, external link, Credit Union, pop up
Requires at least: 4.0
Tested up to: 5.7
Stable tag: 1.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a way to display a notification to site users that they have clicked an external link and are leaving your site.

== Description ==

Some industries' compliance recommendations suggest that a notice be presented anytime someone leaves your site. I searched for a plugin to do this, and came up empty, so here you go!

Features:
* Works with little or no configuration.
* The title and the body of the exit box, and the text on the buttons are all editable, and honor shortcodes.
* There are also options for providing a visual indication on your selected links, and for opening selected links in a new window/tab.
* You can completely customize the display of the dialog by modifying the CSS.
* You can set a timeout that will continue or cancel when the time expires, with optional visual feedback.
* Add CSS classes to selected links.
* Add Custom CSS that applies to the whole site.
* You can add custom tags in the anchor tag for each link to provide custom title and body for each link.
* Compatible with simple forms in some cases. Should work well with WooCommerce External/Affiliate product pages.
* Will honor &lt;a target="whatever"&gt; for selectively opening in a new tab/window.
* Allows you to add rel="nofollow" to all outbound links.
* You can now exclude individual links by applying a CSS class.
* Accessibility issues addressed with library updates.

== NOTICE ==

When upgrading, please be sure to clear the cache if you're using a caching plugin like the excellent WP Fastest Cache (http://www.wpfastestcache.com/) or something similar. There have been substantial changes and having older versions of the files cached will almost certainly lead to problems when an external link is clicked.

== Credit where credit is due ==

I have made liberal use of the excellent Wordpress Plugin Template by Hugh Lashbrooke found at https://github.com/hlashbrooke/WordPress-Plugin-Template. Thanks, Hugh!

Also, to <a href="https://htmlguy.com">HTMLGuy</a>, the maker of <a href="https://htmlguyllc.github.io/jAlert/">jAlert</a>, a very versatile and simple alert component! Thanks!


== Installation ==

Installing "Exit Notifier" can be done either by searching for "Exit Notifier" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What is the plugin for? =

This plugin is designed to provide a mechanism for notifying users of your site that they are leaving for another server. It can also be used to pop an alert for pretty much any &lt;a&gt; tag target (and now some forms!) with some jQuery selector magic. Some industries recommend notifying users if they are leaving your site.

= Can I edit the text in the box? =

Yes. The plugin provides generic defaults, but the settings page allows you to edit the title and body of the exit box. You can now also edit the text of the buttons, and determine the box behavior and look and feel.<br>
You can also add exit-notifier-title="Your title here" and/or exit-notifier-body="Custom Exit Notifier Body text" to any link to customize the message for that particular link.

= How do I "whitelist" links so the notifier does not appear even though they are offsite? =

You can bypass exit notifier for some links by following the guidelines here: <a href="https://wordpress.org/support/topic/a-way-to-whitelist-a-site/">https://wordpress.org/support/topic/a-way-to-whitelist-a-site/</a>

== Screenshots ==

1. A sample notice in action.
2. Default exit box closeup.
3. Content settings page.
4. Alternate Content settings page.
5. Behavior settings page.
6. Display settings page.
7. Timeout settings page.
8. Custom CSS settings page.

== Changelog ==
= 1.9.1 =
* Added target parameter to exit_notifier_js() function call.

= 1.9.0 =
* Confirm compatibility with WordPress 5.7
* Add JavaScript callable function exit_notifier_js(). Thanks Brian!

= 1.8.3 =
* Fix IE11 BUG with sweetalert addition. Fix provided by @dwarho in the support threads. Thanks @dwarho!

= 1.8.2 =
* Fix Custom Theme BUG -- Thanks Erik!
* Add SweetAlert2 as an option for notification

= 1.8.1 =
* 2020-09-14
* Updated jAlert for accessibility

= 1.8.0 =
* 2020-09-09 (unreleased)

= 1.7.6 =
* 2020-08-25
* Added exclusion class to anchor behavior page.
* Added rel="nofollow" option as requested by Chuck W.
* Added debugtoconsole parameter to print the jQuery selector to the console if checked.

= 1.7.5 =
* 2020-02-19
* BUGFIX: Long overdue fix for Alternate Content not including the URL in the Go button text. First reported by @rdesk.

= 1.7.4 =
* 2020-02-19
* Added shortcode handling to button text as requested by @wust.

= 1.7.3 =
* 2020-02-07
* ACTUAL BUGFIX: Fatal errors in some circumstances when detecting target. Thanks Austin!

= 1.7.2 =
* 2020-02-07
* BUGFIX: Fatal errors in some circumstances when detecting target. Thanks Austin!

= 1.7.1 =
* 2020-02-01
* Honors &lt;a target="_blank"&gt;.
* Timeout text is customizable for Continue and Cancel. Both modifications in this version suggested by @blubstar.

= 1.6.4 =
* 2020-01-07
* Cosmetic/typo issue

= 1.6.3 =
* 2019-11-20
* Compatibility check/update

= 1.6.2 =
* 2019-01-15
* BUGFIX: Error in applying jQuery selector appropriately. Thanks again to Paul A. for his quick testing!

= 1.6.1 =
* 2019-01-15
* Form handling is now off by default, due to some unforseen interactions on some sites. Thanks to Paul A. for finding the issue.

= 1.6.0 =
* 2019-01-14
* Add the ability to attach Exit Notifier to forms. Suggested by/with help from @jdrda, @kepu, and Annina W.

= 1.5.6 =
* 2019-01-10
* BUGFIX: Include the URL on the Go button checkbox fixed. Thanks again to @ccondray for alerting me to this one!

= 1.5.5 =
* 2019-01-10
* Added Advanced tab in settings and the option to not delete settings on uninstall.
* Due to inspiration from @internetnoob, added a per-link option to customize the title and body for each link with custom attributes.

= 1.5.4 =
* 2019-01-10
* BUGFIX: Custom theme stopped working in 1.5. Thanks to @ccondray for helping find the bug!

= 1.5.3 =
* 2018-12-17
* Updated a few cosmetic issues and credits, including a warning about caching plugins when upgrading from 1.4 to 1.5 and later.
* Added the option to blur the background.
* Properly cleaning up settings when plugin is deleted.

= 1.5.2 =
* 2018-12-14
* Added an Alternate Exit Notifier option and more timeout options.

= 1.5.1 =
* 2018-12-13
* BUGFIX! Missing file. Sorry everyone!

= 1.5.0 =
* 2018-12-13
* Updated jAlert version
* Fixed some WCAG issues brought to my attention by @mothershiparts
* Added the ability to expand the target URL in the body or title by including {target} in the text. Thanks to @mdsteveb for the suggestion.
* Added an option to display a progress bar for the timeout. Thanks to @mdsteveb for the suggestion.
* Adjusted jAlert font size to use em instead of px. Thanks to @hmsproducts for the suggestion.

= 1.4.5 =
* 2018-12-12
* Added option to add CSS classes to selected links.
* Added sensible hardcoded defaults in case of no selections preventing errors in some installations before specific configurations are chosen.

= 1.4.3 =
* 2017-09-07
* Internal changes for a specific use case.

= 1.4.2 =
* 2017-06-15
* Fixed minor error involving the Exit Box Theme sometimes not being selected leading to it failing to fire.

= 1.4.1 =
* 2017-06-09
* Minor metadata update

= 1.4.0 =
* 2017-06-09
* Added the timeout feature and settings tab.

= 1.3.4 =
* 2017-04-03
* Added exitNotifierLink class to all selected links so that you can style them to suit. Added the option to use no animation.

= 1.3.3 =
* 2017-03-16
* Added option to turn off URL in button text, and process shortcodes in the body text and popup title.

= 1.3.2 =
* 2017-03-01
* Added in an advanced CSS field that allows editing CSS that will affect the whole site and should take precedence over other CSS. WARNING: Do not use if you are not confident in your understanding of CSS!

= 1.3.1 =
* 2017-02-23
* Narrowed the scope of the Custom CSS setting to prevent sitewide CSS issues.

= 1.3.0 =
* 2017-02-15
* Added the ability to completely customize the look of the dialog by modifying the CSS.

= 1.2.3 =
* 2017-02-11
* Fixed CSS defining width for text fields affecting more than intended

= 1.2.2 =
* 2015-12-17
* Verified compatibility with WordPress 4.4. Updated Behavior tab in settings to clear up how the jQuery selector gets used.

= 1.2.1 =
* 2015-09-25
* Rearranged settings into tabs for readability and to reduce the need to scroll. Added backgroundColor option.

= 1.2 =
* 2015-09-24
* Visual controls added to the admin page

= 1.1.2 =
* 2015-09-14
* Minor terminology updates.

= 1.1.1 =
* 2015-09-14
* Added option to change the jQuery selector, allowing you to affect what links are notified.

= 1.0.2 =
* 2015-09-13
* Updated default body text to better match compliance suggestions.

= 1.0.1 =
* 2015-08-16
* Added options for visual indication of an external link and whether or not to open external links in a new window/tab.

= 1.0 =
* 2015-08-15
* Initial release

== Upgrade Notice ==
= 1.9.1 =
Added target parameter to exit_notifier_js() function call.

= 1.9.0 =
Confirm compatibility with WordPress 5.7
Add JavaScript callable function exit_notifier_js(). Thanks Brian!

= 1.8.3 =
Fix IE11 BUG with sweetalert addition. Fix provided by @dwarho in the support threads. Thanks @dwarho!

= 1.8.2 =
Fix Custom Theme BUG -- Thanks Erik!
Add SweetAlert2 as an option for notification

= 1.8.1 =
Updated jAlert for accessibility

= 1.7.6 =
Added exclusion class to anchor behavior page.
Added rel="nofollow" option as requested by Chuck W.
Added debugtoconsole parameter to print the jQuery selector to the console if checked.

= 1.7.5 =
BUGFIX: Long overdue fix for Alternate Content not including the URL in the Go button text. First reported by @rdesk.

= 1.7.4 =
Added shortcode handling to button text as requested by @wust.

= 1.7.3 =
ACTUAL BUGFIX: Fatal errors in some circumstances when detecting target. Thanks Austin!

= 1.7.2 =
BUGFIX: Fatal errors in some circumstances when detecting target. Thanks Austin!

= 1.7.1 =
Honors &lt;a target="_blank"&gt;.
Timeout text is customizable for Continue and Cancel. Both modifications in this version suggested by @blubstar.

= 1.6.4 =
Cosmetic/typo issue

= 1.6.3 =
Compatibility check/update

= 1.6.2 =
BOGFIX: Error in applying jQuery selector appropriately.

= 1.6.1 =
Form handling is now off by default due to some unforseen interactions with existing installations.

= 1.6.0 =
Add the ability to attach Exit Notifier to forms.

= 1.5.6 =
BUGFIX: Include the URL on the Go button checkbox fixed.

= 1.5.5 =
Added Advanced tab in settings and the option to not delete settings on uninstall.
Due to inspiration from @internetnoob, added a per-link option to customize the title and body for each link with custom attributes.

= 1.5.4 =
BUGFIX: Custom theme stopped working in 1.5. Thanks to @ccondray for helping find the bug!

= 1.5.3 =
Updated a few cosmetic issues and credits, including a warning about caching plugins when upgrading from 1.4 to 1.5 and later.
Added the option to blur the background.
Properly cleaning up settings when plugin is deleted.

= 1.5.2 =
Added an Alternate Exit Notifier option and more timeout options.

= 1.5.1 =
BUGFIX! Missing file. Sorry everyone!

= 1.5.0 =
Updated jAlert version
Fixed some WCAG issues brought to my attention by @mothershiparts. More WCAG fixes on the way soon.
Added the ability to expand the target URL in the body or title by including {target} in the text. Thanks to @mdsteveb for the suggestion.
Added an option to display a progress bar for the timeout. Thanks to @mdsteveb for the suggestion.
Adjusted jAlert font size to use em instead of px. Thanks to @hmsproducts for the suggestion.

= 1.4.5 =
Added option to add CSS classes to selected links.
Added sensible hardcoded defaults in case of no selections preventing errors in some installations before specific configurations are chosen.

= 1.4.3 =
Internal changes for a specific use case.

= 1.4.2 =
Fixed minor error involving the Exit Box Theme sometimes not being selected leading to it failing to fire.

= 1.4.1 =
Minor metadata update.

= 1.4.0 =
Added the timeout feature and settings tab.

= 1.3.4 =
Added exitNotifierLink class to all selected links so that you can style them to suit. Added the option to use no animation.

= 1.3.3 =
Added option to turn off URL in button text, and process shortcodes in the body text and popup title.

= 1.3.2 =
Added in an advanced CSS field that allows editing CSS that will affect the whole site and should take precedence over other CSS. WARNING: Do not use if you are not confident in your understanding of CSS!

= 1.3.1 =
Narrowed the scope of the Custom CSS setting to prevent sitewide CSS issues.

= 1.3.0 =
Added the ability to completely customize the look of the dialog by modifying the CSS.

= 1.2.3 =
Fixed CSS defining width for text fields affecting more than intended

= 1.2.2 =
Verified compatibility with WordPress 4.4. Updated Behavior tab in settings to clear up how the jQuery selector gets used.

= 1.2.1 =
Rearranged settings into tabs for readability and to reduce the need to scroll. Added backgroundColor option.

= 1.2 =
Added visual control to the admin panel

= 1.1.1 =
Added option to change the jQuery selector, allowing you to affect what links are notified.

= 1.0.2 =
Updated default body text to better match compliance suggestions.

= 1.0.1 =
Added options for visual indication of an external link and whether or not to open external links in a new window/tab.

= 1.0 =
Initial release
