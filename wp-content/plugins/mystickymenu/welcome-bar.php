<?php

function mysticky_welcome_bar_backend() {
	$upgarde_url = admin_url("admin.php?page=my-stickymenu-upgrade");
	$nonce = wp_create_nonce('mysticky_option_welcomebar_update');
	$nonce_reset = wp_create_nonce('mysticky_option_welcomebar_reset');

	$welcomebar = get_option( 'mysticky_option_welcomebar' );
	
	if ( $welcomebar == '' || empty($welcomebar)) {
		$welcomebar = mysticky_welcomebar_pro_widget_default_fields();
	}

	$welcomebar["mysticky_welcomebar_x_color"] = isset($welcomebar["mysticky_welcomebar_x_color"]) ? $welcomebar["mysticky_welcomebar_x_color"] : '#000000';

	$welcomebar['mysticky_welcomebar_bgcolor'] = ( isset($welcomebar['mysticky_welcomebar_bgcolor']) && $welcomebar['mysticky_welcomebar_bgcolor'] != '' ) ? $welcomebar['mysticky_welcomebar_bgcolor'] : '#03ed96';
	
	$welcomebar['mysticky_welcomebar_bgtxtcolor'] = ( isset($welcomebar['mysticky_welcomebar_bgtxtcolor']) && $welcomebar['mysticky_welcomebar_bgtxtcolor'] != '' ) ? $welcomebar['mysticky_welcomebar_bgtxtcolor'] : '#000000';
	
	$welcomebar['mysticky_welcomebar_bar_text'] = (isset($welcomebar['mysticky_welcomebar_bar_text']) && $welcomebar['mysticky_welcomebar_bar_text'] != '' ) ? $welcomebar['mysticky_welcomebar_bar_text'] : '#000000';
	
	$welcomebar['mysticky_welcomebar_btntxtcolor'] = (isset($welcomebar['mysticky_welcomebar_btntxtcolor']) && $welcomebar['mysticky_welcomebar_btntxtcolor'] != '' ) ? $welcomebar['mysticky_welcomebar_btntxtcolor'] : '#ffffff';
	
	$welcomebar['mysticky_welcomebar_btncolor'] = (isset($welcomebar['mysticky_welcomebar_btncolor']) && $welcomebar['mysticky_welcomebar_btncolor'] != '' ) ? $welcomebar['mysticky_welcomebar_btncolor'] : '';
	
	$mysticky_welcomebar_showx_desktop = $mysticky_welcomebar_showx_mobile = '';
	$mysticky_welcomebar_btn_desktop = $mysticky_welcomebar_btn_mobile = '';
	$mysticky_welcomebar_display_desktop = $mysticky_welcomebar_display_mobile = '';
	if( isset($welcomebar['mysticky_welcomebar_x_desktop']) ) {
		$mysticky_welcomebar_showx_desktop = ' mysticky-welcomebar-showx-desktop';
	}
	if( isset($welcomebar['mysticky_welcomebar_x_mobile']) ) {
		$mysticky_welcomebar_showx_mobile = ' mysticky-welcomebar-showx-mobile';
	}
	if( isset($welcomebar['mysticky_welcomebar_btn_desktop']) ) {
		$mysticky_welcomebar_btn_desktop = ' mysticky-welcomebar-btn-desktop';
	}
	if( isset($welcomebar['mysticky_welcomebar_btn_mobile']) ) {
		$mysticky_welcomebar_btn_mobile = ' mysticky-welcomebar-btn-mobile';
	}
	
	if( !isset($welcomebar['mysticky_welcomebar_redirect_rel']) ) {
		$welcomebar['mysticky_welcomebar_redirect_rel'] = '';
	}
	$display = ' mysticky-welcomebar-attention-'. ( isset($welcomebar['mysticky_welcomebar_attentionselect']) ? $welcomebar['mysticky_welcomebar_attentionselect'] : '' );
	$display_entry_effect = (isset($welcomebar['mysticky_welcomebar_entry_effect'])) ? ' mysticky-welcomebar-entry-effect-'.$welcomebar['mysticky_welcomebar_entry_effect'] : ' mysticky-welcomebar-entry-effect-slide-in';
	$welcomebar['mysticky_welcomebar_position'] = isset($welcomebar['mysticky_welcomebar_position']) ? $welcomebar['mysticky_welcomebar_position'] : 'top';
	$display_main_class = "mysticky-welcomebar-position-" . $welcomebar['mysticky_welcomebar_position'] . $mysticky_welcomebar_showx_desktop . $mysticky_welcomebar_showx_mobile . $mysticky_welcomebar_btn_desktop . $mysticky_welcomebar_btn_mobile . $display . $display_entry_effect;

	$welcomebar['mysticky_welcomebar_lead_input'] = (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] != '' ) ? $welcomebar['mysticky_welcomebar_lead_input'] : "email_address";
	

	$welcomebar['lead_name_placeholder'] = (isset($welcomebar['lead_name_placeholder']) && $welcomebar['lead_name_placeholder'] != '' ) ? stripslashes($welcomebar['lead_name_placeholder']) : "Name";

	$welcomebar['lead_email_placeholder'] = (isset($welcomebar['lead_email_placeholder']) &&$welcomebar['lead_email_placeholder'] != '' ) ? stripslashes($welcomebar['lead_email_placeholder']) : "Email";

	$welcomebar['lead_phone_placeholder'] = (isset($welcomebar['lead_phone_placeholder']) &&$welcomebar['lead_phone_placeholder'] != '' ) ? stripslashes($welcomebar['lead_phone_placeholder']) : "Phone";

	$welcomebar['mysticky_welcomebar_enable_lead'] = (isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] != '' ) ? $welcomebar['mysticky_welcomebar_enable_lead'] : 0;

	?>
	<form class="mysticky-welcomebar-form" id="mysticky_welcomebar_form" method="post" action="<?php echo admin_url('admin.php?page=my-stickymenu-welcomebar&save=1&widget=0');?>">
		<div class="mysticky-welcomebar-header-title">
			<h3><?php _e('Welcome Bar', 'myStickymenu'); ?></h3>
			<label for="mysticky-welcomebar-contact-form-enabled" class="mysticky-welcomebar-switch">
				<input type="checkbox" id="mysticky-welcomebar-contact-form-enabled" name="mysticky_option_welcomebar[mysticky_welcomebar_enable]" value="1" <?php checked( @$welcomebar['mysticky_welcomebar_enable'], '1' );?> />
				<span class="slider"></span>
			</label>
		</div>
		<div class="mysticky-welcomebar-setting-wrap">
			<div class="mysticky-welcomebar-setting-left">
				<div class="mysticky-welcomebar-setting-block">
					<div class="mysticky-welcomebar-subheader-title">
						<h4><?php _e('Bar Settings', 'myStickymenu'); ?></h4>
					</div>
					<div class="mysticky-welcomebar-setting-content mysticky-welcomebar-setting-position">
						<label><?php _e('Position', 'myStickymenu'); ?><span class="mysticky-custom-fields-tooltip">
									<a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose if you want to show the welcome bar on top or at the bottom of your site</p></span></label>
						<div class="mysticky-welcomebar-setting-content-right">
							<label>
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_position]" value= "top" type="radio" <?php checked( @$welcomebar['mysticky_welcomebar_position'], 'top' );?> />
								<?php _e("Top", 'mystickymenu'); ?>
							</label>
							<label>
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_position]" value="bottom" type="radio" disabled />
								<?php _e("Bottom", 'mystickymenu'); ?>
							</label>
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content height-setting" <?php if(isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] == 1):?> style="display:none;"<?php endif;?>>
						<label><?php _e('Height', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose the size of your welcome bar in pixels</p></span>
						</label>
						<div class="mysticky-welcomebar-setting-content-right">
							<div class="px-wrap">
								<input type="number" class="" min="0" step="1" id="mysticky_welcomebar_height" name="mysticky_option_welcomebar[mysticky_welcomebar_height]" value="60" disabled />
								<span class="input-px">PX</span>
							</div>
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Welcome Bar Color', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-colorpicker">
							<input type="text" id="mysticky_welcomebar_bgcolor" name="mysticky_option_welcomebar[mysticky_welcomebar_bgcolor]" class="my-color-field" data-alpha="true" value="<?php echo esc_attr($welcomebar['mysticky_welcomebar_bgcolor']);?>" />
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Welcome Bar Text Color', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-colorpicker">
							<input type="text" id="mysticky_welcomebar_bgtxtcolor" name="mysticky_option_welcomebar[mysticky_welcomebar_bgtxtcolor]" class="my-color-field" data-alpha="true" value="<?php echo esc_attr($welcomebar['mysticky_welcomebar_bgtxtcolor']);?>" />
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Font', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right">
							<select name="mysticky_option_welcomebar[mysticky_welcomebar_font]" class="form-fonts">
								<option value=""><?php _e( 'Select font family', 'myStickymenu' );?></option>
								<?php $group= ''; foreach( myStickymenu_fonts() as $key=>$value):
											if ($value != $group){
												echo '<optgroup label="' . $value . '">';
												$group = $value;
											}
										?>
									<option value="<?php echo esc_attr($key);?>" <?php selected( @$welcomebar['mysticky_welcomebar_font'], $key ); ?>><?php echo esc_html($key);?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Font Size', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right">
							<div class="px-wrap">
								<input type="number" class="" min="0" step="1" id="mysticky_welcomebar_fontsize" name="mysticky_option_welcomebar[mysticky_welcomebar_fontsize]" value="<?php echo @$welcomebar['mysticky_welcomebar_fontsize'];?>" />
								<span class="input-px">PX</span>
							</div>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Text', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right">
						<?php 
							$settings = array(
								'media_buttons' => false, 
								'textarea_name' => 'mysticky_option_welcomebar[mysticky_welcomebar_bar_text]',
								'tinymce' => false,
								'quicktags' => array(
									'buttons' => 'strong,em,link'
								)
							);
							wp_editor( stripslashes($welcomebar['mysticky_welcomebar_bar_text']), 'mysticky_bar_text', $settings );      
							// add more buttons to the html editor
							function underline_tag_add_quicktags() {
								if ( wp_script_is('quicktags') ){ ?>
								<script type="text/javascript">
									QTags.addButton( 'underline_tag', 'U', '<u>', '</u>', 'underline', 'underline', 20, '' );
								</script>
							<?php
								}
							}
							add_action( 'admin_print_footer_scripts', 'underline_tag_add_quicktags' );    
							?>
						<!--<textarea id="mysticky_bar_text" class="mystickyinput" name="mysticky_option_welcomebar[mysticky_welcomebar_bar_text]" rows="4" style="display: none;"><?php echo stripslashes($welcomebar['mysticky_welcomebar_bar_text']);?> </textarea>-->
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Show an X Button', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose if you want to show an X button to close the welcome bar or not or desktop and mobile devices</p></span>	
						</label>
						<div class="mysticky-welcomebar-setting-content-right">
							<label>
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_x_desktop]" value= "desktop" type="checkbox" <?php checked( @$welcomebar['mysticky_welcomebar_x_desktop'], 'desktop' );?> />
								<?php _e( 'Desktop', 'mystickymenu' );?>
							</label>
							<label>
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_x_mobile]" value= "mobile" type="checkbox" <?php checked( @$welcomebar['mysticky_welcomebar_x_mobile'], 'mobile' );?> />
								<?php _e( 'Mobile', 'mystickymenu' );?>
							</label>
							<div class="x-color-wrap"><label>X Color</label>
							<div class="mysticky-welcomebar-colorpicker color-x-input">
								<input type="text" id="mysticky_welcomebar_xcolor" name="mysticky_option_welcomebar[mysticky_welcomebar_x_color]" class="my-color-field" data-alpha="true" value="<?php echo isset($welcomebar['mysticky_welcomebar_x_color']) ? $welcomebar['mysticky_welcomebar_x_color'] : ''; ?>"></div></div>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Countdown', 'myStickymenu'); ?><span class="dashicons dashicons-clock" style="margin-left:5px;color:#a8aeaf;"></span><div class="html-tooltip side">
							<span class="dashicons dashicons-editor-help"></span>
							<span class="tooltip-text top ">Add a countdown timer element to your Welcome Bar to increase conversion rate, announce flash sales, and more
							<img src="<?php echo MYSTICKYMENU_URL ?>/images/countdown.gif" /><p style="z-index: 99999;"></span></div></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-close-automatically-sec">
							<label for="mysticky-welcomebar-countdown-enabled" class="mysticky-welcomebar-switch">
								<input type="checkbox" id="mysticky-welcomebar-countdown-enabled" name="mysticky_option_welcomebar[mysticky_welcomebar_enable_countdown]" value="1" data-url="<?php echo esc_url($upgarde_url); ?>" />
								<span class="slider"></span>
								
							</label>
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
						</div>
					</div>
					<!-- Collect lead Section  -->
					<div class="mysticky-welcomebar-setting-content">
						<label style="position:relative;"><?php _e('Collect leads', 'myStickymenu'); ?>&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16" style="fill: #a8aeaf;position: absolute;top: 3px;margin-left: 5px;"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"></path></svg> 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;"><?php echo sprintf(__(" Collect the visitor's details such as Name, email address or phone number from the welcome bar. Collected visitor details can be viewed on the %s page","mystickymenu"), '<a href="' .admin_url("admin.php?page=my-sticky-menu-leads"). '" target="_blank">' .__( 'Contact Form Leads', 'mystickymenu') .'</a>');?></p></span>
						</label>
						<div class="mysticky-welcomebar-setting-content-right">
							<label for="mysticky-welcomebar-collectlead-enabled" class="mysticky-welcomebar-switch collect-lead-switch">
								<input type="checkbox" id="mysticky-welcomebar-collectlead-enabled" name="mysticky_option_welcomebar[mysticky_welcomebar_enable_lead]" data-button-text= "<?php echo $welcomebar["mysticky_welcomebar_btn_text"]; ?>" value="1" <?php checked( @$welcomebar['mysticky_welcomebar_enable_lead'], '1' );?>/>
								<span class="slider"></span>
							</label>
						</div>
					</div>
					<div class="mysticky-collect-lead" <?php if( isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] != 1 ):?> style="display:none;" <?php endif;?>>
						<div class="mysticky-welcomebar-setting-content">
							<label><?php _e('Select inputs', 'myStickymenu'); ?></label>
							<div class="mysticky-welcomebar-setting-content-right lead_inputs">
								<label>
									<input id="mysticky_lead_input_email" name="mysticky_option_welcomebar[mysticky_welcomebar_lead_input]" value= "email_address" type="radio" <?php checked( @$welcomebar['mysticky_welcomebar_lead_input'], 'email_address' );?> />
									<span><?php _e("Name & email address", 'mystickymenu'); ?></span>
								</label>
								<label>
									<input id="mysticky_lead_input_phone" class="mysticky_lead_input_phone"  name="mysticky_option_welcomebar[mysticky_welcomebar_lead_input]" value="phone" type="radio" <?php checked( @$welcomebar['mysticky_welcomebar_lead_input'], 'phone' );?> />
									<span><?php _e("Name & phone number", 'mystickymenu'); ?></span>
								</label>				
							</div>
						</div>

						<div class="mysticky-welcomebar-setting-content">
							<label><?php _e('Placeholder for Name', 'myStickymenu'); ?></label>
							<div class="mysticky-welcomebar-setting-content-right">
								<input type="text" class="mysticky_welcome_lead_name_placeholder" autocomplete="off"  value="<?php echo isset($welcomebar['lead_name_placeholder']) ? $welcomebar['lead_name_placeholder'] : ''; ?>" name="mysticky_option_welcomebar[lead_name_placeholder]" id="lead-name-placeholder" />	
							</div>
						</div>

						<div class="mysticky-welcomebar-setting-content" id="lead-email-content" style="display:<?php echo (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] == 'email_address') ? 'flex' : 'none'; ?>">
							<label><?php _e('Placeholder for Email', 'myStickymenu'); ?></label>
							<div class="mysticky-welcomebar-setting-content-right">
								<input type="text" class="mysticky_welcome_lead_email_placeholder" autocomplete="off"  value="<?php echo isset($welcomebar['lead_email_placeholder']) ? $welcomebar['lead_email_placeholder'] : ''; ?>" name="mysticky_option_welcomebar[lead_email_placeholder]" id="lead-email-placeholder" />	
							</div>
						</div>

						<div class="mysticky-welcomebar-setting-content" id="lead-phone-content" style="display:<?php echo (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] == 'phone') ? 'flex' : 'none'; ?>">
							<label><?php _e('Placeholder for Phone', 'myStickymenu'); ?></label>
							<div class="mysticky-welcomebar-setting-content-right">
								<input type="text" class="mysticky_welcome_lead_phone_placeholder" autocomplete="off"  value="<?php echo isset($welcomebar['lead_phone_placeholder']) ? $welcomebar['lead_phone_placeholder'] : ''; ?>" name="mysticky_option_welcomebar[lead_phone_placeholder]" id="lead-phone-placeholder" />	
							</div>
						</div>
						
						<div class="mysticky-welcomebar-setting-content">
							<label for="mysticky_welcomebar_show_success_message">
								<?php _e( 'Show success message', 'mystickymenu');?>
							</label>
							<div class="mysticky-welcomebar-setting-content-right" style="margin-top: 8px;">
								<label for="mysticky_welcomebar_show_success_message" class="mysticky-welcomebar-switch">
									<input name="mysticky_option_welcomebar[mysticky_welcomebar_show_success_message]" id="mysticky_welcomebar_show_success_message" value= "1" type="checkbox" <?php checked( @$welcomebar['mysticky_welcomebar_show_success_message'], '1' );?> />
									<span class="slider"></span>
								</label>
							</div>
						</div>
						<div id="mysticky-welcomebar-thankyou-wrap" class="mysticky-welcomebar-setting-content" <?php if ( !isset($welcomebar['mysticky_welcomebar_show_success_message']) ) : ?> style="display:none;" <?php endif;?>>
							<label><?php _e('Thank You Text', 'myStickymenu'); ?></label>
							
							<?php $mysticky_welcomebar_thankyou_screen_text = (isset($welcomebar['mysticky_welcomebar_thankyou_screen_text'])) ? $welcomebar['mysticky_welcomebar_thankyou_screen_text'] : 'Thank you for submitting the form' ; ?>
							<div class="mysticky-welcomebar-setting-content-right">
								<?php 
								$settings = array(
									'media_buttons' => false, 
									'textarea_name' => 'mysticky_option_welcomebar[mysticky_welcomebar_thankyou_screen_text]',
									'tinymce' => false,
									'quicktags' => array(
										'buttons' => 'strong,em,link'
									)
								);
								wp_editor( stripslashes($mysticky_welcomebar_thankyou_screen_text), 'mysticky_thankyou_screen_text', $settings ); 
								?>								
							</div>
						</div>

						<div class="mysticky-welcomebar-setting-content">
							<label  style="width:351px;">
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_send_email_lead]" id="send_lead_email_enable" data-url="<?php echo esc_url($upgarde_url); ?>" value= "1" type="checkbox" /><?php _e( 'Send leads to email', 'mystickymenu');?>
								<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
							</label>	
						</div>	
					</div>			
					<!-- Coupon Section Start  -->
					<div class="mysticky-welcomebar-setting-content">
						<label class="bagicon"><?php _e('Show Coupons', 'myStickymenu'); ?> &nbsp;<img src="<?php echo MYSTICKYMENU_URL; ?>/images/shopyicon.svg" />
						<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;"><?php _e("Add a coupon to your welcome bar. Users can click on the coupon, copy it and use it on your website","mystickymenu");?><br><img src="<?php echo MYSTICKYMENU_URL ?>/images/show-coupon-ss.png" style="width:100%;"/></p></span>
					</label>
						<div class="mysticky-welcomebar-setting-content-right" style="margin-top: 8px;">
							<label for="mysticky-welcomebar-showcoupon-enabled" class="mysticky-welcomebar-switch showcoupon-switch">
								<input type="checkbox" id="mysticky-welcomebar-showcoupon-enabled" name="mysticky_option_welcomebar[mysticky_welcomebar_enable_coupon]" data-url="<?php echo esc_url($upgarde_url); ?>"  value="1"/>
								<span class="slider"></span>
							</label>
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
						</div>
					</div>
				</div>
				<div class="mysticky-welcomebar-setting-block">
					<div class="mysticky-welcomebar-subheader-title">
						<h4><?php _e('Button Settings', 'myStickymenu'); ?></h4>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Show a Button On', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose whether you want to display a button on your welcome bar or not on desktop and mobile devices</p></span>	
						</label>
						<div class="mysticky-welcomebar-setting-content-right">
							<label>
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_btn_desktop]" value= "desktop" type="checkbox" <?php checked( @$welcomebar['mysticky_welcomebar_btn_desktop'], 'desktop' );?> />
								<?php _e( 'Desktop', 'mystickymenu' );?>
							</label>
							<label>
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_btn_mobile]" value= "mobile" type="checkbox"<?php checked( @$welcomebar['mysticky_welcomebar_btn_mobile'], 'mobile' );?> />
								<?php _e( 'Mobile', 'mystickymenu' );?>
							</label>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Button Color', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-colorpicker mysticky_welcomebar_btn_color">
							<input type="text" id="mysticky_welcomebar_btncolor" name="mysticky_option_welcomebar[mysticky_welcomebar_btncolor]" class="my-color-field" data-alpha="true" value="<?php echo esc_attr($welcomebar['mysticky_welcomebar_btncolor']);?>" />
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Button Text Color', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-colorpicker mysticky_welcomebar_btn_color">
							<input type="text" id="mysticky_welcomebar_btntxtcolor" name="mysticky_option_welcomebar[mysticky_welcomebar_btntxtcolor]" class="my-color-field" data-alpha="true" value="<?php echo esc_attr($welcomebar['mysticky_welcomebar_btntxtcolor']);?>" />
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Button Text', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right welcomebar-text-button">
							<input type="text" id="mysticky_welcomebar_btn_text" class="mystickyinput mysticky_welcomebar_disable" name="mysticky_option_welcomebar[mysticky_welcomebar_btn_text]" value="<?php echo stripslashes($welcomebar['mysticky_welcomebar_btn_text']);?>" />
						</div>
					</div>
					<!-- -->
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Attention Effect', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right">
							<div class="mysticky-welcomebar-setting-attention">
								<select name="mysticky_option_welcomebar[mysticky_welcomebar_attentionselect]" class="mysticky-welcomebar-attention mysticky_welcomebar_disable">
									<option value="default" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], '	' ); ?>><?php _e( 'None', 'myStickymenu' );?></option>
									<option value="flash" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], 'flash' ); ?>><?php _e( 'Flash', 'myStickymenu' );?></option>
									<option value="shake" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], 'shake' ); ?>><?php _e( 'Shake', 'myStickymenu' );?></option>
									<option value="swing" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], 'swing' ); ?>><?php _e( 'Swing', 'myStickymenu' );?></option>
									<option value="tada" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], 'tada' ); ?>><?php _e( 'Tada', 'myStickymenu' );?></option>
									<option value="heartbeat" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], 'heartbeat' ); ?>><?php _e( 'Heartbeat', 'myStickymenu' );?></option>
									<option value="wobble" <?php selected( @$welcomebar['mysticky_welcomebar_attentionselect'], 'wobble' ); ?>><?php _e( 'Wobble', 'myStickymenu' );?></option>
								</select>
							</div>
						</div>
					</div>
					<!-- -->
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Action On Button Click', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Select what you'd like to happen when a visitor clicks on the button <br/>Redirect the visitor to another URL - your visitor will be redirected to another URL after they click on the button (for example, a specific product or latest collection) <br/>Close the Welcome Bar - after they user clicks on the button, the Welcome Bar will be closed <br/>Launch a Poptin pop-up - when the user clicks on the button, a Poptin pop-up will be launched. You need to first create a free Poptin account (link on "free Poptin account" to <a href='https://www.poptin.com/?utm_source=msm' target="_blank">https://www.poptin.com/?utm_source=msm</a>) and set up your pop-ups <br/>Show a thank-you screen - show a thank you screen after the user clicks on a button with different text from your Welcome Bar text</p></span>		
						</label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-setting-redirect-wrap">
							<div class="mysticky-welcomebar-setting-action">
								<select name="mysticky_option_welcomebar[mysticky_welcomebar_actionselect]" class="mysticky-welcomebar-action mysticky_welcomebar_disable">
									<option value="redirect_to_url" <?php selected( @$welcomebar['mysticky_welcomebar_actionselect'], 'redirect_to_url' ); ?>><?php _e( 'Redirect the visitor to another URL', 'myStickymenu' );?></option>
									<option value="close_bar" <?php selected( @$welcomebar['mysticky_welcomebar_actionselect'], 'close_bar' ); ?>><?php _e( 'Close the Welcome Bar', 'myStickymenu' );?></option>
									<option value="poptin_popup" <?php selected( @$welcomebar['mysticky_welcomebar_actionselect'], 'poptin_popup' ); ?> ><?php _e( 'Launch a Poptin pop-up', 'myStickymenu' );?></option>
									<option value="thankyou_screen" data-href="<?php echo esc_url($upgarde_url); ?>"><?php _e( 'Show a thank-you screen (Pro Feature)', 'myStickymenu' );?></option>
								</select>
							</div>
							
						</div>
					</div>
					
					<div class="mysticky-welcomebar-poptin-popup" <?php if ( $welcomebar['mysticky_welcomebar_actionselect'] != 'poptin_popup' ) : ?> style="display:none;" <?php endif;?>>						
						<div class="mysticky-welcomebar-setting-content">
							<p class="mysticky-welcomebar-poptin-content" >Sign up at <a href="https://www.poptin.com/?utm_source=msm" target="_blank">Poptin</a> for free and launch pop-ups on <a href="https://help.poptin.com/article/show/72942-how-to-show-a-poptin-when-the-visitor-clicks-on-a-button-link-on-your-site" target="_blank">click</a>							
							</p>							
						</div>
						<div class="mysticky-welcomebar-setting-content">
							<label><?php _e('Poptin pop-up direct link', 'myStickymenu'); ?></label>
							<div class="mysticky-welcomebar-setting-content-right">
								<input type="text" id="mysticky_welcomebar_poptin_popup_link" class="mystickyinput mysticky_welcomebar_disable" name="mysticky_option_welcomebar[mysticky_welcomebar_poptin_popup_link]" value="<?php echo (isset($welcomebar['mysticky_welcomebar_poptin_popup_link'])) ? $welcomebar['mysticky_welcomebar_poptin_popup_link'] : '';?>" placeholder="<?php echo esc_url("https://app.popt.in/APIRequest/click/some_id_here"); ?>"  />
								<input type="hidden" id="welcome_save_anyway"  value='' />
							</div>
						</div>
					</div>
					<!-- -->
					
					<?php 
						if( is_email($welcomebar['mysticky_welcomebar_redirect']) ){
							if( strpos($welcomebar['mysticky_welcomebar_redirect'], 'mailto:') === false ){
								$welcomebar['mysticky_welcomebar_redirect'] = "mailto:".$welcomebar['mysticky_welcomebar_redirect'];
							}
						}
					?>
					<div class="mysticky-welcomebar-setting-content mysticky-welcomebar-redirect-container" <?php if ( $welcomebar['mysticky_welcomebar_actionselect'] != 'redirect_to_url' ) : ?> style="display:none;" <?php endif;?>>
						<label><?php _e('Redirection link', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-setting-action mysticky-welcomebar-redirect" <?php if ( $welcomebar['mysticky_welcomebar_actionselect'] == 'close_bar' ) : ?> style="display:none;" <?php endif;?> >
							<input type="text" id="mysticky_welcomebar_redirect" class="mystickyinput mysticky_welcomebar_disable" name="mysticky_option_welcomebar[mysticky_welcomebar_redirect]" value="<?php echo (is_email($welcomebar['mysticky_welcomebar_redirect'])) ? $welcomebar['mysticky_welcomebar_redirect'] : esc_url($welcomebar['mysticky_welcomebar_redirect']);?>" placeholder="<?php echo esc_url("https://www.yourdomain.com"); ?>"  />
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content mysticky-welcomebar-redirect-container" <?php if ( $welcomebar['mysticky_welcomebar_actionselect'] != 'redirect_to_url' ) : ?> style="display:none;" <?php endif;?>>
						<label><?php _e( 'Open in a new tab', 'mystickymenu' );?></label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-setting-newtab mysticky-welcomebar-redirect"  >
							<label class="mysticky-welcomebar-switch">
								<input name="mysticky_option_welcomebar[mysticky_welcomebar_redirect_newtab]" value= "1" type="checkbox" disabled />
								<span class="slider"></span>
							</label>
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
						</div>
					</div>
					<div class="mysticky-welcomebar-setting-content mysticky-welcomebar-redirect-container" <?php if ( $welcomebar['mysticky_welcomebar_actionselect'] != 'redirect_to_url' ) : ?> style="display:none;" <?php endif;?>>
						<label><?php _e('rel Attribute', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip">
								<a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a>
								<p><?php _e("Add a \"rel\" attribute to the button link. You can use it to add a rel=\"nofollow\", \"sponsored\", or any other \"rel\" attribute option","mystickymenu");?></p>
							</span>
						</label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-setting-newtab mysticky-welcomebar-redirect"  >
							<input type="text" id="mysticky_welcomebar_redirect_rel" class="mystickyinput mysticky_welcomebar_disable unactive_rel_input" name="mysticky_option_welcomebar[mysticky_welcomebar_redirect_rel]" value="" placeholder="" disabled />
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
						</div>
					</div>
					<!-- -->
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Welcome Bar Appearance After Button Click', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;"><?php _e("Choose welcome bar display settings after a visitor click on the button. The \"Don't show the Welcome Bar again for the user\" option is the preferable option if you don't want to annoy your visitors by showing the welcome bar over and over","mystickymenu");?></p></span>
						</label>
						<div class="mysticky-welcomebar-setting-content-right">
							<div class="mysticky-welcomebar-setting-action">
								<select name="mysticky_option_welcomebar[mysticky_welcomebar_aftersubmission]" class="mysticky-welcomebar-aftersubmission mysticky_welcomebar_disable">
									<option value="dont_show_welcomebar" <?php selected( @$welcomebar['mysticky_welcomebar_aftersubmission'], 'dont_show_welcomebar' ); ?>><?php _e( "Don't show the Welcome Bar again for the user", 'myStickymenu' );?></option>
									<option value="show_welcomebar_next_visit" <?php selected( @$welcomebar['mysticky_welcomebar_aftersubmission'], 'show_welcomebar_next_visit' ); ?>><?php _e( 'Show the Welcome Bar again when the user visits the website next time', 'myStickymenu' );?></option>
									<option value="show_welcomebar_every_page" <?php selected( @$welcomebar['mysticky_welcomebar_aftersubmission'], 'show_welcomebar_every_page' ); ?> ><?php _e( 'Show the Welcome Bar when the user refreshes/goes to another page', 'myStickymenu' );?></option>
								</select>
							</div>
						</div>
					</div>
					<!-- -->
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Close Welcome Bar Automatically After Click', 'myStickymenu'); ?>
							<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;"><?php _e("Choose if you'd like the welcome bar to be closed automatically after button submission",'mystickymenu');?></p></span>
						</label>
						<div class="mysticky-welcomebar-setting-content-right mysticky-welcomebar-close-automatically-sec">
							<label for="mysticky-welcomebar-close-automatically-enabled" class="mysticky-welcomebar-switch">
								<input type="checkbox" id="mysticky-welcomebar-close-automatically-enabled" name="mysticky_option_welcomebar[mysticky_welcomebar_enable_automatical]" value="1" data-url="<?php echo esc_url($upgarde_url); ?>"/>
								<span class="slider"></span>
							</label>
							<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
							<div class="mysticky-welcomebar-setting-action" style="display:none;">
								<div class="px-wrap">
									<span><?php _e('Close welcome bar after ', 'myStickymenu'); ?></span>
									<input type="number" class="" min="0" step="1" id="mysticky_welcomebar_triggersec_automatically" name="mysticky_option_welcomebar[mysticky_welcomebar_triggersec_automatically]" value="0">
									<span class="input-px"><?php _e('Sec', 'myStickymenu'); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mysticky-welcomebar-setting-block">
					<div class="mysticky-welcomebar-subheader-title" style="display:flex;">
						<h4><?php _e('Display Rules', 'myStickymenu'); ?></h4>
						<span class="mysticky-custom-fields-tooltip" style="margin-top:5px;"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose if you want to show the welcome bar on desktop or mobile only, or on both</p></span>
					</div>
					<div class="mysticky-welcomebar-setting-content">
						<label><?php _e('Entry effect', 'myStickymenu'); ?></label>
						<div class="mysticky-welcomebar-setting-content-right">
							<?php $welcomebar['mysticky_welcomebar_entry_effect'] = (isset($welcomebar['mysticky_welcomebar_entry_effect']) && $welcomebar['mysticky_welcomebar_entry_effect']!= '') ? $welcomebar['mysticky_welcomebar_entry_effect'] : 'slide-in'; ?>
							<select id="myStickymenu-entry-effect" name="mysticky_option_welcomebar[mysticky_welcomebar_entry_effect]" >
								<option value="none" <?php selected( @$welcomebar['mysticky_welcomebar_entry_effect'], 'none' ); ?>><?php _e( 'No effect', 'myStickymenu' );?></option>
								<option value="slide-in" <?php selected( @$welcomebar['mysticky_welcomebar_entry_effect'], 'slide-in' ); ?>><?php _e( 'Slide in', 'myStickymenu' );?></option>
								<option value="fade" <?php selected( @$welcomebar['mysticky_welcomebar_entry_effect'], 'fade' ); ?>><?php _e( 'Fade', 'myStickymenu' );?></option>
							</select>
						</div>
					</div>
					<div class="mysticky-welcomebar-upgrade-main mysticky_device_upgrade">
						<span class="myStickymenu-upgrade">
							<a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( ' Upgrade Now', 'mystickymenu' );?></a>
						</span>
						<div class="mysticky-welcomebar-setting-content">
							<label><?php _e('Devices', 'myStickymenu'); ?></label>
							<div class="mysticky-welcomebar-setting-content-right">
								<label>
									<input name="mysticky_option_welcomebar[mysticky_welcomebar_device_desktop]" value= "desktop" type="checkbox" checked disabled />
									<?php _e( 'Desktop', 'mystickymenu' );?>
								</label>
								<label>
									<input name="mysticky_option_welcomebar[mysticky_welcomebar_device_mobile]" value= "mobile" type="checkbox" checked disabled />
									<?php _e( 'Mobile', 'mystickymenu' );?>
								</label>
							</div>
						</div>
						<div class="mysticky-welcomebar-setting-content">
							<label><?php _e('Trigger', 'myStickymenu'); ?>
								<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose when you'd like the welcome bar to appear on your site</p></span>
							</label>
							<div class="mysticky-welcomebar-setting-content-right">
								<div class="mysticky-welcomebar-setting-action mysticky-welcomebar-trigger-wrap">
									<label>
										<input type="radio" name="mysticky_option_welcomebar[mysticky_welcomebar_trigger]" value="after_a_few_seconds" checked disabled />&nbsp;<?php _e( 'After a few seconds', 'myStickymenu' );?>
									</label>
									<label>
										<input type="radio" name="mysticky_option_welcomebar[mysticky_welcomebar_trigger]" value="after_scroll" disabled />&nbsp;<?php _e( 'After Scroll', 'myStickymenu' );?>
									</label>
								</div>
								<div class="mysticky-welcomebar-setting-action mysticky-welcomebar-triggersec">
									<div class="px-wrap">
										<input type="number" class="" min="0" step="1" id="mysticky_welcomebar_triggersec" name="mysticky_option_welcomebar[mysticky_welcomebar_triggersec]" value="0" disabled />
										<span class="input-px"><?php echo ( isset($welcomebar['mysticky_welcomebar_trigger']) && $welcomebar['mysticky_welcomebar_trigger'] == 'after_scroll' ) ? '%' : 'Sec'; ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="mysticky-welcomebar-setting-content">
							<label><?php _e('Expiry date', 'myStickymenu'); ?>
								<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">Choose a date if you'd like the welcome bar to expire on a specific date. For example, if your welcome bar advertises a time-limited offer only</p></span>
							</label>
							<div class="mysticky-welcomebar-setting-content-right">
								<div class="mysticky-welcomebar-expirydate">
									<input type="text" class="mysticky_welcome_expiry1" id="mysticky_welcomebar_expirydate" name="mysticky_option_welcomebar[mysticky_welcomebar_expirydate]" placeholder="<?php _e('No expiry date', 'myStickymenu'); ?>" value="" disabled />
									<span class="dashicons dashicons-calendar-alt"></span>
								</div>
								<div class="mysticky-welcomebar-expirydate-gmt">
									<select name="mysticky_option_welcomebar[mysticky_welcomebar_expirydate_gmt]" id="mysticky_welcomebar_expirydate_gmt" disabled>
										<?php for( $i=12; $i>=-12;$i-- ) { ?>
										<option value="<?php echo esc_attr($i); ?>"><?php echo "GMT " . ( $i>0 ? "+" : "" ).( $i ) ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="mysticky-welcomebar-setting-content show-on-apper">
							<label><?php _e('Page targeting', 'myStickymenu'); ?>
								<span class="mysticky-custom-fields-tooltip"><a href="javascript:void(0);" class="mysticky-tooltip mysticky-new-custom-btn"><i class="dashicons dashicons-editor-help"></i></a><p style="z-index: 99999;">
									<?php esc_html_e("Add a rule if you want to show or don't show the welcome bar on specific pages only. For example, you can show the welcome bar just on speicifc collections/products","mystickymenu");?></p></span>
							</label>
							<div class="mysticky-welcomebar-setting-content-right">
								<a href="javascript:void(0);" class="create-rule" id="create-rule"><?php esc_html_e( "Add Rule", "mystickyelements" );?></a>
							</div>
							<?php 
							$url_options = array(
								'page_contains' => 'pages that contain',
								'page_has_url' => 'a specific page',
								'page_start_with' => 'pages starting with',
								'page_end_with' => 'pages ending with',
							);
							?>
							<div class="mysticky-welcomebar-page-options-html" style="display: none">
								<div class="mysticky-welcomebar-page-option">
									<div class="url-content">
										<div class="mysticky-welcomebar-url-select">
											<select name="" id="url_shown_on___count___option">
												<option value="show_on"><?php esc_html_e("Show on", "mysticky" );?></option>
												<option value="not_show_on"><?php esc_html_e("Don't show on", "mysticky" );?></option>
											</select>
										</div>
										<div class="mysticky-welcomebar-url-option">
											<select class="mysticky-welcomebar-url-options" name="" id="url_rules___count___option">
												<option selected="selected" value=""><?php esc_html_e("Select Rule", "mysticky" );?></option>
												<?php foreach($url_options as $key=>$value) {
													echo '<option value="'.$key.'">'.$value.'</option>';
												} ?>
											</select>
										</div>
										<div class="mysticky-welcomebar-url-box">
											<span class='mysticky-welcomebar-url'><?php echo site_url("/"); ?></span>
										</div>
										<div class="mysticky-welcomebar-url-values">
											<input type="text" value="" name="mysticky_option_welcomebar[page_settings][__count__][value]" id="url_rules___count___value" disabled />
										</div>
										<div class="clear"></div>
									</div>
									<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade-now" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
								</div>
							</div>
							<div class="mysticky-welcomebar-page-options mysticky-welcomebar-setting-content-right" id="mysticky-welcomebar-page-options" style="display:none"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="mysticky-welcomebar-setting-right">
				<div class="mysticky-welcomebar-backword-page">
					<a href="<?php echo admin_url("admin.php?page=my-stickymenu-welcomebar");?>"><span class="dashicons dashicons-arrow-left-alt2 back-dashboard" style="color: unset;font-size: 17px;"></span> Back to Dashboard</a>
				</div>
				<div class="mysticky-welcomebar-header-title">
					<h3><?php _e('Preview', 'mystickyelements'); ?></h3>
				</div>
				<div class="mysticky-welcomebar-preview-screen">
					<?php if(isset($welcomebar['mysticky_welcomebar_font']) && $welcomebar['mysticky_welcomebar_font'] != '' ):?>
					<link href="https://fonts.googleapis.com/css?family=<?php echo esc_attr($welcomebar['mysticky_welcomebar_font']) ?>:400,600,700|Lato:400,500,600,700" rel="stylesheet" type="text/css" class="sfba-google-font">
					<?php endif; ?>
					<div class="mysticky-welcomebar-fixed mysticky-welcomebar-display-desktop <?php echo esc_attr($display_main_class); ?>" >
						<div class="mysticky-welcomebar-fixed-wrap">
							<?php 
								$content_width = (isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] === '1') ? '90%'  : '75%';
							?>	
							<div class="mysticky-welcomebar-content" style="width:<?php  echo $content_width; ?>">
								<?php echo wpautop(isset($welcomebar['mysticky_welcomebar_bar_text'])? stripslashes($welcomebar['mysticky_welcomebar_bar_text']) :"Get 30% off your first purchase");?>
							</div>

							<div class="mysticky-welcomebar-lead-content" <?php if((isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] != 1)) :?> style="display:none;" <?php endif; ?>>

								<input type="text" class="preview-lead-name" placeholder="<?php echo $welcomebar['lead_name_placeholder'];?>"/>
								<input type="text" class="preview-lead-email" placeholder="<?php echo $welcomebar['lead_email_placeholder'];?>" style="display:<?php echo (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] == 'email_address') ? 'flex' : 'none';?>"/>
								<input type="text" class="preview-lead-phone" placeholder="<?php echo $welcomebar['lead_phone_placeholder'];?>" style="display:<?php echo (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] == 'phone') ? 'flex' : 'none';?>"/>

							</div>

							<div class="mysticky-welcomebar-btn <?php  if(isset( $welcomebar['mysticky_welcomebar_enable_lead'] ) && $welcomebar['mysticky_welcomebar_enable_lead'] == 1): ?>collect-lead<?php endif; ?>">
								<?php 
									$mysticky_welcomebar_btn_text =  isset($welcomebar['mysticky_welcomebar_btn_text']) ? stripslashes($welcomebar['mysticky_welcomebar_btn_text']) : "Got it!";
								?>
										
								<a href="javascript:void(0)"><?php echo stripslashes($mysticky_welcomebar_btn_text);?></a>
							</div>

							<?php 
								$x_color = (isset($welcomebar['mysticky_welcomebar_x_color']) && $welcomebar['mysticky_welcomebar_x_color'] != '') ? $welcomebar['mysticky_welcomebar_x_color'] : '#000000';
							?>
							<a href="javascript:void(0)" class="mysticky-welcomebar-close" style="color:<?php echo $x_color;?>">X</a>						
						</div>
					</div>
				</div>
				<div class="timer-message" <?php if(isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] != 1):?> style="display:none;"<?php endif;?>>
					<p><span class="dashicons dashicons-info"></span> The elements will be displayed in 1-line on your actual website. <a class="save_change" href="javascript:void(0);">Save changes</a> and <a href="<?php echo site_url();?>" target="_blank" class="visit_site_link"><span class="dashicons dashicons-migrate" style="color: #2271b1 !important;"></span> visit your website</a> to check how it’d look like</p>
				</div>
			</div>
		</div>
		<div class="mysticky-welcomebar-submit">
			<input type="submit" name="submit" id="submit" class="button button-primary welcombar_save" value="<?php _e('Save', 'mystickymenu');?>">
			<input type="submit" name="submit" id="submit" class="button button-primary save_view_dashboard" style="width: auto;" value="<?php _e('SAVE & VIEW DASHBOARD', 'mystickymenu');?>">
		</div>
		<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
		<input type="hidden" name="active_tab_element" value="1">
		<input type="hidden" name="widget_no" value="0">
		<input type="hidden" id="save_welcome_bar" name="save_welcome_bar" >

	</form>
	<form class="mysticky-welcomebar-form-reset" method="post" action="#">
		<div class="mysticky-welcomebar-submit">
			<input type="submit" name="mysticky_welcomebar_reset" id="reset" class="button button-secondary" value="<?php _e('Reset', 'mystickymenu');?>">
		</div>
		<input type="hidden" name="nonce_reset" value="<?php echo esc_attr($nonce_reset); ?>">
		<input type="hidden" name="active_tab_element" value="1">
	</form>
	
	<div class="mystickymenu-action-popup new-center" id="welcomebar-save-confirm" style="display:none;">
		<div class="mystickymenu-action-popup-header">
			<h3><?php esc_html_e("Welcome Bar is currently off","mystickymenu"); ?></h3>
			<span class="dashicons dashicons-no-alt close-button" data-from = "welcombar-confirm"></span>
		</div>
		<div class="mystickymenu-action-popup-body">
			<p><?php esc_html_e("Your Welcome Bar is currently turned off, would you like to save and show it on your site?","mystickymenu"); ?></p>
		</div>
		<div class="mystickymenu-action-popup-footer">
			<button type="button" class="btn-enable btn-nevermind-status" id="welcombar_sbmtbtn_off" ><?php esc_html_e("Just save and keep it off","mystickymenu"); ?></button>
			<button type="button" class="btn-disable-cancel btn-turnoff-status button-save-turnon" id="welcomebar_yes_sbmtbtn" style="background:#00c67c;border-color:#00c67c;"><?php esc_html_e("Save & Turn on Welcome Bar","mystickymenu"); ?></button>
		</div>
	</div>
	<div class="mystickymenupopup-overlay" id="welcombar-sbmtvalidation-overlay-popup"></div>
	
	<div id="mysticky-welcomebar-poptin-popup-confirm" style="display:none;" title="<?php esc_attr_e( 'Poptin pop-up is not configured properly', 'mystickymenu' ); ?>">
		<p>
			Seems like you haven't filled up the Poptin pop-up direct link field properly. Please <a href="https://help.poptin.com/article/show/72942-how-to-show-a-poptin-when-the-visitor-clicks-on-a-button-link-on-your-site" target="_blank">check the guide</a> to know how you can copy direct link of a pop-up from Poptin.
		</p>
	</div>
	<script>
	jQuery(".mysticky-welcomebar-fixed").on(
		"animationend MSAnimationEnd webkitAnimationEnd oAnimationEnd",
		function() {
			jQuery(this).removeClass("animation-start");
		}
	);
	jQuery(document).ready(function() { 
		var container = jQuery(".mysticky-welcomebar-fixed");
        var refreshId = setInterval(function() {
            container.addClass("animation-start");
        }, 3500);
    });
	</script>


	 <style>
		.mysticky-welcomebar-fixed {
			background-color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_bgcolor']); ?>;
			font-family: <?php echo ($welcomebar['mysticky_welcomebar_font']); ?>;
			position: absolute;
			left: 0;
			right: 0;
			opacity: 0;
			z-index: 9;
			-webkit-transition: all 1s ease 0s;
			-moz-transition: all 1s ease 0s;
			transition: all 1s ease 0s;
		}

	
		.mysticky-welcomebar-fixed-wrap {
			min-height: 60px;
			padding: 20px 10px 20px 10px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.mysticky-welcomebar-preview-mobile-screen .mysticky-welcomebar-fixed{
			padding: 0 25px;
		}
		.mysticky-welcomebar-position-top {
			top:0;
		}
		.mysticky-welcomebar-position-bottom {
			bottom:0;
		}
		.mysticky-welcomebar-position-top.mysticky-welcomebar-entry-effect-slide-in {
			top: -80px;
		}
		.mysticky-welcomebar-position-bottom.mysticky-welcomebar-entry-effect-slide-in {
			bottom: -80px;
		}
		.mysticky-welcomebar-display-desktop.mysticky-welcomebar-position-top.mysticky-welcomebar-entry-effect-slide-in.entry-effect {
			top:0;
			opacity: 1;
		}
		.mysticky-welcomebar-display-desktop.mysticky-welcomebar-position-bottom.mysticky-welcomebar-entry-effect-slide-in.entry-effect {
			bottom:0;
			opacity: 1;
		}
		.mysticky-welcomebar-entry-effect-fade {
			opacity: 0;
		}
		.mysticky-welcomebar-display-desktop.mysticky-welcomebar-entry-effect-fade.entry-effect {
			opacity: 1;
		}
		.mysticky-welcomebar-entry-effect-none {
			display: none;
		}
		.mysticky-welcomebar-display-desktop.mysticky-welcomebar-entry-effect-none.entry-effect {
			display: block;
			opacity: 1;
		}
		.mysticky-welcomebar-position-top.mysticky-welcomebar-entry-effect-slide-in.entry-effect.mysticky-welcomebar-fixed {
			top: 0;			
		}
		.mysticky-welcomebar-position-bottom.mysticky-welcomebar-entry-effect-slide-in.entry-effect.mysticky-welcomebar-fixed {
			bottom: 0;
		}		
		.mysticky-welcomebar-fixed .mysticky-welcomebar-content p a,
		.mysticky-welcomebar-fixed .mysticky-welcomebar-content p {
			color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_bgtxtcolor']); ?>;
			font-size: <?php echo esc_attr($welcomebar['mysticky_welcomebar_fontsize']); ?>px;
			font-family: inherit;
			margin: 0;
			padding: 0;
			line-height: 1.2;
			font-weight: 400;
		}
		/*.mysticky-welcomebar-fixed .mysticky-welcomebar-btn {
			padding-left: 30px;
			margin: 0 30px;
			display: none;
		}*/
		.mysticky-welcomebar-fixed.mysticky-site-front.mysticky-welcomebar-btn-desktop .mysticky-welcomebar-btn {
			display: block;
			margin-left:5px;
		}
		.mysticky-welcomebar-fixed .mysticky-welcomebar-btn a {
			background-color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_btncolor']); ?>;
			font-family: inherit;
			color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_btntxtcolor']); ?>;
			border-radius: 4px;
			text-decoration: none;
			display: inline-block;
			vertical-align: top;
			line-height: 1.2;
			font-size: <?php echo esc_attr($welcomebar['mysticky_welcomebar_fontsize']) ?>px;
			font-weight: 400;
			padding: 5px 15px;
			white-space: nowrap;
			text-align: center;
		}
		.mysticky-welcomebar-fixed .mysticky-welcomebar-btn a:hover {
			/*opacity: 0.7;*/
			-moz-box-shadow: 1px 2px 4px rgba(0, 0, 0,0.5);
			-webkit-box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.5);
			box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.5);
		}

		@media only screen and (max-width: 1024px) {
			.mysticky-welcomebar-fixed {
				padding: 0 10px 0 10px;
			}
		}
		
		/* Animated Buttons */
		.mysticky-welcomebar-btn a {
			-webkit-animation-duration: 1s;
			animation-duration: 1s;
		}
		@-webkit-keyframes flash {
			from,
			50%,
			to {
				opacity: 1;
			}

			25%,
			75% {
				opacity: 0;
			}
		}
		@keyframes flash {
			from,
			50%,
			to {
				opacity: 1;
			}

			25%,
			75% {
				opacity: 0;
			}
		}
		.mysticky-welcomebar-attention-flash.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: flash;
			animation-name: flash;
		}
		
		@keyframes shake {
			from,
			to {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}

			10%,
			30%,
			50%,
			70%,
			90% {
				-webkit-transform: translate3d(-10px, 0, 0);
				transform: translate3d(-10px, 0, 0);
			}

			20%,
			40%,
			60%,
			80% {
				-webkit-transform: translate3d(10px, 0, 0);
				transform: translate3d(10px, 0, 0);
			}
		}

		.mysticky-welcomebar-attention-shake.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: shake;
			animation-name: shake;
		}
		
		@-webkit-keyframes swing {
			20% {
				-webkit-transform: rotate3d(0, 0, 1, 15deg);
				transform: rotate3d(0, 0, 1, 15deg);
			}

			40% {
				-webkit-transform: rotate3d(0, 0, 1, -10deg);
				transform: rotate3d(0, 0, 1, -10deg);
			}

			60% {
				-webkit-transform: rotate3d(0, 0, 1, 5deg);
				transform: rotate3d(0, 0, 1, 5deg);
			}

			80% {
				-webkit-transform: rotate3d(0, 0, 1, -5deg);
				transform: rotate3d(0, 0, 1, -5deg);
			}
	
			to {
				-webkit-transform: rotate3d(0, 0, 1, 0deg);
				transform: rotate3d(0, 0, 1, 0deg);
			}
		}

		@keyframes swing {
			20% {
				-webkit-transform: rotate3d(0, 0, 1, 15deg);
				transform: rotate3d(0, 0, 1, 15deg);
			}

			40% {
				-webkit-transform: rotate3d(0, 0, 1, -10deg);
				transform: rotate3d(0, 0, 1, -10deg);
			}

			60% {
				-webkit-transform: rotate3d(0, 0, 1, 5deg);
				transform: rotate3d(0, 0, 1, 5deg);
			}

			80% {
				-webkit-transform: rotate3d(0, 0, 1, -5deg);
				transform: rotate3d(0, 0, 1, -5deg);
			}

			to {
				-webkit-transform: rotate3d(0, 0, 1, 0deg);
				transform: rotate3d(0, 0, 1, 0deg);
			}
		}

		.mysticky-welcomebar-attention-swing.animation-start .mysticky-welcomebar-btn a {
			-webkit-transform-origin: top center;
			transform-origin: top center;
			-webkit-animation-name: swing;
			animation-name: swing;
		}
		
		@-webkit-keyframes tada {
			from {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}

			10%,
			20% {
				-webkit-transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
			}

			30%,
			50%,
			70%,
			90% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
			}

			40%,
			60%,
			80% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
			}

			to {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}
		}

		@keyframes tada {
			from {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}

			10%,
			20% {
				-webkit-transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
			}

			30%,
			50%,
			70%,
			90% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
			}

			40%,
			60%,
			80% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
			}

			to {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}
		}

		.mysticky-welcomebar-attention-tada.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: tada;
			animation-name: tada;
		}
		
		@-webkit-keyframes heartBeat {
			0% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			14% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			28% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			42% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			70% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}
		}

		@keyframes heartBeat {
			0% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			14% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			28% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			42% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			70% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}
		}

		.mysticky-welcomebar-attention-heartbeat.animation-start .mysticky-welcomebar-btn a {
		  -webkit-animation-name: heartBeat;
		  animation-name: heartBeat;
		  -webkit-animation-duration: 1.3s;
		  animation-duration: 1.3s;
		  -webkit-animation-timing-function: ease-in-out;
		  animation-timing-function: ease-in-out;
		}
		
		@-webkit-keyframes wobble {
			from {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}

			15% {
				-webkit-transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
				transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
			}

			30% {
				-webkit-transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
				transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
			}

			45% {
				-webkit-transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
				transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
			}

			60% {
				-webkit-transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
				transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
			}

			75% {
				-webkit-transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
				transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
			}

			to {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}
		}

		@keyframes wobble {
			from {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}

			15% {
				-webkit-transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
				transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
			}

			30% {
				-webkit-transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
				transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
			}

			45% {
				-webkit-transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
				transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
			}

			60% {
				-webkit-transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
				transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
			}

			75% {
				-webkit-transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
				transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
			}

			to {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}
		}
		
		.mysticky-welcomebar-attention-wobble.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: wobble;
			animation-name: wobble;
		}
	</style> 

	<?php
}

