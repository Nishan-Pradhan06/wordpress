<?php
/**
 * Icegram Stats for Dashboard
 *
 * @author 		Icegram
 * @category 	Admin
 * @package 	Icegram Analytics
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Icegram_Stats' ) ) {

	/**
	* Icegram Stats for Dashboard
	*
	*/
	
	class Icegram_Stats{

		public function __construct() {
			
		}

		public static function get_last_campaign_post_data( $count){
			$args = array(
				  'numberposts' => $count,
				  'post_type'   => 'ig_campaign',
				  'post_status' => array( 'publish', 'draft' ),
			);
			$post_results = get_posts( $args );

			return $post_results;
		}
		
	}
}
