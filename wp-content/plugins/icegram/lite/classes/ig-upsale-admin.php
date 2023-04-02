<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Upsell feature Class
*/
if ( ! class_exists( 'Icegram_upsale' ) ) {
	class Icegram_upsale {
		function __construct() {

			add_filter('icegram_message_field_link' ,array(&$this, 'display_cta_upsale'));
			add_action('icegram_after_button_label', array(&$this, 'add_button_animations_upsale'), 10, 2);
			add_action( 'icegram_after_campaign_where_rule',  array( &$this, 'add_option_where_upsale' ),10,2);
			add_action( 'icegram_after_campaign_when_rule',  array( &$this, 'add_option_for_time_upsale' ),10,2);
			add_action('icegram_additional_campaign_rules', array(&$this,'display_countdown_timer_upsale'),10,2);
			add_action( 'icegram_campaign_target_rules', array( &$this, 'add_campaign_geo_target_rules_form_upsale' ), 15, 2 );
			add_action('icegram_add_campaign_ctas', array( &$this, 'campaign_st_ctas_upsale' ), 10 );
			add_action( 'add_meta_boxes', array( &$this, 'add_campaigns_analytics_metaboxes' ), 0 );

			add_action('icegram_behavior_settings', array($this,'display_content_locker_upsell'),10,2);
			add_action('icegram_behavior_settings', array(&$this,'display_behavior_triggers_upsale'),10,2);

			add_action( 'icegram_after_campaign_pages_where_rule',  array( &$this, 'add_selected_post_types_rule_upsell' ),10,2);

			// Add upsale metabox only if there isn't any other ongoing sale period.
			// if ( ! self::is_offer_period( 'bfcm' ) ) {
			// 	add_action( 'add_meta_boxes', array( &$this, 'add_upsell_notice' ), 0 );
			// }
			
			
		}

		function add_campaigns_analytics_metaboxes(){
			global $icegram;
			
			if( $icegram->can_upsell_features( array('lite') ) ){
				add_meta_box( 'campaign_stats_upsale', __( 'Statistics', 'icegram' ), array( &$this, 'print_campaign_image' ), 'ig_campaign', 'normal', 'high' );
			}
		}


		function print_campaign_image(){
			global $icegram;
			?>
			<a href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=analytics&utm_campaign=ig_upsell" target="blank"><img src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/analytics.png"/></a>
			<?php
		}

		
		function display_cta_upsale( $params ){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<a class="ig_cta_upsale" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=cta_actions&utm_campaign=ig_upsell" target="_blank"><img class="" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/cta_actions.png"/></a>
			<?php
			}

			return $params;
		}

		function add_button_animations_upsale( $message_id, $message_data ){
			global $icegram;
			
			if( $icegram->can_upsell_features( array('lite', 'plus', 'pro') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=cta_animations&utm_campaign=ig_upsell" target="_blank"><img src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/cta_effects.png"/></a>
			
			<?php
			}
		}

		function display_behavior_triggers_upsale( $message_id, $message_data ){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=behavior_trigger&utm_campaign=ig_upsell" target="_blank"><img class="" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/behavior_triggers.png"/></a>
			<?php
			}
		}

		function add_option_where_upsale( $message_id, $message_data ){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=where_not_rule&utm_campaign=ig_upsell" target="_blank"><img class="-ml-2" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/do_not_show_urls.png"/></a>
			<?php
			}

			if( $icegram->can_upsell_features( array('lite', 'plus', 'pro') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=remote_site_rule&utm_campaign=ig_upsell" target="_blank"><img class="-ml-1.5" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/remote_sites.png"/></a>
			<?php
			}
		}
		
		function add_option_for_time_upsale( $message_id, $message_data ){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=when_rule&utm_campaign=ig_upsell" target="_blank"><img class="-ml-2" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/when_rules.png"/></a>
			<?php
			}
		}

		function display_countdown_timer_upsale( $message_id, $message_data ){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<div class="options_group">
					<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=countdown_timer&utm_campaign=ig_upsell" target="_blank"><img class="ml-3" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/countdown_timer.png"/></a>
				</div>
			<?php
			}
		}

		function add_campaign_geo_target_rules_form_upsale( $message_id, $message_data ){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus', 'pro') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=geo_targeting&utm_campaign=ig_upsell" target="_blank"><img class="mt-2" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/geo-targeting.png"/></a>	
			<?php
			}
		}
		
		function campaign_st_ctas_upsale(){
			global $icegram;

			if( $icegram->can_upsell_features( array('lite', 'plus', 'pro') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=split_testing&utm_campaign=ig_upsell" target="_blank"><img src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/split_testing.png"/></a>	
			<?php
			}
		}

		function display_content_locker_upsell( $message_id, $message_data ){
			global $icegram;
			
			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=split_testing&utm_campaign=ig_upsell" target="_blank"><img src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/content_locker.png"/></a>
			<?php
			}
		}
		
		function add_selected_post_types_rule_upsell( $campaign_id, $campaign_target_rules ){
			global $icegram;
			
			if( $icegram->can_upsell_features( array('lite', 'plus') ) ){
			?>
				<a class="" href="https://www.icegram.com/pricing/?utm_source=in_app_new&utm_medium=split_testing&utm_campaign=ig_upsell" target="_blank"><img class="-ml-2" src="<?php echo $icegram->plugin_url ?>/assets/images/upsell/selected-post-types.png"/></a>
			<?php
			}
		}

	}
	$icegram_upsale = new Icegram_upsale();
}
