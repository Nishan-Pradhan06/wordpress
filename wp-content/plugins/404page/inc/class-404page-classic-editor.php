<?php

/**
 * The 404page classic editor plugin class
 *
 * @since  9
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The classic editor plugin class
 */
if ( !class_exists( 'PP_404Page_ClassicEditor' ) ) {
  
  class PP_404Page_ClassicEditor extends PPF08_SubClass {
    
    /**
	   * Do Init
     *
     * @since 9
     * @access public
     */
    public function init() {

      add_action( 'admin_head', array( $this, 'admin_style' ) );
    
    }
    
    
    /**
	   * Add Classic Editor Style to Header if currently edited page is a custom 404 error page
     *
     * @since 9
     * @access public
     */
    public function admin_style() {
      
      // we just ignore whether Gutenberg is used or not, because this classes do not exist if Gutenberg is active
      if ( get_current_screen()->id == 'page' && $this->settings()->get( 'page_id' ) > 0 ) {
        
        global $post;
        
        $all404pages = $this->core()->get_all_page_ids();
        if ( in_array( $post->ID, $all404pages  ) ) {
          
          ?>
          <style type="text/css">
            #post-body-content:before { content: "<?php esc_html_e( 'You are currently editing your custom 404 error page', '404page'); ?>"; background-color: #333; color: #FFF; padding: 8px; font-size: 16px; display: block; margin-bottom: 10px };
          </style>
          <?php
          
        }
      }
      
		}

  }
  
}

?>