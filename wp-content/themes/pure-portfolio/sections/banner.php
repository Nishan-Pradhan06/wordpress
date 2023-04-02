<?php

if ( ! get_theme_mod( 'pure_portfolio_enable_banner_section', false ) ) {
	return;
}

$section_content = array();

$section_content['banner_image']      = get_theme_mod( 'pure_portfolio_banner_image', '' );
$section_content['introduction_text'] = get_theme_mod( 'pure_portfolio_banner_introduction_text', __( 'Hi, I’m Jone Lee', 'pure-portfolio' ) );
$section_content['short_description'] = get_theme_mod( 'pure_portfolio_banner_short_description', '' );

$section_content = apply_filters( 'pure_portfolio_banner_section_content', $section_content );

pure_portfolio_render_banner_section( $section_content );

/**
 * Render Banner Section
 */
function pure_portfolio_render_banner_section( $section_content ) {

	$banner_social_links = get_theme_mod( 'pure_portfolio_banner_social_links' );
	if ( ! empty( $banner_social_links ) ) {
		$social_links = explode( ',', get_theme_mod( 'pure_portfolio_banner_social_links' ) );
	}

	$featured_text = get_theme_mod( 'pure_portfolio_banner_featured_text', '' );
	$text_list     = ! empty( $featured_text ) ? explode( '|', $featured_text ) : array();
	$class         = ! empty( $text_list ) ? 'featured-typed' : '';

	?>

	<section id="pure_portfolio_banner_section" class="pure-portfolio-main-banner-section banner-style-2 <?php echo esc_attr( $class ); ?>">
		<?php
		if ( is_customize_preview() ) :
			pure_portfolio_section_link( 'pure_portfolio_banner_section' );
		endif;
		?>
		<div class="main-slider">
			<div class="vertical-lines">
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div class="ascendoor-wrapper">
				<div class="pure-portfolio-banner-slider-single">
					<div class="pure-portfolio-banner-slider-detail">
						<div class="pure-portfolio-banner-slider-detail-inside">
							<?php if ( ! empty( $section_content['introduction_text'] ) ) : ?>
								<h3 class="pure-portfolio-banner-head-title">
									<span class="hello-txt"><?php echo esc_html( $section_content['introduction_text'] ); ?></span>
								</h3>
								<?php
							endif;
							if ( ! empty( $text_list ) ) {
								?>
								<div id="typed-strings" >
									<?php foreach ( $text_list as $list ) : ?>
										<span class="feature-text"><?php echo esc_html( $list ); ?></span>
									<?php endforeach; ?>
								</div>
								<div class="pure-portfolio-banner-head-title">
									<span id="typed" class="feature-text"></span>
								</div>
								<?php
							}
							if ( ! empty( $section_content['short_description'] ) ) :
								?>
								<p><?php echo wp_kses_post( $section_content['short_description'] ); ?></p>
							<?php endif; ?>
							<div class="social-icons-part">
								<?php if ( ! empty( $social_links ) ) { ?>
									<div class="teams-social">
										<?php foreach ( $social_links as $social_link ) { ?>
											<a class="magic-hover magic-hover__square" href="<?php echo esc_url( $social_link ); ?>" target="_blank"></a>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php if ( ! empty( $section_content['banner_image'] ) ) : ?>
						<div class="pure-portfolio-banner-slider-img">
							<img src="<?php echo esc_url( $section_content['banner_image'] ); ?>" alt="<?php esc_attr_e( 'Banner Image', 'pure-portfolio' ); ?>">
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php
}
