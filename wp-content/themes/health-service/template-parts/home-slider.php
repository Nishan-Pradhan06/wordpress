<?php
/**
 * Template part for displaying section of Home Slider
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @subpackage health-service
 * @since 1.0 
 */
	$health_service_enable_slider_section = get_theme_mod( 'health_service_enable_slider_section', false );
	$health_service_slider_no        = 3;
	$health_service_slider_pages      = array();
	for( $i = 1; $i <= $health_service_slider_no; $i++ ) {
		 $health_service_slider_pages[] = get_theme_mod('health_service_slider_page'.$i); 
		 $health_service_slider_page_btn_txt[]    =  get_theme_mod( "health_service_slider_page_btn_txt_$i", 1 );
		 $health_service_slider_page_second_btn_txt[]    =  get_theme_mod( "health_service_slider_page_second_btn_txt_$i", 1 );
		 $health_service_slider_page_btn_url[]    =  get_theme_mod( "health_service_slider_page_btn_url_$i", 1 );
		 $health_service_slider_page_second_btn_url[]    =  get_theme_mod( "health_service_slider_page_second_btn_url_$i", 1 );
		 $enable_dark_header    =  get_theme_mod( "enable_dark_header", 1 );
		 
	}
	$health_service_slider_args  = array(
	'post_type' => 'page',
	'post__in' => array_map( 'absint', $health_service_slider_pages ),
	'posts_per_page' => absint($health_service_slider_no),
	'orderby' => 'post__in'
	); 
	$health_service_slider_query = new WP_Query( $health_service_slider_args );
      
if($health_service_enable_slider_section==false ) {
?>  
 <section class="main-slider">
	<div class="slide-item">
		<img src="<?php echo esc_url(header_image());?>">
		  <div class="slide-overlay">
			 <div class="slide-table">
				<div class="slide-table-cell">
					<div class="container">
						<div class="slide-content">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php }

else 
{?>
 <section class="home7-hero-sec">
    <!-- Home Area -->
	<div class="home-area">
		<div class="container-fluid m-0 p-0">
			<div class="home-slider owl-carousel owl-theme">
			<?php
				$count = 0;
				while($health_service_slider_query->have_posts() && $count <= 2 ) :
				$health_service_slider_query->the_post();
			 ?> 
			  <div class="slider-item item-bg1" style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>);">
					<div class="slider-content banner-content">
					  <div class="container">
						<h1><?php the_title(); ?></h1>
							<?php the_excerpt() ?>
						<div class="slider-btn">
							<?php if($health_service_slider_page_btn_txt[$count]) :?>
								<a href="<?php echo esc_url($health_service_slider_page_btn_url[$count])?>" class="btn slider-btn1"><?php echo esc_html($health_service_slider_page_btn_txt[$count]); ?></a>
							<?php endif; ?>
							<?php if($health_service_slider_page_second_btn_txt[$count]) :?>
								<a href="<?php echo esc_url($health_service_slider_page_second_btn_url[$count])?>" class="btn slider-btn2"><?php echo esc_html($health_service_slider_page_second_btn_txt[$count]); ?></a>
							<?php endif; ?>
						</div>
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
</section>
<?php
}

$enable_dark_header    =  get_theme_mod( "enable_dark_header", 1 );
if($enable_dark_header=="1")
{?>
	<style>
		.home7-hero-sec .banner-content h1, .home7-hero-sec .banner-content p
		{
			color:#000 !important;
		}
		.home7-hero-sec .home-area .owl-theme .owl-dots .owl-dot span
		{
			background:#000 !important;
		}
	</style>
<?php 
}

$enable_sticy_header    =  get_theme_mod( "enable_sticy_header", 1 );
if($enable_sticy_header=="0")
{?>
	<style>
		.affix.sticky-menu
		{
		  display:none !important;
		}
	</style>
<?php 
}

?>
 