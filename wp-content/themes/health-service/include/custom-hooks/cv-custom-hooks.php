<?php
/**
 * Managed the custom functions and hooks for entire theme.
 *
 * @subpackage health-service
 * @since 1.0
 */

if( ! function_exists( 'health_service_frontpage_manage_sections' ) ) :

	/**
	 * function to manage the sections display at frontpage
	 */

	function health_service_frontpage_manage_sections() {

		get_template_part( 'template-parts/home', 'slider' );
		get_template_part( 'template-parts/home', 'feature' );
		get_template_part( 'template-parts/home', 'about' );
		get_template_part( 'template-parts/home', 'service' );
		get_template_part( 'template-parts/home', 'callout' );
		get_template_part( 'template-parts/home', 'portfolio' );
		get_template_part( 'template-parts/home', 'testimonial' );
		get_template_part( 'template-parts/home', 'team' );
		get_template_part( 'template-parts/home', 'blog' );
	}

endif;

add_action( 'health_service_frontpage_sections', 'health_service_frontpage_manage_sections', 10 );


/*----------------------------------------------------------------------------------------------------------------------------------*/

if( ! function_exists( 'health_service_innerpage_header_start' ) ) :

	/**
	 * function to manage starting div of section
	 */

	function health_service_innerpage_header_start() {
       
?>
		 <section class="page-banner">
<div class="container">
            <div class="row">
            	<div class="col-12">
            	
<?php
	}

endif;

if( ! function_exists( 'health_service_innerpage_header_title' ) ) :

	function health_service_innerpage_header_title() {
		if( is_single() || is_page() ) {
			the_title( '<h3><a href="#">', '</a></h3>' );
		} elseif( is_archive() ) {
			the_archive_title( '<h3>', '</h3>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		} elseif( is_search() ) {
	?>
			<h3><?php printf(/* translators: %s: post date. */ esc_html__( 'Search Results for: %s', 'health-service' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h3>
	<?php
		} elseif( is_404() ) {
			echo '<h3>'. esc_html( '404 Error', 'health-service' ) .'</h3>';
		}
		elseif(is_home() || is_front_page()) { ?>						
			<h3><a href="#"><?php echo esc_html__('Blog', 'health-service') ?></a></h3>
		<?php }
	}

endif;

if( !function_exists( 'health_service_breadcrumb_content' ) ) :
	function health_service_breadcrumb_content() {

		$health_service_breadcrumb_option = get_theme_mod( 'health_service_enable_breadcrumb_option', true );

		if ( false === $health_service_breadcrumb_option ) {
			return;
		}


	}

endif;
if( ! function_exists( 'health_service_innerpage_header_end' ) ) :

	function health_service_innerpage_header_end() {
?></div>
</div>
			</div>
		</div>
	</div>
</section>

<?php
	}
endif;
add_action( 'health_service_innerpage_header', 'health_service_innerpage_header_start', 5 );
add_action( 'health_service_innerpage_header', 'health_service_innerpage_header_title', 10 );
add_action( 'health_service_innerpage_header', 'health_service_breadcrumb_content', 15 );
add_action( 'health_service_innerpage_header', 'health_service_innerpage_header_end', 20 );