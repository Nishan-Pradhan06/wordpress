<?php
/**
 * health-service functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @subpackage health-service
 * @since health-service 1.0
 */

/**
 * health-service only works in WordPress 4.7 or later.
 */

if ( ! function_exists( 'health_service_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function health_service_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on health-service, use a find and replace
		 * to change 'health-service' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'health-service');
        
		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
		
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in single locations.
		add_theme_support( 'nav-menus' );
		register_nav_menu('primary', esc_html__( 'Primary Menu', 'health-service' ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add the custom background prperty
		add_theme_support( 'custom-background', apply_filters( 'health_service_custom_background_args', array(
			'default-color' => '#000000',
			'default-image' => '',
		) ) );

		// Add supportive refresh widgets 
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		// Add default posts and comments RSS feed links 
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		
		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'health_service_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function health_service_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'health_service_content_width', 640 );
}
add_action( 'after_setup_theme', 'health_service_content_width', 0 );

/**
 * Set the theme version, based on theme stylesheet.
 *
 * @global string $health_service_theme_version
 */
function health_service_theme_version_info() {
	$health_service_theme_info = wp_get_theme();
	$GLOBALS['health_service_theme_version'] = $health_service_theme_info->get( 'Version' );
}
add_action( 'after_setup_theme', 'health_service_theme_version_info', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function health_service_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'health-service' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'health-service' ),
		'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s clearfix">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="title-sep2 mb-30">',
		'after_title'   => '</h4>',
	) );


	     register_sidebar(array(
		'name' => esc_html__( 'Footer Widget Area', "health-service"),
		'id' => 'footer-widget-area',
		'description' => esc_html__( 'The footer widget area', "health-service"),
		'before_widget' => '<div class="%2$s footer-widget col-md-3 col-sm-6 col-xs-12">',
		'after_widget' => '</div>',
		'before_title' => '<div class="foot-title"><h4>',
		'after_title' => '</h4></div>',
	));	
}
add_action( 'widgets_init', 'health_service_widgets_init' );

add_action( 'admin_init', 'health_service_detect_button' );
	function health_service_detect_button() {
	wp_enqueue_style( 'health-service-button', get_template_directory_uri() . '/assets/css/notice-button.css' );
}
 

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/include/template-tags.php';

/**
 * File to manage the custom body classes
 */
require get_template_directory() . '/include/template-css-class.php';
/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/include/template-functions.php';

/**
 * Add feature in Customizer  
 */
require get_template_directory() . '/include/customizer/cv-customizer.php';


/**
 * Custom Hooks defined 
 */
require get_template_directory() . '/include/custom-hooks/cv-custom-hooks.php';
require get_template_directory() . '/include/custom-hooks/footer-hooks.php';
require get_template_directory() . '/include/custom-hooks/header-hooks.php';
 
 
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package health-service
 */

/**
 * Header fearures expanded 
 */
function health_service_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'health_service_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/assets/images/header.jpg',
		'default-text-color'     => '000',
		'width'                  => 1920,
		'height'                 => 850,
		'flex-height'            => true,
		'wp-head-callback'       => 'health_service_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'health_service_custom_header_setup' );

if ( ! function_exists( 'health_service_header_style' ) ) :

	function health_service_header_style() {
		$header_text_color = get_header_textcolor();

		?>
		<style type="text/css">
			<?php
				//Check if user has defined any header image.
				if ( get_header_image() ) :
			?>
			.page-banner
			  {
				background-image:url('<?php header_image(); ?>');
			  }
		
			.site-title,.site-description
			 {
			color: #<?php echo esc_attr($header_text_color); ?>;
			
			  }

			<?php endif; ?>	
		</style>
		<?php
	}
endif;

function health_service_change_excerpt( $text )
{
    $pos = strrpos( $text, '[');
    if ($pos === false)
    {
        return $text;
    }

    return rtrim (substr($text, 0, $pos) );
}
add_filter('get_the_excerpt', 'health_service_change_excerpt');


/**
 * Customizer additional settings.
 */
require_once( trailingslashit( get_template_directory() ) . '/include/custom-addition/class-customize.php' );

/**
 * plugin Recommendations.
 */
 
require_once  get_template_directory()  . '/include/tgm/class-tgm-plugin-activation.php';
require get_template_directory(). '/include/tgm/hook-tgm.php';

function health_service_comparepage_css($hook) {
  if ( 'appearance_page_health-service-info' != $hook ) {
    return;
  }
  wp_enqueue_style( 'health-service-custom-style', get_template_directory_uri() . '/assets/css/compare.css' );
}
add_action( 'admin_enqueue_scripts', 'health_service_comparepage_css' );

/**
 * Compare page content
 */

