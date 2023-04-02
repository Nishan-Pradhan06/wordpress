<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Icegram_Loader' ) ) {

	class Icegram_Loader {
		/**
		 * IG instance
		 *
		 *
		 * @var Icegram_Loader The one true Icegram_Loader
		 *
		 */
		private static $instance;

		/**
		 * Return a true instance of a class
		 *
		 * @return Icegram_Loader
		 *
		 * @since 1.11.0
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Icegram_Loader ) ) {
				self::$instance = new Icegram_Loader();
			}

			return self::$instance;
		}

		/**
		 * Load required files
		 *
		 * @param string $plugin_path Plugin path from which files to load.
		 * 
		 * @since 1.11.0
		 */
		public function load_dependencies( $plugin_path = '' ) {

			if ( ! empty( $plugin_path ) ) {
				
				$files_to_load = array(
					$plugin_path . 'plus/icegram-engage.php',
				);
	
				foreach ( $files_to_load as $file ) {
					if ( is_file( $file ) ) {
						require_once $file;
					}
				}
			}
		}

	}
}