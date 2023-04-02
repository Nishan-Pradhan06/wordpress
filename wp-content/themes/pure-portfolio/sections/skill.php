<?php

if ( ! get_theme_mod( 'pure_portfolio_enable_skill_section', false ) ) {
	return;
}

$section_content = array();

$section_content['section_subtitle'] = get_theme_mod( 'pure_portfolio_skill_section_subtitle', __( 'Ability & Skills', 'pure-portfolio' ) );
$section_content['section_title']    = get_theme_mod( 'pure_portfolio_skill_section_title', __( 'My Expertise', 'pure-portfolio' ) );

$section_content = apply_filters( 'pure_portfolio_skill_section_content', $section_content );

pure_portfolio_render_skill_section( $section_content );

/**
 * Render Progress Bar section
 */
function pure_portfolio_render_skill_section( $section_content ) {
	?>
		<section id="pure_portfolio_skill_section" class="pure-portfolio-frontpage-section skill-section">
			<?php
			if ( is_customize_preview() ) :
				pure_portfolio_section_link( 'pure_portfolio_skill_section' );
			endif;
			?>
			<div class="ascendoor-wrapper">
				<?php if ( ! empty( $section_content['section_title'] || $section_content['section_subtitle'] ) ) : ?>
					<div class="section-header-subtitle">
						<h3 class="section-title"><?php echo esc_html( $section_content['section_title'] ); ?></h3>
						<p class="section-subtitle"><?php echo esc_html( $section_content['section_subtitle'] ); ?></p>
					</div>
				<?php endif; ?>
				<div class="pure-portfolio-section-body">
					<div class="skill-wrapper">
						<?php
						for ( $i = 1; $i <= 4; $i++ ) :
							$skill_value    = get_theme_mod( 'pure_portfolio_skill_value_' . $i, '' );
							$skill_title    = get_theme_mod( 'pure_portfolio_skill_title_' . $i, '' );
							$skill_subtitle = get_theme_mod( 'pure_portfolio_skill_subtitle_' . $i, '' );
							?>
							<div class="progressbar-single">
								<div class="progressbar" data-animate="false">
									<div class="circle" data-percent="<?php echo esc_attr( $skill_value ); ?>" data-size="180" data-thickness="12" data-fill="{ &quot;color&quot;: &quot;#2896DF&quot;}" >
									<div></div>
									</div>
								</div>
								<h3 class="progress-title"><?php echo esc_html( $skill_title ); ?></h3>
								<h4 class="progress-subtitle"><?php echo esc_html( $skill_subtitle ); ?></h4>
							</div>
							<?php
						endfor;
						?>
					</div>
				</div>
			</div>	
		</section>
	<?php
}
