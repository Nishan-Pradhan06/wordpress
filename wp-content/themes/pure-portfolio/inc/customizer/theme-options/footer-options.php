<?php
/**
 * Footer Options
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_footer_options',
	array(
		'panel' => 'pure_portfolio_theme_options',
		'title' => esc_html__( 'Footer Options', 'pure-portfolio' ),
	)
);

// Footer Options - Copyright Text.
/* translators: 1: Year, 2: Site Title with home URL. */
$copyright_default = sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'pure-portfolio' ), '[the-year]', '[site-link]' );
$wp_customize->add_setting(
	'pure_portfolio_footer_copyright_text',
	array(
		'default'           => $copyright_default,
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	'pure_portfolio_footer_copyright_text',
	array(
		'label'    => esc_html__( 'Copyright Text', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_footer_options',
		'settings' => 'pure_portfolio_footer_copyright_text',
		'type'     => 'textarea',
	)
);

// Footer Options - Scroll Top.
$wp_customize->add_setting(
	'pure_portfolio_scroll_top',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_scroll_top',
		array(
			'label'   => esc_html__( 'Enable Scroll Top Button', 'pure-portfolio' ),
			'section' => 'pure_portfolio_footer_options',
		)
	)
);
