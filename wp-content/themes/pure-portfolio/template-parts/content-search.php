<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pure_Portfolio
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a class="magic-hover magic-hover__square" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			pure_portfolio_posted_on();
			pure_portfolio_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php pure_portfolio_post_thumbnail(); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php pure_portfolio_entry_footer(); ?>
		<div class="pure-portfolio-button pure-portfolio-button-noborder-noalternate">
			<a class="magic-hover magic-hover__square" href="<?php the_permalink(); ?>"><?php echo esc_html( get_theme_mod( 'pure_portfolio_excerpt_more_text', __( 'Read More', 'pure-portfolio' ) ) ); ?></a>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
