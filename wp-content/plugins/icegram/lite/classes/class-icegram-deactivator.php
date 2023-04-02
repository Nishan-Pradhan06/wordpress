<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.11.0
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.11.0
 */
class Icegram_Deactivator {

	/**
	 * Handles tasks to do on plugin deactivation
	 *
	 * @since 1.11.0
	 */
	public static function deactivate() {
		do_action( 'ig_deactivated' );
	}

}
