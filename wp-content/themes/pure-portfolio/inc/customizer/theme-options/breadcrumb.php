<?php
/**
 * Breadcrumb
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_breadcrumb',
	array(
		'title' => esc_html__( 'Breadcrumb', 'pure-portfolio' ),
		'panel' => 'pure_portfolio_theme_options',
	)
);

// Breadcrumb - Enable Breadcrumb.
$wp_customize->add_setting(
	'pure_portfolio_enable_breadcrumb',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_breadcrumb',
		array(
			'label'   => esc_html__( 'Enable Breadcrumb', 'pure-portfolio' ),
			'section' => 'pure_portfolio_breadcrumb',
		)
	)
);

// Breadcrumb - Separator.
$wp_customize->add_setting(
	'pure_portfolio_breadcrumb_separator',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '/',
	)
);

$wp_customize->add_control(
	'pure_portfolio_breadcrumb_separator',
	array(
		'label'           => esc_html__( 'Separator', 'pure-portfolio' ),
		'active_callback' => 'pure_portfolio_is_breadcrumb_enabled',
		'section'         => 'pure_portfolio_breadcrumb',
	)
);
