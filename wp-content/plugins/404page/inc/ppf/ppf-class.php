<?php

/**
 * Base Class
 *
 * Peter's Plugins Foundation 08
 *
 * @package    PPF08
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */
 
 
// No need to check this in all files
 if ( ! defined( 'WPINC' ) ) {
  
	die;
  
}

 
if ( !class_exists( 'PPF08_Class' ) ) {
  
  
  abstract class PPF08_Class {
    
    
    /**
     * foundation dir
     *
     * removed in PPF02
     *
     * @since  PPF01
     * @var    string
     * @access private
     */
    // private $_dir;
    
    
    /**
     * Init the Class 
     *
     * @since PPF01
     * @see   getInstance
     */
    public function __construct() {
      
      // removed in PPF02
      // $this->_dir = __DIR__;
      
    }
    
    
    /**
	   * get foundation directory
     *
     * @since  PPF01
     * @access protected
     * @return string
     */
    protected function get_foundation_dir() {
      
      // since PPF02 we have to get the Directory of the Called Class to allow multiple classes based on the same Plugin Foundation
      
      $rc = new ReflectionClass( get_called_class() );
      return dirname( $rc->getFileName() ) . '/ppf';
     
    }
    
    
    /**
     * get url for foundation asset file
     *
     * @since  PPF01
     * @access protected
     * @param  string $dir  sub-directory of assets dir (js, css)
     * @param  string $file filename
     * @return string
     */
    protected function get_foundation_asset_url( $dir, $file ) {
      
      return plugins_url() . str_replace( WP_PLUGIN_DIR, '', $this->get_foundation_dir() ) . '/assets/' . $dir . '/' . $file;
     
    }
    
    
    /**
     * get path for foundation asset file
     *
     * @since  PPF01
     * @access protected
     * @param  string $dir  sub-directory of assets dir (js, css)
     * @param  string $file filename
     * @return string
     */
    protected function get_foundation_asset_path( $dir, $file ) {
     
      return plugin_dir_path( $this->get_foundation_dir() ) . 'assets/' . $dir . '/' . $file;
     
    }
    
    
    /**
     * add action
     *
     * @since  PPF01
     * @access public
     * @param  string $wpaction      name of the action to add
     * @param  int    $priority      priority - optional - default 10
   	 * @param  int    $accepted_args number of arguments the function accepts - optional - default 1
     */
    public function add_action( $wpaction, $priority = 10, $accepted_args = 1 ) {
      
      add_action( $wpaction, array( $this, 'action_' . $wpaction ), $priority, $accepted_args );
      
    }
    
    
    /**
     * add multiple actions
     * this function does not allow to specify priority and accepted_args
     *
     * @since  PPF01
     * @access public
     * @param  array  $actions array of actions to add
   	 * @see    add_action()
     */
    public function add_actions( $actions ) {
      
      foreach ( $actions as $action ) {
        
        $this->add_action( $action );
        
      }
      
    }
    
    
    /**
     * add filter
     *
     * @since  PPF01
     * @access public
     * @param  string $wpfilter      name of the filter to add
     * @param  int    $priority      priority - optional - default 10
   	 * @param  int    $accepted_args number of arguments the function accepts - optional - default 1
     */
    public function add_filter( $wpfilter, $priority = 10, $accepted_args = 1 ) {
      
      add_filter( $wpfilter, array( $this, 'filter_' . $wpfilter ), $priority, $accepted_args );
      
    }
    
    
  }
  
}

?>