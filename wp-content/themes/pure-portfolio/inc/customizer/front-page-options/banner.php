<?php
/**
 * Banner Section
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_banner_section',
	array(
		'panel'    => 'pure_portfolio_front_page_options',
		'title'    => esc_html__( 'Banner Section', 'pure-portfolio' ),
		'priority' => 10,
	)
);

// Banner Section - Enable Section.
$wp_customize->add_setting(
	'pure_portfolio_enable_banner_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_banner_section',
		array(
			'label'    => esc_html__( 'Enable Banner Section', 'pure-portfolio' ),
			'section'  => 'pure_portfolio_banner_section',
			'settings' => 'pure_portfolio_enable_banner_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'pure_portfolio_enable_banner_section',
		array(
			'selector' => '#pure_portfolio_banner_section .section-link',
			'settings' => 'pure_portfolio_enable_banner_section',
		)
	);
}

// Banner Section - Image.
$wp_customize->add_setting(
	'pure_portfolio_banner_image',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_image',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'pure_portfolio_banner_image',
		array(
			'label'           => esc_html__( 'Banner Image', 'pure-portfolio' ),
			'section'         => 'pure_portfolio_banner_section',
			'settings'        => 'pure_portfolio_banner_image',
			'active_callback' => 'pure_portfolio_is_banner_section_enabled',
		)
	)
);

// Banner Section - Introduction Text.
$wp_customize->add_setting(
	'pure_portfolio_banner_introduction_text',
	array(
		'default'           => __( 'Hi, I’m Jone Lee', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_banner_introduction_text',
	array(
		'label'           => esc_html__( 'Introduction Text', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_banner_section',
		'settings'        => 'pure_portfolio_banner_introduction_text',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_banner_section_enabled',
	)
);

// Banner Section - Short Description.
$wp_customize->add_setting(
	'pure_portfolio_banner_short_description',
	array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	'pure_portfolio_banner_short_description',
	array(
		'label'           => esc_html__( 'Short Description', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_banner_section',
		'settings'        => 'pure_portfolio_banner_short_description',
		'type'            => 'textarea',
		'active_callback' => 'pure_portfolio_is_banner_section_enabled',
	)
);

// Banner Section - Featured Text.
$wp_customize->add_setting(
	'pure_portfolio_banner_featured_text',
	array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Multi_Input_Custom_control(
		$wp_customize,
		'pure_portfolio_banner_featured_text',
		array(
			'label'           => esc_html__( 'Featured Text List', 'pure-portfolio' ),
			'section'         => 'pure_portfolio_banner_section',
			'settings'        => 'pure_portfolio_banner_featured_text',
			'active_callback' => 'pure_portfolio_is_banner_section_enabled',
		)
	)
);

// Banner Section - Social Links.
$wp_customize->add_setting(
	'pure_portfolio_banner_social_links',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control(
	new Pure_Portfolio_Sortable_Repeater_Custom_Control(
		$wp_customize,
		'pure_portfolio_banner_social_links',
		array(
			'label'           => esc_html__( 'Social Icons', 'pure-portfolio' ),
			'section'         => 'pure_portfolio_banner_section',
			'button_labels'   => array(
				'add' => __( 'Add', 'pure-portfolio' ),
			),
			'active_callback' => 'pure_portfolio_is_banner_section_enabled',
		)
	)
);
