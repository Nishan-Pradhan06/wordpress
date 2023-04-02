try {
		// load icegram scripts and css
		function load_scripts_and_css(icegram_data){
			var pending_scripts = {};
			// Collect all CSS/JS in pending list
				jQuery.each(icegram_data['css'], function(i, v){ 
				pending_scripts['ig_css_'+i] = 1;
			});
			jQuery.each(icegram_data['scripts'], function(i, v){ 
				pending_scripts['ig_script_'+i] = 1;
			});


			var boot_check = function ( e ) {
				var	id = e.target.id || '';

				if (id != '' && pending_scripts.hasOwnProperty(id)) {
					delete pending_scripts[id];
				}
				if (jQuery.isEmptyObject(pending_scripts)) {

					jQuery( window ).trigger( 'scripts_loaded.icegram' );
						jQuery( function () { 
							window.icegram = new Icegram();
							window.icegram.init( icegram_data );
							jQuery('body').addClass('ig_'+icegram_pre_data.post_obj['device']);
							icegram_timing['end'] = Date.now();
					
						}); 
					
				}
			};
			
			jQuery.each(icegram_data['css'], function(i, v) {
				jQuery('<link>').attr('type', 'text/css')
					.attr('rel', 'stylesheet')
					.attr('id', 'ig_css_'+i)
					.attr('media', 'all')
					.appendTo('head')
					.on( 'load', boot_check )
					.attr('href', v);
			});

			var ig_main_js = icegram_data['scripts'].shift();
			jQuery('<script>').attr('type', 'text/javascript')
				.attr('id', 'ig_script_0')
				.appendTo('body')
				.on( 'load', function( e ) { 
					boot_check( e );
					jQuery.each(icegram_data['scripts'] , function(i, v) {
						jQuery('<script>').attr('type', 'text/javascript')
							.attr('id', 'ig_script_'+(i+1))
							.appendTo('body')
							.on( 'load', boot_check )
							.attr('src', v);
					});
				} )
				.attr('src', ig_main_js);
		}
		var icegram_data;
		var icegram_timing = {};
		icegram_pre_data.post_obj['referral_url'] = window.location.href;
		icegram_timing['start'] = Date.now();
		
		if(icegram_pre_data.post_obj['cache_compatibility'] === 'yes'){
			
			// Check if 'add-to-cart' property is present.
			if ( icegram_pre_data.post_obj.hasOwnProperty('add-to-cart') ) {
				
				/**
				 * Remove 'add-to-cart' property to avoid conflict with WooCommerce add to cart functionality.
				 * Product getting added twice on add to cart when ajax add-to-cart is disabled in WooCommerce and cache compatibility is enabled in Icegram.
				 */
				delete icegram_pre_data.post_obj['add-to-cart'];
			}

			jQuery.ajax({
				url: icegram_pre_data.ajax_url,
				type: "POST",
				async: true,
				cache: false,
				data : icegram_pre_data.post_obj,
				dataType : "json",
				success:function(res) {
					if(res){
						icegram_data = res;
						load_scripts_and_css(icegram_data);
					}else{
						//remove empty inline div
				        jQuery('.ig_inline_container:empty').remove();
					}
				},
				error:function(res) {
				}
			}); 

			// Add CSS and Js files not loaded during message process
			// TODO :: Test for inline style and scripts, do the needful
		  	jQuery( window ).on( "init.icegram", function(e, ig) {
		  		if(typeof ig !== 'undefined' && typeof ig.messages !== 'undefined' ){
				  	jQuery.each(ig.messages, function(i, msg){
				  		if(msg.data.assets){
				  			jQuery.each(msg.data.assets.styles || [], function(id, style){
				  				var src = jQuery('<div/>').html(style).find('link').attr('href');
				  				if(src && jQuery('link[href="'+src+'"]').length == 0){
				  					jQuery('body').append(style);
				  				}
				  			});	
				  			jQuery.each(msg.data.assets.scripts || [], function(id, script){
				  				var src = jQuery('<div/>').html('<script ' + script).find('script').attr('src');
				  				if(src && jQuery('script[src="'+src+'"]').length == 0){
				  					jQuery('body').append('<script ' + script);
				  				}
				  			});
				  		}
				  	});
			  	}

		  	}); 

		}else{
			if(typeof(icegram_data) !== 'undefined'){
				load_scripts_and_css(icegram_data);
			}
		}

		jQuery( window ).on( "init.icegram", function(e, ig) {
	  		if(typeof ig !== 'undefined' && typeof ig.messages !== 'undefined' ){
			  	jQuery.each(ig.messages, function(i, msg){
			  		if(msg.data.use_custom_code =='yes' && typeof(msg.data.custom_js) !== 'undefined'){
				    	jQuery('body').append(msg.data.custom_js);
					}
			  	});
		  	}

		  	if(jQuery('body').find('.trigger_onclick').length){
		  		jQuery.each(jQuery('body').find('.trigger_onclick'), function(i,t){
		  		    var onclick = '';
			  		var campaigns = jQuery(t).data('campaigns');
			  			var msgs = ig.get_message_by_campaign_id(campaigns);
			  			jQuery.each(msgs,function(i,msg){
			  				var msg_id = parseInt(msg.data.id);
			  				onclick += 'icegram.get_message_by_id('+msg_id+').show();' 
			  			});
			  			if(jQuery(t).children().length){
			  				jQuery(t).children().attr('onclick',onclick);
			  			}else{
			  				jQuery(t).attr('onclick',onclick);
			  			}
		  		});
		  	}
	  	}); 

} catch(err) {
	console.log(err);
}
