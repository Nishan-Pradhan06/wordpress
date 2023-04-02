<?php
/**
 * Managed the custom classes for design.
 *
 * @subpackage health-service
 * @since 1.0 
 */
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function health_service_body_classes( $classes ) {
    $post = get_post();
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }
    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }
    /**
     * Add classes about style and sidebar layout for archive, post and page
     */
    if ( is_archive() || is_home() ) {
        $archive_sidebar_layout = get_theme_mod( 'health_service_archive_sidebar_layout', 'no-sidebar' );
        $classes[] = esc_attr( $archive_sidebar_layout );
    } elseif( is_single() ) {
        $single_post_sidebar_layout = get_post_meta( $post->ID, 'health_service_post_sidebar_layout', true );
        if ( 'layout--default-sidebar' !== $single_post_sidebar_layout && !empty( $single_post_sidebar_layout ) ) {
            $classes[] = esc_attr( $single_post_sidebar_layout );
        } else {
            $posts_sidebar_layout = get_theme_mod( 'health_service_posts_sidebar_layout', 'right-sidebar' );
            $classes[] = esc_attr( $posts_sidebar_layout );
        }
    } elseif( is_page() ) {
        $single_page_sidebar_layout = get_post_meta( $post->ID, 'health_service_post_sidebar_layout', true );
        if ( 'layout--default-sidebar' !== $single_page_sidebar_layout && !empty( $single_page_sidebar_layout ) ) {
            $classes[] = esc_attr( $single_page_sidebar_layout );
        } else {
            $pages_sidebar_layout = get_theme_mod( 'health_service_pages_sidebar_layout', 'right-sidebar' );
            $classes[] = esc_attr( $pages_sidebar_layout );
        }
    }
    return $classes;
}
add_filter( 'body_class', 'health_service_body_classes' );