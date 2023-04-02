<?php

/**
 * Plugin Base Class
 *
 * Peter's Plugins Foundation 08
 *
 * @package    PPF08
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */

 
if ( !class_exists( 'PPF08_Plugin' ) ) {
  
  abstract class PPF08_Plugin extends PPF08_Class {
    
    /**
     * Instances
     *
     * since PPF02 this is an array to allow multiple classes based on the same Plugin Foundation
     *
     * @since  PPF02
     * @var    singleton
     * @access protected
     */
    protected static $_instances = array();
    
    
    /**
     * Plugin Name
     *
     * @since  PPF01
     * @var    string
     * @access protected 
     * was private prior to PPF04
     */
    protected  $plugin_name;
    
    
    /**
     * Plugin Short Name (for Display)
     *
     * @since  PPF01
     * @var    string
     * @access protected
     * was private prior to PPF04
     */
    protected $plugin_shortname;
    
    
    /**
     * Plugin File
     *
     * @since  PPF01
     * @var    string
     * @access protected
     * was private prior to PPF04
     */
    protected $plugin_file;
    
    
    /**
     * Plugin Base Dir
     *
     * @since  PPF01
     * @var    string
     * @access protected
     * was private prior to PPF04
     */
    protected $plugin_dir;
    
    
    /**
     * Plugin Slug
     *
     * @since  PPF01
     * @var    string
     * @access protected
     * was private prior to PPF04
     */
    protected $plugin_slug;
    
    
    /**
     * Plugin Version
     *
     * @since  PPF01
     * @var    int
     * @access protected
     * was private prior to PPF04
     */
    protected $plugin_version;
    
    
    /**
     * name of plugin data in databse (meta_key)
     *
     * @since  PPF01
     * @var    string
     * @access protected
     * was private prior to PPF04
     */
    protected $_data_key;
    
    
    /**
     * stored data
     *
     * @since  PPF01
     * @var    array
     * @access protected
     * was private prior to PPF04
     */
    protected $_data;
    
    
    /**
     * Settings Class ( if the plugin uses settings )
     *
     * @since  PPF01
     * @var    object
     * @access protected
     * was private prior to PPF04
     */
    protected $settings;
    

    /**
     * Init the Class 
     *
     * @since PPF01
     * @see   getInstance
     * @access public - must be public due to an error in PHP 7.1 - fixed in PHP 7.2
     */
    public function __construct( $settings ) {
     
      $this->plugin_file      = $settings['file'];
      $this->plugin_slug      = $settings['slug'];
      $this->plugin_name      = $settings['name'];
      $this->plugin_shortname = $settings['shortname'];
      $this->plugin_version   = $settings['version'];
      
      $this->plugin_dir       =  dirname( $settings['file'] );
      
      $this->_data_key = str_replace( '-', '_', $settings['slug'] ) . '_data';
      $this->data_load(); 
      
      $this->plugin_install_update();
      
      $this->plugin_init();
    }

    
    
    /**
     * plugin init
     *
     * force to be defined
     *
     * @since PPF01
     */
    abstract public function plugin_init();    
    
    
    /**
     * Prevent Cloning
     *
     * @since PPF01
     */
    final protected function __clone() {}
    
    
    /**
	   * Get the Instance
     *
     * @since PPF01
     * @param array $settings {
     *   @type string $file    Plugin Main File Path and Name
     *   @type string $slug    Plugin Slug
     *   @type string $name    Plugin Name
     *   @type string $version Plugin Verion
     * }
     * @return singleton
     */
    final public static function getInstance( $settings ) {
        
        $calledClass = get_called_class();

        if ( !isset( self::$_instances[$calledClass] ) )
        {
            self::$_instances[$calledClass] = new $calledClass( $settings );
        }

        return self::$_instances[$calledClass];
    }
    
    
    /**
	   * get plugin file
     *
     * @since  PPF01
     * @access public
     * @return string
     */
    public function get_plugin_file() {
      
      return $this->plugin_file;
      
    }
    
    
    /**
	   * get plugin slug
     *
     * @since  PPF01
     * @access public
     * @return string
     */
    public function get_plugin_slug() {
      
      return $this->plugin_slug;
      
    }
    
    
    /**
	   * get plugin name
     *
     * @since  PPF01
     * @access public
     * @return string
     */
    public function get_plugin_name() {
      
      return $this->plugin_name;
      
    }
    
    
    /**
	   * get plugin shortname
     *
     * @since  PPF01
     * @access public
     * @return string
     */
    public function get_plugin_shortname() {
      
      return $this->plugin_shortname;
      
    }
    
    
    /**
	   * get plugin version
     *
     * @since  PPF01
     * @access public
     * @return string
     */
    public function get_plugin_version() {
      
      return $this->plugin_version;
      
    }
    
    
    /**
     * get url for asset file
     *
     * @since  PPF01
     * @access public
     * @param  string $dir  sub-directory of assets dir (js, css)
     * @param  string $file filename
     * @return string
     */
    public function get_asset_url( $dir, $file ) {
     
      return plugins_url( 'assets/' . $dir . '/' . $file, $this->get_plugin_file() );
     
    }
    
    
    /**
     * get path for asset file
     *
     * @since  PPF01
     * @access public
     * @param  string $dir  sub-directory of assets dir (js, css)
     * @param  string $file filename
     * @return string
     */
    public function get_asset_path( $dir, $file ) {
     
      return plugin_dir_path( $this->get_plugin_file() ) . 'assets/' . $dir . '/' . $file;
     
    }
    
    
    /**
     * add a sub class
     *
     * @since  PPF01
     * @access private
     * @param  string $class     name of the class
     * @param  string $file      class file without extension php (must bei located in inc dir) 
     * @param  object $_core     the core class
     * @param  object $_settings the settings class (optional)
     * @return class
     */
    private function add_sub_class( $class, $file, $_core, $_settings = false ) {
      
      require_once plugin_dir_path( $this->get_plugin_file() ) . 'inc/' . $file . '.php';
      return new $class( $_core, $_settings);
      
    }
    
    
    /**
     * add a sub class as well in frontend as in backend
     *
     * @since  PPF01
     * @access public
     * @uses   add_sub_class()
     * @return class
     */
    public function add_sub_class_always( $class, $file, $_core, $_settings = false ) {
      
      return $this->add_sub_class( $class, $file, $_core, $_settings );
      
    }
    
    
    /**
     * add a sub class only in frontend
     *
     * @since  PPF01
     * @access public
     * @uses   add_sub_class()
     * @return class or false
     */
    public function add_sub_class_frontend( $class, $file, $_core, $_settings = false ) {
      
      if ( ! is_admin() ) {
        
        return $this->add_sub_class( $class, $file, $_core, $_settings );
        
      }
      
      return false;
      
    }
    
    
    /**
     * add a sub class only in backend
     *
     * @since  PPF01
     * @access public
     * @uses   add_sub_class()
     * @return class or false
     */
    public function add_sub_class_backend( $class, $file, $_core, $_settings = false ) {
      
      if ( is_admin() ) {
        
        return $this->add_sub_class( $class, $file, $_core, $_settings );
        
      }
      
      return false;
      
    }
    
    
    /**
     * add the settings class ( if the plugin uses settings )
     *
     * @since  PPF01
     * @access public
     * @param  string $class     name of the class
     * @param  string $file      class file without extension php (must bei located in inc dir) 
     * @param  object $_core     the core class
     * @param  object $defaults  the settings defaults
     */
    public function add_settings_class( $class, $file, $_core, $defaults ) {
      
      require_once plugin_dir_path( $this->get_plugin_file() ) . 'inc/' . $file . '.php';
      $this->settings = new $class( $_core, $defaults );
      
    }
    
    
    /**
	   * get settings class
     *
     * @since  PPF01
     * @access public
     * @return object
     */
    public function settings() {
      
      return $this->settings;
      
    }
    
    
    /**
	   * do plugin install or update
     *
     * @since  PPF01
     * @access protected (since PPF04, was private before)
     */
    protected  function plugin_install_update() {
      
      $version = $this->data_get( 'current_version' );
      
      if ( false === $version || version_compare( $version, $this->get_plugin_version(), '<' ) ) {
        
        if ( false === $version ) {
          
          // the plugin was not installed before (or the plugin foundation was not used before...)
        
          $this->do_plugin_install();
        
        } else {
        
          // do updates if needed
          $this->do_plugin_update( $version, $this->get_plugin_version() );
        
        }
        
        $this->data_set( 'current_version', $this->get_plugin_version() );
        $this->data_save();
        
      } 
      
    }
    
    
    /**
     * plugin install
     *
     * to be overwritten
     *
     * @since PPF01
     * @access public
     */
    public function do_plugin_install() {}
    
    
    /**
     * plugin update
     *
     * to be overwritten
     *
     * @since PPF01
     * @access public
     */
    public function do_plugin_update( $stored_version, $actual_version ) {}
    
    
    /**
	   * load plugin data from database
     *
     * @since PPF01
     * @access public
     */
    public function data_load() {
      
      $this->_data = get_option( $this->_data_key, array() );

      
    }
    
    /**
	   * get a data value
     *
     * @since PPF01
     * @param string $key  data key
     * @access public
     */
    public function data_get( $key ) {
      
      if ( is_array( $this->_data ) && array_key_exists( $key, $this->_data ) ) {
        
        return $this->_data[$key];
      
      } else {
        
        return false;
        
      }
      
    }
    
    
    /**
	   * set a data value
     *
     * @since PPF01
     * @param string $key   data key
     * @param string $value new value
     * @access public
     */
    public function data_set( $key, $value ) {
      
      return $this->_data[$key] = $value;
      
    }
    
    
    /**
	   * save data to database
     *
     * @since PPF01
     * @access public
     */
    public function data_save() {
      
      update_option( $this->_data_key, $this->_data );
      
    }
    
    
    /**
	   * remove all data from database
     *
     * @since PPF01
     * @access public
     */
    public function data_remove() {
      
      delete_option( $this->_data_key );
      
    }
    
    
  }
  
}

?>