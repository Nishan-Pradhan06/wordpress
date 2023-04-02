<?php
/**
 * Pure Portfolio Theme Customizer
 *
 * @package Pure_Portfolio
 */

// Sanitize callback.
require get_template_directory() . '/inc/customizer/sanitize-callback.php';

// Active Callback.
require get_template_directory() . '/inc/customizer/active-callback.php';

// Custom Controls.
require get_template_directory() . '/inc/customizer/custom-controls/custom-controls.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pure_portfolio_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'pure_portfolio_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'pure_portfolio_customize_partial_blogdescription',
			)
		);
	}

	// Homepage Settings - Enable Homepage Content.
	$wp_customize->add_setting(
		'pure_portfolio_enable_frontpage_content',
		array(
			'default'           => false,
			'sanitize_callback' => 'pure_portfolio_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'pure_portfolio_enable_frontpage_content',
		array(
			'label'           => esc_html__( 'Enable Homepage Content', 'pure-portfolio' ),
			'description'     => esc_html__( 'Check to enable content on static homepage.', 'pure-portfolio' ),
			'section'         => 'static_front_page',
			'type'            => 'checkbox',
			'active_callback' => 'pure_portfolio_is_static_homepage_enabled',
		)
	);

	// Upsell Section.
	$wp_customize->add_section(
		new Pure_Portfolio_Upsell_Section(
			$wp_customize,
			'upsell_section',
			array(
				'title'            => __( 'Pure Portfolio Pro', 'pure-portfolio' ),
				'button_text'      => __( 'Buy Pro', 'pure-portfolio' ),
				'url'              => 'https://ascendoor.com/themes/pure-portfolio-pro/',
				'background_color' => '#2896df',
				'text_color'       => '#fff',
				'priority'         => 0,
			)
		)
	);

	// Theme Options.
	require get_template_directory() . '/inc/customizer/theme-options.php';

	// Front Page Options.
	require get_template_directory() . '/inc/customizer/front-page-options.php';
}
add_action( 'customize_register', 'pure_portfolio_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function pure_portfolio_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function pure_portfolio_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pure_portfolio_customize_preview_js() {
	wp_enqueue_script( 'pure-portfolio-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), PURE_PORTFOLIO_VERSION, true );
}
add_action( 'customize_preview_init', 'pure_portfolio_customize_preview_js' );

/**
 * Enqueue script for custom customize control.
 */
function pure_portfolio_custom_control_scripts() {

	// Append .min if SCRIPT_DEBUG is false.
	$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_style( 'pure-portfolio-custom-controls-css', get_template_directory_uri() . '/assets/css/custom-controls.css', array(), '1.0', 'all' );
	wp_enqueue_script( 'pure-portfolio-custom-controls-js', get_template_directory_uri() . '/assets/js/custom-controls.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'pure_portfolio_custom_control_scripts' );
