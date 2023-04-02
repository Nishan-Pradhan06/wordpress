<?php
/**
 * Front Page Options
 *
 * @package Pure_Portfolio
 */

$wp_customize->add_panel(
	'pure_portfolio_front_page_options',
	array(
		'title'    => esc_html__( 'Front Page Options', 'pure-portfolio' ),
		'priority' => 130,
	)
);

// Banner Section.
require get_template_directory() . '/inc/customizer/front-page-options/banner.php';

// Skill Section.
require get_template_directory() . '/inc/customizer/front-page-options/skill.php';

// Experience Section.
require get_template_directory() . '/inc/customizer/front-page-options/experience.php';

// Gallery Section.
require get_template_directory() . '/inc/customizer/front-page-options/gallery.php';

// Blog Section.
require get_template_directory() . '/inc/customizer/front-page-options/blog.php';

// CTA Section.
require get_template_directory() . '/inc/customizer/front-page-options/cta.php';
