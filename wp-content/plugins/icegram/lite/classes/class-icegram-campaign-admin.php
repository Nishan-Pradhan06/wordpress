<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Campaign_Admin' ) ) {
	class Icegram_Campaign_Admin {

		var $default_target_rules;
		var $site_url;
		public function __construct() {

			// add_action( 'add_meta_boxes', array( &$this, 'add_campaigns_metaboxes' ), 0 );
			add_action( 'edit_form_advanced', array( &$this, 'add_campaigns_tabs' ), 11 );
			add_action( 'edit_form_advanced', array( &$this, 'campaign_message_list' ), 10 );

			//Before post title 
			add_action( 'edit_form_advanced', array( &$this, 'add_campaign_settings' ) );
			add_action( 'admin_init', array( &$this, 'remove_campaign_extra_meta_box' ) );

			add_action( 'save_post', array( &$this, 'save_campaign_settings' ), 10, 2 );
			add_action( 'wp_ajax_icegram_json_search_messages', array( &$this, 'icegram_json_search_messages' ) );
			add_action( 'wp_ajax_get_message_action_row', array( &$this, 'get_message_action_row' ) );		
		    // add_filter( 'wp_default_editor', create_function('', 'return "html";') );
			add_action( 'wp_ajax_save_campaign_preview', array( &$this, 'save_campaign_preview' ) );
			add_action( 'icegram_campaign_target_rules', array( &$this, 'icegram_add_campaign_target_rules' ), 10, 2 );
			add_filter('icegram_campaign_messages' ,array( &$this, 'get_icegram_campaign_messages' ) ,10,3 );
	       	//duplicate campaign
			add_filter( 'post_row_actions', array(&$this , 'add_campaign_action'), 10, 2 );
			add_action('admin_init', array(&$this ,'duplicate_campaign') ,10, 1);

	        // Adding tabs here 
			add_filter( 'icegram_campaign_tabs', array( &$this, 'campaign_data_tab_content' ), 0, 1 );
			add_filter( 'icegram_display_rules', array( &$this, 'campaign_target_rules_tab_content' ), 100, 1 );

			$this->site_url = home_url().'/';
			
			$this->default_target_rules = apply_filters( 'icegram_campaign_default_rules',
				array ( 'homepage' 	=> 'yes',
					'when' 		=> 'always',
					'mobile' 	=> 'yes',
					'tablet' 	=> 'yes',
					'laptop' 	=> 'yes',
					'logged_in' => 'all'									    
				)
			);

		}
		public static function getInstance(){
			static $ig_campaign_admin = null;
			if (null === $ig_campaign_admin) {
				$ig_campaign_admin = new Icegram_Campaign_Admin();
			}
			return $ig_campaign_admin;
		}

		// Initialize campaign Tabs
		public function add_campaigns_tabs() {
			global $post, $pagenow;
			if ($post->post_type != 'ig_campaign') return;

			$tabs = array();
			$tabs = array('tabs' => array());
			$tabs = apply_filters('icegram_campaign_tabs', $tabs);
			
			$display_rule_tab = apply_filters('icegram_display_rules', '');
	
			if(!empty($tabs)){

				$tabs_content = !empty($tabs['tabs']) ? implode('', $tabs['tabs']) : '';

				echo '<div id="ig-admin-tabs" class="border-t-2 border-b-2 border-dashed border-gray-200">'. $tabs_content . '</div>' . $display_rule_tab;   
			}

			if ( $pagenow == 'post-new.php' ) {
				echo "<style>
					#poststuff{
				position:relative;
			}
					#poststuff #post-body.columns-2{
			margin-right: 0;
		}
		.wrap h1.wp-heading-inline,
					#post-body-content,
		.postbox-container + *:not(:last-child),
		.postbox-container > *:not(.ig-gallery-wrap){
			display:none;
		}
		.wrap h1.wp-heading-inline.gallery-heading{
			display:inline-block;
		}
		.ig-gallery-position{
			position: absolute;
			width: 100%;
			left: 0;
			top: 50px;
		}
					#postbox-container-1 #side-sortables.gal-toggled{
		display:block;
	}
	</style>";
				// echo '<h1 class="wp-heading-inline gallery-heading">'. __('Import from beautiful design templates', 'icegram'). '<a href="#" class="page-title-action" id="ig-add-new-campaign">'. __('Add New Campaign', 'icegram'). '</a></h1>';
				// echo '<h1 class="wp-heading-inline gallery-heading">'. __('Import from beautiful design templates', 'icegram').'</h1>';
	Icegram::gallery_screen();
}

}

		public function remove_campaign_extra_meta_box(){
			remove_meta_box( 'submitdiv' , 'ig_campaign' , 'side' ); 
			
		}

		public function add_campaign_settings(){
			global $post;

			if( $post->post_type != 'ig_campaign' ){
				return;
			}

			$args = array(
				'post_type'			=> array( 'ig_campaign' ),
				'post_status'	 	=> array('publish', 'draft'),
				'posts_per_page' 	=> -1,
				'post__not_in' 		=> array( $post->ID ),
			);

			$ig_campaigns = get_posts( $args );
			?>
			<!-- Campaign Navigation Links -->
			<div class="ig-top-nav shadow fixed bg-white p-0 box-border">
				<h1 class="flex items-center">
					<div class="ig_campaign_settings_links inline-block w-2/4">
						<ul class="ig-es-tabs inline-flex">
							<li id="campaign_content_menu" class="px-1 pb-2 text-center list-none cursor-pointer active ">
								<a href="#ig-campaign-overview" class=""><span class="mt-1 text-base font-medium tracking-wide text-gray-400 active"><?php esc_html_e( 'Overview', 'icegram' ) ?></span></a>
							</li>
							<li id="campaign_summary_menu" class="px-1 pb-2 ml-5 text-center list-none cursor-pointer hover:border-2 ">
								<a href="#ig-campaign-content-design" class=""><span class="mt-1 text-base font-medium tracking-wide text-gray-400"><?php esc_html_e( 'Content & Design', 'icegram' ) ?></span></a>
							</li>
							<li id="campaign_summary_menu" class="px-1 pb-2 ml-5 text-center list-none cursor-pointer hover:border-2 ">
								<a href="#ig-campaign-display-rules" class=""><span class="mt-1 text-base font-medium tracking-wide text-gray-400"><?php esc_html_e( 'Display Rules', 'icegram' ) ?></span></a>
							</li>
						</ul>
					</div>

					<?php

					/* Campaign CTAS */
					$campaign_ctas = '<div class="campaign_ctas right-0 float-right relative inline-block w-2/4">';
					
					if ( ! in_array( $post->post_status, array( 'publish' ), true ) ) { 
						
						$campaign_ctas .= get_submit_button( __('Publish'), 'primary large float-right ig_campaign_publish', 'publish', false );
						
						$campaign_ctas .= '<input type="submit" name="save" id="save-post" value="' . esc_attr__( 'Save Draft' ) . '" class="button float-right ig_campaign_save" />';

					} else {
						
						$campaign_ctas .= get_submit_button( __( 'Update' ), 'primary large float-right ig_campaign_update', 'save', false, array( 'id' => 'publish' ) );
						
						$campaign_ctas .= '<button class="button button-secondary float-right ig_campaign_switch_draft" type="submit" name="post_status" id="post_status" value="Draft">' . __('Switch to Draft', 'icegram') . '</button>' ;
					}

					$campaign_ctas .= '<div class="button button-secondary float-right campaign_preview">' . esc_html__( 'Preview', 'icegram' ) . '</div>';
					
					$campaign_ctas .= '<div class="button button-secondary float-right load_display_rules">' . esc_html__( 'Load Display Rules', 'icegram' ) . '</div>';
					
					$campaign_ctas .= '</div>';
					
					echo $campaign_ctas;
				?>
				</h1>
			</div>
			<div class="campaign_box hidden load_rules_popup">
				<div class="fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);">
					<div id="load-rules-main-container" class="absolute h-fit py-5 ml-16 mr-4 text-left bg-white rounded shadow-xl md:max-w-5xl lg:max-w-7xl md:pt-3 lg:pt-2" style="z-index:999">
						
						<div class="px-5 py-2 flex border-b border-gray-200">
							<h3 class="w-full text-2xl text-left">
								<?php
									echo esc_html__( 'Load display rules from', 'email-subscribers' );
								?>
							</h3>
							<div>			
								<span class="close-display-rules-popup cursor-pointer text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg">
									<svg class="h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
									</svg>
								</span>
							</div>
						</div>
						<div class="block flex pt-6 pb-4 px-5">
							<span class="text-sm font-medium pt-1 w-1/4">
							<?php 
								echo esc_html__('Choose a campaign', 'icegram');
							?>
							</span>
							<select class="form-select" name="load_display_rules_from">
								<option value=""> <?php echo esc_html__('Select a campaign', 'icegram');  ?> </option>
									<?php
										if ( ! empty( $ig_campaigns ) && is_array( $ig_campaigns ) ) {
							 				foreach ( $ig_campaigns as $campaign ) {
											?>
												<option value="<?php echo esc_html( $campaign->ID); ?>"><?php echo esc_html($campaign->post_title); ?></option>
											<?php
											}
										}
									?>
							</select>
						</div>
						<div class="px-7 pt-2 float-right">
							<button class="button button-primary button-large ig_campaign_load_rules" type="submit" name=""><?php echo esc_html__('Done', 'icegram'); ?></button>
							<button class="button button-secondary close-display-rules-popup"><?php echo esc_html__('Cancel', 'icegram'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		// Display list of messages of campaign tab
		public function campaign_data_tab_content($tabs) {

			$tab_id = 'main';
			$tab_class = 'campaign_data';
			
			
			$tabs['tabs'][$tab_id] = '';
			ob_Start();
			self::campaign_data_content();
			$tabs['tabs'][$tab_id] .= '<div id="ig-campaign-content-design"><div id="ig-admin-tab-'. $tab_id .'" class="variation-block ig-admin-tab '. $tab_class.'" variation="' . $tab_id . '">' . ob_get_clean() .'</div></div>';

			return $tabs;
		}

		// Campaign targeting rules tab
		public function campaign_target_rules_tab_content($tabs) {

			ob_Start();
			self::campaign_target_rules_content();
			$tabs .= '<div id="ig-campaign-display-rules" class="">' . ob_get_clean() .'</div>';

			return $tabs;
		}

		public function campaign_message_list(){
			global $post, $icegram;
			
			if( $post->post_type != 'ig_campaign' ) return;

			$messages = array();
			$messages = self::get_icegram_campaign_messages($messages, $post->ID);

			$tab_id = 'main';
			$tab_class = 'campaign_data'; // space seperated classes

			$tabs['main_message'][$tab_id] = array();
			$tabs['main_message'][$tab_id]['name'] = '<div class="ig-admin-nav"><p variation="' .$tab_id .'" class="py-3 pl-8 text-sm font-medium  ig-admin-nav-'. $tab_id . '"><a href="#ig-admin-tab-'. $tab_id .'">'. __( 'Messages', 'icegram' ) .'</a></p></div>';
			$tabs['main_message'][$tab_id]['messages'] = $messages;


			?>
			<div class="stats_cta text-right">
				<?php do_action('icegram_campaign_stats_cta') ?>
			</div>

			<!-- Message Navigation Table -->
			<table class="min-w-full border mt-3 mb-8" id="ig_message_list_table">
				<thead>	
					<tr class="bg-gray-100 text-sm text-left leading-4 text-gray-500 tracking-wider border-b border-t border-gray-200 ">
						<th colspan="3">
							<div>
								<div class="message-list-header-name inline-block pl-8 py-4 font-medium" scope="col"><?php echo esc_html_e('Messages', 'icegram') ?></div>
								<div class="mx-2 inline relative top-1 float-right" id="ig_campaign_cta">
										<?php do_action('icegram_add_campaign_ctas') ?>
								</div>
							</div>
						</th>
					</tr>
				</thead>
				<tbody class="bg-white">	
				<?php 
					
					$icegram_tab_list = apply_filters('icegram_campaign_messages_list', $tabs);
					
					$icegram_message_meta_key = 'messages';
					if ( !empty( $icegram_tab_list ) ) {
						foreach( $icegram_tab_list as $messages_list => $message_type) {
							if( is_array($message_type)){
								foreach ($message_type as $variation => $value) {
									$meta_key = $variation;
									$icegram_message_meta_key = apply_filters('icegram_message_meta_key', 'messages', $meta_key);
								?>	
									<tr class="variation_row border-b text-sm font-normal text-gray-700 border-gray-200" variation="<?php echo esc_html( $variation ) ?>">
										<td class="w-3/12">
											<?php echo $value['name']; ?>	
										<td class="message-list-col pl-2" style="width: 62%">
											<?php
												if( ! empty($value['messages'])) {
													foreach ( $value['messages'] as $row => $message ) {
														
														$message_title = get_the_title( $message['id'] );
														$message_data = get_post_meta( $message['id'], 'icegram_message_data', true );
														$message_type = ( !empty( $message_data['type'] ) ) ? $message_data['type'] : '';
														$message_id = is_numeric( $message['id'] ) ? $message['id'] : ''; 
														$class = ( !empty( $icegram->message_types[ $message_type ] ) ) ? $message_type : 'unknown';
														
														?>
														<div class="form-field message-row px-1 py-2 inline-block" value="<?php echo esc_attr( $message_id ); ?>">
															<div class="message_edit inline-block hover:bg-gray-200 cursor-pointer px-2 py-1 rounded">
																<div class="message_header inline-block">
																	<label class="message_header_label <?php echo "ig_".$message_type ." " .$class; ?>"><?php echo esc_attr($class); ?></label>
																</div>
																<div class="message_title inline-block">
																	<span class="message-title-text font-medium"><?php echo $message_title; ?></span>
																	
																</div>
															
																
																<div class="action_links inline-block">
																	<svg class="actions message_delete h-4 w-4 -mt-1 inline hover:bg-white" title="<?php esc_html_e( 'Remove from Campaign', 'icegram' ); ?>" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
																		  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
																	</svg>
																	
																</div>
															</div>
														</div>
													
													<?php
													}
												} ?>
											<div class="empty_variation_messages" style="display: none">
												<?php
												echo esc_html__( 'No messages yet. Use search / create bar above to add messages to this campaign.', 'icegram' );
												?>
											</div>
										</td>
										<td class="text-right">
											<a class="add-message-button text-gray-500 text-xs px-2 py-1 border border-white font-medium hover:border-gray-200 rounded cursor-pointer hover:bg-gray-100 hover:border-gray-300  ">
												<svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
											  		<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
												</svg>
												<span class="add_message_txt"><?php echo esc_html__('Message', 'icegram'); ?></span>
											</a>
											<?php
											$campaign_box =  '<select id="icegram_messages" name="icegram_messages[]" class="ajax_chosen_select_messages form-select" data-placeholder="' . __( 'Search to add &hellip;', 'icegram' ) . '">';
											$campaign_box .= '<option value=""></option>';
											
											$campaign_box .= '</select>';
											
											$title = '<label class="options_header" for="icegram_messages"><p class="inline-block font-medium text-gray-600 text-sm w-28">' . __( 'Add a message', 'icegram' ) . '</p></label>';
											?>
											
											<div class="campaign_box hidden add-message-popup">
												<div class="fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);">
													<div id="add-message-main-container" class="absolute h-1/3 pt-8 ml-16 mr-4 text-left bg-white rounded shadow-xl w-2/5 md:max-w-5xl lg:max-w-7xl md:pt-3 lg:pt-2" style="z-index:999">
														
														<div class="px-4 py-2 flex border-b border-gray-200">
															<h3 class="w-full text-2xl text-left">
																<?php
																	echo esc_html__( 'Message', 'email-subscribers' );
																?>
															</h3>
															<div>			
																<span class="close-add-message-popup cursor-pointer text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg">
																	<svg class="h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
																		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
																	</svg>
																</span>
															</div>
														</div>
														<div class="block pt-4 pb-2 px-5" id="search_message">
															<?php 
																echo $title;
																echo $campaign_box; 
																?>
														</div>
														
													</div>
												</div>
											</div>
										</td>
									</tr>

									
									

									<?php

										
								}
							}
						
						}
					} 

					 do_action('icegram_blank_variation', array( 'title'=> $title, 'campaign_box' => $campaign_box) );
					 ?>
				</tbody>		
			</table>

			<?php 
			do_action('icegram_additional_campaign_data');
			
		}

		// Display list of messages of campaign
		public function campaign_data_content() {
			global $post, $icegram;
			$ig_message_admin = Icegram_Message_Admin::getInstance();

			?>
				
			
			<div class="campaign_target_rules_panel">
				<div class="options_group">
					<div class="messages-list">
						<table class="messages_list_table">
							
							<tbody>
								<?php 
								$messages = array();
								$messages = apply_filters('icegram_campaign_messages', $messages, $post->ID);
								$icegram_message_meta_key = apply_filters('icegram_message_meta_key', 'messages');
								
								if ( !empty( $messages ) ) {
									foreach ( $messages as $row => $message ) {
													
										$message_title = get_the_title( $message['id'] );
										$message_data = get_post_meta( $message['id'], 'icegram_message_data', true );
										
										$message_type = ( !empty( $message_data['type'] ) ) ? $message_data['type'] : '';
										$message_id = is_numeric( $message['id'] ) ? $message['id'] : ''; 
										
										$class = ( !empty( $icegram->message_types[ $message_type ] ) ) ? $message_type : 'unknown';
											//if ( empty( $icegram->message_types[ $message_type ] ) ) continue;
										?>
										<tr class="message-row basic-message-fields" value="<?php echo esc_attr( $message_id ); ?>">
											<td>
												<p class="inline-block w-28 font-bold message_title"><?php _e( 'Message name', 'icegram' ); ?></p>
												<p class="inline-block message_title w-2/3">
													<input type="text" class="message-title-input w-full" name="message_data[<?php echo esc_attr( $message_id ); ?>][post_title]" value="<?php echo esc_attr($message_title); ?>" placeholder="<?php echo esc_html__( 'Give this message a name for your own reference', 'icegram' ); ?>">
												</p>
												<br/>
												<p class="message_seconds inline-block w-28 font-bold message_title"><?php _e( 'Show After', 'icegram' ); ?>
												</p>
												<p class="message_seconds my-2 inline-block">
													<input type="hidden" name="<?php echo $icegram_message_meta_key .'['.$row; ?>][id]" value="<?php echo esc_attr( $message_id )?>">
													<input type="number" class="seconds-text form-input" name="<?php echo $icegram_message_meta_key .'['.$row; ?>][time]" min="-1" value="<?php echo ( !empty( $message['time'] ) ) ? esc_attr( $message['time'] ) : 0; ?>" size="3" />
													<?php _e( ' sec', 'icegram' )?>
												</p>
										
										
												<div id="message_row_<?php echo esc_attr( $message_id ); ?>" class="message-edit-row ig_message" style="display: none;">
											
											
													<?php 
													$ig_message_admin->message_form_fields( '', array( 'message_id' => $message['id'] ) );
													?>
												</div>
											</td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
						<div class="empty_campaign">
							<?php
							echo esc_html__( 'No messages yet. Use search / create bar above to add messages to this campaign.', 'icegram' );
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		
		// Campaign targeting rules metabox
		function campaign_target_rules_content() {
			global $post;

			wp_nonce_field( 'icegram_campaign_save_data', 'icegram_campaign_meta_nonce' );
			$campaign_target_rules = get_post_meta( $post->ID, 'icegram_campaign_target_rules', true );

			if( empty( $campaign_target_rules ) ) {
				$campaign_target_rules = $this->default_target_rules;
			}
			
			?>
			
			<div class="campaign_target_rules_panel">						
				<?php do_action( 'icegram_campaign_target_rules', $post->ID, $campaign_target_rules ); ?>
			</div>
			<?php
		}

		function get_icegram_campaign_messages($messages, $campaign_id, $key = ''){
			$messages = get_post_meta($campaign_id, 'messages', true);
			
			return $messages;
		}

		// Display setting fields for campaign targeting rules
		function icegram_add_campaign_target_rules( $campaign_id, $campaign_target_rules  ) {
			global $wp_roles;
			?>
			<div class="options_group p-3" id="campaign_target_rules_where">
				<p class="form-field pt-4">
					<label class="options_header">
						<span class="font-semibold text-sm"><?php _e( 'Where?', 'icegram' ); ?></span>
						<span class="help_tip admin_field_icon float-none mt-0 ml-1" data-tip="<?php _e('Messages in this campaign will be shown when all these rules match...', 'icegram') ?>"></span>
					</label>
				</p>
				<p class="form-field py-2">
					<label for="where_sitewide">
						
						<input type="checkbox" name="campaign_target_rules[sitewide]" id="where_sitewide" value="yes" class="form-checkbox mr-1" <?php ( !empty( $campaign_target_rules['sitewide'] ) ) ? checked( $campaign_target_rules['sitewide'], 'yes' ) : ''; ?> />
						<span class="text-gray-600"><?php _e( 'Sitewide', 'icegram' ); ?></span></p>
					</label>
				</p>
				<p class="form-field py-1 " <?php echo ( !empty( $campaign_target_rules['sitewide'] ) && $campaign_target_rules['sitewide'] == 'yes' ) ? '' : 'style="display: none;"'; ?>>
					
					<?php 
					echo '<select name="exclude_page_id[]" id="exclude_page_id" data-placeholder="' . __( 'Select pages to exclude&hellip;', 'icegram' ) .  '" style="min-width:300px;" class="icegram_chosen_page" multiple>';
					foreach ( get_pages() as $page ) {
						echo '<option value="' . $page->ID . '"';
						if( !empty( $campaign_target_rules['exclude_page_id'] ) ) {
							echo selected( in_array( $page->ID, $campaign_target_rules['exclude_page_id'] ) );
						}
						echo '>' . $page->post_title . '</option>';
					}
					echo '</select>';
					?>
				</p>
				<p class="form-field py-1">
					
					<label for="where_homepage">
						<input type="checkbox" name="campaign_target_rules[homepage]" id="where_homepage" value="yes" class="form-checkbox mr-1" <?php ( !empty( $campaign_target_rules['homepage'] ) ) ? checked( $campaign_target_rules['homepage'], 'yes' ) : ''; ?> />
						<?php _e( 'Homepage', 'icegram' ); ?>
					</label>
				</p>
				<p class="form-field py-1">
					
					<label for="where_other_page">
						<input type="checkbox" name="campaign_target_rules[other_page]" id="where_other_page" value="yes" class="form-checkbox mr-1" <?php ( !empty( $campaign_target_rules['other_page'] ) ) ? checked( $campaign_target_rules['other_page'], 'yes' ) : ''; ?> />
						<?php _e( 'Selected pages', 'icegram' ); ?>
					</label>
				</p>
				<p class="form-field py-1" <?php echo ( !empty( $campaign_target_rules['other_page'] ) && $campaign_target_rules['other_page'] == 'yes' ) ? '' : 'style="display: none;"'; ?>>
					
					<?php 
					echo '<select name="page_id[]" id="where_page_id" data-placeholder="' . __( 'Select a page&hellip;', 'icegram' ) .  '" style="min-width:300px;" class="icegram_chosen_page" multiple>';
					foreach ( get_pages() as $page ) {
						echo '<option value="' . $page->ID . '"';
						if( !empty( $campaign_target_rules['page_id'] ) ) {
							echo selected( in_array( $page->ID, $campaign_target_rules['page_id'] ) );
						}
						echo '>' . $page->post_title . '</option>';
					}
					echo '</select>';
					?>
				</p>
				<?php
				do_action( 'icegram_after_campaign_pages_where_rule', $campaign_id, $campaign_target_rules );
				?>
				<p class="form-field py-1">
					
					<label for="where_local_url">
						<input type="checkbox" name="campaign_target_rules[local_url]" id="where_local_url" value="yes" class="form-checkbox mr-1" <?php ( !empty( $campaign_target_rules['local_url'] ) ) ? checked( $campaign_target_rules['local_url'], 'yes' ) : ''; ?> />
						<?php _e( 'Specific URLs on this site', 'icegram' ); ?>
					</label>
				</p>
				<p class="form-field py-1 local_url" <?php echo ( !empty( $campaign_target_rules['local_url'] ) && $campaign_target_rules['local_url'] == 'yes' ) ? '' : 'style="display: none;"'; ?>>
					<?php 
					if(!empty($campaign_target_rules['local_urls'])){
						foreach ($campaign_target_rules['local_urls'] as $url) {?>
							<span><span id="valid-field"> </span>
							<input type="text" class="form-input url_input_field" data-option="local_url" name="campaign_target_rules[local_urls][]" value="<?php echo $this->site_url.$url ;?>"/><span class="delete-url text-sm"></span></span>
							<?php	
						}
					}else{ ?>
						<span><span id="valid-field"> </span>
						<input type="text" class="form-input url_input_field" data-option="local_url" name="campaign_target_rules[local_urls][]" value="<?php echo $this->site_url.'*' ;?>"/><span class="delete-url text-sm"></span></span>
					<?php }
					?>
					<br/><label class="options_header display-rules-add-url" id="add_local_url_row_label">&nbsp;</label><span id="add-url-icon"> </span><a  class="campaign_add_url" id="add_local_url_row" href="#"><?php _e( ' Add another', 'icegram' ); ?></a>
				</p>
				
				<?php
				do_action( 'icegram_after_campaign_where_rule', $campaign_id, $campaign_target_rules );
				?>
				<p class="form-field py-2">
					<span class="campaign_shortcode light">
						<?php echo sprintf(__( 'Additionally you can insert <code>[%s]</code> wherever you want to run this campaign.', 'icegram' ), 'icegram campaigns="' .$campaign_id . '"' ); ?>
					</span> 
				</p>
				
			</div>
			<div class="options_group p-3" id="campaign_target_rules_when">
				<p class="form-field py-2">
					<label class="options_header"><span class="font-semibold text-sm"><?php _e( 'When?', 'icegram' ); ?></span></label>
				</p>
				<p class="form-field py-1">
					<label for="when_always">
						<input type="radio" class="schedule_rule form-radio" name="campaign_target_rules[when]" id="when_always" value="always" <?php ( !empty( $campaign_target_rules['when'] ) ) ? checked( $campaign_target_rules['when'], 'always' ) : ''; ?> />
						<?php _e( 'Always', 'icegram' ); ?>
					</label>
				</p>
				<p class="form-field py-2">
					
					<label for="when_schedule">
						<input type="radio" class="schedule_rule form-radio" name="campaign_target_rules[when]" id="when_schedule" value="schedule" <?php ( !empty( $campaign_target_rules['when'] ) ) ? checked( $campaign_target_rules['when'], 'schedule' ) : ''; ?> />
						<?php _e( 'Schedule', 'icegram' ); ?>
						<span class="form-field" id="date_picker" <?php echo ( !empty( $campaign_target_rules['when'] ) && $campaign_target_rules['when'] == 'schedule' ) ? '' : 'style="display: none;"'; ?>>
							<label class="date_picker">
								<input type="text" class="date-picker form-input" name="campaign_target_rules[from]" value="<?php echo ( !empty( $campaign_target_rules['from'] ) ) ? esc_attr( $campaign_target_rules['from'] ) : ''; ?>" placeholder="<?php _e( 'From&hellip;', 'icegram' );?>" />
							</label>
							<label class="date_picker">
								<input type="text" class="date-picker form-input" name="campaign_target_rules[to]" value="<?php echo ( !empty( $campaign_target_rules['to'] ) ) ? esc_attr( $campaign_target_rules['to'] ) : ''; ?>" placeholder="<?php _e( 'To&hellip;', 'icegram' );?>" />
							</label>
						</span>
					</label>
				</p>
				<?php
				do_action( 'icegram_after_campaign_when_rule', $campaign_id, $campaign_target_rules );
				?>
			</div>
			<?php 
			do_action( 'icegram_additional_campaign_rules', $campaign_id, $campaign_target_rules );
			?>
			<div class="options_group p-3" id="campaign_target_rules_device">
				<p class="form-field py-2">
					<label class="options_header"><span class="font-semibold text-sm"><?php _e( 'Device?', 'icegram' ); ?></span></label>
				</p>
				<p class="form-field py-2">
					<label for="device_mobile" class="device" title="<?php _e( 'Mobile / Smartphones', 'icegram' ); ?>">
						<input type="checkbox" name="campaign_target_rules[mobile]" id="device_mobile" value="yes" class="form-checkbox" <?php ( !empty( $campaign_target_rules['mobile'] ) ) ? checked( $campaign_target_rules['mobile'], 'yes' ) : ''; ?> />
						<span class="device_mobile"></span>
					</label>
					<label for="device_tablet" class="device" title="<?php _e( 'Tablet', 'icegram' ); ?>">
						<input type="checkbox" name="campaign_target_rules[tablet]" id="device_tablet" value="yes" class="form-checkbox" <?php ( !empty( $campaign_target_rules['tablet'] ) ) ? checked( $campaign_target_rules['tablet'], 'yes' ) : ''; ?> />
						<span class="device_tablet"></span>
					</label>
					<label for="device_laptop" class="device" title="<?php _e( 'Desktop / Laptop', 'icegram' ); ?>">
						<input type="checkbox" name="campaign_target_rules[laptop]" id="device_laptop" value="yes" class="form-checkbox" <?php ( !empty( $campaign_target_rules['laptop'] ) ) ? checked( $campaign_target_rules['laptop'], 'yes' ) : ''; ?> />
						<span class="device_laptop"></span>
					</label>
				</p>
			</div>
			<div class="options_group p-3" id="campaign_target_rules_users">
				<p class="form-field py-2">
					<label class="options_header"><span class="font-semibold text-sm"><?php _e( 'Who?', 'icegram' ); ?></span></label>
				</p>
				<p class="form-field py-1">
					<label for="users_all">
						<input type="radio" name="campaign_target_rules[logged_in]" id="users_all" value="all" class="form-radio" <?php ( !empty( $campaign_target_rules['logged_in'] ) ) ? checked( $campaign_target_rules['logged_in'], 'all' ) : ''; ?> />
						<?php _e( 'All users', 'icegram' ); ?>
					</label>
				</p>
				<p class="form-field py-1">
					
					<label for="users_logged_in">
						<input type="radio" name="campaign_target_rules[logged_in]" id="users_logged_in" value="logged_in" class="form-radio" <?php ( !empty( $campaign_target_rules['logged_in'] ) ) ? checked( $campaign_target_rules['logged_in'], 'logged_in' ) : ''; ?> />
						<?php _e( 'Logged in users only', 'icegram' ); ?>
					</label>
				</p>
				
				<div class="user_roles">
					<?php
					if ( !empty( $campaign_target_rules['logged_in'] ) && ($campaign_target_rules['logged_in'] == 'all' || $campaign_target_rules['logged_in'] == 'not_logged_in') ) {
						$campaign_logged_in_user_style = 'style="display: none;"';
					} else {
						$campaign_logged_in_user_style = 'style="display: block;"';
					}
					?>
					<p class="form-field" <?php echo $campaign_logged_in_user_style; ?>>
						
						<?php
						if ( isset( $wp_roles ) ) {
							$wp_roles = new WP_Roles();
							$roles = $wp_roles->get_names();
							
							echo '<select name="campaign_target_rules[users][]" id="users_roles" data-placeholder="' . __( 'Select a user role&hellip;', 'icegram' ) .  '" style="min-width:300px;" class="icegram_chosen_page" multiple>';
							foreach ( $roles as $role_value => $role_name ) {
								echo '<option value="' . $role_value . '"';
								if( !empty( $campaign_target_rules['users'] ) ) {
									echo selected( in_array( $role_value, $campaign_target_rules['users'] ) );
								}
								echo '>' . $role_name . '</option>';
							}
							echo '</select>';
						}
						?>
					</p>
				</div>
				<p class="form-field py-1">
					
					<label for="users_not_logged_in">
						<input type="radio" name="campaign_target_rules[logged_in]" id="users_not_logged_in" value="not_logged_in" class="form-radio" <?php ( !empty( $campaign_target_rules['logged_in'] ) ) ? checked( $campaign_target_rules['logged_in'], 'not_logged_in' ) : ''; ?> />
						<?php _e( 'Not Logged in users', 'icegram' ); ?>
					</label>
				</p>
			</div>
				<?php 	$expiry_options_for_shown = array(  'current_session' => __('Current Session' ,'icegram'),
					'+50 years' => __('Never' ,'icegram'),
					'today' => __('Today' ,'icegram'),
					'+1 week' => __('One week' ,'icegram') ,
					'+2 week' => __('Two weeks' ,'icegram'),
					'+1 month' => __('One Month ' ,'icegram'),
					'+3 months' => __('Three Months ' ,'icegram') ,
					'+1 year' => __('One year' ,'icegram') ,
					'+2 years' => __('Two Years' ,'icegram')); 
				$expiry_options_for_clicked = array(  '+50 years' => __('Never' ,'icegram'),
					'current_session' => __('Current Session' ,'icegram'),
					'today' => __('Today' ,'icegram'),
					'+1 week' => __('One week' ,'icegram') ,
					'+2 week' => __('Two weeks' ,'icegram'),
					'+1 month' => __('One Month ' ,'icegram'),
					'+3 months' => __('Three Months ' ,'icegram') ,
					'+1 year' => __('One year' ,'icegram') ,
					'+2 years' => __('Two Years' ,'icegram')); 

					?>
					<div class="options_group p-3" id="campaign_target_rules_retargeting">
						<?php
						$html_content = '<p class="form-field py-2">
						<label class="options_header"><span class="font-semibold text-sm">'.__( 'Retargeting', 'icegram' ).'</span></label>
						</p>
						<p class="form-field py-1">
						<label for="retargeting">
						<input type="checkbox" name="campaign_target_rules[retargeting]" id="retargeting" value="yes" class="form-checkbox" '.(( !empty( $campaign_target_rules['retargeting'] ) ) ? checked( $campaign_target_rules['retargeting'], 'yes', false ) : '').'/>';
						$html_content .= __(' Once shown, do NOT show this campaign again for ', 'icegram' );
						$html_content .= '<select class="form-select" name="campaign_target_rules[expiry_time]">';
						foreach($expiry_options_for_shown as $key => $option){
							?>
							<?php	$html_content .= '<option value="'.$key.'"'.((!empty($campaign_target_rules['expiry_time'])) ? selected( $campaign_target_rules['expiry_time'], $key, false ) : "").'>'.$option.'</option>';
						} 
						$html_content .= '</select>';
						$html_content .= '</label>';
						$campaign_target_rules_retargeting = apply_filters('icegram_campaign_target_rules_retargeting' , array( 'html' => $html_content, 'campaign_target_rules' => $campaign_target_rules, 'expiry_options_for_shown' => $expiry_options_for_shown));
						echo $campaign_target_rules_retargeting['html'];
						?>
					</p>
					<p class="form-field py-1">
						
						<label for="retargeting_clicked">
							<input type="checkbox" name="campaign_target_rules[retargeting_clicked]" id="retargeting_clicked" value="yes" class="form-checkbox" <?php ( !empty( $campaign_target_rules['retargeting_clicked'] ) ) ? checked( $campaign_target_rules['retargeting_clicked'], 'yes' ) : ''; ?> />
							<?php _e( 'Once CTA is clicked, do NOT show this campaign again for', 'icegram' ); ?>
							<select class="form-select" name="campaign_target_rules[expiry_time_clicked]">
								<?php foreach($expiry_options_for_clicked as $key => $option){
									?>
									<option value="<?php echo $key; ?>" <?php (!empty($campaign_target_rules['expiry_time_clicked'])) ? selected( $campaign_target_rules['expiry_time_clicked'], $key ) : ''; ?>><?php echo $option; ?></option>
									<?php
								}
								?>
							</select>
						</label>
					</p>
				</div>
				<?php
			}

			// Return json encoded messages for searched term
			function icegram_json_search_messages( $x = '' ) {
				global $icegram;
				check_ajax_referer( 'search-messages', 'security' );

				header( 'Content-Type: application/json; charset=utf-8' );

				$term = isset( $_GET['term'] ) ? ( string ) urldecode( stripslashes( strip_tags( sanitize_text_field( $_GET['term'] ) ) ) ) : '';
				$post_types = array('ig_message');

				if ( empty( $term ) ) die();

				if ( is_numeric( $term ) ) {

					$args = array(
						'post_type'			=> $post_types,
						'post_status'	 	=> 'publish',
						'posts_per_page' 	=> -1,
						'post__in' 			=> array( 0, $term ),
						'fields'			=> 'ids'
					);

					$posts = get_posts( $args );

				} else {

					$args = array(
						'post_type'			=> $post_types,
						'post_status' 		=> 'publish',
						'posts_per_page' 	=> -1,
						's' 				=> $term,
						'fields'			=> 'ids'
					);

					$posts = get_posts( $args );

				}

				$found_messages = array();
				if ( $posts ) {

					foreach ( $posts as $post ) {

						$message_title 			= get_the_title( $post );
						$message_data 			= get_post_meta( $post, 'icegram_message_data', true );
						$message_type 				= ( !empty( $icegram->message_types[ $message_data['type'] ]['type'] ) ) ? $icegram->message_types[ $message_data['type'] ]['type'] : '';
						$found_messages[ $post ] 	= $message_type . ' &mdash; ' . $message_title;

					}
				// $found_messages[''] 	= __( '- - - - - - - - - - - - - - - - - - - - - - - - - -', 'icegram' );
				}

			// foreach ( $icegram->message_types as $message ) {
			// 	$found_messages[ $message['type'] ] = __( 'Create new', 'icegram' ) . ' ' . $message['name'] . ' ...';
			// }
				ob_clean();
				$found_messages = apply_filters( 'icegram_searched_messages', $found_messages, $term );
				echo json_encode( $found_messages );
				die();
			}


			// Return html for message row in json encoded format
			function get_message_action_row() {

				$ig_message_admin = Icegram_Message_Admin::getInstance();
				$ig_message_admin->is_icegram_editor = true;

				//check_ajax_referer( 'ig-nonce', 'security' );
				if ( empty( $_POST['message_id'] ) || !is_numeric( $_POST['message_id'] ) ) {

					$my_post = array(
						'post_status' => 'auto-draft',
						'post_type' 	=> 'ig_message'
					);
					$message_id 	= wp_insert_post( $my_post );
					$message_title 	= '';
					$message_type 	= sanitize_text_field( $_POST['message_id'] );

				} else {

					$message_id 	= sanitize_text_field( $_POST['message_id'] );
					$message_title 	= get_the_title( $message_id );
					$message_data 	= get_post_meta( $message_id, 'icegram_message_data', true );
					$message_type 	= $message_data['type'];

				}

				$variation = sanitize_text_field( $_POST['variation'] );
				ob_start();
				$icegram_message_meta_key = apply_filters('icegram_message_meta_key' , 'messages', $variation );
				$message_header_label     =  ucwords( str_replace( "-", ' ', $message_type ) );
				?>
				<div class="form-field message-row p-1 inline-block" value="<?php echo esc_attr( $message_id ); ?>">
					<div class="message_edit inline-block hover:bg-gray-200 cursor-pointer px-2 py-1 rounded">
						<div class="message_header inline-block">
							<label class="message_header_label <?php echo "ig_". esc_attr( $message_type ); ?>"><?php echo esc_html( $message_header_label ); ?></label>
						</div>
						<div class="message_title inline-block">
							<div class="message-title-text font-medium" ><?php echo esc_html( $message_title ); ?>
							</div>
							
						</div>
						
						<div class="action_links inline-block">
							
							<svg class="actions message_delete h-4 w-4 -mt-1 inline hover:bg-white" title="<?php esc_html_e( 'Remove from Campaign', 'icegram' ); ?>" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
								  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
							</svg>
						</div> 
					</div>
				</div>

				<?php
					$message_meta = ob_get_clean();

					ob_start();
				?>
				<tr class="message-row basic-message-fields" value="<?php echo esc_attr( $message_id ); ?>">
					<td>
						<p class="inline-block w-28 font-bold message_title"><?php _e( 'Message name', 'icegram' ); ?></p>
						<p class="inline-block message_title w-2/3">
							<input type="text" class="message-title-input w-full form-input" name="message_data[<?php echo esc_attr( $message_id ); ?>][post_title]" value="<?php echo esc_attr($message_title); ?>" placeholder="<?php echo esc_html__( 'Give this message a name for your own reference', 'icegram' ); ?>">
						</p>
						<br/>
						<p class="message_seconds inline-block w-28 font-bold message_title"><?php _e( 'Show After', 'icegram' ); ?>
						</p>
						<p class="message_seconds my-2 inline-block">
							<?php
								$row = isset( $_POST['row'] ) ? sanitize_text_field( $_POST['row'] ) : '';
							?>
							<input type="hidden" name="<?php echo $icegram_message_meta_key .'['.$row; ?>][id]" value="<?php echo esc_attr( $message_id )?>">
							<input type="number" class="seconds-text form-input" name="<?php echo $icegram_message_meta_key .'['.$row; ?>][time]" min="-1" value="<?php echo ( !empty( $message['time'] ) ) ? esc_attr( $message['time'] ) : 0; ?>" size="3" />
							<?php _e( ' sec', 'icegram' )?>
						</p>

						<div id="message_row_<?php echo esc_attr($message_id); ?>" class="message-edit-row">
							<div>
								<?php 
								$ig_message_admin->message_form_fields( '', array( 'message_type' => $message_type, 'message_id' => $message_id, 'new_message_row' => true ) );
								?>
							</div>
						</div>
				</td>
			</tr>
				<?php
				$message_settings = ob_get_clean();
				echo json_encode( array( 'id' => $message_id, 'message' => $message_meta, 'message_settings' => $message_settings ) );

				die();

			}

			// Save all list of messages and targeting rules
			function save_campaign_settings( $post_id, $post ) {

				if (empty( $post_id ) || empty( $post ) || empty( $_POST )) return;
				if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) return;
				if (is_int( wp_is_post_revision( $post ) )) return;
				if (is_int( wp_is_post_autosave( $post ) )) return;
				if ( empty( $_POST['icegram_campaign_meta_nonce'] ) || ! wp_verify_nonce( $_POST['icegram_campaign_meta_nonce'], 'icegram_campaign_save_data' ) ) return;
				if (! current_user_can( 'edit_post', $post_id )) return;
				if ($post->post_type != 'ig_campaign') return;
				
				$campaign_target_rules = '';

				if( ! empty( $_POST['load_display_rules_from'] ) ) {
					$campaign_target_rules = get_post_meta( $_POST['load_display_rules_from'], 'icegram_campaign_target_rules', true );

					if ( isset( $campaign_target_rules['page_id'] ) ) {
						update_post_meta( $post_id, 'icegram_campaign_target_pages', $campaign_target_rules['page_id'] );
					}
					if ( isset( $campaign_target_rules['exclude_page_id'] ) ) {
						update_post_meta( $post_id, 'icegram_campaign_target_pages', $campaign_target_rules['exclude_page_id'] );
					}

				} else{
					if ( isset( $_POST['campaign_target_rules'] ) ){
						$campaign_target_rules = apply_filters( 'icegram_update_campaign_rules', $_POST['campaign_target_rules'], $post_id );
					}

					if(!empty($campaign_target_rules) && !empty($campaign_target_rules['local_urls'])){
						foreach ($campaign_target_rules['local_urls'] as $key => $url) {
							if( !empty( $url ) ){
								$url = esc_url( $url );
								if( $url == '*'){
									$campaign_target_rules['local_urls'][$key] = $url;		
								}else{   							
									$url = str_replace($this->site_url, '', $url);
									$campaign_target_rules['local_urls'][$key] = $url;
								}				
							} else {
								unset($campaign_target_rules['local_urls'][$key]);
							}
						}
					}

					if ( isset( $_POST['page_id'] ) ) {
						$campaign_target_rules['page_id'] = $_POST['page_id'];
						update_post_meta( $post_id, 'icegram_campaign_target_pages', $_POST['page_id'] );
					}
					if ( isset( $_POST['exclude_page_id'] ) ) {
						$campaign_target_rules['exclude_page_id'] = $_POST['exclude_page_id'];
						update_post_meta( $post_id, 'icegram_campaign_target_pages', $_POST['exclude_page_id'] );
					}

				}

				if ( count( $campaign_target_rules ) > 0 ) {
					update_post_meta( $post_id, 'icegram_campaign_target_rules', $campaign_target_rules );
				}

				if ( empty( $_POST['messages'] ) ) {
					update_post_meta( $post_id, 'messages', array() );
				} else {
					$messages = $_POST['messages'];
					
					foreach( $messages as $message => $data ) {
						if( isset( $data['id'] ) ) {
							$messages[ $message ]['id'] = is_numeric( $data['id'] ) ? sanitize_text_field( $data['id']) : '';
						}
						if( isset( $data['time'] ) ) {
							$messages[ $message ]['time'] = sanitize_text_field( $data['time'] ); 
						}
					}

					update_post_meta( $post_id, 'messages', array_values( $messages ) );
					update_post_meta( $post_id, 'campaign_preview', array_values( $messages ) );

				// Saving $_POST to temp var before updating messages 
				// to avoid problems with action handlers that rely on
				// $_POST vars - e.g. WPML!!
					$old_post = $_POST;
					$_POST = array();

					foreach ( $old_post['message_data'] as $message_id => $message_data ) {

						$type = $message_data['type'];
						if( isset( $message_data['theme'][$type] ) ) {
							$message_data['theme'] = $message_data['theme'][$type];
						} else {
							unset( $message_data['theme'] );
						}
						if( isset( $message_data['animation'][$type] ) ) {
							$message_data['animation'] = $message_data['animation'][$type];
						} else {
							unset( $message_data['animation'] );
						}
						if( isset( $message_data['position'][$type] ) ) {
							$message_data['position'] = $message_data['position'][$type];
						} elseif( isset( $message_data['position']['ig_default'] ) ) {
							$message_data['position'] = $message_data['position']['ig_default'];
						}

						$sanitizing_fields = array( 'bg_color', 'text_color', 'cta_bg_color', 'cta_text_color', 'alt_cta_bg_color', 'alt_cta_text_color', 'form_bg_color', 'form_text_color' );
						
						$wpkses_fields_sanitization = array( 'post_title', 'title', 'headline', 'label', 'form_header', 'form_footer', 'response_text', 'alt_label' );

						foreach ( $message_data as $index => $value ) {
							if( in_array( $index, $sanitizing_fields) ) {
								$message_data[ $index ] = sanitize_text_field( $message_data[ $index ] );
							} 	

							if( in_array( $index, $wpkses_fields_sanitization) ) {
								$message_data[ $index ] = wp_kses_post( $message_data[ $index ] );
							}
							
						}

						if( empty( $message_data['rainmaker_form_code'] ) && ! empty( $message_data['form_html_original'] ) && strpos( $message_data['form_html_original'], 'rainmaker_form') )  {
							$rm_shortcode_pos = strpos($message_data['form_html_original'], '"')+1;
							$rm_shortcode = substr($message_data['form_html_original'], $rm_shortcode_pos , strripos($message_data['form_html_original'], "\\") - $rm_shortcode_pos);
							$message_data['rainmaker_form_code'] = $rm_shortcode ;
						}
					//save message data when campaign is save
						$message_data = apply_filters( 'icegram_update_message_data', $message_data, $message_id );
						update_post_meta( $message_id, 'icegram_message_data', $message_data );
						update_post_meta( $message_id, 'icegram_message_preview_data', $message_data );
						wp_update_post( array ( 'ID' 			=> $message_id,
							'post_content' 	=> $message_data['message'],
							'post_status'	=> 'publish',
							'post_title'	=> empty( $message_data['post_title'] ) ? $message_data['headline']: sanitize_text_field( $message_data['post_title'] )
						) );			
					}
					$_POST = $old_post;
				}
			}

			// On preview button click save campaign messages list
			function save_campaign_preview() {

				// check_ajax_referer( 'ig-nonce', 'security' );

				if ( empty($_POST['post_ID']) ) die();
				$post_id = sanitize_text_field( $_POST['post_ID'] );
				if ( !current_user_can( 'edit_post', $post_id ) ) die();

				$messages = apply_filters('campaign_preview_messages',  $_POST['messages'], $_POST);
				
				if( !empty( $messages ) ) {
					update_post_meta( $post_id, 'campaign_preview', $messages ) ;
					if( isset( $_POST['message_data'] ) ) {
						foreach ( (array) $_POST['message_data'] as $message_id => $message_data ) {
							$type = $message_data['type'];
							if( isset( $message_data['theme'][$type] ) ) {
								$message_data['theme'] = $message_data['theme'][$type];
							} else {
								unset( $message_data['theme'] );
							}
							if( isset( $message_data['animation'][$type] ) ) {
								$message_data['animation'] = $message_data['animation'][$type];
							} else {
								unset( $message_data['animation'] );
							}
							if( isset( $message_data['position'][$type] ) ) {
								$message_data['position'] = $message_data['position'][$type];
							} elseif( isset( $message_data['position']['ig_default'] ) ) {				
								$message_data['position'] = $message_data['position']['ig_default'];
							}
							$message_data = apply_filters( 'icegram_update_message_preview_data', $message_data, $message_id );
							update_post_meta( $message_id, 'icegram_message_preview_data', $message_data );
						}
					}
				// Determine page url to preview on...
					$page_url = '';
					
					if ( !empty($_POST['campaign_target_rules']) && !empty($_POST['campaign_target_rules']['other_page']) && !empty($_POST['page_id']) && is_array($_POST['page_id'])) {
						$page_url = isset( $_POST['page_id'][0] ) ? get_permalink( sanitize_text_field( $_POST['page_id'][0] ) ) : '';
					}
					if ($page_url == '') {
						if(!empty($_POST['campaign_target_rules']['local_url']) && is_array($_POST['campaign_target_rules']['local_urls'])){
							$local_urls = sanitize_text_field( $_POST['campaign_target_rules']['local_urls'][0] );
							$page_url = (strpos( $local_urls, '*') === false) ? $local_urls : home_url();
						}else{
							$page_url = home_url();
						}
					}
					ob_clean();
					echo esc_url(add_query_arg( 'campaign_preview_id', $post_id, $page_url ));
				}
				die();

			}

			function add_campaign_action( $actions, $post ){
				if ($post->post_type != 'ig_campaign') return $actions;

				// Create a nonce & add an action
				$actions['duplicate_campaign'] = '<a class="ig-duplicate-campaign"  href="post.php?campaign_id='.$post->ID.'&action=duplicate-campaign" >'.__('Duplicate' ,'icegram').'</a>';
				$actions['preview_campaign'] = '<a class="ig-preview-campaign" target="_blank" href="'.home_url().'?campaign_preview_id='.$post->ID.'" >'.__('Preview' ,'icegram').'</a>';
				return $actions;
			}

			function duplicate_campaign(){
				if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'duplicate-campaign' && !empty($_REQUEST['campaign_id'])){
					Icegram::duplicate( sanitize_text_field( $_REQUEST['campaign_id'] ) );
				}
			}

		}
	}