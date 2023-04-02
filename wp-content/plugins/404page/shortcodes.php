<?php

/**
 * The 404page Plugin Shortcodes
 *
 * @since 11.4.0
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

 
// shortcode to show the url that caused the 404 error
add_shortcode( 'pp_404_url', 'pp_404_get_the_url' );

?>