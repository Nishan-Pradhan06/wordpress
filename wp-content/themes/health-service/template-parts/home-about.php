<?php
/**
 * Template part for displaying section of About Us content
  
 * @subpackage health-service
 * @since 1.0 
 */
$health_service_enable_about_us_section = get_theme_mod( 'health_service_enable_about_us_section', true );
$health_service_about_title = get_theme_mod( 'health_service_about_title');
$health_service_about_subtitle = get_theme_mod( 'health_service_about_subtitle');

if($health_service_enable_about_us_section==true ) {
 

$health_service_about_page = get_theme_mod( 'health_service_about_page' );

if( !empty( $health_service_about_page ) ) {
	$page_args['page_id'] = absint( $health_service_about_page );
	$page_query = new WP_Query( $page_args );
	if( $page_query->have_posts() ) {
?>
<section id="about" class="about" style="background-image:url(<?php echo esc_url(get_template_directory_uri() .'/assets/images/testbg.png'); ?>)" >
<?php
	while( $page_query->have_posts() ) {
	$page_query->the_post();
?>
  <div class="container">
	<div class="section-title-5">
	<?php if($health_service_about_title) { ?>
	  <h2><?php echo esc_html($health_service_about_title); ?></h2>
	  <div class="separator">
		<ul>
		  <li></li>
		  <li></li>
		  <li></li>
		</ul>
	  </div>
	  <?php } ?>
	  <?php if($health_service_about_subtitle) { ?>
		<p><?php echo esc_html($health_service_about_subtitle); ?></p>
	  <?php } ?>
	</div>
	<div class="row">
		<?php 
		if(has_post_thumbnail())
		{
			$health_service_about_cols ="col-md-6"; 
		}
		else
		{
			$health_service_about_cols ="col-md-12"; 
		}
		?>	
		<?php if(has_post_thumbnail()) : ?>
		  <div class="col-md-6 col-sm-12 img-box">
			<div class="about-box-img">
			 <?php the_post_thumbnail(); ?>
			</div>
		  </div>
		  <?php endif; ?>
		  <div class="<?php echo $health_service_about_cols ?> col-sm-12">
			<div class="about-content">
			  <?php the_content(); ?>
			</div>
		  </div>
	</div>
  </div>
  <?php
	}
wp_reset_postdata();
?>
</section>
<?php
	}
}
}
if(have_posts()) : 
  while(have_posts()) : the_post();
    if(get_the_content()!= "")
    {
    ?>
      <section class="blog sp-100 mb-pad">
          <div class="container">
            <div class="row">
          <?php the_content(); ?> 
        </div>
        </div> 
      </section>  
    <?php 
    } 
  endwhile;
endif; 
?>