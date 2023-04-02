<?php
/**
 * Experience Section
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_experience_section',
	array(
		'panel'    => 'pure_portfolio_front_page_options',
		'title'    => esc_html__( 'Experience Section', 'pure-portfolio' ),
		'priority' => 30,
	)
);

// Experience Section - Enable Section.
$wp_customize->add_setting(
	'pure_portfolio_enable_experience_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_experience_section',
		array(
			'label'    => esc_html__( 'Enable Experience Section', 'pure-portfolio' ),
			'section'  => 'pure_portfolio_experience_section',
			'settings' => 'pure_portfolio_enable_experience_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'pure_portfolio_enable_experience_section',
		array(
			'selector' => '#pure_portfolio_experience_section .section-link',
			'settings' => 'pure_portfolio_enable_experience_section',
		)
	);
}

// Experience Section - Section Title.
$wp_customize->add_setting(
	'pure_portfolio_experience_title',
	array(
		'default'           => __( 'Job Experience', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_experience_title',
	array(
		'label'           => esc_html__( 'Section Title', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_experience_section',
		'settings'        => 'pure_portfolio_experience_title',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_experience_section_enabled',
	)
);

// Experience Section - Section Subtitle.
$wp_customize->add_setting(
	'pure_portfolio_experience_subtitle',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_experience_subtitle',
	array(
		'label'           => esc_html__( 'Section Subtitle', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_experience_section',
		'settings'        => 'pure_portfolio_experience_subtitle',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_experience_section_enabled',
	)
);

// Experience Section - Content Type.
$wp_customize->add_setting(
	'pure_portfolio_experience_content_type',
	array(
		'default'           => 'page',
		'sanitize_callback' => 'pure_portfolio_sanitize_select',
	)
);

$wp_customize->add_control(
	'pure_portfolio_experience_content_type',
	array(
		'label'           => esc_html__( 'Select Content Type', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_experience_section',
		'settings'        => 'pure_portfolio_experience_content_type',
		'type'            => 'select',
		'active_callback' => 'pure_portfolio_is_experience_section_enabled',
		'choices'         => array(
			'page' => esc_html__( 'Page', 'pure-portfolio' ),
			'post' => esc_html__( 'Post', 'pure-portfolio' ),
		),
	)
);

for ( $i = 1; $i <= 4; $i++ ) {
	// Experience Section - Select Post.
	$wp_customize->add_setting(
		'pure_portfolio_experience_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_experience_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'pure-portfolio' ), $i ),
			'section'         => 'pure_portfolio_experience_section',
			'settings'        => 'pure_portfolio_experience_content_post_' . $i,
			'active_callback' => 'pure_portfolio_is_experience_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => pure_portfolio_get_post_choices(),
		)
	);

	// Experience Section - Select Page.
	$wp_customize->add_setting(
		'pure_portfolio_experience_content_page_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_experience_content_page_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Page %d', 'pure-portfolio' ), $i ),
			'section'         => 'pure_portfolio_experience_section',
			'settings'        => 'pure_portfolio_experience_content_page_' . $i,
			'active_callback' => 'pure_portfolio_is_experience_section_and_content_type_page_enabled',
			'type'            => 'select',
			'choices'         => pure_portfolio_get_page_choices(),
		)
	);

	// Experience Section - Job Duration.
	$wp_customize->add_setting(
		'pure_portfolio_job_duration_' . $i,
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_job_duration_' . $i,
		array(
			'label'           => esc_html__( 'Job Duration', 'pure-portfolio' ),
			'section'         => 'pure_portfolio_experience_section',
			'settings'        => 'pure_portfolio_job_duration_' . $i,
			'type'            => 'text',
			'active_callback' => 'pure_portfolio_is_experience_section_enabled',
		)
	);

}
