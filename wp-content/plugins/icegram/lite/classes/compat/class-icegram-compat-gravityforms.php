<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Compat_gravityforms' ) ) {
	class Icegram_Compat_gravityforms extends Icegram_Compat_Base {

		function __construct() {
			global $icegram; 
			parent::__construct();
			
			if($icegram->cache_compatibility === 'yes') {
				add_filter( 'gform_form_tag', 'change_form_action_url', 10, 2 );
				function change_form_action_url( $form_tag, $form ) {
				    $form_tag = preg_replace( "|action='(.*?)'|", "action='".Icegram::get_current_page_url()."'", $form_tag );
				    return $form_tag;
				}
			    
			}
		}

		function render_js( $type ) {
			global $icegram;
			if( 'gravityform' === $type || 'yes' === $icegram->cache_compatibility ){
			?>

<style type="text/css"> 
	body.ig_laptop div#ui-datepicker-div[style],
 	body.ig_tablet div#ui-datepicker-div[style],
 	body.ig_mobile div#ui-datepicker-div[style]{
 		z-index: 9999999!important; 
 	} 
</style>

<script type="text/javascript">
jQuery(function() {
  	jQuery( window ).on( "init.icegram", function(e, ig) {
	  	// Find and init all datepicker inside gravityForms
	  	jQuery('body').on('focus', 'form[id^="gform_"] .datepicker', function(){
	  		jQuery(this).datepicker();
	  	});
  	}); // init.icegram
});
</script>

			<?php
			}
		}		
	}
}