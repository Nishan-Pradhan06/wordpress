<?php

/**
 * The 404page deprecated class
 *
 * to ensure backward compatibility
 *
 * @since  11.0.0
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The deprecated plugin class
 */
if ( !class_exists( 'PP_404Page_Deprecated' ) ) {
  
  class PP_404Page_Deprecated extends PPF08_SubClass {  
    
    /**
	   * Do Init
     *
     * @since 11.0.0
     * @access public
     */
    public function init() {

     
      // since 11.0.0 all settings are stored in a single serialized value
      // collet all the individual settings from older versions and create the serialized value
      // plus the user meta data are deleted
      
      $newkey  = str_replace( '-', '_', $this->core()->get_plugin_slug() ) . '_settings';
      
      if ( false === get_option( $newkey ) ) {
        
        if ( false !== get_option( '404page_page_id' ) ) {
          
          $oldkeys = array(
            '404page_page_id',
            '404page_hide',
            '404page_fire_error',
            '404page_force_error',
            '404page_no_url_guessing',
            '404page_http410_if_trashed',
            '404page_method'
          );
          
          $newvals = array();
          
          foreach ( $oldkeys as $key ) {
            
            $newvals[ str_replace( '404page_', '', $key ) ] = get_option( $key );
            delete_option( $key );
            
          }
          
          update_option( $newkey, $newvals );
          
          
          // delete user meta for old admin notices for all users
          foreach ( array( 'pp-404page-admin-notice-1', 'pp-404page-admin-notice-2', 'pp-404page-update-notice-v8', 'pp-404page-update-notice-v9' ) as $key ) {
            delete_metadata( 'user', false, $key, false, true );
          }
          
        }
        
      }
    
    }

  }
  
}

?>