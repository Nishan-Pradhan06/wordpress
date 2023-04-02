<?php 
/**
 * Template part for displaying section of Home Team Section 
 * @subpackage health-service
 * @since 1.0 
 */
 
$health_service_enable_team_section = get_theme_mod( 'health_service_enable_team_section', false );
$health_service_team_title  = get_theme_mod( 'health_service_team_title' );
$health_service_team_subtitle  = get_theme_mod( 'health_service_team_subtitle' );


if($health_service_enable_team_section==true ) {
    

        $health_service_teams_no        = 4;
        $health_service_teams_pages      = array();
        for( $i = 1; $i <= $health_service_teams_no; $i++ ) {
             $health_service_teams_pages[] = get_theme_mod('health_service_team_page'.$i);

        }
        $health_service_teams_args  = array(
        'post_type' => 'page',
        'post__in' => array_map( 'absint', $health_service_teams_pages ),
        'posts_per_page' => absint($health_service_teams_no),
        'orderby' => 'post__in'
        ); 
        $health_service_teams_query = new WP_Query( $health_service_teams_args );
      

?>
<section id="team" class="team-5">
  <div class="container">
	<div class="section-title-5">
	 <?php if($health_service_team_title) : ?>
		  <h2><?php echo esc_html($health_service_team_title); ?></h2>
		  <div class="separator">
			<ul>
			  <li></li>
			  <li></li>
			  <li></li>
			</ul>
		  </div>
		<?php endif; ?>  
		<?php if($health_service_team_subtitle) : ?>
			<p><?php echo esc_html($health_service_team_subtitle); ?></p>
		<?php endif; ?>	
	</div>

	<div class="row">
	<?php
		$count = 0;
		while($health_service_teams_query->have_posts() && $count <= 3 ) :
		$health_service_teams_query->the_post();
	 ?> 
	  <div class="col-lg-3 col-md-4 col-sm-6">
		<div class="team-profile">
		  <div class="profile-image">
			 <?php the_post_thumbnail(); ?>
		  </div>
		  <div class="profile-detail">
			<h4 class="profile-name"><?php the_title(); ?></h4>
			<div class="designation"><?php the_excerpt(); ?></div>
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
</section><!-- End Team Section -->	

<?php } ?>