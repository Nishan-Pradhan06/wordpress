<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @subpackage health-service
 * @since health-service
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