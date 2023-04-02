<?php
/**
 * Excerpt
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_excerpt_options',
	array(
		'panel' => 'pure_portfolio_theme_options',
		'title' => esc_html__( 'Excerpt', 'pure-portfolio' ),
	)
);

// Excerpt - Excerpt Length.
$wp_customize->add_setting(
	'pure_portfolio_excerpt_length',
	array(
		'default'           => 20,
		'sanitize_callback' => 'pure_portfolio_sanitize_number_range',
	)
);

$wp_customize->add_control(
	'pure_portfolio_excerpt_length',
	array(
		'label'       => esc_html__( 'Excerpt Length (no. of words)', 'pure-portfolio' ),
		'section'     => 'pure_portfolio_excerpt_options',
		'settings'    => 'pure_portfolio_excerpt_length',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 10,
			'max'  => 200,
			'step' => 1,
		),
	)
);

// Excerpt - Read More Text.
$wp_customize->add_setting(
	'pure_portfolio_excerpt_more_text',
	array(
		'default'           => esc_html__( 'Read More', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_excerpt_more_text',
	array(
		'label'    => esc_html__( 'Read More Text', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_excerpt_options',
		'settings' => 'pure_portfolio_excerpt_more_text',
		'type'     => 'text',
	)
);
