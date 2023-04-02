<?php
/**
 * health-service manage the Customizer sections.
 *
 * @subpackage health-service
 * @since 1.0 
 */

/**
 * Site Settings
 */
Kirki::add_section( 'health_service_section_site', array(
	'title'    => __( 'Site Settings', 'health-service' ),
	'panel'    => 'health_service_general_panel',
	'priority' => 40,
) );

/**
 * Header Settings
 */
Kirki::add_section( 'health_service_section_site_header', array(
	'title'    => __( 'Top Header Detail', 'health-service' ),
	'panel'    => 'health_service_general_panel',
	'priority' => 41,
) );

/**
 * Sticy Options Settings
 */
Kirki::add_section( 'health_service_section_sticy_header', array(
	'title'    => __( 'Sticy Header Settings', 'health-service' ),
	'panel'    => 'health_service_general_panel',
	'priority' => 42,
) );

/**
 * Hero Section
 */
Kirki::add_section( 'health_service_section_slider_content', array(
	'title'    => __( 'Home Page Slider Settings', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 5,
) );

 

/**
 * About Us Section
 */
Kirki::add_section( 'health_service_section_about_us', array(
	'title'    => __( 'Home About Setting', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 11,
) );

/**
 * Services Section
 */
Kirki::add_section( 'health_service_section_services', array(
	'title'    => __( 'Home Service Settings', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 14,
) );

/**
 * Features Section
 */
Kirki::add_section( 'health_service_section_features', array(
	'title'    => __( 'Home Features Settings', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 8,
) );

/**
 * Portfolio Section
 */
Kirki::add_section( 'health_service_section_portfolio', array(
	'title'    => __( 'Home Portfolio Settings', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 20,
) );


/**
 * Team Section
 */
Kirki::add_section( 'health_service_section_team', array(
	'title'    => __( 'Home Team Section', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 27,
) );

 
/**
 * Blog Section
 */
Kirki::add_section( 'health_service_section_blog', array(
	'title'    => __( 'Home Blog Setting', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 30,
) );
 

/**
 * Callout Section
 */
Kirki::add_section( 'health_service_section_callout_content', array(
	'title'    => __( 'Home Callout Setting', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 17,
) );

/**
 * Testimonial Section
 */
Kirki::add_section( 'health_service_section_testimonial', array(
	'title'    => __( 'Home Testimonial Setting', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 23,
) );
/**
 * Footer Settings
 */
Kirki::add_section( 'health_service_footer_setting', array(
	'title'    => __( 'Footer Settings', 'health-service' ),
	'priority' => 40,
) );

 

/**
 * Footer Copyright Section
 */
Kirki::add_section( 'health_service_section_footer_copyright', array(
	'title'    => __( 'Footer Settings', 'health-service' ),
	'panel'    => 'health_service_frontpage_panel',
	'priority' => 80,
) );