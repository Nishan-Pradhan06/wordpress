<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @subpackage health-service
 * @since health-service 1.0
 */

?>

<?php
	if( ! is_front_page() ) {
?>             
			</div> 
		</div> 
	 </div> 
<?php
	}

do_action( 'health_service_footer' );
 if( is_front_page() ){
		echo '</div>';
	} ?>

</div> 
<?php wp_footer(); ?>
</body>
</html>