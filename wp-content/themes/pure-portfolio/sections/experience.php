<?php

if ( ! get_theme_mod( 'pure_portfolio_enable_experience_section', false ) ) {
	return;
}

$content_ids  = array();
$content_type = get_theme_mod( 'pure_portfolio_experience_content_type', 'page' );

for ( $i = 1; $i <= 4; $i++ ) {
	$content_ids[] = get_theme_mod( 'pure_portfolio_experience_content_' . $content_type . '_' . $i );
}

$args = array(
	'post_type'           => $content_type,
	'post__in'            => array_filter( $content_ids ),
	'orderby'             => 'post__in',
	'posts_per_page'      => absint( 4 ),
	'ignore_sticky_posts' => true,
);

$args = apply_filters( 'pure_portfolio_experience_section_args', $args );

pure_portfolio_render_experience_section( $args );

/**
 * Render Experience Section.
 */
function pure_portfolio_render_experience_section( $args ) {
	$section_title    = get_theme_mod( 'pure_portfolio_experience_title', __( 'Job Experience', 'pure-portfolio' ) );
	$section_subtitle = get_theme_mod( 'pure_portfolio_experience_subtitle', '' );
	$query            = new WP_Query( $args );
	if ( $query->have_posts() ) :
		?>
		<section id="pure_portfolio_experience_section" class="pure-portfolio-frontpage-section experience-section experience-alternate section-has-background">
			<?php
			if ( is_customize_preview() ) :
				pure_portfolio_section_link( 'pure_portfolio_experience_section' );
			endif;
			?>
			<div class="ascendoor-wrapper">
				<?php if ( ! empty( $section_title || $section_subtitle ) ) : ?>
					<div class="section-header-subtitle">
						<h3 class="section-title"><?php echo esc_html( $section_title ); ?></h3>
						<p class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
					</div>
				<?php endif; ?>
				<div class="pure-portfolio-section-body">
					<div class="experience-wrap">
						<?php
						$i = 1;
						while ( $query->have_posts() ) :
							$query->the_post();
							$job_duration = get_theme_mod( 'pure_portfolio_job_duration_' . $i, '' );
							?>
							<div class="experience-timeline-item">
								<div class="experience-timeline-line"></div>
								<div class="experience-timeline-circle-wrapper">
									<span class="experience-timeline-circle"><?php echo absint( $i ); ?></span>
								</div>
								<div class="experience-content-wrapper">
									<h3 class="experience-title">
										<a class="magic-hover magic-hover__square" href="<?php the_permalink(); ?>"><span><?php the_title(); ?></span></a>
									</h3>
									<?php if ( ! empty( $job_duration ) ) : ?>
										<span class="experience-subtitle"><?php echo esc_html( $job_duration ); ?></span>
									<?php endif; ?>
									<div class="experience-exceprt">
										<p><?php echo wp_kses_post( wp_trim_words( get_the_content(), 15 ) ); ?></p>
									</div>
								</div>
							</div>
							<?php
							$i++;
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>	
		</section>

		<?php
	endif;
}
