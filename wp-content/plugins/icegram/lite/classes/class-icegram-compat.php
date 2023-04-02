<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Compatibility class with other plugins
*/
if ( ! class_exists( 'Icegram_Compat_Base' ) ) {
	class Icegram_Compat_Base {

		// Sets up printing compatibility code
		function __construct() {
			global $icegram;
			if($icegram->cache_compatibility === 'yes'){
            	add_action( 'wp_footer', array( &$this, 'render_js' ) );
        	} else {
        		add_action( 'icegram_data_printed', array( &$this, 'render_js' ), 10, 1 );
        	}
		}

		// This will be overridden in child classes
		function render_js( $type ) {
		
		}
	}
}