<?php
/**
 * Template part for displaying Callout Of Home Page
  
 * @subpackage health-service
 * @since 1.0 
 */

$health_service_enable_callout_section = get_theme_mod( 'health_service_enable_callout_section', false );

if($health_service_enable_callout_section == true ) {
$health_service_callout_title = get_theme_mod( 'health_service_callout_title');
$health_service_callout_content = get_theme_mod( 'health_service_callout_content');
$health_service_callout_button_label1 = get_theme_mod( 'health_service_callout_button_label1');
$health_service_callout_button_link1 = get_theme_mod( 'health_service_callout_button_link1');
$health_service_callout_image = get_theme_mod( 'health_service_callout_image', esc_url(  get_template_directory_uri() . '/assets/images/header.jpg' ) );

?>

<section class="cta-7" style="background-image:url(<?php echo $health_service_callout_image; ?>)">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<h3 class="c-white"><?php echo esc_html($health_service_callout_title); ?></h3>
				<p class="c-white mb-0"><?php echo esc_html($health_service_callout_content); ?></p>
			</div>
			<div class="col-md-3 flex-btn">
			<?php if(!empty($health_service_callout_button_label1 && $health_service_callout_button_link1)): ?>
			  <div class="btn">
				<a href="<?php echo esc_url($health_service_callout_button_link1); ?>"><?php echo esc_html($health_service_callout_button_label1); ?></a>
			  </div>
			<?php endif; ?>	  
			</div>
		</div>
	</div>
</section>
<?php } ?> 