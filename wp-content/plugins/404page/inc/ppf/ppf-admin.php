<?php

/**
 * Admin Class
 *
 * Peter's Plugins Foundation 08
 *
 * @package    PPF08
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */
 
if ( !class_exists( 'PPF08_Admin' ) ) {
  
  
  abstract class PPF08_Admin extends PPF08_SubClass {
    
    
    /**
     * settings sections
     *
     * @since  PPF01
     * @var    array
     * @access private
     *
     * as of PPF04 we initialize an empty array
     */
    private $_sections = array();
    
    
    /**
     * is setting registered?
     *
     * @since  PPF04
     * @var    bool
     * @access private
     */
    private $_settings_registered = false;
    
    
    /**
     * toolbar
     *
     * @since  PPF01
     * @var    string
     * @access private
     */
    private $_toolbar;
    
    
    /**
     * id of screen
     *
     * this can be set via set_screen_id()
     *
     * @since  PPF01
     * @var    array
     * @access private
     */
    private $_my_screen_id;
    
  
    /**
     * add multiple setting sections
     *
     * @since  PPF01
     * @param  array $sections array of setting sections to add
     * @access public
     * @see    add_settings()
     *
     * as of PPF04 we add the sections to the _sections array to allow adding more sections
     */
    public function add_setting_sections( $sections ) {
      
      foreach( $sections as $section ) {
        
        // as of PPF04 we use add_setting_section()
        $this->add_setting_section( $section );

        
      }
      
      // since PPF04
      $this->maybe_register_setting();
      
    }
    
    
    /**
     * add a single setting section
     *
     * @since  PPF04
     * @param  array $sections setting section to add
     * @access public
     * @see    add_settings()
     */
    public function add_setting_section( $section ) {
     
      // as of PPF04 add sections to _sections array one by one
      $this->_sections[] = $section;
        
      if ( array_key_exists( 'fields', $section ) ) {
        
        $this->add_settings( $section );
          
      }
      
      $this->maybe_register_setting();
      
    }
    
    
    /**
     * register the setting
     *
     * @since  PPF04
     * @access private
     *
     * was part of add_setting_sections() before PPF04
     */
    public function maybe_register_setting() {
      
      if ( ! $this->_settings_registered ) {
      
        // Register the options
        // since PPF03 only if there is a settings class
        // so we can use the same function also if we don't need any settings
        if ( false !== $this->settings() ) {
          
          register_setting( $this->core()->get_plugin_slug(), $this->settings()->get_option_name(), array( 'sanitize_callback' => array( $this, 'sanitize_callback' ) ) );
          
        }
        
        $this->_settings_registered = true;
        
      }
      
      
    }
    
    
    /**
     * helper function to add a complete setting section
     *
     * @since  PPF01
     * @param  array $settings array of settings to add
     *                         string $section  => ID of the section
     *                         int    $order    => sort order
     *                                             this was added in PPF04, so we check if it exists for backwards compatibility
     *                         string $title    => title for section (used by print_setting_sections())
     *                         string $icon     => icon for tab 
     *                                             since PPF05
     *                         string $html     => HTML code to add to this section
     *                         array  $fields   => multidimensional array of fields to add
     *                                             string $key      =>     key of the option array
     *                                             string $callback =>     function to call
     *                                                                     as of PPF04 this can be an array to enable external callbacks
	 *                                             array  $callbackargs => array of arguments to pass to callback function
	 *                                                                     introduced in PPF08
     *                         bool   $nosubmit => this section should not show the submit button 
     * @access private
     */
    private function add_settings( $settings ) {
      
      $section_id = $this->core()->get_plugin_slug() . '-' . $settings['section'];
     
      add_settings_section( $section_id, '', null, $this->core()->get_plugin_slug() );
      
      foreach ( $settings['fields'] as $field ) {
  
        $field_id = $this->core()->get_plugin_slug() . '-' . $field['key'];
        
        // since PPF04
        if ( is_array( $field['callback'] ) ) {
          
          $callback = $field['callback'];
          
        } else {
        
          $callback = array( $this, $field['callback'] );
          
        }
		
		$callbackargs = false;
		
		if ( isset( $field['callbackargs'] ) && is_array( $field['callbackargs'] ) ) {
		
			$callbackargs = $field['callbackargs'];
			
		}
	  
        add_settings_field( $field_id, '' , $callback, $this->core()->get_plugin_slug(), $section_id, $callbackargs );
        
      }
      
      return;
      
    }
    
    
    /**
     * helper function to print out a slider styled checkbox
     *
     * @since  PPF01
     * @param  string $key      option key name
     * @param  string $title    title
     * @param  string $help     anchor to link to in manual
     * @param  string $video    YouTube video ID
     * @param  string $note     second line
     * @param  bool   $disabled true/false (optional)
     * @access public
     */
    public function print_slider_check( $key, $title, $help, $video, $note, $disabled = false ) {
      
      $dis = '';
      if ( $disabled ) {
        $dis = ' disabled="disabled"';
      }
      
      $hlp = '';
      if ( ! empty( $help ) ) {
        $hlp = $this->add_manual_link( $help );
      }
      
      $vid = '';
      if ( ! empty( $video ) ) {
        $vid = $this->add_video_link( $video );
      }
      
      $add = '';
      if ( ! empty( $note ) ) {
        $add = '<br />' . $note;
      }
       
      echo '<p class="toggle"><span class="slider"><input type="checkbox" name="' . $this->settings()->get_option_key_name( $key ) . '" id="' . $this->core()->get_plugin_slug() . '-' . $key . '" value="1"' . checked( true, $this->settings()->get( $key ), false ) . $dis . ' /><label for="' . $this->core()->get_plugin_slug() . '-' . $key . '" class="check"></label></span><span class="caption">' . $title . $hlp . $vid . $add . '</span></p>';
       
    }
    
    
    /**
     * helper function to add a plugin manual link
     *
     * @since PPF01
     * @param string $anchor name of the anchor to link to
     * @return string
     * @access private
     */
    private function add_manual_link( $anchor = '' ) {
       
      return ' <a class="dashicons dashicons-editor-help" href="https://petersplugins.com/' . $this->core()->get_plugin_slug() . '/manual/#' . $anchor . '"></a>';
       
    }
    
    /**
     * helper function to add a video link
     *
     * @since PPF01
     * @param string $youtubeid ID of the YouTube video
     * @return string
     * @access private
     */
    private function add_video_link( $youtubeid = '' ) {
       
      return ' <a class="dashicons dashicons-video-alt3" href="https://youtu.be/' . $youtubeid . '" data-lity></a>';
       
    }
    
    
    /**
     * print out setting sections
     * it is not possible to use do_settings_sections() because we are not able to create a tabbed interface
     *
     * @since  PPF01
     * @access public
     * @see    add_settings()
     */
    public function print_setting_sections() {
      
      $currentclass = ' current';
      
      foreach( $this->_sections as $section ) {
        
        $section_id = $this->core()->get_plugin_slug() . '-' . $section['section'];
        $extraclass = '';
        
        if ( array_key_exists( 'nosubmit', $section ) && true === $section['nosubmit'] ) {
          
          $extraclass = ' nosubmit';
        }
     
        echo '<div class="panel' . $extraclass . $currentclass . '" id="content-' . $section_id . '">';
        
        if ( array_key_exists( 'html', $section ) ) {
          
          echo '<div class="pp-admin-section-html">';
          echo $section['html'];
          echo '</div>';
          
        }
        
        if ( array_key_exists( 'fields', $section ) ) {
          
          echo '<table class="form-table pp-admin-section-fields">';
          do_settings_fields( $this->core()->get_plugin_slug(), $section_id );
          echo '</table>';
          
        }
        
        echo '</div>';
        
        $currentclass = '';
        
      }
        
    }
    
    
    /**
     * print out setting sections navigation
     *
     * @since  PPF01
     * @access public
     * @see    add_settings()
     */
    public function print_setting_sections_nav() {
      
      $currentclass = ' current';
      
      echo '<ul class="tab-navigation">';
      
      foreach( $this->_sections as $section ) {
        
        $section_id = $this->core()->get_plugin_slug() . '-' . $section['section'];
        
        $iconclass = '';
        
        if ( isset( $section['icon'] ) ) {
          
            $iconclass = ' has-icon icon-' . $section['icon'];
        }
        
        echo '<li><div class="tabset' . $currentclass . $iconclass . '" id="tab-' . $section_id . '" data-tab-content="content-' . $section_id . '">' . $section['title'] . '</div>';
        
        $currentclass = '';
        
      }
      
      echo '</ul>';
        
    }
    
    
    /**
     * sanitize the posted values
     *
     * @since  PPF01
     * @param  array $settings array of settings to save
     * @access public
     */
    public function sanitize_callback( $settings ) {
      
      // since PPF06
      // if wen don't get anything we need to create an empty array
      if ( empty( $settings ) ) {
        $settings = array();
      }
      
      foreach ( $this->settings()->get_defaults() as $key => $value ) {
        
        if ( true === is_bool( $value ) ) {
          
          if ( ! array_key_exists( $key, $settings ) ) {
            
            // we have to add the missing keys
            // HTML forms only send data if a checkbox is checked
            // missing keys would be overwritten with their default on next load
            // this concerns only boolean values
            // if key does not exist the checkbox was not checked
            // so we have to handle it as false
            $settings[$key] = false;
            
          } else {
            
            // also we check if a given value is boolean
            // otherwise we reset it to false
            // this is for security
            // so it is not possible to pass non boolean values
            // if a checkbox was checked we get 1
            // so we only have to check for 1
            
            if ( 1 != $settings[$key] ) {
              
              $settings[$key] = false;
              
            }
            
          }
          
        }
        
      }
      
      // it is not possible to do other sanitation because we do not know what to do
      // more sanitation has to be done by the plugin itself
      
      return $this->sanitize_settings( $settings );
      
    }
    
    
    /**
     * sanitize the settings
     * called by sanitize_callback()
     * this can be used to sanitize the settings after missing keys have been added
     *
     * @since  PPF01
     * @param  array $settings array of settings to save
     * @access public
     */
    public function sanitize_settings( $settings ) {
      
      return $settings;
      
    }
    
    
    /**
     * add toolbar icons
     *
     * @since  PPF01
     * @param  array $icons array of icons to show in toolbar
     *                      string $link  => URL to link to
     *                      string $title => title to show
     *                      string $icon  => icon to use from dashicons
     * @access public
     */
    public function add_toolbar_icons( $icons ) {
      
      $this->_toolbar = '<nav>';
       
      foreach ( $icons as $icon ) {
         
        $this->_toolbar .= '<a href="' . esc_url( $icon['link'] ) . '" title="' . $icon['title'] . '"><span class="dashicons ' . $icon['icon'] . '"></span><span class="text">' . $icon['title'] . '</span></a>';
         
      }
      
      $this->_toolbar .= '</nav>';
      
    }
    
    
    /**
     * show the admin page
     *
     * @since  PPF01
     * @param  string $capability minimum required capability to show page (optional)
     * @access public
     */
    public function show( $capability = 'read' ) {
      
      if ( !current_user_can( $capability ) )  {
        
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
        
      }
      
      
      // sort the sections
      
      // see add_settings()
      $sort = false;
      
      foreach( $this->_sections as $section ) {
        
        if ( array_key_exists( 'order', $section ) ) {
          
          $sort = true;
          break; 
          
        }
      }
      
      if ( $sort ) {
        
        usort( $this->_sections, function( $a, $b ) {
          return $a['order'] - $b['order'];
          
        } );
        
      }
      
      // end of sort
      
      if ( get_current_screen()->parent_base != 'options-general' ) {
      
        // On Option Screens settings_errors() is called automatically
        settings_errors();
        
      }
      
      echo '<div class="wrap pp-admin-page-wrapper" id="pp-' . $this->core()->get_plugin_slug() . '-settings"><div class="pp-admin-notice-area"><div class="wp-header-end"></div></div>';
      echo '<div class="pp-admin-page-header">';
      echo $this->_toolbar;
      echo '<div class="pp-admin-page-title"><h1>' . $this->core()->get_plugin_shortname() . '</h1></div>';
      $this->print_setting_sections_nav();
      echo '</div>';
      echo '<div class="pp-admin-page-inner"><form method="POST" action="options.php">';
      echo '<div class="tab-panel">';
      settings_fields( $this->core()->get_plugin_slug() );
      $this->print_setting_sections();
      submit_button();
      echo '</div></form></div></div>';
      echo '<script>var pp_admin_cookie_prefix="' . $this->core()->get_plugin_slug() . '";</script>';
      
      wp_enqueue_style( $this->core()->get_plugin_slug() . '-ppf03', $this->get_foundation_asset_url( 'css', 'pp-admin-page.css' ) );      

      wp_enqueue_script( $this->core()->get_plugin_slug() . '-ppf03-cookie', $this->get_foundation_asset_url( 'js', 'jquery.cookie.js' ), array( 'jquery' ), false, true );
      wp_enqueue_script( $this->core()->get_plugin_slug() . '-ppf03', $this->get_foundation_asset_url( 'js', 'pp-admin-page.js' ), array( 'jquery', $this->core()->get_plugin_slug() . '-ppf03-cookie' ), false, true );
      
    }
    
    
    /**
     * set screen id
     *
     * @since PPF01
     * @param string $id id of screen
     * @access public
     */
    public function set_screen_id( $id ) {
       
      $this->_my_screen_id = $id;
       
    }
    
    
    /**
     * get screen id
     *
     * @since PPF01
     * @return string
     * @access public
     */
    public function get_screen_id() {
       
      return $this->_my_screen_id;
       
    }
    
    
    /**
     * show admin notice to ask for rating
     * this function does not show the notice immediately
     * this function just needs to be called and it takes care of everything
     *
     * @since  PPF01
     * @param  array $content array of texts to show
     *                        string $title          => e.g. 'Are you happy with the example plugin?'
     *                        string $subtitle       => e.g. 'You've  been using this plugin for a while now. Would be great to get some feedback!'
     *                        string $button_yes     => e.g. 'Yes, I'm happy with it'
     *                        string $button_no      => e.g. 'Not really'
     *                        string $button_later   => e.g. 'Ask me later'
     *                        string $button_close   => e.g. 'Never show again'
     *                        string $like           => e.g. 'I'm really glad you like it. I do not ask for a donation. All I'm asking you for is to give it a good rating. Thank you very much. 
     *                        string $button_rate    => e.g. 'Yes, I'd like to rate it'
     *                        string $dislike        => e.g. 'I'm really sorry you don't like it. Would you please do me a favor and drop me line, why you are not happy with it? Maybe I can do better...'
     *                        string $button_contact => e.g. 'Yes sure'
     * @param  array $links   array of links
     *                        string $rate           => e.g. https://wordpress.org/support/plugin/example/reviews/
     *                        string $contact        => e.g. https://petersplugins.com/contact/
     * @access public
     */
    public function init_rating_notice( $content, $links ) {
      
      // quit immediately if message has already been closed
      if ( 'YES' == $this->core()->data_get( 'ask_rating_closed' ) ) {
        return;
      }
      
      $show_notice_start = $this->core()->data_get( 'ask_rating_start' );
      
      // if start date is not set, set it and quit immediately
      if ( false === $show_notice_start ) {
        $this->core()->data_set( 'ask_rating_start', time() + 30 * DAY_IN_SECONDS );
        $this->core()->data_save();
        return;
      }
      
      // quit immediately if start date is not reached yet
      if ( time() < $show_notice_start ) {
        return;
      }
      
      // quit immediately if current user is not an admin
      if ( ! current_user_can( 'manage_options' ) ) {
        return;
      }
      
      $prefix  = 'pp-' . $this->core()->get_plugin_slug();
      $nonce   = wp_create_nonce( $prefix );
      
      // prepare to show notice
      add_action( 'admin_notices', function() use( $content, $links, $prefix, $nonce ) {
      
        // show notice only on certain pages
        // it's not possible to check this earlier, because we need the id of the current screen for that
        if ( ! in_array( get_current_screen()->id, array( 'dashboard', 'themes', 'plugins', 'options-general' , $this->get_screen_id() ) ) ) {
          return;
        }
        
        
        ?>
        <div class="notice notice-info" id="<?php echo $prefix; ?>-review-notice">
          <h3 style="margin-bottom: 0"><?php echo $content['title']; ?></h3>
          <div class="<?php echo $prefix; ?>-review-notice-container">
            <div id="<?php echo $prefix; ?>-review-step-1" class="<?php echo $prefix; ?>-review-notice-step">
              <p><?php echo $content['subtitle']; ?></p>
              <p><a id="<?php echo $prefix; ?>-review-happy" class="button button-primary" href="javascript:void(0);"><?php echo $content['button_yes']; ?></a> <a id="<?php echo $prefix; ?>-review-unhappy" class="button" href="javascript:void(0);"><?php echo $content['button_no']; ?></a></p>
            </div>
            <div id="<?php echo $prefix; ?>-review-step-like" class="<?php echo $prefix; ?>-review-notice-step">
              <p><?php echo $content['like']; ?></p>
              <p><a class="button button-primary" href="<?php echo $links['rate']; ?>"><?php echo $content['button_rate']; ?></a></p>
            </div>
            <div id="<?php echo $prefix; ?>-review-step-dislike" class="<?php echo $prefix; ?>-review-notice-step">
              <p><?php echo $content['dislike']; ?></p>
              <p><a class="button button-primary" href="<?php echo $links['contact']; ?>"><?php echo $content['button_contact']; ?></a></p>
            </div>
          </div>
          <p class="wp-clearfix"><a id="<?php echo $prefix; ?>-review-later" class="<?php echo $prefix; ?>-review-action" href="javascript:void(0);"><?php echo $content['button_later']; ?></a> <a id="<?php echo $prefix; ?>-review-close" class="<?php echo $prefix; ?>-review-action" href="javascript:void(0);"><?php echo $content['button_close']; ?></a></p>
        </div>
        <?php
        
      } );
      
      
      // Since PPF04 we add CSS and JS to footer for compatibility reasons
      
      add_action( 'admin_print_footer_scripts', function() use( $content, $links, $prefix, $nonce ) {
        
        // show notice only on certain pages
        // it's not possible to check this earlier, because we need the id of the current screen for that
        if ( ! in_array( get_current_screen()->id, array( 'dashboard', 'themes', 'plugins', 'options-general' , $this->get_screen_id() ) ) ) {
          return;
        }
        
        echo '
        <style type="text/css">
            #' . $prefix . '-review-step-like, #' . $prefix . '-review-step-dislike {
              display: none;
            }
            #' . $prefix . 'review-later, #' . $prefix . '-review-close, #' . $prefix . '-review-later:before, #' . $prefix . '-review-close:before {
              display: block;
              height: 20px;
              line-height: 20px;
              text-decoration: none;
            }
            #' . $prefix . '-review-later, #' . $prefix . '-review-close {
              float: left;
              position: relative;
              padding-left: 22px;
            }
            #' . $prefix . '-review-later {
              margin-right: 12px;
            } 
            #' . $prefix . '-review-later:before, #' . $prefix . '-review-close:before {
              font-family: dashicons;
              font-size: 20px;
              position: absolute;
              left: 0;
              top: 0;
            }
            #' . $prefix . '-review-later:before {
              content: "\f508";
            }
            #' . $prefix . '-review-close:before {
              content: "\f153";
            }
          </style>
          
          <script type="text/javascript">
            jQuery( function( $ ) {
              
              $( "#' . $prefix . '-review-happy" ).click( function() {
                  $( "#' . $prefix . '-review-step-1" ).fadeOut( 400, function() {
                    $( "#' . $prefix . '-review-step-like" ).fadeIn();
                  });
              } );
              
              $( "#' . $prefix . '-review-unhappy" ).click( function() {
                  $( "#' . $prefix . '-review-step-1" ).fadeOut( 400, function() {
                    $( "#' . $prefix . '-review-step-dislike" ).fadeIn();
                  });
              } );
              
              $( ".' . $prefix . '-review-action" ).click( function() {
                
                $.post( 
                  ajaxurl, {
                    action    : "' . $prefix . '-review-action",
                    command   : $(this).attr( "id" ),
                    securekey : "' . $nonce .'"
                  } 
                );
                $( "#' . $prefix . '-review-notice" ).fadeOut();
                
              } );
		
            } );
          </script>';
        
        
      } );
      
      
      // prepare for ajax
      add_action( 'wp_ajax_' . $prefix . '-review-action', function() use( $prefix ) {
        
        check_ajax_referer( $prefix, 'securekey' ); // dies if check fails
        
        if ( isset( $_POST['command'] ) ) {
          
          if ( $prefix . '-review-later' == $_POST['command'] ) {
            
            // move start date 14 days into future
            $this->core()->data_set( 'ask_rating_start', time() + 14 * DAY_IN_SECONDS );
            $this->core()->data_save();
            
          }
          
          if ( $prefix . '-review-close' == $_POST['command'] ) {
            
            // do not show notice again
            $this->core()->data_set( 'ask_rating_closed', 'YES' );
            $this->core()->data_save();
            
          }
          
        }
        
        wp_die();
        
      } );
          
    }
    
  }
  
}