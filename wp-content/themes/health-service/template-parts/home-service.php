<?php 
/**
 * Template part for displaying section of Home Services
 * @subpackage health-service
 * @since 1.0 
 */
 
$health_service_enable_service_section = get_theme_mod( 'health_service_enable_service_section', false );
$health_service_service_title = get_theme_mod( 'health_service_service_title');
$health_service_service_subtitle = get_theme_mod( 'health_service_service_subtitle' );
if($health_service_enable_service_section==true ) {


        $health_service_services_no        = 6;
        $health_service_services_pages      = array();
        for( $i = 1; $i <= $health_service_services_no; $i++ ) {
             $health_service_services_pages[] = get_theme_mod('health_service_service_page '.$i); 
             $health_service_service_icon[]= get_theme_mod('health_service_service_icon '.$i,'fa fa-user');
        }
        $health_service_services_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $health_service_services_pages ),
        'posts_per_page' => absint($health_service_services_no),
        'orderby' => 'post__in'
        ); 
        $health_service_services_query = new WP_Query( $health_service_services_args );
      

?>
 
 <!-- ======= Services Section ======= -->
    <section id="services" class="services-5">
      <div class="container">
        <div class="section-title-5">
		<?php if($health_service_service_title) : ?>
          <h2><?php echo esc_html( $health_service_service_title ); ?></h2>
          <div class="separator">
            <ul>
              <li></li>
              <li></li>
              <li></li>
            </ul>
          </div>
		  <?php endif; ?>
		  <?php if($health_service_service_subtitle) : ?>
			<p><?php echo esc_html($health_service_service_subtitle); ?></p>
		  <?php endif; ?>	
        </div>
        <div class="row">
		  <?php
			$count = 0;
			while($health_service_services_query->have_posts() && $count <= 8 ) :
			$health_service_services_query->the_post();
			?> 	
			  <div class="col-lg-4 col-md-6 col-sm-12 p-0">
				<div class="service-box">
				  <i class="fa <?php echo esc_attr($health_service_service_icon[$count]); ?>"></i>
				  <h4><?php the_title(); ?></h4>
				  <?php the_content(); ?>
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