<?php

/**
 * Render homepage sections.
 */
function pure_portfolio_homepage_sections() {
	$homepage_sections = array_keys( pure_portfolio_get_homepage_sections() );

	foreach ( $homepage_sections as $section ) {
		require get_template_directory() . '/sections/' . $section . '.php';
	}
}
