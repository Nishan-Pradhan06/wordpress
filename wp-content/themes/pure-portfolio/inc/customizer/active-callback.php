<?php
/**
 * Active Callbacks
 *
 * @package Pure_Portfolio
 */

// Theme Options.
function pure_portfolio_is_pagination_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_pagination' )->value() );
}
function pure_portfolio_is_breadcrumb_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_breadcrumb' )->value() );
}

// Banner section.
function pure_portfolio_is_banner_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_banner_section' )->value() );
}

// Skill Section
function pure_portfolio_is_skill_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_skill_section' )->value() );
}

// Experience section.
function pure_portfolio_is_experience_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_experience_section' )->value() );
}
function pure_portfolio_is_experience_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_experience_content_type' )->value();
	return ( pure_portfolio_is_experience_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function pure_portfolio_is_experience_section_and_content_type_page_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_experience_content_type' )->value();
	return ( pure_portfolio_is_experience_section_enabled( $control ) && ( 'page' === $content_type ) );
}

// Gallery section.
function pure_portfolio_is_gallery_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_gallery_section' )->value() );
}
function pure_portfolio_is_gallery_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_gallery_content_type' )->value();
	return ( pure_portfolio_is_gallery_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function pure_portfolio_is_gallery_section_and_content_type_page_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_gallery_content_type' )->value();
	return ( pure_portfolio_is_gallery_section_enabled( $control ) && ( 'page' === $content_type ) );
}
function pure_portfolio_is_gallery_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_gallery_content_type' )->value();
	return ( pure_portfolio_is_gallery_section_enabled( $control ) && ( 'category' === $content_type ) );
}

// Blog section.
function pure_portfolio_is_blog_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_blog_section' )->value() );
}
function pure_portfolio_is_blog_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_blog_content_type' )->value();
	return ( pure_portfolio_is_blog_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function pure_portfolio_is_blog_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'pure_portfolio_blog_content_type' )->value();
	return ( pure_portfolio_is_blog_section_enabled( $control ) && ( 'category' === $content_type ) );
}

// CTA section.
function pure_portfolio_is_cta_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'pure_portfolio_enable_cta_section' )->value() );
}

// Check if static home page is enabled.
function pure_portfolio_is_static_homepage_enabled( $control ) {
	return ( 'page' === $control->manager->get_setting( 'show_on_front' )->value() );
}
