<?php

/**
 * The 404page core plugin class
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


// indicate that 404page plugin is active
if ( ! defined( 'PP_404' ) ) {
  define( 'PP_404', true );
}


/**
 * The core plugin class
 */
if ( !class_exists( 'PP_404Page' ) ) {
  
  
  class PP_404Page extends PPF08_Plugin {
    
    
    /**
     * Native Mode
     *
     * @since  11.0.0 - was part of previous settings class
     * @var    bool
     * @access private
     */
    private $native;
    
    private $template;
    private $postid;
    
    
    /**
     * Admin Class
     *
     * @see    class-404page-admin.php
     * @since  10
     * @var    object
     * @access private
     */
    private $admin;
    
    
    /**
     * Block Editor Class
     *
     * @see    class-404page-block-editor.php
     * @since  9
     * @var    object
     * @access private
     */
    private $blockeditor;
    
    
    /**
     * Classic Editor Class
     *
     * @see    class-404page-classic-editor.php
     * @since  9
     * @var    object
     * @access private
     */
    private $classiceditor;
    
    
    /**
     * Deprecated Class
     *
     * @see    class-404page-deprecated.php
     * @since  11.0.0
     * @var    object
     * @access private
     */
    private $deprecated;
	
	
	/**
     * URL that caused the 404 error
     *
     * @since  11.4.0
     * @var    array
     * @access private
     */
    private $error_url;
    
    
    /**
     * Init the Class 
     *
     * @since 11.0.0
     * was part of __construct before
     */
    public function plugin_init() {
      
      // settings defaults
      // @since 11.0.0
      $defaults = array(
        'page_id'            => 0,
        'hide'               => false,
        'fire_error'         => true,
        'force_error'        => false,
        'no_url_guessing'    => false,
        'http410_if_trashed' => false,
		'http410_always'     => false,
        'method'             => 'STD'
      );
      
      // since 11.0.0 we use add_settings_class() to load the settings
      $this->add_settings_class( 'PP_404Page_Settings', 'class-404page-settings', $this, $defaults );
      
      // @since 11.0.0
      $this->add_action( 'init' );
    } 
   
   
    /**
     * do plugin init 
     * this runs after init action has fired to ensure everything is loaded properly
     * was init() before 11.0.0
     */
    function action_init() {
      
      // moved from add_text_domain() in v 11.0.0
      load_plugin_textdomain( '404page' );
      
      
      // change old stuff to new stuff for backward compatibility
      // since v 11.0.0
      $this->deprecated = $this->add_sub_class_always( 'PP_404Page_Deprecated',         'class-404page-deprecated',          $this );
      
      // load the following subclasses only on backend
      // using add_sub_class_backend() sinde v 11 
      $this->admin         = $this->add_sub_class_backend( 'PP_404Page_Admin',         'class-404page-admin',          $this, $this->settings() );
      $this->blockeditor   = $this->add_sub_class_backend( 'PP_404Page_BlockEditor',   'class-404page-block-editor',   $this, $this->settings() );
      $this->classiceditor = $this->add_sub_class_backend( 'PP_404Page_ClassicEditor', 'class-404page-classic-editor', $this, $this->settings() );
      
      // as of v 2.2 always call set_mode
      // as of v 2.4 we do not need to add an init action hook
      
      if ( !is_admin() && $this->settings()->get( 'page_id' ) > 0 ) {
        
        // as of v 3.0 we once check if there's a 404 page set and not in all functions separately
        $this->set_mode();
        add_action( 'pre_get_posts', array ( $this, 'exclude_404page' ) );
        add_filter( 'get_pages', array ( $this, 'remove_404page_from_array' ), 10, 2 );
        
        // Stop URL guessing if activated
        if ( $this->settings()->get( 'no_url_guessing' ) ) {
          add_filter( 'redirect_canonical' ,array ( $this, 'no_url_guessing' ) );
        }
        
        
        // Remove 404 error page from XML sitemaps
        // only if "Send an 404 error if the page is accessed directly by its URL" is active
        if ( $this->settings()->get( 'fire_error' ) ) {
          
          // YOAST sitemap
          // @since 6
          
          // we do not need to check if Yoast Sitemap feature is activated because the filter only fires if it is active
          add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', function ( $alreadyExcluded ) {
            // as of 11.0.5 we add the page ID to the array of already excluded pages
            // plus we exclude the 404 page in all languages
            return array_merge( $alreadyExcluded, $this->get_all_page_ids() );
          }, 999 );
          
          
          // Jetpack sitemap
          // @since 11.1.2
          
          // we do not need to check if Jetpack Sitemap feature is activated because the filter only fires if it is active
          add_filter( 'jetpack_sitemap_skip_post', function ( $skip, $post ) {
            if ( in_array( intval( $post->ID ), $this->get_all_page_ids() ) ) {
              $skip = true;
            }
            return $skip;
          }, 999, 2 );
          
        }
        
      }
      
        
      if ( class_exists( 'PP_404Page_Admin' ) ) {
  
        // load classes only if in admin
        // @since 10
        // using class_exists( 'PP_404Page_Admin' ) instead of is_admin() as of v 10.3 for compatibilty with iThemes Sync
        
        //$this->admin = new PP_404Page_Admin( $this, $this->settings );
        //$this->blockeditor = new PP_404Page_BlockEditor( $this );
        //$this->classiceditor = new PP_404Page_ClassicEditor( $this );
        
        // Remove 404 page from post list if activated
        // not moved to PP_404Page_Admin because we also need exclude_404page() in frontend
        if ( $this->settings()->get( 'hide' ) and $this->settings()->get( 'page_id' ) > 0 ) {
          add_action( 'pre_get_posts' ,array ( $this, 'exclude_404page' ) );
        }
        
      }
      
    }
    
    
    /**
     * init filters 
     */
    function set_mode() {
      
      $this->settings()->set_method();
           
      if ( defined( 'CUSTOMIZR_VER' ) ) {
        
        // Customizr Compatibility Mode 

        // @since 3.1
        add_filter( 'body_class', array( $this, 'add_404_body_class_customizr_mode' ) );
        
        add_filter( 'tc_404_header_content', array( $this, 'show404title_customizr_mode' ), 999 );
        add_filter( 'tc_404_content', array( $this, 'show404_customizr_mode' ), 999 );
        add_filter( 'tc_404_selectors', array( $this, 'show404articleselectors_customizr_mode' ), 999 );
        
        // send http 410 instead of http 404 if requested resource is in trash
        // @since 3.2
		// or always send http 410
		// @since 11.3.0
        if ( $this->settings()->get( 'http410_if_trashed' ) || $this->settings()->get( 'http410_always' ) ) {
          
          add_action( 'template_redirect', array( $this, 'maybe_send_410' ) 	);
          
        }
        
      } elseif ( $this->settings()->get( 'method' ) != 'STD' ) {
          
        // Compatibility Mode
        // as of v 2.4 we use the the_posts filter instead of posts_results, because the posts array is internally processed after posts_results fires
		// as of v 11.4.3 we also pass the query
        add_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999, 2 );
        
        // as of v 2.5 we remove the filter if the DW Question & Answer plugin by DesignWall (https://www.designwall.com/wordpress/plugins/dw-question-answer/) is active and we're in the answers list
        add_filter( 'dwqa_prepare_answers', array( $this, 'remove_show404_compatiblity_mode' ), 999 );
          
      } else {
          
        // Standard Mode
        add_filter( '404_template', array( $this, 'show404_standard_mode' ), 999 );
        
        if ( $this->settings()->get( 'fire_error' ) ) {
          
          add_action( 'template_redirect', array( $this, 'do_404_header_standard_mode' ) );
          
        }
        
        // send http 410 instead of http 404 if requested resource is in trash
        // @since 3.2
		// or always send http 410
		// @since 11.3.0
        if ( $this->settings()->get( 'http410_if_trashed' ) || $this->settings()->get( 'http410_always' ) ) {
          
          add_action( 'template_redirect', array( $this, 'maybe_send_410' ) 	);
          
        }
          
      }
      
    }
    
    
    /**
     * show 404 page 
     * Standard Mode
     */
    function show404_standard_mode( $template ) {
      
      global $wp_query;
      
      // @since 4
      // fix for an ugly bbPress problem
      // see https://wordpress.org/support/topic/not-fully-bbpress-compatible/
      // see https://bbpress.trac.wordpress.org/ticket/3161
      // if a bbPress member page is shown and the member has no topics created yet the 404_template filter hook fires
      // this is a bbPress problem but it has not been fixed since 6 months
      // so let's bypass the problem
      if ( function_exists( 'bbp_is_single_user' ) ) {
        
        if ( bbp_is_single_user() ) {
          
          return $template;
          
        }
      
      }
      // that's it
	  
	  // save URL that caused the 404 - since 11.4.0
      $this->set_404_url();
      
      if ( ! $this->is_native() ) {
        
        $this->disable_caching();
        
        $wp_query = null;
        $wp_query = new WP_Query();
        $wp_query->query( 'page_id=' . $this->get_page_id() );
        
        
        $wp_query->the_post();
        $template = get_page_template();
        rewind_posts();
        add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
      }
      $this->maybe_force_404();
      $this->do_404page_action();
      return $template;
      
    }

    
    /**
     * show 404 page
     * Compatibility Mode
     */
    function show404_compatiblity_mode( $posts, $query ) {
      
      global $wp_query;
          
      // remove the filter so we handle only the first query - no custom queries
	  // moved in 11.4.3 due to problems with WP 6.1
      // remove_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999 ); 
      
      // @since 4
      // fix for an ugly bbPress problem
      // see show404_standard_mode()
      if ( function_exists( 'bbp_is_single_user' ) ) {
        
        if ( bbp_is_single_user() ) {
          
          return $posts;
          
        }
      
      }
      // that's it   
      
      $pageid = $this->get_page_id();
      if ( ! $this->is_native() ) {

        // as of v 10 we also check if $wp_query->query[error] == 404
        // this is necessary to bypass a WordPress bug
        // if permalink setting is something like e.g. /blog/%postname%/ the $posts is not empty
        // bug reported https://core.trac.wordpress.org/ticket/46000
        
        // as of v 11.0.3 we also check for REST_REQUEST to not create a 404 page in case of REST API call
        // as of v 11.2.4 we also check for DOING_CRON
		
		// is_main_query() is true in multiple cases
		// as of v 11.4.3 we check the query against the main query
		
        if ( ( empty( $posts ) || ( isset( $wp_query->query['error'] ) && $wp_query->query['error'] == 404 ) ) && $wp_query == $query && is_main_query() && !is_robots() && !is_home() && !is_feed() && !is_search() && !is_archive() && ( !defined('DOING_AJAX') || !DOING_AJAX ) && ( !defined('DOING_CRON') || !DOING_CRON ) && ( !defined('REST_REQUEST') || !REST_REQUEST ) ) {  
			
		  // save URL that caused the 404 - since 11.4.0
		  $this->set_404_url();	
          
          // as of v2.1 we do not alter the posts argument here because this does not work with SiteOrigin's Page Builder Plugin, template_include filter introduced
          $this->postid = $pageid;
          
          // as of v 2.4 we use the the_posts filter instead of posts_results
          // therefore we have to reset $wp_query 
          // resetting $wp_query also forces us to remove the pre_get_posts action plus the get_pages filter
          
          remove_action( 'pre_get_posts', array ( $this, 'exclude_404page' ) );
          remove_filter( 'get_pages', array ( $this, 'remove_404page_from_array' ), 10, 2 );
		  
		  // moved here in 11.4.3
		  remove_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999 ); 
          
          $this->disable_caching();
         
          $wp_query = null;
          $wp_query = new WP_Query();
          
          // @since 8
          // added suppress_filters for compatibilty with current WPML version
          $wp_query->query( array( 'page_id' => $pageid, 'suppress_filters' => true ) );
		 
          $wp_query->the_post();
          $this->template = get_page_template();
          $posts = $wp_query->posts;
          $wp_query->rewind_posts();          

          add_action( 'wp', array( $this, 'do_404_header' ) );
          add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
          add_filter( 'template_include', array( $this, 'change_404_template' ), 999 );
                 
          
          $this->maybe_force_404();
          $this->do_404page_action();
          
        } elseif ( 1 == count( $posts ) && 'page' == $posts[0]->post_type ) {
          
          // Do a 404 if the 404 page is opened directly
          if ( $this->settings()->get( 'fire_error' ) ) {
			  
			// save URL that caused the 404 - since 11.4.0
		    $this->set_404_url();
            
			$curpageid = $posts[0]->ID;
            
            if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
             
             // WPML is active - get the post ID of the default language
              global $sitepress;
              $curpageid = apply_filters( 'wpml_object_id', $curpageid, 'page', $sitepress->get_default_language() );
              $pageid = apply_filters( 'wpml_object_id', $pageid, 'page', $sitepress->get_default_language() );
              
            } elseif ( function_exists( 'pll_get_post' ) ) {
              
              // was defined( 'POLYLANG_VERSION' ) before version 11.2.3
            
              // Polylang is active - get the post ID of the default language
              $curpageid = pll_get_post( $curpageid, pll_default_language() );
              $pageid = pll_get_post( $pageid, pll_default_language() );
          
            }
            
            if ( $pageid == $curpageid ) {
              add_action( 'wp', array( $this, 'do_404_header' ) );
              add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
              $this->maybe_force_404();
              $this->do_404page_action();
            }
          }
          
        }
      } else {
        $this->maybe_force_404();
        $this->do_404page_action();
      }
      return $posts;
    }
    
    /**
     * disable caching for known caching plugins
     * 
     * @since 11.2.0
     */
    function disable_caching() {
      
      // WP Super Cache
      if ( defined( 'WPCACHEHOME' ) ) {
        
        global $cache_enabled;
        
        // is caching active?
        if ( $cache_enabled ) {
        
          define( 'DONOTCACHEPAGE', true );
          
        }
        
      }
      
      
      // W3 Total Cache
      if ( defined( 'W3TC' ) ) {
      
        if ( class_exists( 'W3TC\Dispatcher' ) ) {
          
          // is caching active?
          if ( W3TC\Dispatcher::config()->get_boolean( 'pgcache.enabled' ) ) {
            
            define( 'DONOTCACHEPAGE', true );
            
          }
          
        }
        
      }
      
    }
    
    
    /**
     * for DW Question & Answer plugin
     * this function is called by the dwqa_prepare_answers filter
     */
    function remove_show404_compatiblity_mode( $args ) {
      remove_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999 );
      return $args;
    }
    
    
    /**
     * this function overrides the page template in compatibilty mode
     */
    function change_404_template( $template ) {
      
      // we have to check if the template file is there because if the theme was changed maybe a wrong template is stored in the database
      $new_template = locate_template( array( $this->template ) );
      if ( '' != $new_template ) {
        return $new_template ;
      }
      return $template;
    }
    
    
    /**
     * send 404 HTTP header
     * Standard Mode
     */
    function do_404_header_standard_mode() {
      if ( is_page() && get_the_ID() == $this->settings()->get( 'page_id' ) && !is_404() ) {
		// save URL that caused the 404 - since 11.4.0
		$this->set_404_url();  
		
        status_header( 404 );
        nocache_headers();
        $this->maybe_force_404();
		// Add 404 body class - since 11.4.2
		add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
        $this->do_404page_action();
      }
    }
    
    
    /**
     * send 404 HTTP header 
     * Compatibility Mode
     */
    function do_404_header() {
      // remove the action so we handle only the first query - no custom queries
      remove_action( 'wp', array( $this, 'do_404_header' ) );
      
      // send http 410 instead of http 404 if requested resource is in trash
      // @since 3.2
      
      if ( $this->settings()->get( 'http410_if_trashed' ) && $this->is_url_in_trash( $this->get_404_url() ) ) {
          
        status_header( 410 );
          
      } else {
      
        status_header( 404 );
        
      }
      nocache_headers();
    }
    
    
    /**
     * add body classes
     */
    function add_404_body_class( $classes ) {
      
      // as of v 3.1 we first check if the class error404 already exists
      if ( ! in_array( 'error404', $classes ) ) {
      
        $classes[] = 'error404';
      
      }
      
      // debug class
      // @since 3.1
      $debug_class = 'pp404-';
      if ( $this->is_native() ) {
        $debug_class .= 'native';
      } elseif ( defined( 'CUSTOMIZR_VER' ) ) {
        $debug_class .= 'customizr';
      } elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        $debug_class .= 'wpml';
      } elseif ( $this->settings()->get( 'method' ) != 'STD' ) {
        $debug_class .= 'cmp';
      } else {
        $debug_class .= 'std';
      }
      $classes[] = $debug_class;
      
      return $classes;
    }
    
    
    /**
     * add body classes customizr mode
     * @since 3.1
     */
    function add_404_body_class_customizr_mode( $classes ) {
      
      if ( is_404() ) {
		  
		// save URL that caused the 404 - since 11.4.0
		$this->set_404_url();  
        
        $classes = $this->add_404_body_class( $classes );
      
      }
      
      return $classes;
    }
    
    
    /**
     * show title
     * Customizr Compatibility Mode
     */ 
    function show404title_customizr_mode( $title ) {
      if ( ! $this->is_native() ) {
        return '<h1 class="entry-title">' . get_the_title( $this->get_page_id() ) . '</h1>';
      } else {
        return $title;
      }
    }
    
    
    /**
     * show content
     * Customizr Compatibility Mode
     */
    function show404_customizr_mode( $content ) {
      if ( ! $this->is_native() ) {
        return '<div class="entry-content">' . apply_filters( 'the_content', get_post_field( 'post_content', $this->get_page_id() ) ) . '</div>';
      } else {
        return $content;
      }
      $this->do_404page_action();
    }
    
    
    /**
     * change article selectors 
     * Customizr Compatibility Mode
     */
    function show404articleselectors_customizr_mode( $selectors ) {
      if ( ! $this->is_native() ) {
        return 'id="post-' . $this->get_page_id() . '" class="' . join( ' ', get_post_class( 'row-fluid', $this->get_page_id() ) ) . '"';
      } else {
        return $selectors;
      }
    }
    
    
    /**
     * do we have to force a 404 in wp_head?
     */
    function maybe_force_404() {
      if ( $this->settings()->get( 'force_error' ) ) {
        add_action( 'wp_head', array( $this, 'force_404_start' ), 9.9 );
        add_action( 'wp_head', array( $this, 'force_404_end' ), 99 );
      }
    }
    
        
    /**
     * Force 404 in wp_head start
     * potentially dangerous!
     */
    function force_404_start() {
      global $wp_query;
      $wp_query->is_404 = true;
    }
    
    
    /**
     * Force 404 in wp_head end
     * potentially dangerous!
     */
    function force_404_end() {
      global $wp_query;
      $wp_query->is_404 = false;
    }
    
    
    /**
     * disable URL autocorrect guessing
     */
    function no_url_guessing( $redirect_url ) {
      if ( is_404() && !isset($_GET['p']) ) {
        $redirect_url = false;
      }  
      return $redirect_url;
    }
    
    
    /**
     * send http 410 instead of http 404 in case the requested URL can be found in trash
     * @since 3.2
	 * or always send http 410
	 * @since 11.3.0
        
     */
    function maybe_send_410() {
            
      // we don't do anything if there is no 404
      if ( is_404() ) {
			
		if ( $this->settings()->get( 'http410_always' ) || ( $this->settings()->get( 'http410_if_trashed' ) && $this->is_url_in_trash( $this->get_404_url() ) ) ) {
          
          status_header( 410 );
          
        }
      }
      
    }
    
    
    /**
     * hide the 404 page from the list of pages 
     */
     
    function exclude_404page( $query ) {
      
      $pageid = $this->get_page_id();
      
      if ( $pageid > 0 ) {
        
        global $pagenow;
        
        $post_type = $query->get( 'post_type' );

        // as of v 2.3 we check the post_type on front end
        // as of v 2.5 we also hide the page from search results on front end
        if( ( is_admin() && ( 'edit.php' == $pagenow && !current_user_can( 'create_users' ) ) ) || ( ! is_admin() && ( is_search() || ( !empty( $post_type) && ( ('page' === $post_type || 'any' === $post_type) || ( is_array( $post_type ) && in_array( 'page', $post_type ) ) ) ) ) ) ) {
          
          // as of v 2.4 we hide all translations in admin for WPML
          // as of v 2.5 we hide all translations from search results on front end for WPML
          if ( is_admin() || ( ! is_admin() && is_search() ) ) {
            
            $pageids = $this->get_all_page_ids();
            
          } else {
            
            $pageids = array( $pageid );
            
          }
          
          // as of v 2.3 we add the ID of the 404 page to post__not_in
          // using just $query->set() overrides existing settings but not adds a new setting
          $query->set( 'post__not_in', array_merge( (array)$query->get( 'post__not_in', array() ), $pageids ) );
          
        }
        
      }
      
    }
    
    
    /**
     * remove the 404 page from get_pages result array
     */
    function remove_404page_from_array( $pages, $r ) {
      
      $pageid = $this->get_page_id();
      
      if ( $pageid > 0 ) {
        
        for ( $i = 0; $i < sizeof( $pages ); $i++ ) {			
        
          if ( $pages[$i]->ID == $pageid ) {
            
            unset( $pages[$i] );
            break;
            
          }
          
        }
      
      }
      
      return array_values( $pages );
      
    }
    
    
    /**
     * check if the requested url is found in trash
     * @since 3.2
     * based on WP core function url_to_postid()
     */
    function is_url_in_trash( $url ) {
	
      global $wp_rewrite;
      global $wp;
	
      // First, check to see if there is a 'p=N' or 'page_id=N' to match against
      if ( preg_match( '#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values ) ) {
        
        $id = absint( $values[2] );
        
        if ( $id ) {
          
          if ( 'trash' == get_post_status( $id ) ) {
            
            return true;
          
          } else {
            
            return false;
          
          }
          
        }
        
      }
      
      // Check to see if we are using rewrite rules
      $rewrite = $wp_rewrite->wp_rewrite_rules();
      
      // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
      if ( empty( $rewrite ) ) {
        
        return false;
        
      }
          
      // Get rid of the #anchor
      $url_split = explode('#', $url);
      $url = $url_split[0];
      
      // Get rid of URL ?query=string
      $url_split = explode('?', $url);
      $url = $url_split[0];
      
      // Add 'www.' if it is absent and should be there
      if ( false !== strpos( home_url(), '://www.' ) && false === strpos( $url, '://www.' ) ) {
      
        $url = str_replace('://', '://www.', $url);
      
      }
      
      // Strip 'www.' if it is present and shouldn't be
      if ( false === strpos( home_url(), '://www.' ) ) {
		
        $url = str_replace('://www.', '://', $url);
        
      }
	
      // Strip 'index.php/' if we're not using path info permalinks
      if ( !$wp_rewrite->using_index_permalinks() ) {
		
        $url = str_replace( $wp_rewrite->index . '/', '', $url );
        
      }
	
  
      if ( false !== strpos( trailingslashit( $url ), home_url( '/' ) ) ) {
		
        // Chop off http://domain.com/[path]
        $url = str_replace(home_url(), '', $url);
      
      } else {
		
        // Chop off /path/to/blog
        $home_path = parse_url( home_url( '/' ) );
        $home_path = isset( $home_path['path'] ) ? $home_path['path'] : '' ;
        $url = preg_replace( sprintf( '#^%s#', preg_quote( $home_path ) ), '', trailingslashit( $url ) );
      
      }
	
      // Trim leading and lagging slashes
      $url = trim($url, '/');
	
      $request = $url;
      $post_type_query_vars = array();
      
      foreach ( get_post_types( array() , 'objects' ) as $post_type => $t ) {
        
        if ( ! empty( $t->query_var ) ) {
          
          $post_type_query_vars[ $t->query_var ] = $post_type;
          
        }
      }
	
      // Look for matches.
      $request_match = $request;
      foreach ( (array)$rewrite as $match => $query) {
		
        // If the requesting file is the anchor of the match, prepend it
        // to the path info.
        if ( !empty( $url ) && ( $url != $request ) && ( strpos( $match, $url ) === 0 ) ) {
			
          $request_match = $url . '/' . $request;
          
        }
		
        if ( preg_match( "#^$match#", $request_match, $matches ) ) {
			
          if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
				
            // This is a verbose page match, let's check to be sure about it.
            if ( ! get_page_by_path( $matches[ $varmatch[1] ] ) ) {
					
              continue;
              
            }
          }

          // Got a match.
			
          // Trim the query of everything up to the '?'.
          $query = preg_replace( "!^.+\?!", '', $query );
			
          // Substitute the substring matches into the query.
          $query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );
			
          // Filter out non-public query vars
          parse_str( $query, $query_vars );
          $query = array();
          
          foreach ( (array) $query_vars as $key => $value ) {
          
            if ( in_array( $key, $wp->public_query_vars ) ) {
					
              $query[$key] = $value;
					
              if ( isset( $post_type_query_vars[$key] ) ) {
						
                $query['post_type'] = $post_type_query_vars[$key];
                $query['name'] = $value;
					
              }
              
            }
            
          }
          
          // Magic
          if ( isset( $query['pagename'] ) ) {
           
            $query['pagename'] .= '__trashed' ;
            
          }
          
          if ( isset( $query['name'] ) ) {

            $query['name'] .= '__trashed' ;
            
          }
          
          $query['post_status'] = array( 'trash' );
          
          // Resolve conflicts between posts with numeric slugs and date archive queries.
          $query = wp_resolve_numeric_slug_conflicts( $query );
          
          // Do the query
          $query = new WP_Query( $query );
          
          if ( $query->found_posts == 1 ) {
				
            return true;
            
          } else {
				
            return false;
            
          }
        
        }
      
      }
	
      return false;

    }
    
    
    /**
     * get the id of the 404 page in the current language if WPML or Polylang is active
     * public since v 11.1.0 to use it in pp_404_get_page_id()
     */
    public function get_page_id() {
      
      $pageid = $this->settings()->get( 'page_id' );
      
      if ( $pageid > 0 ) {
      
        if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            
          // WPML is active
          $pageid = apply_filters( 'wpml_object_id', $pageid, 'page', true ); 
        
        } elseif ( function_exists( 'pll_get_post' ) ) {
        
          // was defined( 'POLYLANG_VERSION' ) before version 11.2.3
      
          // Polylang is active
          $translatedpageid = pll_get_post( $pageid );
          if ( !empty( $translatedpageid ) && 'publish' == get_post_status( $translatedpageid ) ) {
            $pageid = $translatedpageid;
          }
        
        }
        
      }
      
      return $pageid;
      
    }
    
    
    /**
     * get 404 pages in all available languages
     * if WPML is active this function returns an array of all page ids in all available languages
     * otherwise it returns the page id as array
     * introduced in v 2.4
     * public since v9 to access it from other classes
     */
    public function get_all_page_ids() {
      
      if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            
        // WPML is active
        // get an array for all translations
        $pageid = $this->settings()->get( 'page_id' );
        $pages = array( $pageid );
        
        if ( $pageid > 0 ) {
          
          $languages = apply_filters( 'wpml_active_languages', NULL );
          
          if ( !empty( $languages ) ) {
            
            foreach( $languages as $l ) {
              
              $p = apply_filters( 'wpml_object_id', $pageid, 'page', false, $l['language_code'] ); 
              
              if ( $p ) {
                
                $pages[] = $p;
                
              }
              
            }
            
          }
          
        }
        
        $pageids = array_unique( $pages, SORT_NUMERIC );
          
      } else {
        
        $pageids = array( $this->settings()->get( 'page_id' ) );
        
      }
      
      return $pageids;
      
    }
    
    
    /**
     * fire 404page_after_404 hook to make plugin expandable
     */
    function do_404page_action() {
      
      do_action( '404page_after_404' );
      
    }
    
    
    /**
     * uninstall plugin
     */
    function uninstall() {
      
      if( is_multisite() ) {
        
        $this->uninstall_network();
        
      } else {
        
        $this->uninstall_single();
        
      }
      
    }
    
    
    /**
     * uninstall network wide
     */
    function uninstall_network() {
      
      global $wpdb;
      $activeblog = $wpdb->blogid;
      $blogids = $wpdb->get_col( esc_sql( 'SELECT blog_id FROM ' . $wpdb->blogs ) );
      
      foreach ( $blogids as $blogid ) {
        
        switch_to_blog( $blogid );
        $this->uninstall_single();
        
      }
      
      switch_to_blog( $activeblog );
      
    }
    
    
    /**
     * uninstall for a single blog
     */
    function uninstall_single() {
      
      $this->data_remove();
      $this->settings()->remove();
      
    }
    
    
    /**
     * functions for theme usage
     */
    
    // check if there's a custom 404 page set
    function pp_404_is_active() {
      
      return ( $this->settings()->get( 'page_id' ) > 0 );
      
    }
    
    // activate the native theme support
    function pp_404_set_native_support() {
      
      $this->set_native();
      
    }
    
    // get the title - native theme support
    function pp_404_get_the_title() {
      
      $title = '';
      
      if ( $this->settings()->get( 'page_id' ) > 0 && $this->is_native() ) {
        
        $title = get_the_title( $this->get_page_id() );
        
      }
      
      return $title;
      
    }
    
    // print title - native theme support
    function pp_404_the_title() {
      
      echo esc_html( $this->pp_404_get_the_title() );
      
    }
    
    // get the content - native theme support
    function pp_404_get_the_content() {
      
      $content = '';
      
      if ( $this->settings()->get( 'page_id' ) > 0 && $this->is_native() ) {
        
        $content = apply_filters( 'the_content', get_post_field( 'post_content', $this->get_page_id() ) );
        
      }
      
      return $content;
      
    }
    
    // print content - native theme support
    function pp_404_the_content() {
      
      echo esc_html( $this->pp_404_get_the_content() );
      
    }
    
    
    /**
     * set native mode
     *
     * @since  11.0.0 - was part of previous settings class
     * @access public
     */
    public function set_native() {
     
      $this->native = true;
     
    }
    
    
    /**
     * is native mode ative
     *
     * @since  11.0.0 - was part of previous settings class
     * @access public
     */
    public function is_native() {
     
      return ( true === $this->native );
     
    }
    
    
    /**
	 * get settings class
     *
     * @since  11.3.0
     * @access public
     * @return object
     */
    public function admin() {
      
      return $this->admin;
    }
    
	
	
	/**
	 * save URL that caused the 404 error
     *
     * @since  11.4.0
     * @access private
     */
    private function set_404_url() {
      
	  $url = rawurldecode( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	  
      $this->error_url = parse_url( $url );
	  $this->error_url['full'] = $url;
	  
	  return true;
	  
    }
	
	
	/**
	 * get URL that caused the 404 error
     *
     * @since  11.4.0
     * @access public
	 * @param  string $what - array index to get 
	 * @return string
     */
    public function get_404_url( $what = 'full' ) {
		
		if ( $this->error_url && is_array( $this->error_url ) && isset( $this->error_url[$what] ) && ! empty( $this->error_url[$what] ) ) {
			
			return $this->error_url[$what];
		
		}
		
	}
    
  }
  
}

?>