function mysticky_welcomebar_pro_widget_default_fields() {
	return array(
			'mysticky_welcomebar_position' 			=> 'top',
			'mysticky_welcomebar_height' 			=> '60',
			'mysticky_welcomebar_bgcolor' 			=> '#03ed96',
			'mysticky_welcomebar_bgtxtcolor' 		=> '#000000',
			'mysticky_welcomebar_font' 				=> 'Poppins',
			'mysticky_welcomebar_fontsize' 			=> '16',
			'mysticky_welcomebar_bar_text' 			=> 'Get 30% off your first purchase',
			'mysticky_welcomebar_x_desktop' 		=> 'desktop',
			'mysticky_welcomebar_x_mobile' 			=> 'mobile',
			'mysticky_welcomebar_btn_desktop' 		=> 'desktop',
			'mysticky_welcomebar_btn_mobile' 		=> 'mobile',
			'mysticky_welcomebar_btncolor' 			=> '#000000',
			'mysticky_welcomebar_btntxtcolor' 		=> '#ffffff',
			'mysticky_welcomebar_btn_text' 			=> 'Got it!',
			'mysticky_welcomebar_actionselect'		=> 'close_bar',
			'mysticky_welcomebar_aftersubmission'	=> 'dont_show_welcomebar',
			'mysticky_welcomebar_redirect' 			=> 'https://www.yourdomain.com',
			'mysticky_welcomebar_redirect_newtab' 	=> '',
			'mysticky_welcomebar_redirect_rel' 		=> '',
			'mysticky_welcomebar_device_desktop'	=> 'desktop',
			'mysticky_welcomebar_device_mobile' 	=> 'mobile',
			'mysticky_welcomebar_entry_effect'		=> 'slide-in',
			'mysticky_welcomebar_trigger' 			=> 'after_a_few_seconds',
			'mysticky_welcomebar_triggersec' 		=> '0',
			'mysticky_welcomebar_expirydate' 		=> '',
			'mysticky_welcomebar_page_settings' 	=> '',
			'mysticky_welcomebar_timer_position' 	=> 'left',
			'mysticky_welcomebar_timer_bgcolor' 	=> '#000000',
			'mysticky_welcomebar_timer_textcolor' 	=> '#ffffff',
			'lead_name_placeholder' 				=> 'Name',
			'lead_email_placeholder' 				=> 'Email',
			'lead_phone_placeholder' 				=> 'Phone',
			'mysticky_welcomebar_enable_lead' 		=> '0',
	);
}

