<?php

/**
 * The 404page Plugin Functions
 *
 * @since 11.0.0
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

 
// this function can be used by a theme to check if there's an active custom 404 page
function pp_404_is_active() {
  
  return pp_404page()->pp_404_is_active();
  
}


// this function can be used by a theme to activate native support
function pp_404_set_native_support() {
  
  pp_404page()->pp_404_set_native_support();
  
}


// this function can be used by a theme to get the title of the custom 404 page in native support
function pp_404_get_the_title() {
  
  return pp_404page()->pp_404_get_the_title();
  
}


// this function can be used by a theme to print out the title of the custom 404 page in native support
function pp_404_the_title() {
  
  pp_404page()->pp_404_the_title();
  
}


// this function can be used by a theme to get the content of the custom 404 page in native support
function pp_404_get_the_content() {
  
  return pp_404page()->pp_404_get_the_content();
  
}


// this function can be used by a theme to print out the content of the custom 404 page in native support
function pp_404_the_content() {
  
  return pp_404page()->pp_404_the_content();
  
}


// this function returns the page id of the selected 404 page - in a multilingual environment this returs the id of the page in the current language
function pp_404_get_page_id() {
  
  return pp_404page()->get_page_id();
  
}


// this function returns an array of page ids in all languages
function pp_404_get_all_page_ids() {
  
  return pp_404page()->get_all_page_ids();
  
}

/**
 * return URL that caused the 404 error for frontend
 * used by shortcode and block
 *
 * @since  11.4.0
 * @access public
 * @param  string $type
 * @return string
 */ 	 
function pp_404_get_the_url( $type ) {

	$url = '';
	
	if ( is_array( $type ) ) {
		
		$type = $type[0];
		
	}
	
	if ( 'page' == $type ) {
	  
		$url = trim( pp_404page()->get_404_url( 'path' ), '/' );
	  
	} elseif ( 'domainpath' == $type ) {
	
		$url = pp_404page()->get_404_url( 'host' ) . pp_404page()->get_404_url( 'path' );
	  
	} else {
		
		$url = pp_404page()->get_404_url( 'full' );
	  
	}
  
  
	return $url;
	
}
	
?>
