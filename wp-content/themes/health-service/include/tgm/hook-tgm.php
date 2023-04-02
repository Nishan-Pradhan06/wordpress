<?php
/**
 * Recommended plugins
 *
 * @package techup
 */

if ( ! function_exists( 'techup_recommended_plugins' ) ) :

    /**
     * Recommend plugins.
     *
     * @since 1.0.0
     */
    function techup_recommended_plugins() {

        $plugins = array(
            array(
                'name'     => esc_html__( 'One Click Demo Import','health-service' ),
                'slug'     => 'one-click-demo-import',
                'required' => false,
            ),
			array(
                'name'     => esc_html__( 'Image Slider','health-service'),
                'slug'     => 'image-slider-slideshow',
                'required' => false,
            ),
			array(
                'name'     => esc_html__( 'FlipBox Builder','health-service'),
                'slug'     => 'flipbox-builder',
                'required' => false,
            ),
			
        );
		 
		 
        tgmpa( $plugins );

    }

endif;

add_action( 'tgmpa_register', 'techup_recommended_plugins' );