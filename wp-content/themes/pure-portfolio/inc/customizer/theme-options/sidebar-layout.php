<?php
/**
 * Sidebar Option
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_sidebar_option',
	array(
		'title' => esc_html__( 'Layout', 'pure-portfolio' ),
		'panel' => 'pure_portfolio_theme_options',
	)
);

// Sidebar Option - Global Sidebar Position.
$wp_customize->add_setting(
	'pure_portfolio_sidebar_position',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'pure_portfolio_sidebar_position',
	array(
		'label'   => esc_html__( 'Global Sidebar Position', 'pure-portfolio' ),
		'section' => 'pure_portfolio_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'pure-portfolio' ),
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'pure-portfolio' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'pure-portfolio' ),
		),
	)
);

// Sidebar Option - Post Sidebar Position.
$wp_customize->add_setting(
	'pure_portfolio_post_sidebar_position',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'pure_portfolio_post_sidebar_position',
	array(
		'label'   => esc_html__( 'Post Sidebar Position', 'pure-portfolio' ),
		'section' => 'pure_portfolio_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'pure-portfolio' ),
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'pure-portfolio' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'pure-portfolio' ),
		),
	)
);

// Sidebar Option - Page Sidebar Position.
$wp_customize->add_setting(
	'pure_portfolio_page_sidebar_position',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'pure_portfolio_page_sidebar_position',
	array(
		'label'   => esc_html__( 'Page Sidebar Position', 'pure-portfolio' ),
		'section' => 'pure_portfolio_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'pure-portfolio' ),
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'pure-portfolio' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'pure-portfolio' ),
		),
	)
);
