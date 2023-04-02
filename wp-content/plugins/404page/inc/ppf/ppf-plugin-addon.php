<?php

/**
 * Plugin Addon Base Class
 *
 * Peter's Plugins Foundation 08
 *
 * @package    PPF08
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */

 
if ( !class_exists( 'PPF08_Plugin_Addon' ) ) {
  
  abstract class PPF08_Plugin_Addon extends PPF08_Plugin {
    
    /**
     * Base Plugin Name
     *
     * @since  PPF04
     * @var    string
     * @access protected
     */
    protected $base_plugin_name;
    
    
    /**
     * Base Plugin Function
     *
     * @since  PPF04
     * @var    string
     * @access protected
     */
    protected $base_plugin_function;
    
    
    /**
     * Base Plugin Min Required Version
     *
     * @since  PPF04
     * @var    string
     * @access protected
     */
    protected $base_plugin_min_version;


    /**
     * Init the Class 
     *
     * @since PPF04
     * same as PPFxx_Plugin plus
     *   @type string $base_plugin_name        Name of Base Plugin
     *   @type string $base_plugin_function    Function to access Base Plugin
     *   @type string $base_plugin_min_version Minimal required version of Base Plugin
     */
    public function __construct( $settings ) {
     
      $this->plugin_file      = $settings['file'];
      $this->plugin_slug      = $settings['slug'];
      $this->plugin_name      = $settings['name'];
      $this->plugin_shortname = $settings['shortname'];
      $this->plugin_version   = $settings['version'];
      
      $this->base_plugin_name        =  $settings['base_plugin_name'];
      $this->base_plugin_function    =  $settings['base_plugin_function'];
      $this->base_plugin_min_version =  $settings['base_plugin_min_version'];
      
      $this->_data_key = str_replace( '-', '_', $settings['slug'] ) . '_data';
      $this->data_load(); 
      
      $this->addon_check();
      
    }


    /**
	   * get Base Plugin Name
     *
     * @since  PPF04
     * @access public
     * @return string
     */
    public function get_base_plugin_name() {
      
      return $this->base_plugin_name;
      
    }
    
    
    /**
	   * get Base Plugin Function
     *
     * @since  PPF04
     * @access public
     * @return string
     */
    public function get_base_plugin_function() {
      
      return $this->base_plugin_function;
      
    }
    
    
    /**
	   * get Base Plugin minimum required version^
     *
     * @since  PPF04
     * @access public
     * @return string
     */
    public function get_base_plugin_min_version() {
      
      return $this->base_plugin_min_version;
      
    }
    
    
    /**
     * check if base plugin exists and has required minimum version
     *
     * @since  PPF04
     * @access private
     */
    private function addon_check() {
      
      // we need to place all the stuff in plugins_loaded to ensure the base plugin is loaded
      
      add_action( 'plugins_loaded', function() {
        
        $this->plugin_install_update();
      
        $this->plugin_init();        
        
        if ( ! $this->base_exists() ) {
          
          add_action('admin_notices', array( $this, 'admin_notice_base_plugin_not_found' ) );
          
        } elseif ( version_compare( $this->get_base_plugin_min_version(), $this->call_base()->get_plugin_version(), '>' ) ) {
          
          add_action('admin_notices', array( $this, 'admin_notice_base_plugin_version_insufficient' ) );
          
        } else {
      
          $this->addon_init();
          
        }
        
      } );
      
      
    }
    
    
    /**
     * call base plugin 
     *
     * @since PPF04
     */
    public function call_base() {
      
      $base = $this->get_base_plugin_function();
        
      if ( function_exists( $base ) ) {
        
        return $base();
        
      }
      
      return false;
      
    }
    
    
    /**
     * check if base function exists 
     *
     * @since PPF04
     */
    public function base_exists() {
      
      $base = $this->get_base_plugin_function();
        
      if ( function_exists( $base ) ) {
        
        return true;
        
      }
      
      return false;
      
    }
    
    
    /**
     * addon init
     *
     * force to be defined
     *
     * @since PPF04
     */
    abstract public function addon_init();    
    
    
    /**
     * add admin notice if base plugin not found
     *
     * force to be defined
     *
     * @since PPF04
     */
    abstract public function admin_notice_base_plugin_not_found();    
    
    
    /**
     * add admin notice if base plugin version insufficient
     *
     * force to be defined
     *
     * @since PPF04
     */
    abstract public function admin_notice_base_plugin_version_insufficient();    

    
  }
  
}

?>