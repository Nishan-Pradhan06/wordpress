<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.11.0
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.11.0
 */
class Icegram_Activator {

	/**
	 * Handles tasks to do on plugin activation
	 *
	 * @since    1.11.0
	 */
	public static function activate() {
		// Redirect to welcome screen
		delete_option( '_icegram_activation_redirect' );
		add_option( '_icegram_activation_redirect', 'pending' );
		do_action( 'ig_activated' );
	}
}