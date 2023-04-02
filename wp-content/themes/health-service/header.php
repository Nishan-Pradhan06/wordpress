<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @subpackage health-service
 * @since health-service
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php   
    if ( function_exists( 'wp_body_open' ) )
    wp_body_open();
    ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content">
<?php esc_html_e( 'Skip to content', 'health-service' ); ?></a>
<?php
	do_action( 'health_service_before_header' );
	do_action( 'health_service_header' );
?>
<div id="content"></div>

<?php
if (!is_home() && is_front_page()) {
?>
		<div id="cv-fullscreen" class="site-content">
<?php
		do_action( 'health_service_frontpage_sections' );
} else {
		do_action( 'health_service_innerpage_header' );

}
?>