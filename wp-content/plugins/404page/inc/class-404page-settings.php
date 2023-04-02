<?php

/**
 * The 404page settings plugin class
 *
 * @since  7
 *
 * taken from 404page core class and outsourced to a seperate class in version 7
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The settings plugin class
 */
if ( !class_exists( 'PP_404Page_Settings' ) ) {
  
  class PP_404Page_Settings extends PPF08_Settings {
    
    /**
	   * sanitize settings
     *
     * @since 11.0.0
     * @access public
     */
    public function sanitize_settings() {
      
      // Set page ID to -1 if the page does not exist or is not published
      
      if ( $this->get( 'page_id' ) != 0 ) {
        
       $page = get_post( $this->get( 'page_id' ) );
        
        if ( !$page || $page->post_status != 'publish' ) {
          
          $this->set( 'page_id', -1 );
          
        } 
        
      }
      
    }
    
    
    /**
     * set the method
     *
     * @since  7
     * @access public
     */
    public function set_method() {
      
      if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        
        // WPML is active
        $this->set('method', 'CMP' );
        
      } else {
        
        // we dont' need to set this here, because this is set in load()
        
      }
      
    }    

    
  }
  
}

?>