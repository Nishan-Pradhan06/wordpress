<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage health-service
 * @since 1.0
 */
get_header();

?>
<div class="sp-100 bg-w">
	<div class="container">
		<div class="row">
			<?php if( is_active_sidebar( 'blog-sidebar' ) ){ ?>
				<div class="col-lg-8">
			<?php }else{ ?>
				<div class="col-lg-12">
			<?php } ?>
				
			<?php
			if ( have_posts() ) :

				if ( is_home() && is_front_page() ) :
					?>
					<header>
						<h1 class="page-title screen-reader-text"><?php the_archive_title(); ?></h1>
					</header>
					<?php
				endif;
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content', get_post_type() );
				endwhile;
				the_posts_pagination();
			else :
				get_template_part( 'template-parts/content/content', 'none' );
			endif;
			?>
		</div>
		<?php if( is_active_sidebar( 'blog-sidebar' ) ){ ?>
				<?php get_sidebar();
			} ?>

		</div>
	</div>
</div>
</div>
<?php
get_footer();
?>