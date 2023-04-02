<?php
/**
 * health-service manage the Customizer options of general panel.
 *
 * @subpackage health-service
 * @since 1.0 
 */
Kirki::add_field(
	'health_service_config', array(
		'type'        => 'checkbox',
		'settings'    => 'health_service_home_posts',
		'label'       => esc_attr__( 'Checked to hide latest posts in homepage.', 'health-service' ),
		'section'     => 'static_front_page',
		'default'     => true,
	)
);

// Color Picker field for Primary Color
Kirki::add_field( 
	'health_service_config', array(
		'type'        => 'color',
		'settings'    => 'health_service_theme_color',
		'label'       => esc_html__( 'Primary Color', 'health-service' ),
		'section'     => 'colors',
		'default'     => '#00c1e4',
	)
);