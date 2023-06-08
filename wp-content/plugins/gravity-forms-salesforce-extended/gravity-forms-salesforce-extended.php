<?php
/**
 * @wordpress-plugin
 * Plugin Name: Gravity Forms + Salesforce: Web-To Extended
 * Plugin URI: https://gravityplus.pro/gravity-forms-salesforce
 * Description: Additional options for the Salesforce Web-To plugin
 * Version: 1.1.1
 * Author: gravity+
 * Author URI: https://gravityplus.pro
 * Text Domain: gravity-forms-salesforce-extended
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package   GFP_Salesforce_WebTo_Extended
 * @version   1.1.1
 * @author    gravity+ <support@gravityplus.pro>
 * @license   GPL-2.0+
 * @link      https://gravityplus.pro
 * @copyright 2017 gravity+
 *
 * last updated: June 19, 2017
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {

	die;

}

define( 'GFP_SALESFORCE_WEBTO_EXTENDED_CURRENT_VERSION', '1.1.1' );

define( 'GFP_SALESFORCE_WEBTO_EXTENDED_FILE', __FILE__ );

define( 'GFP_SALESFORCE_WEBTO_EXTENDED_PATH', plugin_dir_path( __FILE__ ) );

define( 'GFP_SALESFORCE_WEBTO_EXTENDED_URL', plugin_dir_url( __FILE__ ) );

define( 'GFP_SALESFORCE_WEBTO_EXTENDED_SLUG', plugin_basename( dirname( __FILE__ ) ) );


require_once( 'class-gfp-salesforce-webto-extended.php');

$gravityformssalesforce_webto_extended = new GFP_Salesforce_WebTo_Extended();

$gravityformssalesforce_webto_extended->run();