<?php
/**
 * Welcome Screen Class
 */
class health_service_screen {

	/**
	 * Constructor for the Notice
	 */
	public function __construct() {

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'health_service_activation_admin_notice' ) );

	}
	
	public function health_service_activation_admin_notice() {
		global $pagenow;
			
		if (is_admin() && ('themes.php' == $pagenow) && isset($_GET['activated'])) {
			add_action('admin_notices', array($this, 'health_service_admin_notice'), 99);
		}
	}

	
	public function health_service_admin_notice() {
		?>			
		<div class="updated notice is-dismissible bizzmo-note">
			<h1><?php
			$theme_info = wp_get_theme();
			printf( esc_html__('Thanks for installing  %1$s ', 'health-service'), esc_html( $theme_info->Name ), esc_html( $theme_info->Version ) ); ?>
			</h1>
			<p><?php echo  esc_html__("Welcome! Thank you for choosing Techup WordPress theme. To take full advantage of the features this theme Please Install Our Demo", "health-service"); ?></p>
			<p class="note1"><a href="?page=health-service-info.php" class="button button-blue-secondary button_info" style="text-decoration: none;" target="_blank"><?php echo esc_html__('Theme Detail','health-service'); ?></a> <a href="https://testerwp.com/docs/health-sevices/" target="_blank" class="button button-blue-secondary button_info" style="text-decoration: none;"><?php echo esc_html__('Documentation','health-service'); ?></a></p>
		</div>
		<?php
	}
	
}

$GLOBALS['health_service_screen'] = new health_service_screen();