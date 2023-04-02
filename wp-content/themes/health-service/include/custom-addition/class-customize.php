<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class health_service_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		require_once( trailingslashit( get_template_directory() ) . 'include/custom-addition/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'health_service_Customize_Section_Pro' );

		 
		 
			// upgrade sections.
		$manager->add_section(
			new health_service_Customize_Section_Pro(
				$manager,
				'upgrade-pros',
				array(
					'title'    => esc_html__( 'Documentation', 'health-service'),
					'pro_text' => esc_html__( 'Click Here', 'health-service' ),
					'pro_url'  => 'https://testerwp.com/docs/health-sevices/',
					'priority'  => 3
				)
			)
		);
	}
	public function enqueue_control_scripts() {
		wp_enqueue_script( 'health-service-customize-controls', trailingslashit( get_template_directory_uri() ) . '/include/custom-addition/customize-controls.js', array( 'customize-controls' ) );
		wp_enqueue_style( 'health-service-customize-controls', trailingslashit( get_template_directory_uri() ) . '/include/custom-addition/customize-controls.css' );
	}
}
health_service_Customize::get_instance();