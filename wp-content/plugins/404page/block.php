<?php

/**
 * The 404page Plugin Block
 *
 * @since 11.4.0
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'init', function() {
	
	if ( function_exists('register_block_type_from_metadata' ) ) {
		
		register_block_type_from_metadata( __DIR__, [
			'render_callback' => function( $atts ) {
				$alignmentClass = ( $atts['alignment'] != null ) ? 'has-text-align-' . $atts['alignment'] : '';
				return '<p ' . get_block_wrapper_attributes( [ 'class' => $alignmentClass ] ) . '>' . pp_404_get_the_url( $atts[ 'urltype' ] ) . '</p>';
			}
		] );

	}

} );


// Moved from init to enqueue_block_editor_assets in 11.4.1
add_action( 'enqueue_block_editor_assets', function() {

	wp_enqueue_script(
		'404page-block',
		pp_404page()->get_asset_url( 'js', 'block.js' ),
		[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ],
		pp_404page()->get_plugin_version()
	);

} );