<?php
/**
 * Gallery Section
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_gallery_section',
	array(
		'panel'    => 'pure_portfolio_front_page_options',
		'title'    => esc_html__( 'Gallery Section', 'pure-portfolio' ),
		'priority' => 40,
	)
);

// Gallery Section - Enable Section.
$wp_customize->add_setting(
	'pure_portfolio_enable_gallery_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_gallery_section',
		array(
			'label'    => esc_html__( 'Enable Gallery Section', 'pure-portfolio' ),
			'section'  => 'pure_portfolio_gallery_section',
			'settings' => 'pure_portfolio_enable_gallery_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'pure_portfolio_enable_gallery_section',
		array(
			'selector' => '#pure_portfolio_gallery_section .section-link',
			'settings' => 'pure_portfolio_enable_gallery_section',
		)
	);
}

// Gallery Section - Section Title.
$wp_customize->add_setting(
	'pure_portfolio_gallery_title',
	array(
		'default'           => __( 'Our Gallery', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_gallery_title',
	array(
		'label'           => esc_html__( 'Section Title', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_gallery_section',
		'settings'        => 'pure_portfolio_gallery_title',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_gallery_section_enabled',
	)
);

// Gallery Section - Section Text.
$wp_customize->add_setting(
	'pure_portfolio_gallery_text',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_gallery_text',
	array(
		'label'           => esc_html__( 'Section Text', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_gallery_section',
		'settings'        => 'pure_portfolio_gallery_text',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_gallery_section_enabled',
	)
);

// Gallery Section - Content Type.
$wp_customize->add_setting(
	'pure_portfolio_gallery_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
	)
);

$wp_customize->add_control(
	'pure_portfolio_gallery_content_type',
	array(
		'label'           => esc_html__( 'Select Content Type', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_gallery_section',
		'settings'        => 'pure_portfolio_gallery_content_type',
		'type'            => 'select',
		'active_callback' => 'pure_portfolio_is_gallery_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'pure-portfolio' ),
			'category' => esc_html__( 'Category', 'pure-portfolio' ),
		),
	)
);

for ( $i = 1; $i <= 6; $i++ ) {

	// Gallery Section - Select Post.
	$wp_customize->add_setting(
		'pure_portfolio_gallery_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_gallery_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'pure-portfolio' ), $i ),
			'section'         => 'pure_portfolio_gallery_section',
			'settings'        => 'pure_portfolio_gallery_content_post_' . $i,
			'active_callback' => 'pure_portfolio_is_gallery_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => pure_portfolio_get_post_choices(),
		)
	);

}

// Gallery Section - Select Category.
$wp_customize->add_setting(
	'pure_portfolio_gallery_content_category',
	array(
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
	)
);

$wp_customize->add_control(
	'pure_portfolio_gallery_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_gallery_section',
		'settings'        => 'pure_portfolio_gallery_content_category',
		'active_callback' => 'pure_portfolio_is_gallery_section_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => pure_portfolio_get_post_cat_choices(),
	)
);

// Team Section - Button Label.
$wp_customize->add_setting(
	'pure_portfolio_gallery_button_label',
	array(
		'default'           => __( 'View All', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_gallery_button_label',
	array(
		'label'           => esc_html__( 'Button Label', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_gallery_section',
		'settings'        => 'pure_portfolio_gallery_button_label',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_gallery_section_enabled',
	)
);

// Team Section - Button Link.
$wp_customize->add_setting(
	'pure_portfolio_gallery_button_link',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'pure_portfolio_gallery_button_link',
	array(
		'label'           => esc_html__( 'Button Link', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_gallery_section',
		'settings'        => 'pure_portfolio_gallery_button_link',
		'type'            => 'url',
		'active_callback' => 'pure_portfolio_is_gallery_section_enabled',
	)
);
