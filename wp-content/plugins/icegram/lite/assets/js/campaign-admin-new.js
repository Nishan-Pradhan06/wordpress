jQuery(function() {
	
	jQuery('.ig_campaign_update, .ig_campaign_publish, .ig_campaign_switch_draft, .ig_campaign_save').on('click', function(e){
		jQuery(window).off( 'beforeunload.edit-post' );
	});
	
	var campaign_page_selector = '.post-type-ig_campaign.post-php';
	
	jQuery( campaign_page_selector + ' .wrap' ).addClass( 'icegram_tw' );	
	jQuery( campaign_page_selector + ' input[type="text"]' ).addClass( 'form-input' );		
	jQuery( campaign_page_selector + ' #titlediv' ).wrap( '<div id="ig-campaign-overview"></div>' );		
	
	var titleAction = jQuery( 'body' ).find( '#ig-campaign-overview' );		
	jQuery('.ig-top-nav').insertBefore(titleAction);
	jQuery('.post-type-ig_campaign.post-php .wrap').show();


	jQuery('body').on('click', "#tab-menu li a", function ( e ) {
		e.preventDefault();
		var current_tab = jQuery(this).attr('id');
		var current_message = jQuery(this).closest('.edit-form-section');

	    jQuery(current_message).find('#tab-menu li a').removeClass('active');
	    jQuery(current_message).find('#' + current_tab).addClass('active');
	    
	    var tab = jQuery(this).attr('href');  
	    jQuery(current_message).find('#tab-contents .active').removeClass('active');
	    jQuery(current_message).find(tab).addClass('active').show();
  	});


	jQuery('.campaign_data, #message-settings, #ig_message_list_table').on( 'click', '.message_edit', function(e) {
		e.preventDefault();
		var message_id = jQuery(this).parent().attr('value');
		var variation_id = jQuery(this).closest('.variation_row').find('.variation_name').attr('variation_id');
		
		var variation = jQuery(this).closest('tr').attr('variation');
		jQuery('.message_edit').removeClass('active-message-edit');
		
		jQuery('body').find('.ig_message, #ig-admin-tab-variations, .ig-variation, .ig-admin-tab').hide();	
		jQuery('#ig-admin-tab-main').show();
		
		if( 'undefined' != typeof( variation_id ) && 'original' !== variation_id ) {
			jQuery('#ig-admin-tab-main').hide();
			jQuery('#ig-admin-tab-variations, #' + variation_id).show();
		} 

		jQuery('.basic-message-fields').hide();

		jQuery('#ig-admin-tabs').find('[variation="' + variation + '"]').show().find('.basic-message-fields[value="' + message_id + '"]').show().find('#message_row_'+ message_id).show().closest('.ig-admin-tab').show();
		jQuery(this).addClass('active-message-edit');
		
	});

	jQuery('.campaign_data, #message-settings, #ig_message_list_table').on('click','.message_delete', function(e) {
		e.preventDefault();
		var message_id = jQuery(this).closest('.message-row').attr('value');
		var variation = jQuery(this).closest('tr').attr('variation');
				
		jQuery('#ig-admin-tabs').find('[variation="' + variation + '"]').find('.message-row[value="'+ message_id+'"]').remove();
		jQuery(this).closest('.message-row').remove();

		//hide_empty_campaign_message(variation);

	});

	jQuery('#ig_message_list_table').on('click', '.add-message-button', function(e) {
		e.preventDefault();
		
		jQuery('.add-message-popup').css('visibility','hidden');
		jQuery(this).siblings('.add-message-popup').css('visibility','visible').show();
		
	});

	jQuery('#ig_message_list_table').on('click', '.close-add-message-popup' ,function (e) {
		e.preventDefault();
		jQuery('.add-message-popup').hide();
	});

	jQuery("#ig-admin-tabs").on( "keyup", ".message-title-input", function(e) {
		  var message_name = jQuery(this).val();
		  var current_message = jQuery('.message_edit.active-message-edit .message_title .message-title-text');
		  current_message.text(message_name);
	});
	
	function hide_empty_campaign_message( variation ) {
		var variation_row = jQuery('#ig_message_list_table').find('[variation="' + variation + '"]');
		if( variation_row.find('.message-list-col .message-row').length == 0 ) {
			variation_row.find('.empty_variation_messages').show();
		} else {
			variation_row.find('.empty_variation_messages').hide();
		}
	}

});
