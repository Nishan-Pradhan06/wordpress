<?php
/*
 * Plugin Name: Icegram Engage - Popups, Optins, CTAs & lot more...
 * Plugin URI: https://www.icegram.com/
 * Description: All in one solution to inspire, convert and engage your audiences. Action bars, Popup windows, Messengers, Toast notifications and more. Awesome themes and powerful rules.
 * Version: 3.1.7
 * Tested up to: 6.1.1
 * Author: icegram
 * Author URI: https://www.icegram.com/
 * Copyright (c) 2014-22 Icegram
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: icegram
 * Domain Path: /lang/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'IG_FEEDBACK_TRACKER_VERSION' ) ) {
	define( 'IG_FEEDBACK_TRACKER_VERSION', '1.2.8' );
}

/* ***************************** Initial Compatibility Work (Start) ******************* */

/* =========== Do not edit this code unless you know what you are doing ========= */

/*
 * Note: We are not using IG_PLUGIN_DIR constant at this moment because there are chances
 * It might be defined from older version of IG
 */
require plugin_dir_path( __FILE__ ) . 'lite/classes/feedback/class-ig-tracker.php';

global $ig_tracker;

$ig_tracker = 'IG_Tracker_V_' . str_replace( '.', '_', IG_FEEDBACK_TRACKER_VERSION );


