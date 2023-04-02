<?php
/**
 * Typography
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_typography',
	array(
		'panel' => 'pure_portfolio_theme_options',
		'title' => esc_html__( 'Typography', 'pure-portfolio' ),
	)
);

// Typography - Site Title Font.
$wp_customize->add_setting(
	'pure_portfolio_site_title_font',
	array(
		'default'           => 'Fira Sans',
		'sanitize_callback' => 'pure_portfolio_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'pure_portfolio_site_title_font',
	array(
		'label'    => esc_html__( 'Site Title Font Family', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_typography',
		'settings' => 'pure_portfolio_site_title_font',
		'type'     => 'select',
		'choices'  => pure_portfolio_get_all_google_font_families(),
	)
);

// Typography - Site Description Font.
$wp_customize->add_setting(
	'pure_portfolio_site_description_font',
	array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'pure_portfolio_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'pure_portfolio_site_description_font',
	array(
		'label'    => esc_html__( 'Site Description Font Family', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_typography',
		'settings' => 'pure_portfolio_site_description_font',
		'type'     => 'select',
		'choices'  => pure_portfolio_get_all_google_font_families(),
	)
);

// Typography - Header Font.
$wp_customize->add_setting(
	'pure_portfolio_header_font',
	array(
		'default'           => 'Fira Sans',
		'sanitize_callback' => 'pure_portfolio_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'pure_portfolio_header_font',
	array(
		'label'    => esc_html__( 'Header Font Family', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_typography',
		'settings' => 'pure_portfolio_header_font',
		'type'     => 'select',
		'choices'  => pure_portfolio_get_all_google_font_families(),
	)
);

// Typography - Body Font.
$wp_customize->add_setting(
	'pure_portfolio_body_font',
	array(
		'default'           => 'Merriweather',
		'sanitize_callback' => 'pure_portfolio_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'pure_portfolio_body_font',
	array(
		'label'    => esc_html__( 'Body Font Family', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_typography',
		'settings' => 'pure_portfolio_body_font',
		'type'     => 'select',
		'choices'  => pure_portfolio_get_all_google_font_families(),
	)
);
