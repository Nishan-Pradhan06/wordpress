(function( $ ) {
	"use strict";

	jQuery(document).ready(function($){

		$(document).on("click", ".updates-form button", function(){
			var updateStatus = 0;
			if($(this).hasClass("yes")) {
				updateStatus = 1;
			}
			$(".updates-form button").attr("disabled", true);
			$.ajax({
				url: ajaxurl,
				data: {
					action: "sticky_menu_update_status",
					status: updateStatus,
					nonce: $("#myStickymenu_update_nonce").val(),
					email: $("#myStickymenu_update_email").val()
				},
				type: 'post',
				cache: false,
				success: function(){
					window.location.reload();
				}
			})
		});
		
		$(document).on("click", ".skip-dolatter", function(){
			var updateStatus = 0;
			$(".updates-form button").attr("disabled", true);
			$.ajax({
				url: ajaxurl,
				data: {
					action: "sticky_menu_update_status",
					status: updateStatus,
					nonce: $("#myStickymenu_update_nonce").val(),
					email: $("#myStickymenu_update_email").val()
				},
				type: 'post',
				cache: false,
				success: function(){
					window.location.reload();
				}
			})
		});

		var handle = $( "#custom-handle" );
		$( "#slider" ).slider({
		  create: function() {
			handle.text( $( this ).slider( "value" ) );
			handle.text( $('#myfixed_opacity').val() );
			handle.css('left', $('#myfixed_opacity').val() + '%')
		  },
		  slide: function( event, ui ) {
			$('#myfixed_opacity').val(ui.value);
			handle.text( ui.value );
		  }
		});
		jQuery(
		  '<div class="pt_number"><div class="pt_numberbutton pt_numberup">+</div><div class="pt_numberbutton pt_numberdown">-</div></div>'
		).insertAfter("input.mysticky-number1");

		jQuery(".mystickynumber1").each(function() {

			var spinner = jQuery(this),
			input = spinner.find('input[type="number"]'),
			btnUp = spinner.find(".pt_numberup"),
			btnDown = spinner.find(".pt_numberdown"),
			min = input.attr("min"),
			max = input.attr("max"),
			valOfAmout = input.val(),
			newVal = 0;

			btnUp.on("click", function() {

				var oldValue = parseFloat(input.val());

				if (oldValue >= max) {
				  var newVal = oldValue;
				} else {
				  var newVal = oldValue + 1;
				}
				spinner.find("input").val(newVal);
				spinner.find("input").trigger("change");
			});
			btnDown.on("click", function() {
				var oldValue = parseFloat(input.val());
				if (oldValue <= min) {
				var newVal = oldValue;
				} else {
				var newVal = oldValue - 1;
				}
				spinner.find("input").val(newVal);
				spinner.find("input").trigger("change");
			});
		});


		$(".confirm").on( 'click', function() {
			return window.confirm("Reset to default settings?");
		});

		var flag = 0;
		$( "#mystickymenu-select option" ).each(function( i ) {

			if ($('select#mystickymenu-select option:selected').val() !== '' ) {
				flag = 1;
			}
			if( $('select#mystickymenu-select option:selected').val() == $(this).val() ){
				$('#mysticky_class_selector').show();
			}else {
				$('#mysticky_class_selector').hide();
			}
		});
		if ( flag === 0 ) {
			$('#mysticky_class_selector').show();
			$("select#mystickymenu-select option[value=custom]").attr('selected', 'selected');
		}

		$("#mystickymenu-select").on( 'change', function() {
			if ($(this).val() == 'custom' ) {
				$('#mysticky_class_selector').show();
			}else {
				$('#mysticky_class_selector').hide();
			}

		});
		/*02-08-2019 welcom bar js*/

		$( '.mysticky-welcomebar-action' ).on( 'change', function(){
			var mysticky_welcomebar_action = $( this ).val();
			if ( mysticky_welcomebar_action == 'redirect_to_url' ) {
				$( '.mysticky-welcomebar-redirect' ).show();
				$( '.mysticky-welcomebar-redirect-container' ).show();
			} else {
				$( '.mysticky-welcomebar-redirect' ).hide();
				$( '.mysticky-welcomebar-redirect-container' ).hide();
			}
			if ( mysticky_welcomebar_action == 'poptin_popup' ) {
				$( '.mysticky-welcomebar-poptin-popup' ).show();
			} else {
				$( '.mysticky-welcomebar-poptin-popup' ).hide();
			}
			if ( $('.mysticky-welcomebar-action option:selected').attr('data-href') !== '' && mysticky_welcomebar_action == 'thankyou_screen' ) {
				window.open( $( '.mysticky-welcomebar-action option:selected' ).attr('data-href') , '_blank');
			}
		} );
		
		var page_option_content = "";
	    page_option_content = $( '.mysticky-welcomebar-page-options-html' ).html();
	    $( '.mysticky-welcomebar-page-options-html' ).remove();

	    $( '#create-rule' ).on( 'click', function(){
	        var append_html = page_option_content.replace(/__count__/g, '1', page_option_content);
	        $( '.mysticky-welcomebar-page-options' ).append( append_html );
			$( '.mysticky-welcomebar-page-options' ).show();
			$( this ).parent().remove();
	    });
	    $( '.sticky-header-menu ul li a' ).on( 'click', function(){
	    	if ( $( "#sticky-header-welcome-bar" ).is( ":visible" ) ) {
	    		check_for_preview_pos();
	    	}
	    } );
		jQuery(window).on('scroll', function(){
			if ( $( "#sticky-header-welcome-bar" ).is( ":visible" ) ) {
	    		check_for_preview_pos();
	    	}
		});
		/*Mysticky page target*/
		var mysticky_total_page_option = 0;
		var mysticky_page_option_content = "";
		mysticky_total_page_option = $( '.mysticky-page-option' ).length;
	    mysticky_page_option_content = $( '.mysticky-page-options-html' ).html();
	    $( '.mysticky-page-options-html' ).remove();

	    $( '#mysticky_create-rule' ).on( 'click', function(){

	        var append_html = mysticky_page_option_content.replace(/__count__/g, mysticky_total_page_option, mysticky_page_option_content);
	        mysticky_total_page_option++;
	        $( '.mysticky-page-options' ).append( append_html );
	        $( '.mysticky-page-options .mysticky-page-option' ).removeClass( 'last' );
	        $( '.mysticky-page-options .mysticky-page-option:last' ).addClass( 'last' );

			if( $( '.mysticky-page-option .myStickymenu-upgrade' ).length > 0 ) {
				$( this ).remove();
			}
	    });
	    $( document ).on( 'click', '.mysticky-remove-rule', function() {
	       $( this ).closest( '.mysticky-page-option' ).remove();
	        $( '.mysticky-page-options .mysticky-page-option' ).removeClass( 'last' );
	        $( '.mysticky-page-options .mysticky-page-option:last' ).addClass( 'last' );
	    });
		$( document ).on( 'change', '.mysticky-url-options', function() {
			var current_val = jQuery( this ).val();
			var mysticky_welcomebar_siteURL = jQuery( '#mysticky_welcomebar_site_url' ).val();
			var mysticky_welcomebar_newURL  = mysticky_welcomebar_siteURL;
			if( current_val == 'page_has_url' ) {
				mysticky_welcomebar_newURL = mysticky_welcomebar_siteURL;
			} else if( current_val == 'page_contains' ) {
				mysticky_welcomebar_newURL = mysticky_welcomebar_siteURL + '%s%';
			} else if( current_val == 'page_start_with' ) {
				mysticky_welcomebar_newURL = mysticky_welcomebar_siteURL + 's%';
			} else if( current_val == 'page_end_with' ) {
				mysticky_welcomebar_newURL = mysticky_welcomebar_siteURL + '%s';
			}
			$( this ).closest( '.url-content' ).find( '.mysticky-welcomebar-url' ).text( mysticky_welcomebar_newURL );
		});
		/* welcome bar live preview */
		/* Apply Wp Color Picker */
		var myOptions = {
			change: function(event, ui){
				var color_id = $(this).attr('id');
				var slug = $(this).data('slug');

				var color_code = ui.color.toString();
				if ( color_id === 'mysticky_welcomebar_bgcolor'){
					$('.mysticky-welcomebar-fixed').css('background-color', color_code );
				}
				if ( color_id === 'mysticky_welcomebar_bgtxtcolor'){
					$('.mysticky-welcomebar-fixed .mysticky-welcomebar-content p').css('color', color_code );
				}
				if ( color_id === 'mysticky_welcomebar_btncolor'){
					$('.mysticky-welcomebar-btn a').css('background-color', color_code );
				}
				if ( color_id === 'mysticky_welcomebar_btntxtcolor'){
					$('.mysticky-welcomebar-btn a').css('color', color_code );
				}
				if( color_id === 'mysticky_welcomebar_xcolor' ){
					$(".mysticky-welcomebar-close").css('color',color_code);
				}
			}
	    };
		$('.mysticky-welcomebar-setting-wrap .my-color-field').wpColorPicker(myOptions);

		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_x_desktop]"]' ).on( 'change', function(){
			if( $( this ).prop( "checked" ) == true ) {
				$( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-showx-desktop' );
			} else {
				$( '.mysticky-welcomebar-fixed' ).removeClass( 'mysticky-welcomebar-showx-desktop' );
			}
		} );
		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_x_mobile]"]' ).on( 'change', function(){
			if( $( this ).prop( "checked" ) == true ) {
				$( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-showx-mobile' );
			} else {
				$( '.mysticky-welcomebar-fixed' ).removeClass( 'mysticky-welcomebar-showx-mobile' );
			}
		} );

		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_desktop]"]' ).on( 'change', function(){
			if( $( this ).prop( "checked" ) == true ) {
				$( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-btn-desktop' );
			} else {
				$( '.mysticky-welcomebar-fixed' ).removeClass( 'mysticky-welcomebar-btn-desktop' );
			}

			if( $( this ).prop( "checked" ) == false && $( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_mobile]"]' ).prop( "checked" ) == false ) {
				$( ".mysticky_welcomebar_disable, .mysticky_welcomebar_btn_color button.wp-color-result" ).css({
					'pointer-events': 'none',
					'opacity': '0.5'
				});
			} else {
				$( ".mysticky_welcomebar_disable, .mysticky_welcomebar_btn_color button.wp-color-result" ).css({
					'pointer-events': '',
					'opacity': ''
				});
			}
		} );
		
		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_mobile]"]' ).on( 'change', function(){
			if( $( this ).prop( "checked" ) == true ) {
				$( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-btn-mobile' );
			} else {
				$( '.mysticky-welcomebar-fixed' ).removeClass( 'mysticky-welcomebar-btn-mobile' );
			}

			if( $( this ).prop( "checked" ) == false && $( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_desktop]"]' ).prop( "checked" ) == false ) {
				$( ".mysticky_welcomebar_disable, .mysticky_welcomebar_btn_color button.wp-color-result" ).css({
					'pointer-events': 'none',
					'opacity': '0.5'
				});
			} else {
				$( ".mysticky_welcomebar_disable, .mysticky_welcomebar_btn_color button.wp-color-result" ).css({
					'pointer-events': '',
					'opacity': ''
				});
			}
		} );
		if( $( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_desktop]"]' ).prop( "checked" ) == false && $( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_mobile]"]' ).prop( "checked" ) == false ) {
			$( ".mysticky_welcomebar_disable, .mysticky_welcomebar_btn_color button.wp-color-result" ).css({
				'pointer-events': 'none',
				'opacity': '0.5'
			});
		} else {
			$( ".mysticky_welcomebar_disable, .mysticky_welcomebar_btn_color button.wp-color-result" ).css({
				'pointer-events': '',
				'opacity': ''
			});
		}

		$( 'select[name="mysticky_option_welcomebar[mysticky_welcomebar_font]"]' ).on( 'change', function(){
			var myfixed_font_val = $( this ).val();
			if( myfixed_font_val == 'System Stack'){
				myfixed_font_val = '-apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
			}
			$( 'head' ).append( '<link href="https://fonts.googleapis.com/css?family='+ myfixed_font_val +':400,600,700" rel="stylesheet" type="text/css" class="sfba-google-font">' );
			$( '.mysticky-welcomebar-fixed' ).css( 'font-family', myfixed_font_val );
		} );

		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_fontsize]"]' ).on( 'keyup click', function(){
			var mysticky_welcomebar_fontsize_val = $( this ).val();
			$( '.mysticky-welcomebar-fixed p' ).css( 'font-size', mysticky_welcomebar_fontsize_val + 'px' );
			$( '.mysticky-welcomebar-btn a' ).css( 'font-size', mysticky_welcomebar_fontsize_val + 'px' );
		} );

		$( '#wp-mysticky_bar_text-wrap .wp-editor-tabs button' ).on( 'click', function(){
			if ( $("#wp-mysticky_bar_text-wrap").hasClass("tmce-active") ){

			}
		} );

		$( document ).on( 'click', '#qt_mysticky_bar_text_toolbar .ed_button', function(){
			$( 'textarea[name="mysticky_option_welcomebar[mysticky_welcomebar_bar_text]"]' ).trigger( 'change keyup click' );
		} );

		$( 'textarea[name="mysticky_option_welcomebar[mysticky_welcomebar_bar_text]"]' ).on( 'change keyup click', function(e){
			var mysticky_bar_text_val = $( this ).val().replace(/(?:\r\n|\r|\n)/g, '<br />');
			$( '.mysticky-welcomebar-content' ).html( "<p>" + mysticky_bar_text_val + "</p>");
			$('.mysticky-welcomebar-fixed .mysticky-welcomebar-content p').css('color', $('#mysticky_welcomebar_bgtxtcolor').val() );
			$( '.mysticky-welcomebar-fixed p' ).css( 'font-size', $('#mysticky_welcomebar_fontsize').val() + 'px' );
		} );

		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_btn_text]"]' ).on( 'keyup', function(){
			var mysticky_btn_text_val = $( this ).val();
			$( '.mysticky-welcomebar-btn a' ).text( mysticky_btn_text_val );
		} );

		/* DATE: 11-12-2019 start */
		$( 'select[name="mysticky_option_welcomebar[mysticky_welcomebar_attentionselect]"]' ).on( 'change', function(){
			$(".mysticky-welcomebar-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mysticky-welcomebar-attention-\S+/g) || []).join(' ');
			});
			$( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-attention-' + $(this).val() );

		} );
		/* DATE: 11-12-2019 End */
		$("#myStickymenu-entry-effect").on( 'change', function() {
			$(".mysticky-welcomebar-preview-screen .mysticky-welcomebar-fixed").removeClass('entry-effect');
			$(".mysticky-welcomebar-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mysticky-welcomebar-entry-effect-\S+/g) || []).join(' ');
			});
			$( '.mysticky-welcomebar-preview-screen .mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-entry-effect-' + $(this).val() );
			setTimeout( function(){
				$(".mysticky-welcomebar-preview-screen .mysticky-welcomebar-fixed").addClass('entry-effect');
			}, 1000 );

		});
		$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );

	});
	$( window ).on('load', function(){
	    $( '.mysticky-welcomebar-url-options' ).each( function(){
	        $( this ).trigger( 'change' );
	    });
	});


	/* Preview section part maintain sticky using "check_for_preview_pos"  function */

	function check_for_preview_pos() {

		var $window = $(window);
		var windowsize = $window.width();
		console.log("windowsize == " + windowsize)


		var mysticky_welcomebar_form_pos = $( '#sticky-header-welcome-bar' ).offset().top;
		if($(".show-on-apper").length && $(".mysticky-welcomebar-setting-right").length) {
			var topPos = $(".show-on-apper").offset().top - $(window).scrollTop() - 750;
			if (topPos < 0) {
				topPos = Math.abs(topPos);
				jQuery(".mysticky-welcomebar-setting-right").css("margin-top", ((-1)*topPos)+"px");
			} else {
				jQuery(".mysticky-welcomebar-setting-right").css("margin-top", "0");
			}
		}
		var position_screen = (isRtl == 1 ) ? 'left' : 'right';

		if ( ( mysticky_welcomebar_form_pos + 32 ) < $(window).scrollTop() ) {
			$( '.mysticky-welcomebar-setting-right' ).css( 'position', 'fixed' );
			$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '70px' );

			if ( windowsize < 1181 && windowsize > 768 && position_screen == 'right' ) {
				
				$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '30px' );
				
			}else if ( windowsize <= 768 && position_screen == 'right' ) {
				$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '25px' );
			}
			
			if ( windowsize < 1181 && windowsize > 768 && position_screen == 'left') {
				$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '30px' );
			}else if ( windowsize <= 768 && position_screen == 'left') {
				$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '25px' );
			}
		} else {
			$( '.mysticky-welcomebar-setting-right' ).css( 'position', 'absolute' );
			$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '50px' );

			if ( windowsize < 1181 && position_screen == 'right') {
				$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '10px' );
			}

			if ( windowsize < 1181 && position_screen == 'left') {
				$( '.mysticky-welcomebar-setting-right' ).css( position_screen, '10px' );
			}
		}
		
		
	}
	
	jQuery(document).on("click",".mystickymenu-delete-widget",function(e){
		e.preventDefault();
		
		var widget_id = jQuery(this).data("widget-id");
		jQuery("#widget-delete-dialog-"+widget_id).show();
		jQuery("#mystickymenu-delete-popup-overlay-"+widget_id).show();
	});
	
	/* Mystickymenu: Dashbaord table delete button click action */

	jQuery(document).on("click",".btn-delete",function(e){
		e.preventDefault();
		var delWidId = jQuery(this).data("id");
		jQuery.ajax({
			url: ajaxurl,
			type:'post',
			data: 'action=stickymenu_widget_delete&widget_id=' + delWidId + '&widget_delete=1&wpnonce=' + mystickymenu.ajax_nonce,
			success: function( data ){					
				$( '#stickymenu-widget-' + delWidId ).remove();
				setTimeout('location.reload()', 500);
			},
		});
	});
	
	
	jQuery(document).on("click",".btn-delete-cancel",function(e){
		e.preventDefault();
		var id = jQuery(this).data("id");
		jQuery("#widget-delete-dialog-"+id).hide();
		jQuery("#mystickymenu-delete-popup-overlay-"+id).hide();
	});
	
	/* Mystickymenu: Dashbaord table welcombar widget status change action */
	
	jQuery(document).on("click",".mystickymenu-widget-enabled",function(){
		var widget_id = $(this).data('id');
		if(jQuery(this).prop("checked") != true){
			jQuery('#widget-status-dialog-' + widget_id).show();
			jQuery('#mystickymenu-status-popup-overlay-' + widget_id).show();
		}else{
			var widget_status = 1;
			set_widget_status( widget_id, widget_status );	
		}
	});
	
	jQuery(document).on("click",".btn-turnoff-status",function(e){
		e.preventDefault();
		var widget_id = $(this).data('id');

		if ( typeof widget_id !== "undefined") {
			var widget_status = 0;
			set_widget_status( widget_id, widget_status );	
		}
		
	});	

	
	jQuery(document).on("click",".btn-nevermind-status",function(e){
		e.preventDefault();
		var widget_id = $(this).data('id');
		if ( typeof widget_id !== "undefined") {
			var widget_status = 1;
			set_widget_status( widget_id, widget_status );
			jQuery("#mystickymenu-widget-enabled-"+widget_id).prop('checked', true)
		}
		
	});	

	jQuery(document).on("click",".mystickymenupopup-overlay",function(e){
		e.preventDefault();
		
		if(jQuery(this).data("fromoverlay") == 'welcombar_delete'){
			jQuery(this).hide();
			var delId = jQuery(this).data('id');
			jQuery('#widget-delete-dialog-'+delId).hide();
		}
	});
	
	jQuery(document).on("click",".mystickymenupopup-widget-status-overlay",function(e){
		e.preventDefault();	
		var widget_id = $(this).data('id');
		var widget_status = 1;
		set_widget_status( widget_id, widget_status );
		jQuery("#mystickymenu-widget-enabled-"+widget_id).prop('checked', true);		
		
	});
	
	function set_widget_status( widget_id, widget_status ) {
		jQuery.ajax({
			url: ajaxurl,
			type:'post',
			data: 'action=mystickymenu_widget_status&widget_id='+widget_id+'&widget_status=' + widget_status +'&wpnonce=' + mystickymenu.ajax_nonce,
			success: function( data ){
				$('#widget-status-dialog-' + widget_id).hide();
				$('#mystickymenu-status-popup-overlay-' + widget_id).hide();
			},
		});
	}
	
	jQuery(document).on("click","#close-first-popup",function(){
		jQuery('.first-widget-popup').hide();
		jQuery('.mystickymenupopup-overlay').hide();
	});
	
	jQuery(document).on("click","#first_widget_overlay",function(){
		jQuery('.first-widget-popup').hide();
		jQuery(this).hide();
	});
	
	
	
	jQuery(document).on("click","#btn-config-disable",function(e){
		e.preventDefault();
		jQuery("#stickymenu_status_popupbox").show();
		jQuery("#stickymenuconfig-overlay-popup").show();
	});
	
	jQuery(document).on("click","#stickymenuconfig-overlay-popup",function(){
		jQuery("#stickymenu_status_popupbox").hide();
		jQuery(this).hide();
	});
	
	jQuery(document).on("click","#stickymenu_status_turnoff",function(e){
		e.preventDefault();
		var stickymenu_status = 0;
		set_stickymenu_status( stickymenu_status );
	});	
	
	jQuery(document).on("click","#stickymenu_status_nevermind",function(e){
		e.preventDefault();
		jQuery("#stickymenu_status_popupbox").hide();
		jQuery("#stickymenuconfig-overlay-popup").hide();
	});	
	
	function set_stickymenu_status( stickymenu_status ){
		jQuery.ajax({
			url: ajaxurl,
			type:'post',
			data: 'action=stickymenu_status_update&stickymenu_status=' + stickymenu_status +'&wpnonce=' + mystickymenu.ajax_nonce,
			success: function( data ){
				location.reload();
			},
		});
	}
	
	jQuery(document).on("click",".close-button",function(){
		if(jQuery(this).data("from") == 'welcome-bar-status'){
			var id = jQuery(this).data("id");
			jQuery("#widget-status-dialog-"+id).hide();
			jQuery("#mystickymenu-status-popup-overlay-"+id).hide();
			var widget_status = 1;
			set_widget_status( id, widget_status );
			jQuery("#mystickymenu-widget-enabled-"+id).prop('checked', true)
			
		}else if( jQuery(this).data("from") == "stickymenu-status"){
			jQuery("#stickymenu_status_popupbox").hide();
			jQuery("#stickymenuconfig-overlay-popup").hide();
			
		}else if( jQuery(this).data("from") == "stickymenu-confirm" ){
			jQuery("#mysticky-sticky-save-confirm").hide();
			jQuery("#stickymenu-option-overlay-popup").hide();
		}else if( jQuery(this).data("from") == "welcombar-confirm" ){
			jQuery("#welcomebar-save-confirm").hide();	
			jQuery("#welcombar-sbmtvalidation-overlay-popup").hide();
		}else{
			var id = jQuery(this).data("id");
			jQuery("#widget-delete-dialog-"+id).hide();
			jQuery("#mystickymenu-delete-popup-overlay-"+id).hide();
		}
	});
	
	
	jQuery(document).on("click","#stickymenu-option-overlay-popup",function(){
		$("#mysticky-sticky-save-confirm").hide();
		$(this).hide();
	});
	
	
	jQuery(document).on("click","#welcombar-sbmtvalidation-overlay-popup",function(){
		$("#welcomebar-save-confirm").hide();
		$(this).hide();
	});
	
	
	jQuery(document).on("change","#mysticky-welcomebar-countdown-enabled",function(){
		var url = jQuery(this).data("url");
		jQuery(this).prop('checked',false);
		window.open(url, '_blank');
	});
	
	jQuery(document).on("click",".btn-save-stickymenu" , function(event){
		if ( $( '#mysticky-stickymenu-form-enabled' ).prop( 'checked' ) == false && $('#save_stickymenu').val() == "" ) {
			event.preventDefault();
			$("#mysticky-sticky-save-confirm").show();
			$("#stickymenu-option-overlay-popup").show();
			
			$('#stickymenu_status_ok').attr('data-clickfrom', 'save');
			$('#stickymenu_status_dolater').attr('data-clickfrom', 'save');
		}
	});
	
	jQuery(document).on("click",".save_view_dashboard" , function(event){
		if ( $( '#mysticky-stickymenu-form-enabled' ).prop( 'checked' ) == false && $('#save_stickymenu').val() == "" ) {
			event.preventDefault();
			$("#mysticky-sticky-save-confirm").show();
			$("#stickymenu-option-overlay-popup").show();
			
			$('#stickymenu_status_ok').attr('data-clickfrom', 'dashboard');
			$('#stickymenu_status_dolater').attr('data-clickfrom', 'dashboard');
		}
	});
	
	jQuery(document).on("click","#stickymenu_status_ok",function(){
		var clickFrom = $(this).data("clickfrom");
		$('#save_stickymenu').val("1");
		$( '#mysticky-stickymenu-form-enabled' ).prop( 'checked' , true )
		$("#mysticky-sticky-save-confirm").hide();
		$("#stickymenu-option-overlay-popup").hide();
		if(clickFrom == 'dashboard'){
			$('.save_view_dashboard').trigger("click");
		}else{
			$('.btn-save-stickymenu').trigger("click");	
		}
	});

	jQuery(document).on("click","#stickymenu_status_dolater",function(){
		var clickFrom = $(this).data("clickfrom");
		$('#save_stickymenu').val("1");
		if(clickFrom == 'dashboard'){
			$('.save_view_dashboard').trigger("click");
		}else{
			$('.btn-save-stickymenu').trigger("click");	
		}
	});
	
	jQuery(document).on( 'click','.welcombar_save', function(e){
		
		if ( $( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_enable]"]' ).prop( 'checked' ) == false && $( 'input#save_welcome_bar' ).val() == '' ) {
			e.preventDefault();
			$("#welcomebar-save-confirm").show();	
			$("#welcombar-sbmtvalidation-overlay-popup").show();
			$("#welcombar_sbmtbtn_off").attr("data-clickfrom",'save_button');	
			$("#welcomebar_yes_sbmtbtn").attr("data-clickfrom",'save_button');
		}
	});
	
	
	jQuery(document).on( 'click','.save_view_dashboard', function(e){
		
		if ( $( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_enable]"]' ).prop( 'checked' ) == false && $( 'input#save_welcome_bar' ).val() == '' ) {
			e.preventDefault();
			$("#welcomebar-save-confirm").show();	
			$("#welcombar-sbmtvalidation-overlay-popup").show();	
			$("#welcombar_sbmtbtn_off").attr("data-clickfrom",'save_dashboard_button');	
			$("#welcomebar_yes_sbmtbtn").attr("data-clickfrom",'save_dashboard_button');
		}
	});
			
	jQuery(document).on("click","#welcomebar_yes_sbmtbtn",function(){
		
		var clickFrom = $(this).data("clickfrom");
		
		$("#welcomebar-save-confirm").hide();	
		$("#welcombar-sbmtvalidation-overlay-popup").hide();
		$( 'input#welcome_save_anyway' ).val('1');
		$( 'input#save_welcome_bar' ).val('1');	
		$( 'input[name="mysticky_option_welcomebar[mysticky_welcomebar_enable]"]' ).prop( 'checked',true );
		if(clickFrom == 'save_dashboard_button'){
			$( '.mysticky-welcomebar-submit input.save_view_dashboard' ).trigger('click');	
		}else{
			$( '.mysticky-welcomebar-submit input.welcombar_save' ).trigger('click');		
		}
	});
			
	jQuery(document).on("click","#welcombar_sbmtbtn_off",function(){
		var clickFrom = $(this).data("clickfrom");
		
		$("#welcomebar-save-confirm").hide();	
		$("#welcombar-sbmtvalidation-overlay-popup").hide();	
		$( 'input#welcome_save_anyway' ).val('1');
		$( 'input#save_welcome_bar' ).val('1');
		
		if(clickFrom == 'save_dashboard_button'){
			$( '.mysticky-welcomebar-submit input.save_view_dashboard' ).trigger('click');	
		}else{
			$( '.mysticky-welcomebar-submit input.welcombar_save' ).trigger('click');		
		}
	});

	jQuery(document).on("click","#mysticky-welcomebar-showcoupon-enabled",function(){
		var url = jQuery(this).data("url");
		jQuery(this).prop('checked',false);
		window.open(url, '_blank');
	});

	jQuery(document).on("change","#mysticky-welcomebar-collectlead-enabled",function(){

		var button_text = $(this).data("button-text");

		if( $(this).prop("checked") == true ){

			$(".timer-message").show();
			$(".mysticky-collect-lead").show();
			$(".welcomebar_height_content").hide();
			$(".mysticky-welcomebar-preview-screen .mysticky-welcomebar-fixed .mysticky-welcomebar-content").css("width","90%");
			$(".mysticky-welcomebar-lead-content").show();
			$(".mysticky-welcomebar-btn a").text("Send me");
			$("#mysticky_welcomebar_btn_text").val("Send me");
			$(".mysticky-welcomebar-btn").addClass("collect-lead");
			$(".height-setting").hide();
		}else{

			button_text = ( button_text == 'Send me' ) ? 'Got it!' : button_text;
			$(".timer-message").hide();
			$(".welcomebar_height_content").show();
			$(".mysticky-collect-lead").hide();
			$(".mysticky-welcomebar-preview-screen .mysticky-welcomebar-fixed .mysticky-welcomebar-content").css("width","65%");
			$(".mysticky-welcomebar-lead-content").hide();
			$(".mysticky-welcomebar-btn a").text(button_text);
			$("#mysticky_welcomebar_btn_text").val(button_text);
			$(".height-setting").show();
			$(".mysticky-welcomebar-btn").removeClass("collect-lead");
		}
	});

	jQuery(document).on("click","#send_lead_email_enable",function(){
		var url = jQuery(this).data("url");
		jQuery(this).prop('checked',false);
		window.open(url, '_blank');
	});


	jQuery(document).on("keyup","#lead-name-placeholder,#lead-email-placeholder,#lead-phone-placeholder",function(e){
		if( $(this).attr("id") == "lead-name-placeholder" ){
			$(".preview-lead-name").attr("placeholder",$(this).val());
		}else if( $(this).attr("id") == "lead-email-placeholder" ){
			$(".preview-lead-email").attr("placeholder",$(this).val());
		}else{
			$(".preview-lead-phone").attr("placeholder",$(this).val());
		}
	});

	jQuery(document).on("change","input[name='mysticky_option_welcomebar[mysticky_welcomebar_lead_input]']",function(){
		if( $(this).val() == 'email_address' ){
			$("#lead-email-content").show();
			$("#lead-phone-content").hide();
			$(".preview-lead-email").show();
			$(".preview-lead-phone").hide();
		}else{
			$("#lead-email-content").hide();
			$("#lead-phone-content").show();
			$(".preview-lead-email").hide();
			$(".preview-lead-phone").show();
		}
		
	});

	/* Mystickymenu : Single delete contact lead data - Contact lead page */

	jQuery(document).on("click",".mystickymenu-delete-entry",function(event){

		var deleterowid = $( this ).attr( "data-delete" );
		var confirm_delete = window.confirm("Are you sure you want to delete Record with ID# "+deleterowid);
		if (confirm_delete == true) {
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "mystickymenu_delete_contact_lead","ID": deleterowid, delete_nonce: jQuery("#delete_nonce").val(),"wpnonce": mystickymenu.ajax_nonce},
				success: function(data){
					location.href = window.location.href;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("Status: " + textStatus); alert("Error: " + errorThrown);
				}
			});
		}
		
	});

	/* Mystickymenu : Bulk delete all contact lead data - Contact lead page */

	jQuery(document).on("click","#mystickymenu_delete_all_leads", function(){
		var confirm_delete = window.confirm("Are you sure you want to delete all Record from the database?");
		if (confirm_delete == true) {
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "mystickymenu_delete_contact_lead", 'all_leads': 1 , delete_nonce: jQuery("#delete_nonce").val(),"wpnonce": mystickymenu.ajax_nonce},
				success: function(data){
					location.href = window.location.href;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("Status: " + textStatus); alert("Error: " + errorThrown);
				}
			});
		}
		return false;
	});

	/* Mystickymenu : Bulk do action trigger in contact lead table */

	jQuery(document).on('click','#doaction',function(e){
		e.preventDefault();
		var bulks = [];
		jQuery( '.cb-select-blk' ).each( function(){
			if (this.checked) {
				bulks.push( jQuery(this).val() );
			}
		} ); 

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "my_sticky_menu_bulks","bulks": bulks,"wpnonce": mystickymenu.ajax_nonce},
			success: function(data){
				location.href = window.location.href;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("Status: " + textStatus); alert("Error: " + errorThrown);
			}
		});
	} );

	jQuery(document).on( 'change','#mysticky-welcomebar-close-automatically-enabled', function(){
		$(this).prop("checked",false);
		var url = $(this).data("url");
		window.open(url, '_blank');
	});
	
	jQuery(document).on("click",".save_change",function(){
		$( '.mysticky-welcomebar-submit input.welcombar_save' ).trigger('click');	
	});
	
	jQuery(document).on( 'change','#mysticky_welcomebar_show_success_message', function(){
		if( $( this ).prop( "checked" ) == true ) {
			$('#mysticky-welcomebar-thankyou-wrap').show();
		} else {
			$('#mysticky-welcomebar-thankyou-wrap').hide();
		}
	});
	
})(jQuery);
