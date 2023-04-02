<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Pure_Portfolio
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
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'pure-portfolio' ); ?></a>
		<div id="loader">
			<div class="loader-container">
				<div id="preloader">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/loader.gif' ); ?>">
				</div>
			</div>
		</div>
		<header id="masthead" class="site-header">
			<div class="bottom-header-outer-wrapper">
				<div class="bottom-header-part">
					<div class="ascendoor-wrapper">
						<div class="bottom-header-part-wrapper">
							<div class="site-branding">
								<?php if ( has_custom_logo() ) { ?>
									<div class="site-logo">
										<?php the_custom_logo(); ?>
									</div>
								<?php } ?>
								<div class="site-identity">
									<?php
									if ( is_front_page() && is_home() ) :
										?>
									<h1 class="site-title"><a class="magic-hover magic-hover__square" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
										<?php
									else :
										?>
									<p class="site-title"><a class="magic-hover magic-hover__square" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
										<?php
									endif;
									$pure_portfolio_description = get_bloginfo( 'description', 'display' );
									if ( $pure_portfolio_description || is_customize_preview() ) :
										?>
									<p class="site-description"><?php echo esc_html( $pure_portfolio_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
										<?php
									endif;
									?>
								</div>
							</div><!-- .site-branding -->

							<div class="navigation-part">
								<nav id="site-navigation" class="main-navigation">
									<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
										<span></span>
										<span></span>
										<span></span>
									</button>
									<div class="main-navigation-links">
										<?php
										if ( has_nav_menu( 'primary' ) ) {
											wp_nav_menu(
												array(
													'theme_location' => 'primary',
												)
											);
										}
										?>
									</div>
								</nav><!-- #site-navigation -->
								<div class="header-search">
									<div class="header-search-wrap">
										<a href="#" title="Search" class="header-search-icon magic-hover magic-hover__square">
											<i class="fa fa-search"></i>
										</a>
										<div class="header-search-form">
											<?php get_search_form(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->

		<?php if ( ! is_front_page() || is_home() ) { ?>
			<div id="content" class="site-content">
				<div class="ascendoor-wrapper">
					<div class="ascendoor-page">
					<?php } ?>
