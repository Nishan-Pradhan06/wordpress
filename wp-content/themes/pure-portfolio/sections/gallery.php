<?php
if ( ! get_theme_mod( 'pure_portfolio_enable_gallery_section', false ) ) {
	return;
}

$content_ids  = array();
$content_type = get_theme_mod( 'pure_portfolio_gallery_content_type', 'post' );

if ( $content_type === 'post' ) {

	for ( $i = 1; $i <= 6; $i++ ) {
		$content_ids[] = get_theme_mod( 'pure_portfolio_gallery_content_post_' . $i );
	}

	$args = array(
		'post_type'           => $content_type,
		'post__in'            => array_filter( $content_ids ),
		'orderby'             => 'post__in',
		'posts_per_page'      => absint( 6 ),
		'ignore_sticky_posts' => true,
	);

} else {
	$cat_content_id = get_theme_mod( 'pure_portfolio_gallery_content_category' );
	$args           = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 6 ),
	);
}

$args = apply_filters( 'pure_portfolio_gallery_section_args', $args );

pure_portfolio_render_gallery_section( $args );

/**
 * Render Gallery Section.
 */
function pure_portfolio_render_gallery_section( $args ) {
	$section_title = get_theme_mod( 'pure_portfolio_gallery_title', __( 'Our Gallery', 'pure-portfolio' ) );
	$section_text  = get_theme_mod( 'pure_portfolio_gallery_text', '' );
	$button_label  = get_theme_mod( 'pure_portfolio_gallery_button_label', __( 'View All', 'pure-portfolio' ) );
	$button_link   = get_theme_mod( 'pure_portfolio_gallery_button_link', '' );

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		?>
		<section id="pure_portfolio_gallery_section" class="pure-portfolio-frontpage-section pure-portfolio-secondary1-background pure-portfolio-gallery-section gallery-style-2">
			<?php
			if ( is_customize_preview() ) :
				pure_portfolio_section_link( 'pure_portfolio_gallery_section' );
			endif;
			?>
			<div class="ascendoor-wrapper">
				<div class="section-header-subtitle">
					<h3 class="section-title"><?php echo esc_html( $section_title ); ?></h3>
					<p class="section-subtitle"><?php echo esc_html( $section_text ); ?></p>
				</div>
				<div class="pure-portfolio-section-body">
					<div class="pure-portfolio-gallery-slider pure-portfolio-navigation">
						<?php
						$i = 1;
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<div class="pure-portfolio-gallery-single-wrap wow fadeInUp" data-wow-delay="<?php echo esc_attr( $i * 200 ); ?>ms">
								<div class="pure-portfolio-gallery-single">
									<div class="pure-portfolio-gallery-img"><?php the_post_thumbnail(); ?></div>	
									<div class="pure-portfolio-gallery-detail">
										<h3 class="pure-portfolio-gallery-title"><a class="magic-hover magic-hover__square" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<p class="pure-portfolio-content"><?php echo wp_kses_post( wp_trim_words( get_the_content(), 15 ) ); ?></p>
									</div>
								</div>
							</div>
							<?php
							$i++;
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php if ( ! empty( $button_label ) ) { ?>
						<div class="pure-portfolio-gallery-view-all pure-portfolio-button">
							<a class="magic-hover magic-hover__square" href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_label ); ?></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
		<?php
	endif;
}
