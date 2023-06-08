<?php

/**
 * Plugin Name: Social Share Buttons by Supsystic
 * Plugin URI: http://supsystic.com
 * Description: Social share buttons to increase social traffic and popularity. Social sharing to Facebook, Twitter and other social networks
 * Version: 2.2.7
 * Author: supsystic.com
 * Author URI: http://supsystic.com
 **/

include dirname(__FILE__) . '/app/SupsysticSocialSharing.php';

if (!defined('SSS_PLUGIN_URL')) {
	define('SSS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}
if (!defined('SSS_PLUGIN_ADMIN_URL')) {
	define('SSS_PLUGIN_ADMIN_URL', admin_url());
}

$supsysticSocialSharing = new SupsysticSocialSharing();

$supsysticSocialSharing->run();
$supsysticSocialSharing->activate(__FILE__);
$supsysticSocialSharing->deactivate(__FILE__);
