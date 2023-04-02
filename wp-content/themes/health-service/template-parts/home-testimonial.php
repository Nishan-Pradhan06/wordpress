<?php 
/**
 * Template part for displaying section of Home Testimonial
 * @subpackage health-service
 * @since 1.0 
 */

$health_service_enable_testimonial_section = get_theme_mod( 'health_service_enable_testimonial_section', false );
$health_service_testimonial_title= get_theme_mod( 'health_service_testimonial_title','');
$health_service_testimonial_subtitle= get_theme_mod( 'health_service_testimonial_subtitle','');

if($health_service_enable_testimonial_section == true ) {
	$health_service_testimonials_no        = 6;
	$health_service_testimonials_pages      = array();
	for( $i = 1; $i <= $health_service_testimonials_no; $i++ ) {
		 $health_service_testimonials_pages[] = get_theme_mod('health_service_testimonial_page'.$i);
	}
	$health_service_testimonials_args  = array(
	'post_type' => 'page',
	'post__in' => array_map( 'absint', $health_service_testimonials_pages ),
	'posts_per_page' => absint($health_service_testimonials_no),
	'orderby' => 'post__in'
	); 
	$health_service_testimonials_query = new WP_Query( $health_service_testimonials_args );
?>
<!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials-5" style="background-image:url(<?php echo esc_url(get_template_directory_uri() .'/assets/images/testbg.png'); ?>)">
      <div class="container">
        <div class="section-title-5">
		<?php if($health_service_testimonial_title) : ?>
          <h2 class="title"><?php echo esc_html($health_service_testimonial_title); ?></h2>
          <div class="separator">
            <ul>
              <li></li>
              <li></li>
              <li></li>
            </ul>
          </div>
		  <?php endif; ?>
		  <?php if($health_service_testimonial_subtitle) : ?>
			<p><?php echo esc_html($health_service_testimonial_subtitle); ?> </p>
		  <?php endif; ?>	
        </div>
      </div>
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="testimonials-content owl-carousel owl-theme text-center">
				<?php
					$count = 0;
					while($health_service_testimonials_query->have_posts() && $count <= 5 ) :
					$health_service_testimonials_query->the_post();
				?>
                    <div class="testimonial">
                        <p class="testimonial-desc"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <div class="client-desc">
                          <div class="testimonial-pic">
                              <img src="<?php echo  esc_url(get_the_post_thumbnail_url()) ;?>" alt="<?php echo esc_html(get_post_thumbnail_id()); ?>">
                          </div>
                          <div class="testimonial-profile">
                              <span class="name"><?php the_title(); ?></span>
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
        </div>
      </div>
    </section><!-- End Testimonials Section -->	
<?php } ?>