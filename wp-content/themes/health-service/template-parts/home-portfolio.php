<?php 
/**
 * Template part for displaying section of Home Portfolio
 * @subpackage health-service
 * @since 1.0 
 */
$health_service_enable_portfolio_section = get_theme_mod( 'health_service_enable_portfolio_section', false );
$health_service_portfolio_title = get_theme_mod( 'health_service_portfolio_title');
$health_service_portfolio_subtitle = get_theme_mod( 'health_service_portfolio_subtitle' );

if($health_service_enable_portfolio_section==true ) {
	$health_service_portfolio_no        = 6;
	$health_service_portfolio_page      = array();
	for( $k = 1; $k <= $health_service_portfolio_no; $k++ ) {
		 $health_service_portfolio_page[] = get_theme_mod('health_service_portfolio_page'.$k); 

	}
	$health_service_portfolio_args  = array(
	'post_type' => 'page',
	'post__in' => array_map( 'absint', $health_service_portfolio_page ),
	'posts_per_page' => absint($health_service_portfolio_no),
	'orderby' => 'post__in'
	); 
	$health_service_portfolio_query = new WP_Query( $health_service_portfolio_args );
?>
<section id="portfolio" class="portfolio-5">
  <div class="container">
	<div class="section-title-5">
	 <?php if($health_service_portfolio_title) : ?>
		  <h2><?php echo esc_html($health_service_portfolio_title); ?></h2>
		  <div class="separator">
			<ul>
			  <li></li>
			  <li></li>
			  <li></li>
			</ul>
		  </div>
		<?php endif; ?>  	
	  <?php if($health_service_portfolio_subtitle) : ?> 	
			<p><?php echo esc_html($health_service_portfolio_subtitle); ?></p>
		<?php endif; ?>
	</div>
	<div class="row portfolio-container">
	<?php
		$count = 0;
		while($health_service_portfolio_query->have_posts() && $count <= 5 ) :
		$health_service_portfolio_query->the_post();
	  ?> 
	  <div class="col-lg-4 col-md-6 portfolio-item">
		<div class="portfolio-wrap">
		   <?php the_post_thumbnail(); ?>
			  <div class="portfolio-info">
				<h4><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h4>
			  </div>
		</div>
	  </div>
	<?php
		$count = $count + 1;
		endwhile;
		wp_reset_postdata();
	  ?> 
	</div>     
  </div>
</section>
<!-- End Portfolio Section -->
	
<?php } ?>