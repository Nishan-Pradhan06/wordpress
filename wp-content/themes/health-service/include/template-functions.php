<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @subpackage health-service
 * @since 1.0 
 */

/*-----------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function health_service_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'health_service_pingback_header' );

/*-----------------------------------------------------------------------------------------------------------------------------------*/




if ( ! function_exists( 'health_service_fonts_url' ) ) :

	/**
	 * Register Google fonts for health-service.
	 *
	 * @return string Google fonts URL for the theme.
	 * @since 1.0.0
	 */

    function health_service_fonts_url() {

        $fonts_url = '';
        $font_families = array();

        /*
         * Translators: If there are characters in your language that are not supported
         * by Lora translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Open+Sans: on or off', 'health-service' ) ) {
            $font_families[] = 'Open+Sans:400,600,700,800';
        }

        /*
         * Translators: If there are characters in your language that are not supported
         * by Poppins, translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'health-service' ) ) {
            $font_families[] = 'Roboto:300,400,500,700,900';
        }   

        if( $font_families ) {
            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }

endif;

/*-----------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue scripts and styles for only admin
 *
 * @since 1.0.0
 */
function health_service_scripts() {

    global $health_service_theme_version;
    
	//Enque Styles
     wp_enqueue_style('health-service', health_service_fonts_url(), array(), null );
     wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '', 'all');
     wp_enqueue_style('header-css', get_template_directory_uri() . '/assets/css/header.css', array(), '', 'all');
     wp_enqueue_style('magnific-popup-css', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '', 'all');
     wp_enqueue_style('owl-carousel-css', get_template_directory_uri() . '/assets/css/owl.carousel.css', array(), '', 'all');
     wp_enqueue_style('owl-theme-default', get_template_directory_uri() . '/assets/css/owl.theme.default.css', array(), '', 'all');
     wp_enqueue_style('font-wesome-css', get_template_directory_uri() . '/assets/css/font-awesome.css', array(), '', 'all');
	 wp_enqueue_style('health-service-sites-style', get_stylesheet_uri(), array(), esc_attr( $health_service_theme_version ) );
	 wp_enqueue_style('health-service-template-new', get_template_directory_uri() . '/assets/css/template-change.css', array(), '', 'all');
	 wp_enqueue_style('responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), '', 'all');
     wp_enqueue_style('health-service-skin', get_template_directory_uri() . '/assets/css/skin-2.css', array(), '', 'all');
	 
 	//Enque Scripts
	 wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.js', array('jquery'), true);
	 wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.js', array('jquery'), true);
     wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/assets/js/owl.carousel.js', array(), '', true);
     wp_enqueue_script('magnific-popup-js', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.js', array(), '', true);
     wp_enqueue_script('health-service-custom', get_template_directory_uri() . '/assets/js/custom.js', array(), '', true);
     wp_enqueue_script( 'health-service-sites-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '', true );
      
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'health_service_scripts' );


/*--------------------------------------------------------------------------------------------------------------------------------*/

if( ! function_exists( 'health_service_select_categories_list' ) ) :

    /**
     * function to return category lists
     *
     * @return $health_service_categories_list in array
     */
    
    function health_service_select_categories_list() {

        $health_service_get_categories = get_categories( array( 'hide_empty' => 0 ) );
        $health_service_categories_list[''] = __( 'Select Category', 'health-service' );

        foreach ( $health_service_get_categories as $category ) {
            $health_service_categories_list[esc_attr( $category->slug )] = esc_html( $category->cat_name );
        }
        
        return $health_service_categories_list;
    }

endif;

/*--------------------------------------------------------------------------------------------------------------------------------*/


if ( ! function_exists( 'health_service_single_post_navigation' ) ) :
/**
 * Displays an optional single post navigation
 *
 *
 * Create your own health_service_post_navigation() function to override in a child theme.
 *
 * @since Create Magazine 1.0
 */
function health_service_single_post_navigation() {

   

    the_post_navigation( array(
        'prev_text' => '<i class="fa fa-angle-left"></i>'.esc_html__( ' Previous Article','health-service' ),
        'next_text' => esc_html__( 'Next Article','health-service' ).' <i class="fa fa-angle-right"></i>'
    ) );
}
endif;



add_action( 'wp_enqueue_scripts', 'health_service_theme_color' );
if( ! function_exists( 'health_service_theme_color' ) ) :
  
    function health_service_theme_color() { 
        $health_service_theme_color = get_theme_mod( 'health_service_theme_color', '#00c1e4' );
        $output_css = '';

         $output_css .= ".team-slider-two.owl-theme .owl-nav [class*=owl-]:hover, .team-slider-two.owl-theme .owl-nav [class*=owl-]:focus,.post-meta li a:hover, .post-meta li a:focus,h5 a:hover, h6 a:hover, h5 a:focus, h6 a:focus, .widget_categories a:hover, .widget_archive a:hover, .widget_categories a:focus, .widget_archive a:focus,.widget_meta a:hover, .widget_meta a:focus,.foot-bottom a,.main-navigation a:hover,.widget_recent_entries a:hover, .widget_recent_entries a:focus,.widget_recent_comments .recentcomments a:hover, .widget_recent_comments .recentcomments a:focus,.widget_recent_entries .post-date,.blog-detail .post-meta li a, .logged-in-as a,.read-more:hover, .read-more:focus,.blog-detail .post-meta li i,.comment-meta a,.says,.sp-100 .pagination-blog .navigation .nav-links a,.post-tags a:hover, .post-tags a:focus , .btn-one:hover, .btn-one:focus,.widget_pages a:hover, .widget_pages a:focus,.hero-sec .caption h1,.main-menu ul ul.sub-menu a:hover,.navbar-expand-lg a:hover, .services-5 .icon-box h4 a:hover ,.widget_categories li:hover, .widget_archive li:hover, .widget_categories li a:focus, .widget_archive li a:focus, .testimonials-5 .testimonial-item i, .about .about-box i, .services-5 .icon-box i, #testimonials .testimonials-content .icon, .feature-area .feature-box .icon i,.services-5 .service-box i, .services-5 .service-box h4 a:hover, .blog-5 .blog-item:hover a.heading,#testimonials .testimonials-content .testimonial-profile span.post ,.top-bar .content .icon i ,.feature-box:hover h5, .top-bar .content .details p.light:hover {color:".esc_attr( $health_service_theme_color )." !important;}"; 

        $output_css .= ".all-title .title-sep{ fill:".esc_attr( $health_service_theme_color )."; }";
		$output_css .= ".feature-area .feature-box .icon i{ background: ".esc_attr( $health_service_theme_color )."1a !important; }";

        $output_css .= ".team-two:hover, .team-two:focus, .service-box:hover .service-content, .carousel-caption a, .blog-5 .btn-wraper .read-more-btn:hover{ border-color:".esc_attr( $health_service_theme_color )." !important; }";
        $output_css .= ".btn-two:before, .btn-two:after{ border-bottom:".esc_attr( $health_service_theme_color ). " 25px solid ; !important;
border-top:".esc_attr( $health_service_theme_color ). " 25px solid ; !important
          }";
        $output_css .= ".search-form input[type='submit'],.widget_tag_cloud .tagcloud a:hover, .widget_tag_cloud .tagcloud a:focus,.pagination .nav-links .page-numbers.current, .pagination .nav-links .page-numbers:hover{ border-color:".esc_attr( $health_service_theme_color )."; }";
        $output_css .= ".title-line:before,.title-line:after { background: linear-gradient(to left,transparent,".esc_attr( $health_service_theme_color ).") !important; }";


        $output_css .= ".btn-dark,.service-box2:after,.feature-box::after,.class-box:hover h5:before, .class-box:hover h5:after, .class-box:focus h5:before, .class-box:focus h5:after,section.cta,.foot-title h4::after,.main-navigation .nav-menu>.menu-item-has-children > .sub-menu li a:before,.search-form input[type='submit'],.title-sep2::after,::-webkit-scrollbar-thumb,::-webkit-scrollbar-thumb:hover,.comment-respond .comment-reply-title::after,.comment-respond .form-submit input,.widget_tag_cloud .tagcloud a:hover, .widget_tag_cloud .tagcloud a:focus,.pagination .nav-links .page-numbers.current, .pagination .nav-links .page-numbers:hover,.reply:focus,.blog .blog-item:hover .date, .blog .blog-item:focus .date,.owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span, .owl-theme .owl-dots .owl-dot:focus span, .service-box3col:after,.service-box4col:after, .service-box:hover .service-content, .project:hover .proj-content, .project:focus .proj-content,.header-three,.bg-theme,.text-slider-animate p::after, .cta-2, .carousel-indicators .active, .our-team .social_media_team,.service-box3 h5::after,.title-line > i,.main-menu ul ul.sub-menu a::before,.cta-4, .blog-5 .btn-wraper .read-more-btn:hover , .cta-6, .home6-hero-sec, .btn:hover, .services-5 .icon-box:hover i, .cta-7 .btn, .home7-hero-sec .slider-btn .slider-btn1, .home7-hero-sec .slider-btn .slider-btn2:hover, .top-bar .social-icon i:hover{ background-color:".esc_attr( $health_service_theme_color )." !important;}";

               $output_css .= "blockquote{ border-left:".esc_attr( $health_service_theme_color )." 5px solid; }";
               $output_css .= ".services-5 .icon-box i{ border:3px solid".esc_attr( $health_service_theme_color )."  !important; }";
               $output_css .= ".portfolio-5 .portfolio-wrap:hover::before{ background:".esc_attr( $health_service_theme_color )."  !important; }";
               $output_css .= ".sec-title:after, .sec-title:before,.carousel-control-prev-icon:before, .carousel-control-next-icon:before,.separator ul li, .testimonials-5 .testimonial-item, .home-area .owl-theme .owl-dots .owl-dot.active span, .home-area .owl-theme .owl-dots .owl-dot:hover span, .section-title-5 .separator ul li {background:".esc_attr( $health_service_theme_color )." !important; }";


       $health_service_output_css = health_service_css_strip_whitespace( $output_css );
        wp_add_inline_style( 'health-service-sites-style', $health_service_output_css );
    }
endif;




if( ! function_exists( 'health_service_css_strip_whitespace' ) ) :
    
    /**
     * Get minified css and removed space
     *
     * @since 1.0.0
     */

    function health_service_css_strip_whitespace( $css ){
        $replace = array(
            "#/\*.*?\*/#s" => "",  // Strip C style comments.
            "#\s\s+#"      => " ", // Strip excess whitespace.
        );
        $search = array_keys( $replace );
        $css = preg_replace( $search, $replace, $css );

        $replace = array(
            ": "  => ":",
            "; "  => ";",
            " {"  => "{",
            " }"  => "}",
            ", "  => ",",
            "{ "  => "{",
            ";}"  => "}", // Strip optional semicolons.
            ",\n" => ",", // Don't wrap multiple selectors.
            "\n}" => "}", // Don't wrap closing braces.
            "} "  => "}\n", // Put each rule on it's own line.
        );
        $search = array_keys( $replace );
        $css = str_replace( $search, $replace, $css );

        return trim( $css );
    }

endif;


if( ! function_exists( 'health_service_select_page_list' ) ) :

    /**
     * function to return page lists
     *
     * @return $health_service_page_list in array
     */
    
    function health_service_select_page_list() {

        $health_service_get_pages = get_pages();
        $health_service_page_list[''] = __( 'Select Page', 'health-service' );

        foreach ( $health_service_get_pages as $page ) {
            $health_service_page_list[esc_attr( $page->post_name )] = esc_html( $page->post_title );
        }
        
        return $health_service_page_list;
    }

endif;