add_action('admin_menu', 'health_service_themepage');
function health_service_themepage(){
  $theme_info = add_theme_page( __('Theme Details','health-service'), __('Theme Details','health-service'), 'manage_options', 'health-service-info.php', 'health_service_info_page' );
}

function health_service_info_page() {
  $user = wp_get_current_user();
  ?>
  <div class="wrap about-wrap one-pageily-add-css">
    <div>
      <h1>
        <?php echo __('Welcome to Health Service!','health-service'); ?>
      </h1>

      <div class="feature-section three-col">
        <div class="col">
          <div class="widgets-holder-wrap">
            <h3><?php echo __("Recommended Plugins", "health-service" ); ?></h3>
            <p><?php echo __("Please install recommended plugins for better use of theme. It will help you to make website more useful", "health-service" ); ?></p>
            <p><a target="blank" href="<?php echo esc_url(admin_url('/themes.php?page=tgmpa-install-plugins&plugin_status=activate'), 'health-service'); ?>" class="button button-primary">
              <?php echo __("Install Plugins", "health-service" ); ?>
            </a></p>
          </div>
        </div>
        <div class="col">
          <div class="widgets-holder-wrap">
            <h3><?php echo __("Review Theme", "health-service" ); ?></h3>
            <p><?php echo __("Nothing motivates us more than feedback, are you are enjoying Theme? We would love to hear what you think!.", "health-service" ); ?></p>
            <p><a target="blank" href="<?php echo esc_url('https://wordpress.org/support/theme/health-service/reviews/', 'health-service'); ?>" class="button button-primary">
              <?php echo __("Submit A Review", "health-service" ); ?>
            </a></p>
          </div>
        </div>
         <div class="col">
          <div class="widgets-holder-wrap">
            <h3><?php echo __("Contact Support", "health-service" ); ?></h3>
            <p><?php echo __("Getting started with a new theme can be difficult, if you have issues with Theme then throw us an email.", "health-service" ); ?></p>
            <p><a target="blank" href="<?php echo esc_url('https://testerwp.com/contact/', 'health-service'); ?>" class="button button-primary">
              <?php echo __("Contact Support", "health-service" ); ?>
            </a></p>
          </div>
        </div>
      </div>
	  
	  <h2><?php echo __("Free Vs Premium","health-service" ); ?></h2>
    <div class="one-pageily-button-container">
      <a target="blank" href="<?php echo esc_url('https://testerwp.com/product/logitech/', 'health-service'); ?>" class="button button-primary">
        <?php echo __("Read Full Description", "health-service" ); ?>
      </a>
      <a target="blank" href="<?php echo esc_url('https://testerwp.com/demo/logitech/', 'health-service'); ?>" class="button button-primary">
        <?php echo __("View Theme Demo", "health-service" ); ?>
      </a>
    </div>


    <table class="wp-list-table widefat">
      <thead>
        <tr>
          <th><strong><?php echo __("Theme Feature", "health-service" ); ?></strong></th>
          <th><strong><?php echo __("Basic Version", "health-service" ); ?></strong></th>
          <th><strong><?php echo __("Premium Version", "health-service" ); ?></strong></th>
        </tr>
      </thead>

      <tbody>
		  <tr>
          <td><?php echo __("Import Demo Content", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          </tr>	
		  <tr>
          <td><?php echo __("Responsive Design", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
			</tr>
			<tr>
          <td><?php echo __("Unlimited Color Option", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
			</tr>
			<tr>
          <td><?php echo __("Header Customization", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          </tr>
		  <tr>
          <td><?php echo __("Footer Customization", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          </tr>
		  <tr>
          <td><?php echo __("Unlimited post/Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          </tr>
			<tr>
          <td><?php echo __("Mulitple Header Layout", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Home Page", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Page Builder", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Coming Soon Page", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Blog Layout", "health-service" ); ?></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>


        <tr>
          <td><?php echo __("Premium Support", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Portfolio Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Google Fonts", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Team Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("404 Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Service Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Premium Widgets", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Footer", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Shortcodes", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Sidebar", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Multiple Page Layout", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("SEO Friendly", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Slider", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Footer Featured Cusomization", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Contact Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Customize Footer Colors", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        <tr>
          <td><?php echo __("Mega Menu", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr> 
        <tr>
          <td><?php echo __("Pricing Page", "health-service" ); ?></td>
          <td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "health-service" ); ?>" /></span></td>
          <td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "health-service" ); ?>" /></span></td>
        </tr>
        

      </tbody>
    </table>
    <div class="one-pageily-button-container">
      <a target="blank" href="<?php echo esc_url('https://testerwp.com/product/logitech/', 'health-service'); ?>" class="button button-primary">
        <?php echo __("GO PREMIUM", "health-service" ); ?>
      </a>
    </div>
	  
    </div>
    <hr>
 
  </div>
  <?php
}
//		
if ( is_admin() ) {
	require get_template_directory() . '/include/theme-notice.php';
}