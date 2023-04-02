<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Compat_contact_form_7' ) ) {
	class Icegram_Compat_contact_form_7 extends Icegram_Compat_Base {

		function __construct() {
			global $icegram; 
			parent::__construct();

			if($icegram->cache_compatibility === 'yes') {
				add_filter('wpcf7_form_action_url', array( &$this, 'change_form_action_url') );
			}
		}

		function change_form_action_url($url) {
		    return Icegram::get_current_page_url();
		}


		function render_js( $type ) {
			global $icegram;
			if( 'contact-form-7' === $type || 'yes' === $icegram->cache_compatibility ){
			?>

			<style type="text/css">
				.ig_hide .wpcf7-response-output,
				.ig_form_container .screen-reader-response{
					display: none !important;
				}
				.ig_show .ig_form_container.layout_bottom .wpcf7-response-output,
				.ig_show .ig_form_container.layout_right .wpcf7-response-output,
				.ig_show .ig_form_container.layout_left .wpcf7-response-output{
					background-color: #FFF;
					color: #444;
					position: absolute;
				}
				.ig_sidebar .ig_form_bottom.ig_show .ig_form_container.layout_bottom .wpcf7-response-output{
					bottom: 0;
				}
				.ig_overlay.ig_form_bottom.ig_show .ig_form_container.layout_bottom .wpcf7-response-output,
				.ig_action_bar.ig_bottom.ig_show .ig_form_container.layout_right .wpcf7-response-output,
				.ig_action_bar.ig_bottom.ig_show .ig_form_container.layout_left .wpcf7-response-output{
					bottom: 100%;
				}
				</style>
				<script type='text/javascript'>
jQuery(function() {
	//Recaptcha value not being added 
	var ig_cf7_recaptcha_token = '';
	document.addEventListener( 'wpcf7grecaptchaexecuted', event => {
		ig_cf7_recaptcha_token = event.detail.token;
	});
	jQuery( window ).on( 'init.icegram', function(event) {
		setInterval(()=>{
			ig_set_cf7_recaptcha_token( ig_cf7_recaptcha_token );
		},1000);
	});
	var ig_set_cf7_recaptcha_token = function(token) {
		const captcha_fields = jQuery('form input[name="_wpcf7_recaptcha_response"].ig_form_hidden_field');
		jQuery(captcha_fields).each(function() {
			jQuery(this).val(token);
		});
	}

  	jQuery( window ).on( "init.icegram", function(e, ig) {

	  	// Find and init all CF7 forms within Icegram messages/divs and init them
  		if(typeof ig !== 'undefined' && typeof ig.messages !== 'undefined' ){
		  	jQuery.each(ig.messages, function(i, msg){
		  		jQuery(msg.el).find('form input[name=_wpcf7]').each(function(){
			  		var form = jQuery(this).closest('form');
			  		if(form && !form.hasClass('ig_form_init_done')){
			  			if(form.closest('.ig_form_container').length > 0){
			  				
			  				form.wrap('<div class="wpcf7 js" dir="ltr"></div>');
			  				form.addClass('wpcf7-form init');
			  				
			  				form.find('input[type="submit"]').addClass('wpcf7-form-control has-spinner wpcf7-submit');

			  				//Hiding some extra field
							form.find('textarea[name="_wpcf7_ak_hp_textarea"]').parent().hide();
				  			
				  			if(form.parent().find('.screen-reader-response').length == 0){
				  				
				  				form.before('<div class="screen-reader-response"><p role="status" aria-live="polite" aria-atomic="true"></p> <ul></ul></div>')
				  			}
				  			if(form.find('wpcf7-response-output').length == 0){
				  				form.append('<div class="wpcf7-response-output wpcf7-display-none"></div>')
				  			}
					  		form.closest('.ig_form_container').attr('id', form.find('input[name=_wpcf7_unit_tag]').val()); //_wpcf7_unit_tag
			  			}
			  			if(typeof _wpcf7 !== 'undefined'){
							form.wpcf7InitForm();
			  			}else{
		
			  				form[0].addEventListener( 'submit', function(event){
			  					wpcf7.init(form[0]);
								wpcf7.submit( form[0], { submitter: event.submitter } );
								event.preventDefault();
							} );
				  	
			  			}
			  			form.addClass('ig_form_init_done');
			  		}
		  		});

		  	});
	  	}

  	}); // init.icegram

 
	// Dismiss response text/div when shown within icegram form container
	jQuery('body').on('click', '.ig_form_container .wpcf7-response-output', function(e) {
    		jQuery(e.target).slideUp();
	});
	// Handle CTA function(s) after successful submission of form
  	document.addEventListener( 'wpcf7mailsent', function( e ) {
  		if( typeof icegram !== 'undefined' ){
		  	var msg_id = ((jQuery(e.target).closest('[id^=icegram_message_]') || {}).attr('id') || '').split('_').pop() || 0 ;
		  	var ig_msg = icegram.get_message_by_id(msg_id) || undefined;
		  	if(ig_msg && ig_msg.data.cta === 'form_via_ajax' && ig_msg.data.cta_option_form_via_ajax == 'hide_on_success'){
			  	setTimeout(function(){
					ig_msg.hide();
				}, 2000);
			}
  		}
	});
});
</script>
			<?php
			}
		}
	}
}