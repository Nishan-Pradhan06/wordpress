<?php

/**
 * Dynamic CSS
 */
function pure_portfolio_dynamic_css() {

	$header_font           = get_theme_mod( 'pure_portfolio_header_font', 'Fira Sans' );
	$body_font             = get_theme_mod( 'pure_portfolio_body_font', 'Merriweather' );
	$site_title_font       = get_theme_mod( 'pure_portfolio_site_title_font', 'Fira Sans' );
	$site_description_font = get_theme_mod( 'pure_portfolio_site_description_font', 'Roboto' );

	$custom_css = '';

	$custom_css .= '
    /* Color */
    :root {
		--header-text-color: ' . '#' . esc_attr( get_header_textcolor() ) . ';
    }
    ';

	$custom_css .= '
    /* Typograhpy */
    :root {
        --font-heading: "' . esc_attr( $header_font ) . '", serif;
        --font-main: -apple-system, BlinkMacSystemFont,"' . esc_attr( $body_font ) . '", "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    body,
	button, input, select, optgroup, textarea {
        font-family: "' . esc_attr( $body_font ) . '", serif;
	}

	.site-title a {
        font-family: "' . esc_attr( $site_title_font ) . '", serif;
	}
    
	.site-description {
        font-family: "' . esc_attr( $site_description_font ) . '", serif;
	}
    ';

	wp_add_inline_style( 'pure-portfolio-style', $custom_css );

}
add_action( 'wp_enqueue_scripts', 'pure_portfolio_dynamic_css', 99 );
