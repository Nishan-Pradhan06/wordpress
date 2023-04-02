<?php
/**
 * Post Options
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_post_options',
	array(
		'title' => esc_html__( 'Post Options', 'pure-portfolio' ),
		'panel' => 'pure_portfolio_theme_options',
	)
);

// Post Options - Hide Date.
$wp_customize->add_setting(
	'pure_portfolio_post_hide_date',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_post_hide_date',
		array(
			'label'   => esc_html__( 'Hide Date', 'pure-portfolio' ),
			'section' => 'pure_portfolio_post_options',
		)
	)
);

// Post Options - Hide Author.
$wp_customize->add_setting(
	'pure_portfolio_post_hide_author',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_post_hide_author',
		array(
			'label'   => esc_html__( 'Hide Author', 'pure-portfolio' ),
			'section' => 'pure_portfolio_post_options',
		)
	)
);

// Post Options - Hide Category.
$wp_customize->add_setting(
	'pure_portfolio_post_hide_category',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_post_hide_category',
		array(
			'label'   => esc_html__( 'Hide Category', 'pure-portfolio' ),
			'section' => 'pure_portfolio_post_options',
		)
	)
);

// Post Options - Hide Tag.
$wp_customize->add_setting(
	'pure_portfolio_post_hide_tags',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_post_hide_tags',
		array(
			'label'   => esc_html__( 'Hide Tag', 'pure-portfolio' ),
			'section' => 'pure_portfolio_post_options',
		)
	)
);

// Post Options - Related Post Label.
$wp_customize->add_setting(
	'pure_portfolio_post_related_post_label',
	array(
		'default'           => __( 'Related Posts', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_post_related_post_label',
	array(
		'label'    => esc_html__( 'Related Posts Label', 'pure-portfolio' ),
		'section'  => 'pure_portfolio_post_options',
		'settings' => 'pure_portfolio_post_related_post_label',
		'type'     => 'text',
	)
);

// Post Options - Hide Related Posts.
$wp_customize->add_setting(
	'pure_portfolio_post_hide_related_posts',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_post_hide_related_posts',
		array(
			'label'   => esc_html__( 'Hide Related Posts', 'pure-portfolio' ),
			'section' => 'pure_portfolio_post_options',
		)
	)
);
