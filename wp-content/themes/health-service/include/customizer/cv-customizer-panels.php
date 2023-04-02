<?php
/**
 * health-service manage the Customizer panels.
 *
 * @subpackage health-service
 * @since 1.0 
 */

/**
 * General Settings Panel
 */
Kirki::add_panel( 'health_service_general_panel', array(
	'priority' => 10,
	'title'    => __( 'General Settings', 'health-service' ),
) );

/**
 * Header Settings Panel
 */
Kirki::add_panel( 'health_service_header_panel', array(
	'priority' => 15,
	'title'    => __( 'Header Options', 'health-service' ),
) );

/**
 * Frontpage Settings Panel
 */
Kirki::add_panel( 'health_service_frontpage_panel', array(
	'priority' => 20,
	'title'    => __( 'Theme Front Page', 'health-service' ),
) );

/**
 * Design Settings Panel
 */
Kirki::add_panel( 'health_service_design_panel', array(
	'priority' => 25,
	'title'    => esc_html__( 'Design Settings', 'health-service' ),
) );