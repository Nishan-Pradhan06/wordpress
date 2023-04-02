<?php
/**
 * Pagination
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_pagination',
	array(
		'panel' => 'pure_portfolio_theme_options',
		'title' => esc_html__( 'Pagination', 'pure-portfolio' ),
	)
);

// Pagination - Enable Pagination.
$wp_customize->add_setting(
	'pure_portfolio_enable_pagination',
	array(
		'default'           => true,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_pagination',
		array(
			'label'    => esc_html__( 'Enable Pagination', 'pure-portfolio' ),
			'section'  => 'pure_portfolio_pagination',
			'settings' => 'pure_portfolio_enable_pagination',
			'type'     => 'checkbox',
		)
	)
);

// Pagination - Pagination Type.
$wp_customize->add_setting(
	'pure_portfolio_pagination_type',
	array(
		'default'           => 'default',
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
	)
);

$wp_customize->add_control(
	'pure_portfolio_pagination_type',
	array(
		'label'           => esc_html__( 'Pagination Type', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_pagination',
		'settings'        => 'pure_portfolio_pagination_type',
		'active_callback' => 'pure_portfolio_is_pagination_enabled',
		'type'            => 'select',
		'choices'         => array(
			'default' => __( 'Default (Older/Newer)', 'pure-portfolio' ),
			'numeric' => __( 'Numeric', 'pure-portfolio' ),
		),
	)
);