function mysticky_welcome_bar_frontend(){
	global $wp;
	$welcomebar = get_option( 'mysticky_option_welcomebar' );

	if ( ( isset($welcomebar['mysticky_welcomebar_expirydate']) && $welcomebar['mysticky_welcomebar_expirydate'] !='' && strtotime( date('m/d/Y')) > strtotime($welcomebar['mysticky_welcomebar_expirydate']) ) || !isset($welcomebar['mysticky_welcomebar_enable'] ) || (isset($welcomebar['mysticky_welcomebar_enable']) && $welcomebar['mysticky_welcomebar_enable'] == 0) ) {
		return;
	}
	
	$mysticky_welcomebar_showx_desktop = $mysticky_welcomebar_showx_mobile = '';
	$mysticky_welcomebar_btn_desktop = $mysticky_welcomebar_btn_mobile = '';
	$mysticky_welcomebar_display_desktop = $mysticky_welcomebar_display_mobile = '';
	if( isset($welcomebar['mysticky_welcomebar_x_desktop']) ) {
		$mysticky_welcomebar_showx_desktop = ' mysticky-welcomebar-showx-desktop';
	}
	if( isset($welcomebar['mysticky_welcomebar_x_mobile']) ) {
		$mysticky_welcomebar_showx_mobile = ' mysticky-welcomebar-showx-mobile';
	}
	if( isset($welcomebar['mysticky_welcomebar_btn_desktop']) ) {
		$mysticky_welcomebar_btn_desktop = ' mysticky-welcomebar-btn-desktop';
	}
	if( isset($welcomebar['mysticky_welcomebar_btn_mobile']) ) {
		$mysticky_welcomebar_btn_mobile = ' mysticky-welcomebar-btn-mobile';
	}
	
	$welcomebar['mysticky_welcomebar_position'] = (isset($welcomebar['mysticky_welcomebar_position'])) ? $welcomebar['mysticky_welcomebar_position'] : 'top';
	
	$welcomebar['mysticky_welcomebar_height'] = (isset($welcomebar['mysticky_welcomebar_height'])) ? $welcomebar['mysticky_welcomebar_height'] : '60';
	$welcomebar['mysticky_welcomebar_actionselect'] = (isset($welcomebar['mysticky_welcomebar_actionselect'])) ? $welcomebar['mysticky_welcomebar_actionselect'] : 'close_bar';
	$welcomebar['mysticky_welcomebar_aftersubmission'] = (isset($welcomebar['mysticky_welcomebar_aftersubmission'])) ? $welcomebar['mysticky_welcomebar_aftersubmission'] : 'dont_show_welcomebar';
	$welcomebar['mysticky_welcomebar_attentionselect'] = (isset($welcomebar['mysticky_welcomebar_attentionselect'])) ? $welcomebar['mysticky_welcomebar_attentionselect'] : '';
	
	$display = ' mysticky-welcomebar-attention-'.$welcomebar['mysticky_welcomebar_attentionselect'];
	$display_entry_effect = (isset($welcomebar['mysticky_welcomebar_entry_effect'])) ? ' mysticky-welcomebar-entry-effect-'.$welcomebar['mysticky_welcomebar_entry_effect'] : ' mysticky-welcomebar-entry-effect-slide-in';
	$mysticky_welcomebar_display_desktop = ' mysticky-welcomebar-display-desktop';
	$mysticky_welcomebar_display_mobile = ' mysticky-welcomebar-display-mobile';
	
	
	$display_main_class = "mysticky-welcomebar-position-" . $welcomebar['mysticky_welcomebar_position'] . $mysticky_welcomebar_showx_desktop . $mysticky_welcomebar_showx_mobile . $mysticky_welcomebar_btn_desktop . $mysticky_welcomebar_btn_mobile . $mysticky_welcomebar_display_desktop . $mysticky_welcomebar_display_mobile .$display . $display_entry_effect;


	if( isset($welcomebar['mysticky_welcomebar_enable_lead']) && $welcomebar['mysticky_welcomebar_enable_lead'] == 1 ): 
		$display_main_class .= ' welcombar-contact-lead ';
	endif;

	if( isset($welcomebar['mysticky_welcomebar_actionselect']) ) {
		if( $welcomebar['mysticky_welcomebar_actionselect'] == 'redirect_to_url' ) {
			$mysticky_welcomebar_actionselect_url = ( is_email($welcomebar['mysticky_welcomebar_redirect']) ) ? $welcomebar['mysticky_welcomebar_redirect'] : esc_url( $welcomebar['mysticky_welcomebar_redirect'] );
		} else if( $welcomebar['mysticky_welcomebar_actionselect'] == 'poptin_popup'){
			$mysticky_welcomebar_actionselect_url = esc_url( $welcomebar['mysticky_welcomebar_poptin_popup_link'] );
		} else {
			$mysticky_welcomebar_actionselect_url = 'javascript:void(0)';
		}
	}

	?>
	<div class="mysticky-welcomebar-fixed mysticky-site-front <?php echo esc_attr($display_main_class); ?>"  data-after-triger="after_a_few_seconds" data-triger-sec="0" data-position="<?php echo esc_attr($welcomebar['mysticky_welcomebar_position']);?>" data-height="<?php echo esc_attr($welcomebar['mysticky_welcomebar_height']);?>" data-rediect="<?php echo esc_attr($welcomebar['mysticky_welcomebar_actionselect']);?>" data-aftersubmission="<?php echo esc_attr($welcomebar['mysticky_welcomebar_aftersubmission']);?>">
		<div class="mysticky-welcomebar-fixed-wrap">
			<div class="mysticky-welcomebar-content">
				<?php echo wpautop( isset($welcomebar['mysticky_welcomebar_bar_text'])? stripslashes($welcomebar['mysticky_welcomebar_bar_text']) :"Get 30% off your first purchase" );?>
				
				
			</div>

			<?php if( isset( $welcomebar['mysticky_welcomebar_enable_lead'] ) && $welcomebar['mysticky_welcomebar_enable_lead'] == 1 ): ?>
				<div class="mystickymenu-front mysticky-welcomebar-lead-content">
					<div>
						<input type="text" class="contact-lead-name" id="contact-lead-name-0"  name="contact_lead_name" placeholder="<?php echo $welcomebar['lead_name_placeholder'];?>" style="display: flex;"/>	
					</div>
					
					<div>
						<input type="text" class="contact-lead-email" id="contact-lead-email-0" name="contact_lead_email" placeholder="<?php echo $welcomebar['lead_email_placeholder'];?>" style="display:<?php echo (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] == 'email_address') ? 'flex' : 'none';?>"/>	
					</div>
					<div>
						<input type="text" class="contact-lead-phone" id="contact-lead-phone-0" name="contact_lead_phone" placeholder="<?php echo $welcomebar['lead_phone_placeholder'];?>" style="display:<?php echo (isset($welcomebar['mysticky_welcomebar_lead_input']) && $welcomebar['mysticky_welcomebar_lead_input'] == 'phone') ? 'flex' : 'none';?>"/>
					</div>

					

					<input type="hidden" id="contact-lead-pagelink-0" name="contact-page-link" value=" <?php echo esc_url(home_url( $wp->request ));?>">

					<input type="hidden" id="send-lead-email-0" value="<?php echo (isset($welcomebar['mysticky_welcomebar_send_email_lead']) && $welcomebar['mysticky_welcomebar_send_email_lead'] == 1) ? 1 : 0;?>">
				</div>
				
				<div class="mysticky-welcomebar-thankyou-content mysticky-welcomebar-content" style="display: none;">
					<?php echo wpautop( isset( $welcomebar['mysticky_welcomebar_thankyou_screen_text'] )? stripslashes( $welcomebar['mysticky_welcomebar_thankyou_screen_text'] ):"Thank you for submitting the form" );?>
				</div>
			<?php endif; ?>

			<div class="mysticky-welcomebar-btn <?php if( isset( $welcomebar['mysticky_welcomebar_enable_lead'] ) && $welcomebar['mysticky_welcomebar_enable_lead'] == 1 ): ?> contact-lead-button<?php endif; ?>" >
				<?php 
					$mysticky_welcomebar_btn_text =  isset($welcomebar['mysticky_welcomebar_btn_text']) ? stripslashes($welcomebar['mysticky_welcomebar_btn_text']) : stripslashes("Got it!");
					if( is_email($mysticky_welcomebar_actionselect_url) ){
						if( strpos($mysticky_welcomebar_actionselect_url, 'mailto:') === false ){
							$mysticky_welcomebar_actionselect_url = "mailto:".$mysticky_welcomebar_actionselect_url;
						}
					}
				?>

				<a href="<?php echo $mysticky_welcomebar_actionselect_url; ?>" <?php if( isset($welcomebar['mysticky_welcomebar_redirect_newtab']) && $welcomebar['mysticky_welcomebar_actionselect'] == 'redirect_to_url' && $welcomebar['mysticky_welcomebar_redirect_newtab']== 1):?> target="_blank" <?php endif;?>><?php echo stripslashes($mysticky_welcomebar_btn_text);?>
				</a>
			</div>
		
			<?php 
				$x_color = (isset($welcomebar['mysticky_welcomebar_x_color']) && $welcomebar['mysticky_welcomebar_x_color'] != '') ? $welcomebar['mysticky_welcomebar_x_color'] : '#000000';
			?>
			<a href="javascript:void(0)" class="mysticky-welcomebar-close" style="color:<?php echo $x_color; ?>">X</a>		
		</div>
	</div>
	<script>
	var welcomebar_frontjs = {
			'ajaxurl' : '<?php echo admin_url( 'admin-ajax.php' )?>',
			'days' 	  :	'<?php  echo __( 'Days', 'mystickymenu' ) ?>',
			'hours'   : '<?php echo __( 'Hours', 'mystickymenu' ) ?>',
			'minutes' : '<?php echo __( 'Minutes', 'mystickymenu' ) ?>',
			'seconds' : '<?php echo __( 'Seconds', 'mystickymenu' ) ?>',
			'ajax_nonce' :'<?php  echo wp_create_nonce('mystickymenu') ?>',
		};	

	jQuery(document).ready(function($){
		var adminBarHeight = 0;
		if ( $("#wpadminbar").length != 0 ){
			var adminBarHeight = $('#wpadminbar').height();
		}
		var mysticky_welcomebar_height = adminBarHeight + jQuery( '.mysticky-welcomebar-fixed' ).outerHeight();
		if( jQuery( '.mysticky-welcomebar-fixed' ).data('position') == 'top' ) {
			jQuery( '.mysticky-welcomebar-entry-effect-slide-in.mysticky-welcomebar-fixed' ).css( 'top', '-' + mysticky_welcomebar_height + 'px' );
		} else {
			jQuery( '.mysticky-welcomebar-entry-effect-slide-in.mysticky-welcomebar-fixed' ).css( 'bottom', '-' + mysticky_welcomebar_height + 'px' );
		}
		var divi_topbar_height = $( '.et_fixed_nav #top-header' ).outerHeight();
		var divi_total_height = mysticky_welcomebar_height + divi_topbar_height;
		var welcombar_aftersubmission = $( '.mysticky-welcomebar-fixed' ).data('aftersubmission');
		if( welcombar_aftersubmission == 'dont_show_welcomebar' ){
			var welcomebar_storage = localStorage.getItem("welcomebar_close");
		} else if( welcombar_aftersubmission == 'show_welcomebar_next_visit' ) {
			var welcomebar_storage = sessionStorage.getItem("welcomebar_close");
		} else {
			sessionStorage.removeItem('welcomebar_close');
			localStorage.removeItem('welcomebar_close');
			var welcomebar_storage = null;
		}
		if ( welcomebar_storage === null ){

			var after_trigger = jQuery( '.mysticky-welcomebar-fixed' ).data('after-triger');
			
			jQuery( 'body' ).addClass( 'mysticky-welcomebar-apper' );

			if ( after_trigger == 'after_a_few_seconds' ) {
				
				if ( $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-desktop' ) ) {
					if ( $( window ).width() > 767 ) {
						var trigger_sec = jQuery( '.mysticky-welcomebar-fixed' ).data('triger-sec') * 1000;
						var welcombar_position = $( '.mysticky-welcomebar-fixed' ).data('position');
						var welcombar_height = $( '.mysticky-welcomebar-fixed' ).outerHeight();
						
						setTimeout(function(){
							jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
							$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );
							if ( welcombar_position == 'top' ) {								
								
								jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', (adminBarHeight + 0) + 'px' );
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
								$( 'html' ).css( 'margin-bottom', '' );
								jQuery( '#mysticky_divi_style' ).remove();
								jQuery( '.et_fixed_nav #top-header' ).css( 'top', welcombar_height + 'px' );
								jQuery( 'head' ).append( '<style id="mysticky_divi_style" type="text/css">.et_fixed_nav #main-header {top: ' + welcombar_height + 'px !important}.et_fixed_nav #top-header + #main-header{top: ' + divi_total_height + 'px !important}</style>' );
								$( 'html' ).attr( 'style', 'margin-top: ' + mysticky_welcomebar_height + 'px !important' );
								$( '#mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
							} else {
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '0' );
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
								$( 'html' ).css( 'margin-top', '' );
								jQuery( '#mysticky_divi_style' ).remove();
								jQuery( '.et_fixed_nav #top-header' ).css( 'top', '' );
								$( 'html' ).attr( 'style', 'margin-bottom: ' + mysticky_welcomebar_height + 'px !important' );
							}
						}, trigger_sec );
					}
				}
			}
			if ( $( window ).width() < 767 ) {
				if ( after_trigger == 'after_a_few_seconds' ) {
					if ( $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-mobile' ) ) {
						var trigger_sec = jQuery( '.mysticky-welcomebar-fixed' ).data('triger-sec') * 1000;
						var welcombar_position = $( '.mysticky-welcomebar-fixed' ).data('position');
						var welcombar_height = $( '.mysticky-welcomebar-fixed' ).outerHeight();
						setTimeout(function(){
							jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
							$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );
							jQuery( '#mysticky_divi_style' ).remove();
							jQuery( '.et_fixed_nav #top-header' ).css( 'top', '' );							
							if ( welcombar_position == 'top' ) {
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', ( adminBarHeight + 0) + 'px' );
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
								$( 'html' ).css( 'margin-bottom', '' );
								$( 'html' ).attr( 'style', 'margin-top: ' + mysticky_welcomebar_height + 'px !important' );
								$( '#mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
							} else {
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '0' );
								jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
								$( 'html' ).css( 'margin-top', '' );
								$( 'html' ).attr( 'style', 'margin-bottom: ' + mysticky_welcomebar_height + 'px !important' );
							}
						}, trigger_sec );
					}
				}
			}
			mystickyelements_present();
		}
		$( window ).resize( function(){
			var mysticky_welcomebar_height = jQuery( '.mysticky-welcomebar-fixed' ).outerHeight();
			if( welcombar_aftersubmission == 'dont_show_welcomebar' ){
				var welcomebar_storage = localStorage.getItem("welcomebar_close");
			} else if( welcombar_aftersubmission == 'show_welcomebar_next_visit' ) {
				var welcomebar_storage = sessionStorage.getItem("welcomebar_close");
			} else {
				sessionStorage.removeItem('welcomebar_close');
				localStorage.removeItem('welcomebar_close');
				var welcomebar_storage = null;
			}
			if ( welcomebar_storage === null ){
				var after_trigger = jQuery( '.mysticky-welcomebar-fixed' ).data('after-triger');
				if ( ! $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-notapper' ) ) {
					jQuery( 'body' ).addClass( 'mysticky-welcomebar-apper' );
				} else {
					jQuery( 'body' ).removeClass( 'mysticky-welcomebar-apper' );
				}
				if ( after_trigger == 'after_a_few_seconds' ) {
					var trigger_sec = jQuery( '.mysticky-welcomebar-fixed' ).data('triger-sec') * 1000;
					var welcombar_position = $( '.mysticky-welcomebar-fixed' ).data('position');
					var welcombar_height = $( '.mysticky-welcomebar-fixed' ).outerHeight();
					if ( $( window ).width() < 767 ) {
						if ( $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-mobile' ) ) {
							setTimeout(function(){
								jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
								$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );
								jQuery( '#mysticky_divi_style' ).remove();
								jQuery( '.et_fixed_nav #top-header' ).css( 'top', '' );
								if ( welcombar_position == 'top' ) {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', ( adminBarHeight +  0) + 'px' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-bottom', '' );
									$( 'html' ).attr( 'style', 'margin-top: ' + mysticky_welcomebar_height + 'px !important' );
									$( '.mysticky-welcomebar-apper #mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
								} else {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '0' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-top', '' );
									$( 'html' ).attr( 'style', 'margin-bottom: ' + mysticky_welcomebar_height + 'px !important' );
								}
							}, trigger_sec );
						}
					} else {
						if ( $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-desktop' ) ) {
							setTimeout(function(){
								jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
								$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );
								if ( welcombar_position == 'top' ) {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', ( adminBarHeight + 0) + 'px' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-bottom', '' );
									jQuery( '#mysticky_divi_style' ).remove();
									jQuery( '.mysticky-welcomebar-apper.et_fixed_nav #top-header' ).css( 'top', welcombar_height + 'px' );
									jQuery( 'head' ).append( '<style id="mysticky_divi_style" type="text/css">.mysticky-welcomebar-apper.et_fixed_nav #main-header {top: ' + welcombar_height + 'px !important}.mysticky-welcomebar-apper.et_fixed_nav #top-header + #main-header{top: ' + divi_total_height + 'px !important}</style>' );
									$( 'html' ).attr( 'style', 'margin-top: ' + mysticky_welcomebar_height + 'px !important' );
									$( '.mysticky-welcomebar-apper #mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
								} else {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '0' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-top', '' );
									jQuery( '#mysticky_divi_style' ).remove();
									jQuery( '.et_fixed_nav #top-header' ).css( 'top', '' );
									$( 'html' ).attr( 'style', 'margin-bottom: ' + mysticky_welcomebar_height + 'px !important' );
								}
							}, trigger_sec );
						}
					}
				}
				mystickyelements_present();
			}
		} );

		jQuery(window).on( 'scroll', function(){			
			if( welcombar_aftersubmission == 'dont_show_welcomebar' ){
				var welcomebar_storage = localStorage.getItem("welcomebar_close");
			} else if( welcombar_aftersubmission == 'show_welcomebar_next_visit' ) {
				var welcomebar_storage = sessionStorage.getItem("welcomebar_close");
			} else {
				sessionStorage.removeItem('welcomebar_close');
				localStorage.removeItem('welcomebar_close');
				var welcomebar_storage = null;
			}
			if ( welcomebar_storage === null ){
				var welcombar_height = $( '.mysticky-welcomebar-fixed' ).outerHeight();
				var welcombar_position = $( '.mysticky-welcomebar-fixed' ).data('position');
				if ( welcombar_position == 'top' ) {
					$( '#mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
				}
				if ( after_trigger === 'after_scroll' ) {
					var scroll = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());
					var after_scroll_val = jQuery( '.mysticky-welcomebar-fixed' ).data('triger-sec');
					var welcombar_position = $( '.mysticky-welcomebar-fixed' ).data('position');
					var welcombar_height = $( '.mysticky-welcomebar-fixed' ).outerHeight();
					if( scroll > after_scroll_val ) {
						if ( $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-desktop' ) ) {
							if ( $( window ).width() > 767 ) {
								jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
								$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );
								if ( welcombar_position == 'top' ) {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', (adminBarHeight+ 0 ) + 'px' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-bottom', '' );
									$( 'html' ).attr( 'style', 'margin-top: ' + mysticky_welcomebar_height + 'px !important' );
									$( '#mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
								} else {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '0' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-top', '' );
									$( 'html' ).attr( 'style', 'margin-bottom: ' + mysticky_welcomebar_height + 'px !important' );
								}
							}
						}
						if ( $( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-mobile' ) ) {
							if ( $( window ).width() < 767 ) {
								jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-animation' );
								$( '.mysticky-welcomebar-fixed' ).addClass( 'entry-effect' );
								if ( welcombar_position == 'top' ) {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', ( adminBarHeight +0 ) + 'px' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-bottom', '' );
									$( 'html' ).attr( 'style', 'margin-top: ' + mysticky_welcomebar_height + 'px !important' );
									$( '#mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
								} else {
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '0' );
									jQuery( '.mysticky-welcomebar-fixed' ).css( 'opacity', '1' );
									$( 'html' ).css( 'margin-top', '' );
									$( 'html' ).attr( 'style', 'margin-bottom: ' + mysticky_welcomebar_height + 'px !important' );
								}
							}
						}
					}
				}
				mystickyelements_present();
			}

		});
		
		jQuery( '.mysticky-welcomebar-close, .mysticky-welcomebar-btn a' ).on( 'click', function(){


		/* Submit contact lead form */

		var flag=true;
		var trigger_sec = 100;
		var welcomebar_widget = 0;	
		if( jQuery(".mysticky-welcomebar-lead-content").length > 0 && !$(this).hasClass("mysticky-welcomebar-close")){

			if( jQuery('#contact-lead-name-'+welcomebar_widget).val() != '' && ( jQuery('#contact-lead-phone-'+welcomebar_widget).val() != '' || jQuery('#contact-lead-email-'+welcomebar_widget).val() != '' )){

				if( jQuery('#contact-lead-email-'+welcomebar_widget).css("display") != 'none' &&  IsEmail(jQuery('#contact-lead-email-'+welcomebar_widget).val()) != true ){
					
					if( $(".input-error").length ){
						$(".input-error").remove();
					}

					$( '<div class="input-error"><span>Please, enter valid email</span></div>' ).insertAfter( jQuery('#contact-lead-email-' + welcomebar_widget) );
					flag = false;
				}	


				if( jQuery('#contact-lead-phone-'+welcomebar_widget).css("display") != 'none' &&  validatePhone(jQuery('#contact-lead-phone-'+welcomebar_widget).val()) != true  ){
					if( $(".input-error").length ){
						$(".input-error").remove();
					}
					$( '<div class="input-error"><span>Please, enter valid phone</span></div>' ).insertAfter( jQuery('#contact-lead-phone-'+welcomebar_widget) );
					flag=false;
				}	
				
				if (flag == true) {
					var data = [];
					data["contact_name"] = jQuery('#contact-lead-name-'+welcomebar_widget).val();
					data["contact_email"] = jQuery('#contact-lead-email-'+welcomebar_widget).val();
					data["contact_phone"] = jQuery('#contact-lead-phone-'+welcomebar_widget).val();
					data["contact_page_link"] = jQuery('#contact-lead-pagelink-'+welcomebar_widget).val();
					
					$(".mysticky-welcomebar-fixed .mysticky-welcomebar-lead-content").hide();
					$(".mysticky-welcomebar-fixed .mysticky-welcomebar-content p").hide();
					$(".mysticky-welcomebar-fixed .mysticky-welcomebar-btn.contact-lead-button").hide();
					$(".mysticky-welcomebar-fixed .mysticky-welcomebar-thankyou-content").show();
					$(".mysticky-welcomebar-fixed .mysticky-welcomebar-thankyou-content p").show();
					var trigger_sec = 2000;
					jQuery.ajax({
						url: welcomebar_frontjs.ajaxurl,
						type:'post',
						data: 'contact_name='+data["contact_name"]+'&contact_email='+data["contact_email"]+'&contact_phone='+data["contact_phone"]+'&action=stickymenu_contact_lead_form&widget_id=' + welcomebar_widget  + '&page_link='+ data["contact_page_link"]+'&save_form_lead=1&wpnonce=' + welcomebar_frontjs.ajax_nonce,
						success: function( data ){					
							$(".mysticky-welcomebar-widget-"+welcomebar_widget+" .mysticky-welcomebar-fixed-wrap").css("margin-bottom","0");
						},
					});
				}else{
					$(".mysticky-welcomebar-widget-"+welcomebar_widget+" .mysticky-welcomebar-fixed-wrap").css("margin-bottom","10px");
					return false;
				}

				
			}else{
				localStorage.removeItem('welcomebar_close_' + welcomebar_widget);
				sessionStorage.removeItem('welcomebar_close_' + welcomebar_widget);
				
				if($(".input-error").length){
					$(".input-error").remove();
				}

				if( jQuery('#contact-lead-name-'+welcomebar_widget).css("display") != 'none' && jQuery('#contact-lead-name-'+welcomebar_widget).val() == '' && jQuery('#contact-lead-email-'+welcomebar_widget).css("display") != 'none' &&  jQuery('#contact-lead-email-'+welcomebar_widget).val() == '' ){

					$( '<div class="input-error"><span>Please enter your name and email</span></div>' ).insertAfter( jQuery('#contact-lead-name-'+welcomebar_widget) );
					flag=false;

				}else if( jQuery('#contact-lead-name-'+welcomebar_widget).css("display") != 'none' && jQuery('#contact-lead-name-'+welcomebar_widget).val() == '' && jQuery('#contact-lead-phone-'+welcomebar_widget).css("display") != 'none' && jQuery('#contact-lead-phone-'+welcomebar_widget).val() == '' ){

					$( '<div class="input-error"><span>Please enter your name and phone</span></div>' ).insertAfter( jQuery('#contact-lead-name-'+welcomebar_widget) );
					flag=false;

				}else if( jQuery('#contact-lead-name-'+welcomebar_widget).css("display") != 'none' && jQuery('#contact-lead-name-'+welcomebar_widget).val() == '' ){

					$( '<div class="input-error"><span>Please enter your name</span></div>' ).insertAfter( jQuery('#contact-lead-name-'+welcomebar_widget) );
					flag=false;

				}else if( jQuery('#contact-lead-email-'+welcomebar_widget).css("display") != 'none' &&  jQuery('#contact-lead-email-'+welcomebar_widget).val() == '' ){

					$( '<div class="input-error"><span>Please, enter your email</span></div>' ).insertAfter( jQuery('#contact-lead-email-'+welcomebar_widget) );
					flag=false;

				}else if( jQuery('#contact-lead-phone-'+welcomebar_widget).css("display") != 'none' && jQuery('#contact-lead-phone-'+welcomebar_widget).val() == '' ){

					$( '<div class="input-error"><span>Please, enter your phone</span></div>' ).insertAfter( jQuery('#contact-lead-phone-'+welcomebar_widget) );
					flag=false;

				}

				if(flag==false){
					
					$(".mysticky-welcomebar-widget-"+welcomebar_widget+" .mysticky-welcomebar-fixed-wrap").css("margin-bottom","10px");
				} else{
					$(".mysticky-welcomebar-widget-"+welcomebar_widget+" .mysticky-welcomebar-fixed-wrap").css("margin-bottom","0");
				}
				return false;
			}
		}else{
			if( $(this).hasClass("mysticky-welcomebar-close") ){
				localStorage.setItem('is_close_trigger_' + welcomebar_widget, 'yes');
			}
		}
		
			setTimeout(function(){
				if( welcombar_aftersubmission != 'show_welcomebar_every_page' ){
					if( welcombar_aftersubmission == 'dont_show_welcomebar' ){
						sessionStorage.removeItem('welcomebar_close');
						localStorage.setItem('welcomebar_close', 'close');
					} else if( welcombar_aftersubmission == 'show_welcomebar_next_visit' ) {
						localStorage.removeItem('welcomebar_close');
						sessionStorage.setItem('welcomebar_close', 'close');
					}
				}
				var welcombar_position = $( '.mysticky-welcomebar-fixed' ).data('position');
				var welcombar_height = $( '.mysticky-welcomebar-fixed' ).outerHeight();
				jQuery( '.mysticky-welcomebar-fixed' ).addClass( 'mysticky-welcomebar-notapper' );
				jQuery( 'body' ).removeClass( 'mysticky-welcomebar-apper' );
				jQuery( '.mysticky-welcomebar-fixed' ).slideUp( 'slow' );
				if ( welcombar_position == 'top' ) {
					jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', '-' + mysticky_welcomebar_height + 'px' );
				} else {
					jQuery( '.mysticky-welcomebar-fixed' ).css( 'bottom', '-' + mysticky_welcomebar_height + 'px' );
				}
				jQuery( '#mysticky_divi_style' ).remove();
				jQuery( '.et_fixed_nav #top-header' ).css( 'top', '' );
				jQuery( 'html' ).css( 'margin-top', '' );
				jQuery( 'html' ).css( 'margin-bottom', '' );
				$( '#mysticky-nav' ).css( 'top', '0px' );
				/*if mystickyelements show*/
				var mystickyelements_show = $( '.mystickyelements-fixed' ).length;
				if( mystickyelements_show && $( window ).width() <= 1024 && $( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-position-mobile-top' ) && welcombar_position == 'top' ) {
					var mystickyelements_height = $( '.mystickyelements-fixed' ).height();
					$( '.mystickyelements-fixed' ).css( 'top', '' );
					$( 'html' ).attr( 'style', 'margin-top: ' + mystickyelements_height + 'px !important' );
				}
			}, trigger_sec );
		} );
	});
	function mystickyelements_present() {
		var after_trigger 		  = jQuery( '.mysticky-welcomebar-fixed' ).data('after-triger');
		var mystickyelements_show = jQuery( '.mystickyelements-fixed' ).length;		
		var welcombar_position 			  = jQuery( '.mysticky-welcomebar-fixed' ).data('position');
		var adminBarHeight = 0;
		if ( jQuery("#wpadminbar").length != 0 ){
			var adminBarHeight = jQuery('#wpadminbar').height();
		}
		
		if ( jQuery( window ).width() <= 600 && jQuery(window).scrollTop() != 0 && welcombar_position == 'top') {
			jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', '0px' );
			var welcombar_height = jQuery( '.mysticky-welcomebar-fixed' ).outerHeight();
			
			if (jQuery( '.mysticky-welcomebar-fixed' ).css('display') === 'none') {
				welcombar_height= 0;
			}			
			jQuery( '#mysticky-nav' ).css( 'top', welcombar_height + 'px' );
			
		} else if ( welcombar_position == 'top' ) {
			var mysticky_welcomebar_height = adminBarHeight + jQuery( '.mysticky-welcomebar-fixed' ).outerHeight();
			if (jQuery( '.mysticky-welcomebar-fixed' ).css('display') === 'none') {
				mysticky_welcomebar_height= adminBarHeight + 0;
			}	
			jQuery( '.mysticky-welcomebar-fixed' ).css( 'top', ( adminBarHeight + 0) + 'px' );			
			jQuery( '#mysticky-nav' ).css( 'top', mysticky_welcomebar_height + 'px' );
		}
		if( mystickyelements_show ) {
			
			var welcombar_height 			  = jQuery( '.mysticky-welcomebar-fixed' ).outerHeight();
			var mystickyelements_height 	  = jQuery( '.mystickyelements-fixed' ).height();
			var mystickyelements_total_height = welcombar_height + mystickyelements_height;
			if ( jQuery( window ).width() <= 1024 && jQuery( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-position-mobile-top' ) ) {
				if ( after_trigger == 'after_a_few_seconds' ) {
					if ( jQuery( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-mobile' ) ) {
						var trigger_sec = jQuery( '.mysticky-welcomebar-fixed' ).data('triger-sec') * 1000;
						setTimeout(function(){
							if ( welcombar_position == 'top' ) {
								jQuery( '.mystickyelements-fixed' ).css( 'top', welcombar_height );
								jQuery( 'html' ).attr( 'style', 'margin-top: ' + mystickyelements_total_height + 'px !important' );
							} else {
								jQuery( '.mystickyelements-fixed' ).css( 'top', '' );
								jQuery( 'html' ).attr( 'style', 'margin-bottom: ' + welcombar_height + 'px !important' );
							}
						}, trigger_sec );
					}
				} else if ( after_trigger === 'after_scroll' ) {
					var scroll = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());
					var after_scroll_val = $( '.mysticky-welcomebar-fixed' ).data('triger-sec');
					if( scroll > after_scroll_val ) {
						if ( jQuery( '.mysticky-welcomebar-fixed' ).hasClass( 'mysticky-welcomebar-display-mobile' ) ) {
							if ( welcombar_position == 'top' ) {
								jQuery( '.mystickyelements-fixed' ).css( 'top', welcombar_height );
								jQuery( 'html' ).attr( 'style', 'margin-top: ' + mystickyelements_total_height + 'px !important' );
							} else {
								jQuery( '.mystickyelements-fixed' ).css( 'top', '' );
								jQuery( 'html' ).attr( 'style', 'margin-bottom: ' + welcombar_height + 'px !important' );
							}
						}
					}
				}
			}
		}
	}
	jQuery(".mysticky-welcomebar-fixed").on(
		"animationend MSAnimationEnd webkitAnimationEnd oAnimationEnd",
		function() {
			jQuery(this).removeClass("animation-start");
		}
	);
	jQuery(document).ready(function() { 
		var container = jQuery(".mysticky-welcomebar-fixed");
        var refreshId = setInterval(function() {
            container.addClass("animation-start");
        }, 3500);
    });

    function IsEmail(email) {
	    var regex =
	/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	    if (!regex.test(email)) {
	        return false;
	    }
	    else {
	        return true;
	    }
	}

	function validatePhone(txtPhone) {
	    var a = txtPhone;
	    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
	    if (filter.test(a)) {
	        return true;
	    }
	    else {
	        return false;
	    }
	}
	</script>

<?php 
	if( isset($welcomebar['mysticky_welcomebar_font']) && $welcomebar['mysticky_welcomebar_font'] == 'System Stack' ){
		$welcomebar['mysticky_welcomebar_font'] = '-apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
	}
	
	$welcomebar['mysticky_welcomebar_font'] = (isset($welcomebar['mysticky_welcomebar_font']) && $welcomebar['mysticky_welcomebar_font'] == 'Inherit') ? strtolower($welcomebar['mysticky_welcomebar_font']) : $welcomebar['mysticky_welcomebar_font'];
?>

	<style>
/*-------------New-----*/



/*--------------------------------------------------------------------------------------------*/

	.mysticky-welcomebar-fixed , .mysticky-welcomebar-fixed * {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.mysticky-welcomebar-fixed {
		background-color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_bgcolor']) ?>;
		font-family: <?php echo $welcomebar['mysticky_welcomebar_font'] ?>;
		position: fixed;
		left: 0;
		right: 0;
		z-index: 9999999;
		opacity: 0;
	}

	.mysticky-welcomebar-fixed-wrap {
		min-height: 60px;
		padding: 20px 50px;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100%;
	}
	.mysticky-welcomebar-animation {
		-webkit-transition: all 1s ease 0s;
		-moz-transition: all 1s ease 0s;
		transition: all 1s ease 0s;
	}
	.mysticky-welcomebar-position-top {
		top:0;
	}
	.mysticky-welcomebar-position-bottom {
		bottom:0;
	}
	.mysticky-welcomebar-position-top.mysticky-welcomebar-entry-effect-slide-in {
		top: -60px;
	}
	.mysticky-welcomebar-position-bottom.mysticky-welcomebar-entry-effect-slide-in {
		bottom: -60px;
	}
	.mysticky-welcomebar-entry-effect-fade {
		opacity: 0;
	}
	.mysticky-welcomebar-entry-effect-none {
		display: none;
	}
	.mysticky-welcomebar-fixed .mysticky-welcomebar-content p a{
		text-decoration: underline;
		text-decoration-thickness: 1px;
		text-underline-offset: 0.25ch;
	}
	
	
	.mysticky-welcomebar-fixed .mysticky-welcomebar-content p a,
	.mysticky-welcomebar-fixed .mysticky-welcomebar-content p {
		color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_bgtxtcolor']) ?>;
		font-size: <?php echo esc_attr($welcomebar['mysticky_welcomebar_fontsize']) ?>px;
		margin: 0;
		padding: 0;
		line-height: 1.2;
		font-weight: 400;
		font-family:<?php echo ($welcomebar['mysticky_welcomebar_font']); ?>
	}
	.mysticky-welcomebar-fixed .mysticky-welcomebar-btn {
		/*padding-left: 30px;*/
		display: none;
		line-height: 1;
		margin-left: 10px;
	}
	.mysticky-welcomebar-fixed.mysticky-welcomebar-btn-desktop .mysticky-welcomebar-btn {
		display: block;
	}
	.mysticky-welcomebar-fixed .mysticky-welcomebar-btn a {
		background-color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_btncolor']); ?>;
		font-family: inherit;
		color: <?php echo esc_attr($welcomebar['mysticky_welcomebar_btntxtcolor']); ?>;
		border-radius: 4px;
		text-decoration: none;
		display: inline-block;
		vertical-align: top;
		line-height: 1.2;
		font-size: <?php echo esc_attr($welcomebar['mysticky_welcomebar_fontsize']); ?>px;
		font-weight: 400;
		padding: 5px 20px;
		white-space: nowrap;
	}
	.mysticky-welcomebar-fixed .mysticky-welcomebar-btn a:hover {
		/*opacity: 0.7;*/
		-moz-box-shadow: 1px 2px 4px rgba(0, 0, 0,0.5);
		-webkit-box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.5);
		box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.5);
	}


	.mysticky-welcomebar-fixed .mysticky-welcomebar-close {
		display: none;
		vertical-align: top;
		width: 30px;
		height: 30px;
		text-align: center;
		line-height: 30px;
		border-radius: 5px;
		color: #000;
		position: absolute;
		top: 5px;
		right: 10px;
		outline: none;
		font-family: Lato; 
		text-decoration: none;
		text-shadow: 0 0 0px #fff;
		-webkit-transition: all 0.5s ease 0s;
		-moz-transition: all 0.5s ease 0s;
		transition: all 0.5s ease 0s;
		-webkit-transform-origin: 50% 50%;
		-moz-transform-origin: 50% 50%;
		transform-origin: 50% 50%;
	}


	.mysticky-welcomebar-fixed .mysticky-welcomebar-close:hover {
		opacity: 1;
		-webkit-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		transform: rotate(180deg);
	}
	.mysticky-welcomebar-fixed .mysticky-welcomebar-close span.dashicons {
		font-size: 27px;
	}
	.mysticky-welcomebar-fixed.mysticky-welcomebar-showx-desktop .mysticky-welcomebar-close {
		display: inline-block;
	}	
	
	/* Animated Buttons */
		.mysticky-welcomebar-btn a {
			-webkit-animation-duration: 1s;
			animation-duration: 1s;
		}
		@-webkit-keyframes flash {
			from,
			50%,
			to {
				opacity: 1;
			}

			25%,
			75% {
				opacity: 0;
			}
		}
		@keyframes flash {
			from,
			50%,
			to {
				opacity: 1;
			}

			25%,
			75% {
				opacity: 0;
			}
		}
		.mysticky-welcomebar-attention-flash.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: flash;
			animation-name: flash;
		}
		
		@keyframes shake {
			from,
			to {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}

			10%,
			30%,
			50%,
			70%,
			90% {
				-webkit-transform: translate3d(-10px, 0, 0);
				transform: translate3d(-10px, 0, 0);
			}

			20%,
			40%,
			60%,
			80% {
				-webkit-transform: translate3d(10px, 0, 0);
				transform: translate3d(10px, 0, 0);
			}
		}

		.mysticky-welcomebar-attention-shake.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: shake;
			animation-name: shake;
		}
		
		@-webkit-keyframes swing {
			20% {
				-webkit-transform: rotate3d(0, 0, 1, 15deg);
				transform: rotate3d(0, 0, 1, 15deg);
			}

			40% {
				-webkit-transform: rotate3d(0, 0, 1, -10deg);
				transform: rotate3d(0, 0, 1, -10deg);
			}

			60% {
				-webkit-transform: rotate3d(0, 0, 1, 5deg);
				transform: rotate3d(0, 0, 1, 5deg);
			}

			80% {
				-webkit-transform: rotate3d(0, 0, 1, -5deg);
				transform: rotate3d(0, 0, 1, -5deg);
			}
	
			to {
				-webkit-transform: rotate3d(0, 0, 1, 0deg);
				transform: rotate3d(0, 0, 1, 0deg);
			}
		}

		@keyframes swing {
			20% {
				-webkit-transform: rotate3d(0, 0, 1, 15deg);
				transform: rotate3d(0, 0, 1, 15deg);
			}

			40% {
				-webkit-transform: rotate3d(0, 0, 1, -10deg);
				transform: rotate3d(0, 0, 1, -10deg);
			}

			60% {
				-webkit-transform: rotate3d(0, 0, 1, 5deg);
				transform: rotate3d(0, 0, 1, 5deg);
			}

			80% {
				-webkit-transform: rotate3d(0, 0, 1, -5deg);
				transform: rotate3d(0, 0, 1, -5deg);
			}

			to {
				-webkit-transform: rotate3d(0, 0, 1, 0deg);
				transform: rotate3d(0, 0, 1, 0deg);
			}
		}

		.mysticky-welcomebar-attention-swing.animation-start .mysticky-welcomebar-btn a {
			-webkit-transform-origin: top center;
			transform-origin: top center;
			-webkit-animation-name: swing;
			animation-name: swing;
		}
		
		@-webkit-keyframes tada {
			from {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}

			10%,
			20% {
				-webkit-transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
			}

			30%,
			50%,
			70%,
			90% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
			}

			40%,
			60%,
			80% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
			}

			to {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}
		}

		@keyframes tada {
			from {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}

			10%,
			20% {
				-webkit-transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
			}

			30%,
			50%,
			70%,
			90% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
			}

			40%,
			60%,
			80% {
				-webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
				transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
			}

			to {
				-webkit-transform: scale3d(1, 1, 1);
				transform: scale3d(1, 1, 1);
			}
		}

		.mysticky-welcomebar-attention-tada.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: tada;
			animation-name: tada;
		}
		
		@-webkit-keyframes heartBeat {
			0% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			14% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			28% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			42% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			70% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}
		}

		@keyframes heartBeat {
			0% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			14% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			28% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}

			42% {
				-webkit-transform: scale(1.3);
				transform: scale(1.3);
			}

			70% {
				-webkit-transform: scale(1);
				transform: scale(1);
			}
		}

		.mysticky-welcomebar-attention-heartbeat.animation-start .mysticky-welcomebar-btn a {
		  -webkit-animation-name: heartBeat;
		  animation-name: heartBeat;
		  -webkit-animation-duration: 1.3s;
		  animation-duration: 1.3s;
		  -webkit-animation-timing-function: ease-in-out;
		  animation-timing-function: ease-in-out;
		}
		
		@-webkit-keyframes wobble {
			from {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}

			15% {
				-webkit-transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
				transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
			}

			30% {
				-webkit-transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
				transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
			}

			45% {
				-webkit-transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
				transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
			}

			60% {
				-webkit-transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
				transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
			}

			75% {
				-webkit-transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
				transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
			}

			to {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}
		}

		@keyframes wobble {
			from {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}

			15% {
				-webkit-transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
				transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
			}

			30% {
				-webkit-transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
				transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
			}

			45% {
				-webkit-transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
				transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
			}

			60% {
				-webkit-transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
				transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
			}

			75% {
				-webkit-transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
				transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
			}

			to {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
			}
		}
		
		.mysticky-welcomebar-attention-wobble.animation-start .mysticky-welcomebar-btn a {
			-webkit-animation-name: wobble;
			animation-name: wobble;
		}
		@media only screen and (min-width: 768px) {
			.mysticky-welcomebar-display-desktop.mysticky-welcomebar-entry-effect-fade.entry-effect {
				opacity: 1;
			}
			.mysticky-welcomebar-display-desktop.mysticky-welcomebar-entry-effect-none.entry-effect {
				display: block;
			}
			.mysticky-welcomebar-display-desktop.mysticky-welcomebar-position-top.mysticky-welcomebar-fixed ,
			.mysticky-welcomebar-display-desktop.mysticky-welcomebar-position-top.mysticky-welcomebar-entry-effect-slide-in.entry-effect.mysticky-welcomebar-fixed {
				top: 0;			
			}
			.mysticky-welcomebar-display-desktop.mysticky-welcomebar-position-bottom.mysticky-welcomebar-fixed ,
			.mysticky-welcomebar-display-desktop.mysticky-welcomebar-position-bottom.mysticky-welcomebar-entry-effect-slide-in.entry-effect.mysticky-welcomebar-fixed {
				bottom: 0;
			}	
		}
		@media only screen and (max-width: 767px) {
			.mysticky-welcomebar-display-mobile.mysticky-welcomebar-entry-effect-fade.entry-effect {
				opacity: 1;
			}
			.mysticky-welcomebar-display-mobile.mysticky-welcomebar-entry-effect-none.entry-effect {
				display: block;
			}
			.mysticky-welcomebar-display-mobile.mysticky-welcomebar-position-top.mysticky-welcomebar-fixed ,
			.mysticky-welcomebar-display-mobile.mysticky-welcomebar-position-top.mysticky-welcomebar-entry-effect-slide-in.entry-effect.mysticky-welcomebar-fixed {
				top: 0;
			}
			.mysticky-welcomebar-display-mobile.mysticky-welcomebar-position-bottom.mysticky-welcomebar-fixed ,
			.mysticky-welcomebar-display-mobile.mysticky-welcomebar-position-bottom.mysticky-welcomebar-entry-effect-slide-in.entry-effect.mysticky-welcomebar-fixed {
				bottom: 0;
			}
			/*.mysticky-welcomebar-fixed.mysticky-welcomebar-showx-desktop .mysticky-welcomebar-close {
				display: none;
			}
			.mysticky-welcomebar-fixed.mysticky-welcomebar-showx-mobile .mysticky-welcomebar-close {
				display: inline-block;
			}*/
			.mysticky-welcomebar-fixed.mysticky-welcomebar-btn-desktop .mysticky-welcomebar-btn {
				display: none;
			}
			.mysticky-welcomebar-fixed.mysticky-welcomebar-btn-mobile .mysticky-welcomebar-btn {
				display: block;
				margin-top: 10px;
			}
		}
		@media only screen and (max-width: 480px) {

			.mysticky-welcomebar-fixed-wrap {padding: 15px 35px 10px 10px; flex-wrap:wrap;}
			/*.welcombar-contact-lead .mysticky-welcomebar-fixed-wrap {flex-wrap: wrap; justify-content: center;}*/
			
			.mysticky-welcomebar-fixed .mystickymenu-front.mysticky-welcomebar-lead-content {margin: 10px 0 10px 20px !important;}

			.mysticky-welcomebar-fixed .mysticky-welcomebar-btn {
				padding-left: 10px;
			}
		}


		body.mysticky-welcomebar-apper #wpadminbar{
			z-index:99999999;
		}

		.mysticky-welcomebar-fixed .mystickymenu-front.mysticky-welcomebar-lead-content {
			display: flex;
			width: auto;
			margin: 0 0px 0 10px;
		}

		.mystickymenu-front.mysticky-welcomebar-lead-content input[type="text"] {
			font-size: 12px;
			padding: 7px 5px;
			margin-right: 10px;
			min-width: 50%;
			border: 0;
			width:auto;
		}

		.mystickymenu-front.mysticky-welcomebar-lead-content input[type="text"]:focus {
			outline: unset;
			box-shadow: unset;
		}

		.input-error {
			color: #ff0000;
			font-style: normal;
			font-family: inherit;
			font-size: 13px;
			display: block;
			position: absolute;
			bottom: 0px;
		}

		.mysticky-welcomebar-fixed.mysticky-site-front .mysticky-welcomebar-btn.contact-lead-button {
		  margin-left: 0;
		}
	</style>
	<?php
}
add_action( 'wp_footer', 'mysticky_welcome_bar_frontend' );

// .welcombar-contact-lead