<?php

/**
 * The 404page Plugin
 *
 * 404page allows creation of 404 error pages in WordPress admin
 *
 * @wordpress-plugin
 * Plugin Name: Smart Custom 404 error page [404page]
 * Plugin URI: https://petersplugins.com/404page/
 * Description: Custom 404 the easy way! Set any page as custom 404 error page. No coding needed. Works with (almost) every Theme.
 * Version: 11.4.3
 * Author: Peter Raschendorfer
 * Author URI: https://petersplugins.com
 * Text Domain: 404page
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Loader
 */
require_once( plugin_dir_path( __FILE__ ) . '/loader.php' );

?>