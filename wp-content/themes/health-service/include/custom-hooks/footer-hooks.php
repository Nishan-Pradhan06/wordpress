<?php
/**
 * Managed the custom functions and hooks for footer section of the theme.
 *
 * @subpackage health-service
 * @since 1.0 
 */
 
$health_service_enable_footer_copyright_section = get_theme_mod( 'health_service_enable_footer_copyright_section', true );



if( ! function_exists( 'health_service_footer_start' ) ):
	function health_service_footer_start(){
		$footer_sticky = get_theme_mod( 'health_service_footer_sticky_opt', true ); ?>
		
		    <footer class="footer footer-one" id="foot-wdgt">
<?php }
endif; 
if( ! function_exists( 'health_service_footer_sidebar' ) ):
	function health_service_footer_sidebar(){
	$health_footer_bg_image = get_theme_mod( 'health_footer_bg_image', esc_url(  get_template_directory_uri() . '/assets/images/foot.jpg' ) ); 
		?>
	    <div class="foot-top" style="background-image:url(<?php echo $health_footer_bg_image; ?>)">
            <div class="container">
                <div class="row clearfix">
                	<?php if( is_active_sidebar( 'footer-widget-area' ) ){ ?>
                    <?php dynamic_sidebar('footer-widget-area'); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
	<?php }
endif; 
if( ! function_exists( 'health_service_footer_site_info' ) ):
	function health_service_footer_site_info(){
		$health_service_enable_footer_copyright_section = get_theme_mod( 'health_service_enable_footer_copyright_section', true );
		$health_service_cr_text = get_theme_mod( 'health_service_cr_text');
			if($health_service_enable_footer_copyright_section) : ?>
			<div class="foot-bottom">
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<?php
								/* translators: %s: WordPress */
								echo $health_service_cr_text; 
							?>
						</div>
					</div>
				</div>
			</div>
			<?php endif;  
		 
			
 }
endif; 

if( ! function_exists( 'health_service_footer_end' ) ):
	function health_service_footer_end(){ ?>
				
		</footer> 
		</div>
<?php }
endif; 
add_action( 'health_service_footer', 'health_service_footer_start', 5  );
add_action( 'health_service_footer', 'health_service_footer_sidebar', 10  );
add_action( 'health_service_footer', 'health_service_footer_site_info', 10  );
add_action( 'health_service_footer', 'health_service_footer_end', 20 );