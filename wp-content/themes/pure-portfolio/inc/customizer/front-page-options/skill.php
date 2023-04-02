<?php
/**
 * Skill Section
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_section(
	'pure_portfolio_skill_section',
	array(
		'panel'    => 'pure_portfolio_front_page_options',
		'title'    => esc_html__( 'Skill Section', 'pure-portfolio' ),
		'priority' => 20,
	)
);

// Skill Section - Enable Section.
$wp_customize->add_setting(
	'pure_portfolio_enable_skill_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'pure_portfolio_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Pure_Portfolio_Toggle_Switch_Custom_Control(
		$wp_customize,
		'pure_portfolio_enable_skill_section',
		array(
			'label'    => esc_html__( 'Enable Skill Section', 'pure-portfolio' ),
			'section'  => 'pure_portfolio_skill_section',
			'settings' => 'pure_portfolio_enable_skill_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'pure_portfolio_enable_skill_section',
		array(
			'selector' => '#pure_portfolio_skill_section .section-link',
			'settings' => 'pure_portfolio_enable_skill_section',
		)
	);
}

// Skill Section - Section Title.
$wp_customize->add_setting(
	'pure_portfolio_skill_section_title',
	array(
		'default'           => __( 'My Expertise', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_skill_section_title',
	array(
		'label'           => esc_html__( 'Section Title', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_skill_section',
		'settings'        => 'pure_portfolio_skill_section_title',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_skill_section_enabled',
	)
);

// Skill Section - Section Subtitle.
$wp_customize->add_setting(
	'pure_portfolio_skill_section_subtitle',
	array(
		'default'           => __( 'Ability & Skills', 'pure-portfolio' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'pure_portfolio_skill_section_subtitle',
	array(
		'label'           => esc_html__( 'Section Subtitle', 'pure-portfolio' ),
		'section'         => 'pure_portfolio_skill_section',
		'settings'        => 'pure_portfolio_skill_section_subtitle',
		'type'            => 'text',
		'active_callback' => 'pure_portfolio_is_skill_section_enabled',
	)
);

for ( $i = 1; $i <= 4; $i++ ) {

	// Skill Section - Skill Value.
	$wp_customize->add_setting(
		'pure_portfolio_skill_value_' . $i,
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_skill_value_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Value %d', 'pure-portfolio' ), $i ),
			'section'         => 'pure_portfolio_skill_section',
			'settings'        => 'pure_portfolio_skill_value_' . $i,
			'type'            => 'number',
			'input_attrs'     => array( 'min' => 1 ),
			'active_callback' => 'pure_portfolio_is_skill_section_enabled',
		)
	);

	// Skill Section - Skill Title.
	$wp_customize->add_setting(
		'pure_portfolio_skill_title_' . $i,
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_skill_title_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Skill Title %d', 'pure-portfolio' ), $i ),
			'section'         => 'pure_portfolio_skill_section',
			'settings'        => 'pure_portfolio_skill_title_' . $i,
			'type'            => 'text',
			'active_callback' => 'pure_portfolio_is_skill_section_enabled',
		)
	);

	// Skill Section - Skill Subtitle.
	$wp_customize->add_setting(
		'pure_portfolio_skill_subtitle_' . $i,
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_skill_subtitle_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Skill Subtitle %d', 'pure-portfolio' ), $i ),
			'section'         => 'pure_portfolio_skill_section',
			'settings'        => 'pure_portfolio_skill_subtitle_' . $i,
			'type'            => 'text',
			'active_callback' => 'pure_portfolio_is_skill_section_enabled',
		)
	);

	// Skill Section - Horizontal Line.
	$wp_customize->add_setting(
		'pure_portfolio_skill_horizontal_line_' . $i,
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new Pure_Portfolio_Customize_Horizontal_Line(
			$wp_customize,
			'pure_portfolio_skill_horizontal_line_' . $i,
			array(
				'section'         => 'pure_portfolio_skill_section',
				'settings'        => 'pure_portfolio_skill_horizontal_line_' . $i,
				'active_callback' => 'pure_portfolio_is_skill_section_enabled',
				'type'            => 'hr',
			)
		)
	);

}