if ( ! function_exists( 'ig_show_upgrade_pro_notice' ) ) {
	/**
	 * Show IG Premium Upgrade Notice
	 *
	 * @since 1.11.0
	 */
	function ig_show_upgrade_pro_notice() {
		$url = admin_url( 'plugins.php?plugin_status=upgrade' );
		?>
		<div class="notice notice-error">
			<p>
			<?php 
			/* translators: %s: Link to Icegram Engage upgrade */
			echo wp_kses_post( sprintf( __( 'You are using older version of <strong>Icegram Engage</strong> plugin. It won\'t work because it needs plugin to be updated. Please update %s plugin.', 'icegram' ),
					'<a href="' . esc_url( $url ) . '" target="_blank">' . __( 'Icegram Engage', 'icegram' ) . '</a>' ) );
			?>
					</p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'deactivate_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$ig_plan = 'lite';
if ( 'icegram-engage.php' === basename( __FILE__ ) ) {
	$ig_plan = 'premium';
} 
$current_active_plugins = $ig_tracker::get_active_plugins();

if ( 'premium' === $ig_plan ) {
	if ( in_array( 'icegram/icegram.php', $current_active_plugins, true ) ) {
		deactivate_plugins( 'icegram/icegram.php', true );
	}
} else {
	/**
	 * Steps:
	 * - Check Whether Icegram Engage Installed
	 * - If It's installed & It's < 2.0.0 => Show Upgrade Notice
	 * - If It's installed & It's >= 2.0.0 => return
	 */

	//- If It's installed & It's < 2.0.0 => Show Upgrade Notice
	$all_plugins = $ig_tracker::get_plugins( 'all', true );

	$ig_prem_plugin         = 'icegram-engage/icegram-engage.php';
	$ig_prem_plugin_version = ! empty( $all_plugins[ $ig_prem_plugin ] ) ? $all_plugins[ $ig_prem_plugin ]['version'] : '';

	if ( ! empty( $ig_prem_plugin_version ) ) {

		// Is Premium active?
		$is_premium_active = $all_plugins[ $ig_prem_plugin ]['is_active'];

		// Free >= 2.0.0 && Premium < 2.0.0
		if ( version_compare( $ig_prem_plugin_version, '2.0.0', '<' ) ) {

			// Show Upgrade Notice if It's Admin Screen.
			if ( is_admin() ) {
				add_action( 'admin_head', 'ig_show_upgrade_pro_notice', PHP_INT_MAX );
			}

		} elseif ( $is_premium_active && version_compare( $ig_prem_plugin_version, '2.0.0', '>=' ) ) {
			return;
		}
	}
}

/* ***************************** Initial Compatibility Work (End) ******************* */

if ( ! defined( 'IG_PLUGIN_DIR' ) ) {
	define( 'IG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'IG_PLUGIN_URL' ) ) {
	define( 'IG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'IG_PLUGIN_FILE' ) ) {
	define( 'IG_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'IG_PLUGIN_VERSION' ) ) {
  	define( 'IG_PLUGIN_VERSION', '3.1.7' );
}

if ( ! defined( 'IG_PRODUCT_ID' ) ) {
	define( 'IG_PRODUCT_ID', 1000 );
}

require plugin_dir_path( __FILE__ ) . 'lite/class-icegram.php';
require plugin_dir_path( __FILE__ ) . 'lite/class-icegram-loader.php';


if ( ! function_exists( 'activate_icegram' ) ) {
	/**
	 * The code that runs during plugin activation.
	 * 
	 * @param bool $network_wide Is plugin being activated on a network.
	 */
	function activate_icegram( $network_wide ) {

		global $wpdb;
		require_once plugin_dir_path( __FILE__ ) . 'lite/classes/class-icegram-activator.php';

		if ( is_multisite() && $network_wide ) {
			
			// Get all active blogs in the network and activate plugin on each one
			$blog_ids = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE deleted = %d", 0 ) );
			foreach ( $blog_ids as $blog_id ) {
				ig_activate_on_blog( $blog_id );
			}
		} else {
			Icegram_Activator::activate();
		}
	}
}

if ( ! function_exists( 'deactivate_icegram' ) ) {
	/**
	 * The code that runs during plugin deactivation.
	 * 
	 * @param bool $network_wide Is plugin being activated on a network.
	 * 
	 */
	function deactivate_icegram( $network_wide ) {

		require_once plugin_dir_path( __FILE__ ) . 'lite/classes/class-icegram-deactivator.php';

		if ( is_multisite() && $network_wide ) {
			
			global $wpdb;
			
			// Get all active blogs in the network.
			$blog_ids = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE deleted = %d", 0 ) );
			foreach ( $blog_ids as $blog_id ) {
				// Run deactivation code on each one
				ig_trigger_deactivation_in_multisite( $blog_id );
			}
		} else {
			Icegram_Deactivator::deactivate();
		}
	}
}

if ( ! function_exists( 'ig_activate_on_blog' ) ) {

	/**
	 * Function to trigger Icegram's activation code for individual site/blog in a network.
	 * 
	 * @param  int $blog_id Blog ID of newly created site/blog.
	 * 
	 * @since  1.11.0
	 */
	function ig_activate_on_blog( $blog_id ) {
		switch_to_blog( $blog_id );
		Icegram_Activator::activate();
		restore_current_blog();
	}
}

if ( ! function_exists( 'ig_trigger_deactivation_in_multisite' ) ) {

	/**
	 * Function to trigger Icegram deactivation code for individual site in a network.
	 * 
	 * @param  int $blog_id Blog ID of newly created site/blog.
	 * 
	 * @since  1.11.0
	 */
	function ig_trigger_deactivation_in_multisite( $blog_id ) {
		switch_to_blog( $blog_id );
		Icegram_Deactivator::deactivate();
		restore_current_blog();
	}
}

register_activation_hook( __FILE__, 'activate_icegram' );
register_deactivation_hook( __FILE__, 'deactivate_icegram' );

if ( ! function_exists( 'initialize_icegram' ) ) {
	function initialize_icegram() {
		/* @var Icegram Object */
		global $icegram;

		// i18n / l10n - load translations
		load_plugin_textdomain( 'icegram', false, IG_PLUGIN_DIR . 'lite/lang/' );

		$icegram = new Icegram();

		do_action( 'icegram_loaded' );
	}
}

add_action( 'plugins_loaded', 'initialize_icegram' );

add_filter( 'ig-engage_is_page_for_notifications', 'ig_show_notification');

if ( ! function_exists( 'ig_show_notification' ) ) {
	function ig_show_notification(){

		$screen = get_current_screen();
		if ( in_array( $screen->id, array( 'ig_campaign', 'ig_message', 'edit-ig_campaign', 'edit-ig_message', 'ig_campaign_page_icegram-reports', 'ig_campaign_page_icegram-support', 'ig_campaign_page_icegram-settings', 'ig_campaign_page_icegram-upgrade' ) ) ){
			return true;		
		} 
		return false;
		
	}
}

if ( ! function_exists( 'IG' ) ) {
	
	/**
	 * Icegram instance
	 *
	 * @param string $plugin_path Plugin path from which files to load.
	 * 
	 * @return Icegram
	 *
	 * @since 1.11.0
	 */
	function IG( $plugin_path = '' ) {
		$icegram_loader = Icegram_Loader::instance();
		// Load files if plugin path given.
		if ( ! empty( $plugin_path ) ) {
			$icegram_loader->load_dependencies( $plugin_path );
		}
		return $icegram_loader;
	}
}

$current_plugin_path = plugin_dir_path( __FILE__ );

/** 
 * We need to pass the plugin path explicitly using $current_plugin_path variable. 
 * We are not using IG_PLUGIN_DIR constant here, since using IG_PLUGIN_DIR constant causes premium version files not getting loaded when lite version is active and user is activating premium versions.
 * In that case, value of IG_PLUGIN_DIR constant is the path of Icegram lite plugin(since it is loaded first before premium version) which does not have premium version's file thus these files are not loaded.
 */
IG( $current_plugin_path );