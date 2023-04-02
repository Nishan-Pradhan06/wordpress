<?php
if ( ! get_theme_mod( 'pure_portfolio_enable_cta_section', false ) ) {
	return;
}

$section_content = array();

$section_content['title']          = get_theme_mod( 'pure_portfolio_cta_title' );
$section_content['text']           = get_theme_mod( 'pure_portfolio_cta_text' );
$section_content['background_url'] = get_theme_mod( 'pure_portfolio_cta_background_image' );
$section_content['button_label']   = get_theme_mod( 'pure_portfolio_cta_button_label' );
$section_content['button_link']    = get_theme_mod( 'pure_portfolio_cta_button_link' );
$section_content['button_link']    = ! empty( $section_content['button_link'] ) ? $section_content['button_link'] : '#';

$section_content = apply_filters( 'pure_portfolio_cta_section_content', $section_content );

pure_portfolio_render_cta_section( $section_content );

/**
 * Render CTA Section
 */
function pure_portfolio_render_cta_section( $section_content ) {
	?>
	<section id="pure_portfolio_cta_section" class="pure-portfolio-frontpage-section pure-portfolio-cta-section section-has-background cta-style-1" style="background-color: #1d1f21;">
		<?php
		if ( is_customize_preview() ) :
			pure_portfolio_section_link( 'pure_portfolio_cta_section' );
		endif;
		?>
		<?php
		if ( ! empty( $section_content['background_url'] ) ) {
			?>
			<div class="pure-portfolio-cta-background-img">
				<img src="<?php echo esc_url( $section_content['background_url'] ); ?>">
			</div>
			<?php
		}
		?>
		<div class="ascendoor-wrapper">
			<div class="pure-portfolio-cta-wrapper">
				<div class="pure-portfolio-cta-text">
					<div class="section-header-subtitle">
						<h3 class="section-title pure-portfolio-cta-head"><?php echo esc_html( $section_content['title'] ); ?></h3>
						<p class="section-subtitle video-detail-subtitle"><?php echo esc_html( $section_content['text'] ); ?></p>
					</div>
					<?php if ( ! empty( $section_content['button_label'] ) ) : ?>
						<div class="pure-portfolio-cta-button pure-portfolio-button pure-portfolio-button-alternate">
							<a class="magic-hover magic-hover__square" href="<?php echo esc_url( $section_content['button_link'] ); ?>"><?php echo esc_html( $section_content['button_label'] ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
