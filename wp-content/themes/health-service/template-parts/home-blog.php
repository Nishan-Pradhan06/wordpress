<?php
/**
 * Home Page Blog design 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage health-service
 * @since 1.0
 */

$health_service_enable_blog_section = get_theme_mod( 'health_service_enable_blog_section', true );
$health_service_blog_cat 		= get_theme_mod( 'health_service_blog_cat', 'uncategorized' );
if($health_service_enable_blog_section == true) {
$health_service_blog_title 	= get_theme_mod( 'health_service_blog_title', __( '', 'health-service' ) );
$health_service_blog_subtitle 	= get_theme_mod( 'health_service_blog_subtitle' );
$health_service_rm_button_label 	= get_theme_mod( 'health_service_rm_button_label', __( '', 'health-service' ) );
$health_service_blog_count 	 = apply_filters( 'health_service_blog_count', 3 );
?>
<section class="blog-5" style="background-image:url(<?php echo esc_url(get_template_directory_uri() .'/assets/images/testbg.png'); ?>)">
	<div class="container">
	  <div class="section-title-5">
	  <?php if($health_service_blog_title) : ?>
		<h2><?php echo esc_html( $health_service_blog_title ); ?></h2>
		<div class="separator">
		<ul>
		  <li></li>
		  <li class="squre"></li>
		  <li></li>
		</ul>
	  </div>
		<?php endif; ?>
		<?php if($health_service_blog_subtitle) : ?>
			<p><?php echo esc_html( $health_service_blog_subtitle ); ?></p>
		<?php endif; ?>	
	</div>
		<div class="row">
			<?php 
			if( !empty( $health_service_blog_cat ) ) 
				{
				$blog_args = array(
					'post_type' 	 => 'post',
					'category_name'	 => esc_attr( $health_service_blog_cat ),
					'posts_per_page' => absint( $health_service_blog_count ),
				);

				$blog_query = new WP_Query( $blog_args );
				if( $blog_query->have_posts() ) {
					while( $blog_query->have_posts() ) {
						$blog_query->the_post();
			?>
			  <div class="col-lg-4">
				<article class="blog-item blog-1">
					<div class="post-img">
						<?php the_post_thumbnail(); ?>
					</div>
					<div class="post-content pt-4 text-left">
						<h5>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h5>
						<?php the_excerpt(); ?>
						<?php if($health_service_rm_button_label) : ?>
							<div class="btn-wraper">
							  <a href="<?php the_permalink(); ?>" class="read-more-btn"><?php echo esc_html($health_service_rm_button_label); ?></a>
							</div>
						<?php endif; ?>	
					</div>
				</article>
			  </div>
		  <?php
				}
			}
			wp_reset_postdata();
		}
		 ?>
		</div>
	</div>
</section>
<?php } ?>