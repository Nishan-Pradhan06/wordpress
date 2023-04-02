<?php
/**
 * Page Loader
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_loader',
	array(
		'title' => esc_html__( 'Page Loader', 'pure-portfolio' ),
		'panel' => 'pure_portfolio_theme_options',
	)
);

// Page Loader - Enable loader.
$wp_customize->add_setting(
	'pure_portfolio_enable_loader',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
		'default'           => false,
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_loader',
		array(
			'label'   => esc_html__( 'Enable Loader', 'pure-portfolio' ),
			'section' => 'pure_portfolio_loader',
		)
	)
);

// Page Loader - Loader Style.
$wp_customize->add_setting(
	'pure_portfolio_loader_type',
	array(
		'default'           => 'style-2',
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
	)
);

$wp_customize->add_control(
	'pure_portfolio_loader_type',
	array(
		'label'           => esc_html__( 'Loader Style', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_loader',
		'type'            => 'select',
		'choices'         => array(
			'style-1' => __( 'Style 1', 'pure-portfolio' ),
			'style-2' => __( 'Style 2', 'pure-portfolio' ),
			'style-3' => __( 'Style 3', 'pure-portfolio' ),
			'style-4' => __( 'Style 4', 'pure-portfolio' ),
		),
		'active_callback' => function( $control ) {
			return ( $control->manager->get_setting( 'pure_portfolio_enable_loader' )->value() );
		},
	)
);
