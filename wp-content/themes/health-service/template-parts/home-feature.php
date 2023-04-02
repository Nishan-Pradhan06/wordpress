<?php 
/**
 * Template part for displaying section of Home Feature
  
 * @subpackage health-service
 * @since 1.0 
 */

$health_service_enable_features_section = get_theme_mod( 'health_service_enable_features_section', false );
if($health_service_enable_features_section==true ) {

        $health_service_features_no        = 4;
        $health_service_features_pages      = array();
        for( $i = 1; $i <= $health_service_features_no; $i++ ) {
             $health_service_features_pages[] = get_theme_mod('health_service_features_page '.$i); 
             $health_service_features_icon[]= get_theme_mod('health_service_features_icon '.$i,'fa fa-user');
        }
        $health_service_features_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $health_service_features_pages ),
        'posts_per_page' => absint($health_service_features_no),
        'orderby' => 'post__in'
        ); 
        $health_service_features_query = new WP_Query( $health_service_features_args );
?>
<section id="feature" class="feature-area">
  <div class="container">
	<div class="row">
	  <?php
		$count = 0;
		while($health_service_features_query->have_posts() && $count <= 4 ) :
		$health_service_features_query->the_post();
	  ?> 
	  <div class="col-md-4">
		<div class="feature-box">
		  <div class="icon">
			<i class="fa <?php echo esc_html($health_service_features_icon[$count]); ?>"></i>
		  </div>
		  <div class="content">
			<h5 class="title"><?php the_title(); ?></h5>
			<p class="description"><?php the_content(); ?></p>
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

<?php } ?>