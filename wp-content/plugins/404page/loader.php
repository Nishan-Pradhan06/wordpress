<?php

/**
 * The 404page Plugin Loader
 *
 * @since 7
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Load Plugin Foundation
 * @since 11.0.0
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/ppf/loader.php' );
 
 
/**
 * Load Plugin Main File
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-404page.php' );


/**
 * Load Plugin Functions
 */
require_once( plugin_dir_path( __FILE__ ) . '/functions.php' );


/**
 * Load Plugin Shortcodes
 * @since 11.4.0
 */
require_once( plugin_dir_path( __FILE__ ) . '/shortcodes.php' );


/**
 * Load Plugin Block
 * @since 11.4.0
 */
require_once( plugin_dir_path( __FILE__ ) . '/block.php' );


/**
 * Main Function
 */
function pp_404page() {

  return PP_404Page::getInstance( array(
    'file'      => dirname( __FILE__ ) . '/404page.php',
    'slug'      => pathinfo( dirname( __FILE__ ) . '/404page.php', PATHINFO_FILENAME ),
    'name'      => 'Smart Custom 404 error page [404page]',
    'shortname' => '404page',
    'version'   => '11.4.3'
  ) );
    
}



/**
 * Run the plugin
 */
pp_404page();


?>