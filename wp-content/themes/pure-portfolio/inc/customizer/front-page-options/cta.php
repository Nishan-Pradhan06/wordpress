<?php
/**
 * CTA Section
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_cta_section',
	array(
		'panel'    => 'pure_portfolio_front_page_options',
		'title'    => esc_html__( 'CTA Section', 'pure-portfolio' ),
		'priority' => 60,
	)
);

// CTA Section - Enable Section.
$wp_customize->add_setting(
	'pure_portfolio_enable_cta_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_cta_section',
		array(
			'label'    => esc_html__( 'Enable CTA Section', 'pure-portfolio' ),
			'section'  => 'pure_portfolio_cta_section',
			'settings' => 'pure_portfolio_enable_cta_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'pure_portfolio_enable_cta_section',
		array(
			'selector' => '#pure_portfolio_cta_section .section-link',
			'settings' => 'pure_portfolio_enable_cta_section',
		)
	);
}

// CTA Section - Section Title.
$wp_customize->add_setting(
	'pure_portfolio_cta_title',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_cta_title',
	array(
		'label'           => esc_html__( 'Section Title', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_cta_section',
		'settings'        => 'pure_portfolio_cta_title',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_cta_section_enabled',
	)
);

// CTA Section - Section Text.
$wp_customize->add_setting(
	'pure_portfolio_cta_text',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_cta_text',
	array(
		'label'           => esc_html__( 'Section Text', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_cta_section',
		'settings'        => 'pure_portfolio_cta_text',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_cta_section_enabled',
	)
);

// CTA Section - Background Image.
$wp_customize->add_setting(
	'pure_portfolio_cta_background_image',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_image',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'pure_portfolio_cta_background_image',
		array(
			'label'           => esc_html__( 'Background Image', 'pure-portfolio' ),
			'section'         => 'pure_portfolio_cta_section',
			'settings'        => 'pure_portfolio_cta_background_image',
			'active_callback' => 'pure_portfolio_is_cta_section_enabled',
		)
	)
);

// CTA Section - Button Label.
$wp_customize->add_setting(
	'pure_portfolio_cta_button_label',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_cta_button_label',
	array(
		'label'           => esc_html__( 'Button Label', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_cta_section',
		'settings'        => 'pure_portfolio_cta_button_label',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_cta_section_enabled',
	)
);

// CTA Section - Button Link.
$wp_customize->add_setting(
	'pure_portfolio_cta_button_link',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'pure_portfolio_cta_button_link',
	array(
		'label'           => esc_html__( 'Button Link', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_cta_section',
		'settings'        => 'pure_portfolio_cta_button_link',
		'type'            => 'url',
		'active_callback' => 'pure_portfolio_is_cta_section_enabled',
	)
);
