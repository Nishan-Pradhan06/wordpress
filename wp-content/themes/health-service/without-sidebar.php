<?php
/**
 * Template Name: Full-width Page 
 *
 * @package Techbit
 */
 
get_header();
if( ! is_front_page() ) 
{ ?>
<div class="sp-100 bg-w">
	<div class="container">
		<div class="row">
			 	<div class="col-lg-12">
		 		<?php
				if ( have_posts() ) :
					if ( is_home() && is_front_page() ) :
						?>
						<header>
							<h1 class="page-title screen-reader-text"><?php the_title(); ?></h1>
						</header>
						<?php
					endif;
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content', 'page' );

					endwhile;?>
				<?php
				else :
            	get_template_part( 'template-parts/content/content', 'none' );
				endif;
				if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif; ?>
		</div>
		 
		</div>
	</div>
</div>
<?php }  
get_footer();
?>