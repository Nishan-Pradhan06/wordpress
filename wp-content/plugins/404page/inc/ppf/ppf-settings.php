<?php

/**
 * Settings Class
 *
 * Peter's Plugins Foundation 08
 *
 * @package    PPF08
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */
 
if ( !class_exists( 'PPF08_Settings' ) ) {
  
  
  abstract class PPF08_Settings extends PPF08_SubClass {
  
    /**
     * name of settings in databse (meta_key)
     *
     * @since  PPF01
     * @var    string
     * @access protected
     * was private prior to PPF04
     */
    protected $_key;
    
    
    /**
     * current settings
     *
     * @since  PPF01
     * @var    array
     * @access protected
     * was private prior to PPF04
     */
    protected $_settings;
    
    
    /**
     * settings defaults
     *
     * @since  PPF01
     * @var    array
     * @access protected
     * was private prior to PPF04
     */
    protected $_defaults;
    
    
    /**
	   * Init the Class 
     *
     * @since PPF01
     * @access public
     */
    public function __construct( $_core, $defaults ) {
      
      parent::__construct( $_core, false );
      
      $this->_key = $this->get_option_name();
      $this->_defaults = $defaults;
      $this->load();
      
    }
    
    
    /**
     * Sub-Class init
     * do nothing
     *
     * @since PPF01
     * @access public
     */
    public function init() {
    }
    
    
    /**
	   * get option name for settings
     *
     * @since PPF01
     * @access public
     * @return string option name
     */
    public function get_option_name() {
      
      return str_replace( '-', '_', $this->core()->get_plugin_slug() ) . '_settings';
      
    }
    
    
    /**
	   * load settings from database
     * settings are automatically loaded, but reload can be forced from outside
     *
     * @since PPF01
     * @access public
     */
    public function load() {
      
      $this->_settings = get_option( $this->_key, array() );
      $this->_settings = wp_parse_args( $this->_settings, $this->_defaults );
      $this->sanitize_settings();
      
    }
    
    
    /**
	 * sanitize settings
     * this function can be used to sanitize settings after they are loaded
     *
     * @since PPF01
     * @access public
     */
    public function sanitize_settings() {
      
      // Just to be defined here
      
    }
    
    
    /**
	   * get a settings value
     *
     * @since PPF01
     * @param string $key  settings key
     * @access public
     */
    public function get( $key ) {
      
      // as of PPF04 we check if the key exists
      if ( array_key_exists( $key, $this->_settings ) ) {
      
        return $this->_settings[$key];
        
      } else {
        
        return null;
        
      }
      
    }
    
    
    /**
	   * set a settings value
     *
     * @since PPF01
     * @param string $key   settings key
     * @param string $value new value
     * @access public
     */
    public function set( $key, $value ) {
      
      return $this->_settings[$key] = $value;
      
    }
    
    
    /**
	   * save settings to database
     *
     * @since PPF01
     * @access public
     */
    public function save() {
      
      update_option( $this->_key, $this->_settings );
      
    }
    
    
    /**
	   * get option key name for HTML form fields
     *
     * @since PPF01
     * @param string $key   settings key
     * @access public
     * @return string option key name
     */
    public function get_option_key_name( $key ) {
      
      return $this->get_option_name() . '[' . $key . ']';
      
    }
    
    
    /**
	   * get the defaults
     *
     * @since PPF01
     * @access public
     * @return array defaults
     */
    public function get_defaults() {
      
      return $this->_defaults;
      
    }
    
    
    /**
	   * remove
     *
     * @since PPF01
     * @access public
     */
    public function remove() {
      
      delete_option( $this->_key );
      
    }
  
    
  }
  
}