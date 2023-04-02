<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Icegram Message Admin class
 */
if ( ! class_exists( 'Icegram_Message_Admin' ) ) {

	class Icegram_Message_Admin {

		var $message_themes;
		var $is_icegram_editor;

		private function __construct() {

			add_action( 'add_meta_boxes', array( &$this, 'add_message_meta_boxes' ) );
			add_action( 'wp_ajax_get_message_setting', array( &$this, 'message_form_fields' ) );

			add_action( 'save_post', array( &$this, 'update_message_settings' ), 10, 2 );
			add_filter( 'wp_insert_post_data', array( &$this, 'save_message_in_post_content' ) );

			add_filter( 'manage_edit-ig_message_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_ig_message_posts_custom_column', array( $this, 'custom_columns' ), 2 );
			add_filter( 'icegram_available_headlines', array( &$this, 'available_headlines' ) );

			//duplicate message
			add_filter( 'post_row_actions', array( &$this, 'add_message_action' ), 10, 2 );
			add_action( 'admin_init', array( &$this, 'duplicate_message' ), 10, 1 );

		}

		public static function getInstance() {
			static $ig_message_admin = null;
			if ( null === $ig_message_admin ) {
				$ig_message_admin = new Icegram_Message_Admin();
			}

			return $ig_message_admin;
		}

		// Initialize message metabox		
		function add_message_meta_boxes() {
			global $icegram;
			add_meta_box( 'message-settings', __( 'Message Settings', 'icegram' ), array( &$this, 'message_form_fields' ), 'ig_message', 'normal', 'high' );

		}

		// Display all message settings fields
		function message_form_fields( $post = '', $action = array() ) {
			global $icegram, $pagenow;
			if ( ( is_object( $post ) && $post->post_type != 'ig_message' ) ) {
				return;
			}
			?>

			<style type="text/css">
				<?php
				foreach ( $icegram->message_types as $message_type => $message ) {
					if( !empty( $message['admin_style'] ) ) {
						$message_type = 'ig_'.$message_type;
						$label_bg_color 		= $message['admin_style']['label_bg_color'];
						$theme_header_height 	= (int)$message['admin_style']['theme_header_height'];
						$theme_header_bg_size	= ( $theme_header_height + 3 )."em";					
						$thumbnail_width 		= $message['admin_style']['thumbnail_width'];
						$thumbnail_height 		= $message['admin_style']['thumbnail_height'];
						echo "	.message_header .$message_type { 
							background-color: {$label_bg_color}; 
						} 
						.message_theme_{$message_type} + .chosen-container-single .chosen-single { 
							height: {$theme_header_height} !important;
						}
						.message_theme_{$message_type} + .chosen-container-single .chosen-single span {
							background-size: {$theme_header_bg_size} !important;
							line-height: {$theme_header_height} !important;
						} 
						.message_theme_{$message_type} + .chosen-container .chosen-results li {
							width: {$thumbnail_width} !important;
							height: {$thumbnail_height} !important;
						}";
					}

				}
				?>
			</style>
			<?php
			$message_id        = ! empty( $action['message_id'] ) ? $action['message_id'] : $post->ID;
			$message_data      = get_post_meta( $message_id, 'icegram_message_data', true );
			$message_headlines = $icegram->available_headlines;
			$settings          = $this->message_settings_to_show();
			$positions         = $this->message_positions_to_show();
			if ( $pagenow == 'post-new.php' ) {
				$message_title_key     = array_rand( $message_headlines );
				$default_message_title = $message_headlines[ $message_title_key ];
			} else {
				$default_message_title = $message_title_key = '';
			}
			$message_headline = ( isset( $message_data['headline'] ) ) ? $message_data['headline'] : $default_message_title;

			if ( empty( $message_data ) ) {
				$message_type = ! empty( $action['message_type'] ) ? $action['message_type'] : '';
				$message_data = $this->default_message_data( $message_type );
			}

			if ( ! empty( $action['message_type'] ) ) {
				$message_data['type'] = $action['message_type'];
			}

			wp_nonce_field( 'icegram_message_save_data', 'icegram_message_meta_nonce' );
			if ( ! empty( $action['message_id'] ) ) {
				?>
				<div class="thickbox_edit_message" id="<?php echo $action['message_id']; ?>">
					<?php
				}
				?>
				
				<div class="wp_attachment_details edit-form-section message-setting-fields">
					<div class="icegram_tw">
						<div class="rounded w-full mx-auto mt-8">
							<!-- Tabs -->
							<ul id="tab-menu" class="inline-flex pt-2 w-full border-b text-sm">
								<li>
									<a id="ig-design-tab" class="message-settings-tab px-4 text-gray-400 font-semibold py-2.5 rounded-t border-t border-r border-l active" id="default-tab" href="#ig_message_create">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 inline relative bottom-0.5" viewBox="0 0 20 20" fill="currentColor">
										 	<path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
										    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
										</svg>
										<?php echo esc_html__('Design/Create', 'icegram') ?></a>
								</li>
								<li>
									<a id="ig-style-tab" class="message-settings-tab px-4 text-gray-400 font-semibold py-2.5 rounded-t border-t border-r border-l" href="#ig_message_styling">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 inline relative bottom-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										  	<path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
										</svg>
										<?php echo esc_html__('Styling', 'icegram') ?></a>
								</li>
								<li>
									<a id="ig-behavior-tab" class="message-settings-tab px-4 text-gray-400 font-semibold py-2.5 rounded-t border-t border-r border-l" href="#ig_message_behavior">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 inline relative bottom-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
										</svg>
										<?php echo esc_html__('Behavior', 'icegram') ?></a>
								</li>
							</ul>

								<!-- Tab Contents -->
							<div id="tab-contents" class="border-b border-l border-r py-4">
								<!-- Create Message Tab -->
								<div id="ig_message_create" class="message-tabs px-4 active">
										<?php 
										$message_type = isset( $message_data['type'] ) ? $message_data['type'] : '';
										?>
										<input id="message_type" class="message_type" name="message_data[<?php echo $message_id; ?>][type]" type="hidden" value="<?php echo esc_attr( $message_type ) ?>"></input>
										<input id="message_theme_ig_<?php echo esc_attr( $message_type ) ?>" name="message_data[<?php echo $message_id; ?>][theme][<?php echo esc_attr( $message_type ) ?>]" type="hidden" value="<?php echo esc_attr( $message_data['theme'] ) ?>"></input>
										<?php // action add for interstitial message setting
										do_action( 'icegram_after_message_theme_settings', $message_id, $message_data );
										?>
										<p class="p-3 message_row <?php echo "ig_" . implode( ' ig_', $settings['headline'] ) ?>">
											<label for="message_headline" class="message_label">
												<span class="font-semibold text-sm"><?php _e( 'Headline', 'icegram' ); ?></span>
												<span class="help_tip admin_field_icon mr-1.5" data-tip="<?php _e( 'Shown with highest prominence. Click on idea button on right to get a new headline.', 'icegram' ); ?>"></span>
											</label>
											<?php
											$message_headline = ( isset( $message_data['headline'] ) ) ? $message_data['headline'] : $default_message_title;
											?>
											<input type="text" class="message_field form-input" name="message_data[<?php echo $message_id; ?>][headline]" id="message_title" value="<?php echo esc_attr( $message_headline ); ?>" data-headline="<?php echo $message_title_key; ?>"/>
											<a class="button message_headline_button tips ml-1.5" data-tip="<?php _e( 'Give Me Another Headline', 'icegram' ); ?>">
												<span class="headline-buttons-icon admin_field_icon"></span>
											</a>
										</p>
										<p class="p-3 message_row <?php echo "ig_" . implode( ' ig_', $settings['icon'] ) ?>">
											<label for="upload_image" class="message_label">
												<span class="font-semibold text-sm"><?php _e( 'Icon / Avatar Image', 'icegram' ); ?></span>
												<span class="help_tip admin_field_icon mr-1.5" data-tip="<?php _e( 'This image will appear in message content.', 'icegram' ); ?>"></span>
											</label>
											<input id="upload_image" type="text" class="message_field form-input" name="message_data[<?php echo $message_id; ?>][icon]" value="<?php if ( isset( $message_data['icon'] ) ) {
												echo esc_attr( $message_data['icon'] );
											} ?>"/>
											<a class="button message_image_button tips ml-1.5" data-tip="<?php _e( 'Upload / Select an image', 'icegram' ); ?>" onclick="tb_show('<?php _e( 'Upload / Select Image' ); ?>', 'media-upload.php?type=image&TB_iframe=true', false);">
												<span class="image-buttons-icon admin_field_icon"></span>
											</a>
										</p>

										<?php
										$editor_args = array(
											'textarea_name' => 'message_data[' . $message_id . '][message]',
											'textarea_rows' => 10,
											'editor_class'  => 'wp-editor-message form-textarea',
											'media_buttons' => true,
											'tinymce'       => true
										);
										?>
										<p class="p-3 message_row <?php echo "ig_" . implode( ' ig_', $settings['message'] ) ?>">
											<style type="text/css">.wp-editor-tools:after {
												display: inline-block !important;
											}</style>
											<label for="message_body" class="message_body message_label"><span class="font-semibold text-sm"><?php _e( 'Message Body', 'icegram' ); ?></span></label>
											<?php
											$message = ( ! empty( $message_data['message'] ) ) ? $message_data['message'] : '';
											//TODO :: check need of exit-redirect Type
											if ( in_array( $message_type, array( 'toast', 'badge', 'ribbon', 'exit-redirect' ) ) ) {
												$message = str_replace( '[ig_form]', '', $message );
											}
											?>

											<?php wp_editor( $message, 'edit' . $message_id, $editor_args ); ?>
										</p>
										<?php foreach ( $icegram->message_types as $message ) {
											if ( empty( $message['settings']['animation']['values'] ) ) {
												continue;
											}
											$animations = $message['settings']['animation']['values']
											?>
											<p class="p-3 message_row <?php echo "ig_" . $message['type']; ?>">
												<label for="message_animation_<?php echo $message['type'] ?>" class="message_label">
													<span class="font-semibold text-sm"><?php _e( 'Animation', 'icegram' ); ?></span>
												</label>
												<select class="form-select" id="message_animation_<?php echo $message['type'] ?>" name="message_data[<?php echo $message_id; ?>][animation][<?php echo $message['type'] ?>]" class="icegram_chosen_page message_animation message_animation_<?php echo $message['type']; ?>">
													<?php asort( $animations );
													$animations = array( 'no-anim' => 'No Animation' ) + $animations;
													foreach ( $animations as $value => $label ) { ?>
														<option value="<?php echo esc_attr( $value ) ?>" <?php echo ( ! empty( $message_data['animation'] ) && esc_attr( $value ) == $message_data['animation'] ) ? 'selected' : ''; ?>><?php echo esc_html( $label ) ?></option>
													<?php } ?>
												</select>
											</p>
										<?php } ?>

										<!-- Embed Form options -->

										<?php

										$form_html           = ( ! empty( $message_data['form_html'] ) ) ? $message_data['form_html'] : '';
										$form_html_original  = ( ! empty( $message_data['form_html_original'] ) ) ? $message_data['form_html_original'] : '';
										$form_header         = ( ! empty( $message_data['form_header'] ) ) ? $message_data['form_header'] : '';
										$form_footer         = ( ! empty( $message_data['form_footer'] ) ) ? $message_data['form_footer'] : '';
										$form_bg_color       = ( ! empty( $message_data['form_bg_color'] ) ) ? $message_data['form_bg_color'] : '';
										$form_text_color     = ( ! empty( $message_data['form_text_color'] ) ) ? $message_data['form_text_color'] : '';
										$form_has_label      = ( ! empty( $message_data['form_has_label'] ) ) ? checked( $message_data['form_has_label'], 'yes', 0 ) : '';
										$form_layouts        = $this->message_form_layouts_to_show();
										$default_form_layout = ! empty( $icegram->message_types[ $message_data['type'] ]['settings']['form_layout']['default'] ) ? $icegram->message_types[ $message_data['type'] ]['settings']['form_layout']['default'] : '';
										$use_form_check      = ( ! empty( $message_data['use_form'] ) )
										? checked( $message_data['use_form'], 'yes', 0 )
										: '';
										$show_form_options   = empty( $use_form_check ) ? 'style="display:none;"' : '';
										?>
										<p class="p-3 message_row message_form_options_check <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>" message_id="<?php echo $message_id; ?>">
											<label for="message_use_form" class="message_label">
												<span class="font-semibold text-sm"><?php _e( 'Form', 'icegram' ); ?></span>
											</label> 
											<label>

												<input class="show_form_options form-checkbox" type="checkbox" name="message_data[<?php echo $message_id; ?>][use_form]" id="message_use_form" value="yes" <?php echo $use_form_check ?>/>
												<?php _e( 'Use Opt-in / Subscription / Lead capture form', 'icegram' ); ?></span>
											</label>
										</p>

											<div class="message_form_options" <?php echo $show_form_options; ?> message_id="<?php echo $message_id; ?>">
												
									<?php

									
									$active_plugins = get_option( 'active_plugins', array() );
									if ( is_multisite() ) {
										$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
									}

									?>

									<p class="py-3 message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
										<label class="message_label">&nbsp;</label>
										<textarea class="message_field message_form_header form-textarea" rows="2" autocomplete="off" cols="65" name="message_data[<?php echo $message_id; ?>][form_header]" id="" value="" placeholder="<?php _e( 'Text / HTML to show before the form', 'icegram' ); ?>"><?php echo esc_textarea(wp_kses_post( $form_header )); ?></textarea>
									</p>
									<p class="message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
										<label class="message_label">&nbsp;</label>
										<?php
										$form_embed_html    = '';
										$force_use_rm       = false;
										$es_current_version = '3.5.18';
										$is_es_active       = false;

										if ( in_array( 'email-subscribers/email-subscribers.php', $active_plugins ) ) {
											$es_plugin_meta_data = get_plugin_data( WP_PLUGIN_DIR . '/email-subscribers/email-subscribers.php' );
											$es_current_version  = ! empty( $es_plugin_meta_data['Version'] ) ? $es_plugin_meta_data['Version'] : '';
											$is_es_active        = true;
										}

										if ( in_array( 'email-subscribers-premium/email-subscribers-premium.php', $active_plugins ) ) {
											$es_plugin_meta_data = get_plugin_data( WP_PLUGIN_DIR . '/email-subscribers-premium/email-subscribers-premium.php' );
											$es_current_version  = ! empty( $es_plugin_meta_data['Version'] ) ? $es_plugin_meta_data['Version'] : '';
											$is_es_active        = true;
										}

										$force_use_rm = ( version_compare( $es_current_version, '4.0', '<' ) ) ? true : false;

												//Add Rainmaker form
										$rm_html    = __( "Add form embed code" ) . '<strong>' . __( " or easily embed using ", "icegram" ) . '<a style="font-style:normal;" href="' . admin_url( "plugin-install.php?tab=search&type=term&s=icegram-rainmaker" ) . '" target="_blank" alt="Icegram Collect">Icegram Collect' . '</a> plugin </strong>';
										$rm_html    = ( true === $force_use_rm ) ? $rm_html : '';
										$hide_embed = '';
										if ( in_array( 'icegram-rainmaker/icegram-rainmaker.php', $active_plugins ) && ( ! empty( $message_data["rainmaker_form_code"] ) || $force_use_rm ) ) {
											$rainmaker_form_list = Rainmaker::get_rm_form_id_name_map();
											$rm_html             = __( "Use Icegram Collect form ", "icegram" );
											$rm_html             .= '<select class="rainmaker_form_list form-select" style="max-width:30%" name="message_data[' . $message_id . '][rainmaker_form_code]">
											<option value="" selected>' . __( "Select form ", "icegram" ) . '</option>';
											foreach ( $rainmaker_form_list as $id => $name ) {
												$rm_html .= '<option ' . ( ( ! empty( $message_data["rainmaker_form_code"] ) && $id == $message_data["rainmaker_form_code"] ) ? 'selected' : '' ) . ' value="' . $id . '">' . $name . '</option>';
											}
											$rm_html         .= '</select><span style="font-style:italic">' . __( ' or add ', 'icegram' ) . '<a class="embed_form_code_toggle" style="cursor: pointer;">' . __( 'form embed code', 'icegram' ) . '</a></span>';
											$hide_embed      = ( empty( $message_data["rainmaker_form_code"] ) && ! empty( $form_html_original ) ) ? '' : 'style="display:none"';
											$form_embed_html = $rm_html;
										}

												//Add Email Subscribers form
										$es_html = __( "Add form embed code" ) . '<strong>' . __( " or easily embed using ", "icegram" ) . '<a style="font-style:normal;" href="' . admin_url( "plugin-install.php?tab=search&type=term&s=email-subscribers" ) . '" target="_blank" alt="Icegram Express">Icegram Express' . '</a> plugin </strong>';
										if ( $is_es_active && ! $force_use_rm ) {
											$forms_db     = new ES_DB_Forms();
											$es_form_list = call_user_func( array( $forms_db, 'get_forms_id_name_map' ) );
											$es_html      = __( "Use Icegram Express form ", "icegram" );
											$es_html      .= '<select class="es_form_list form-select" style="max-width:30%" name="message_data[' . $message_id . '][es_form_code]">
											<option value="" selected>' . __( "Select form ", "icegram" ) . '</option>';
											foreach ( $es_form_list as $id => $name ) {
												$es_html .= '<option ' . ( ( ! empty( $message_data["es_form_code"] ) && $id == $message_data["es_form_code"] ) ? 'selected' : '' ) . ' value="' . $id . '">' . $name . '</option>';
											}
											$es_html    .= '</select><span style="font-style:italic">' . __( ' or add ', 'icegram' ) . '<a class="embed_form_code_toggle" style="cursor: pointer;">' . __( 'form embed code', 'icegram' ) . '</a></span>';
											$hide_embed = ( empty( $message_data["es_form_code"] ) && ! empty( $form_html_original ) ) ? '' : 'style="display:none"';
										}
										$form_embed_html = ! empty( $rm_html ) ? $rm_html : $es_html;
										?>
										
										<span class="message_field py-3"> <?php echo $form_embed_html; ?></span>
										<div class="form_input_code" <?php echo $hide_embed ?>>
											<label class="message_label">&nbsp;</label>
											<textarea class="message_field message_form_html_original form-textarea" rows="6" autocomplete="off" cols="65" name="message_data[<?php echo $message_id; ?>][form_html_original]" id="message_form_html_original_<?php echo $message_id; ?>" value=""
												placeholder="<?php _e( 'Paste HTML / shortcode of your form here...', 'icegram' ); ?>"><?php if ( isset( $form_html_original ) ) {
													echo esc_attr( $form_html_original );
												} ?>		
											</textarea>
										</div>
											<br>
											<label class="message_label">&nbsp;</label>
											<label><p class="text-xs py-1"><input class="message_form_has_label form-checkbox mr-1" type="checkbox" name="message_data[<?php echo $message_id; ?>][form_has_label]" value="yes" <?php echo $form_has_label ?> />
												<?php _e( 'Show labels above fields', 'icegram' ); ?></p></label>
											</p>

											<p class="message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
												<label class="message_label">&nbsp;</label>
												<textarea class="message_field message_form_footer form-textarea" rows="2" autocomplete="off" cols="65" name="message_data[<?php echo $message_id; ?>][form_footer]" id="" value="" placeholder="<?php _e( 'Text / HTML to show after the form', 'icegram' ); ?>"><?php echo esc_textarea(wp_kses_post( $form_footer )); ?></textarea>
											</p>
											<p class="message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
												<label class="message_label">&nbsp;</label>
												<span class="my-1.5 py-0.5 form_inline_shortcode campaign_shortcode inline light message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
													<?php echo __( 'Insert <code>[ig_form]</code> where you want to show this form in message body.', 'icegram' ); ?>
												</span>
											</p>
										</div>

										<!-- Embed Form options : End -->

										<?php

										$show_cta_actions = array_merge( $settings['label'], $settings['link'] );
										$show_only_link   = array_diff( $settings['link'], $settings['label'] );

										?>
										<p class="p-3 message_row <?php echo "ig_" . implode( ' ig_', $show_cta_actions ) ?>">
											<label for="message_label" class="message_label">
												
													<span class="message_row font-semibold text-sm <?php echo "ig_" . implode( ' ig_', $settings['label'] ) ?>"> <?php _e( 'Call To Action', 'icegram' ); ?> </span>
													<span class="message_row font-semibold text-sm <?php echo "ig_" . implode( ' ig_', $show_only_link ) ?>"> <?php _e( 'Call To Action', 'icegram' ); ?> </span>
												
											</label>
											<span class="message_row option_title text-gray-500 font-medium text-sm <?php echo "ig_" . implode( ' ig_', $settings['label'] ) ?>"><?php _e( "Main Call To Action Button", "icegram" ); ?></span>
										</p>

										<p class="p-3 message_row <?php echo "ig_" . implode( ' ig_', $settings['label'] ) ?>">
											<label for="message_label" class="message_label"> &nbsp;
												<span class="help_tip admin_field_icon mr-1.5" data-tip="<?php _e( 'Your call to action text. Something unusual will increase conversions.', 'icegram' ); ?>"></span>
											</label>
											<span class="message_row <?php echo "ig_" . implode( ' ig_', $settings['label'] ) ?>">
												<span class="message_label sub_option_label w-12"><?php _e( "Label", "icegram" ); ?>
												</span>
												<input type="text" class="message_field form-input" name="message_data[<?php echo $message_id; ?>][label]" id="message_label" value="<?php if ( isset( $message_data['label'] ) ) {
												echo esc_attr( $message_data['label'] );
											} ?>"/> 
											</span>
										</p>

										
										<?php

										$target_link_field = '<p class="message_row ig_' . implode( ' ig_', $settings['link'] ) . '">
										<span class="message_row ig_' . implode( ' ig_', $settings['label'] ) . '">
										<label for="message_link" class="message_label">&nbsp;
										<span class="help_tip admin_field_icon mr-1.5" data-tip="' . __( 'Enter destination URL here. Clicking will redirect to this link.', 'icegram' ) . '"></span>
										</label>
										</span>
										<span class="message_row ig_' . implode( ' ig_', $show_only_link ) . '">
										<span style="float:left" class="help_tip admin_field_icon mr-1.5" data-tip="' . __( 'Enter destination URL here. Clicking will redirect to this link.', 'icegram' ) . '"></span>
										</span>
										<span class="message_label sub_option_label">' . __( "Target Link", "icegram" ) . '</span>
										<input type="text" class="message_field message_link form-input" name="message_data[' . $message_id . '][link]" 
										id="message_link" value="' . esc_attr( ( isset( $message_data['link'] ) ? $message_data['link'] : '' ) ) . '" />
										</p>';

										
										$icegram_message_target_link = apply_filters( 'icegram_message_field_link', array( 'html' => $target_link_field, 'message_id' => $message_id, 'message_data' => $message_data, 'settings' => $settings ) );
										echo $icegram_message_target_link['html'];
										?>
										<p class="message_row <?php echo "ig_" . implode( ' ig_', $show_cta_actions ) ?>">
											<label for="message_label" class="message_label"> &nbsp;</label>
											<span>&nbsp;</span>
										</p>
										
										<!-- Custom code -->
										<?php
									$dummy_css                = '#ig_this_message .ig_headline{ /* font-size: 3em !important; */ }';
									$dummy_js                 = '<script type="text/javascript"> /* add your js code here */ </script>';
									$message_custom_css       = ( ! empty( $message_data['custom_css'] ) ) ? $message_data['custom_css'] : $dummy_css;
									$message_custom_js        = ( ! empty( $message_data['custom_js'] ) ) ? $message_data['custom_js'] : $dummy_js;
									$use_custom_code_check    = ( ! empty( $message_data['use_custom_code'] ) )
									? checked( $message_data['use_custom_code'], 'yes', 0 )
									: '';
									$show_custom_code_options = empty( $use_custom_code_check ) ? 'style="display:none;"' : '';

									?>
									<p class="p-3 message_row message_custom_code_options_check <?php echo "ig_" . implode( ' ig_', $settings['custom_code'] ) ?>" message_id="<?php echo $message_id; ?>">
										<label for="message_custom_code" class="message_label font-semibold text-sm"><?php _e( 'Custom Code', 'icegram' ); ?></label>
										<label><input class="form-checkbox mr-1 show_custom_code_options" type="checkbox" name="message_data[<?php echo $message_id; ?>][use_custom_code]" id="message_use_custom_code" value="yes" <?php echo $use_custom_code_check ?>/> <?php _e( 'Add custom code for this message', 'icegram' ); ?></label>
									</p>
									<div class="py-1 message_custom_code_options" <?php echo $show_custom_code_options; ?> message_id="<?php echo $message_id; ?>">
										<label class="message_label">&nbsp;</label>
										<span class="message_label font-medium"> <?php _e( 'CSS', 'icegram' ); ?></span>
										<br>
										<label class="message_label">&nbsp;</label>
										<textarea class="message_field message_custom_css form-textarea" rows="6" autocomplete="off" cols="65" name="message_data[<?php echo $message_id; ?>][custom_css]" id="message_message_custom_css_<?php echo $message_id; ?>" value=""
											placeholder="<?php //_e('Add Custom CSS for this message here...', 'icegram' ); ?>"><?php if ( isset( $message_custom_css ) ) {
												echo esc_attr( $message_custom_css );
											} ?></textarea>
											<br><br>
											<label class="message_label">&nbsp;</label>
											<span class="message_label font-medium"> <?php _e( 'JS', 'icegram' ); ?></span>
											<br>
											<label class="message_label">&nbsp;</label>
											<textarea class="message_field message_custom_js form-textarea" rows="6" autocomplete="off" cols="65" name="message_data[<?php echo $message_id; ?>][custom_js]" id="message_message_custom_js_<?php echo $message_id; ?>" value=""
												placeholder="<?php _e( 'Add Custom javaScript for this message here...', 'icegram' ); ?>"><?php if ( isset( $message_custom_js ) ) {
													echo esc_attr( $message_custom_js );
												} ?></textarea>
											</div>
											<!-- Custom code : End -->
									</div>
								
								<div id="ig_message_styling" class="message-tabs px-4">
									<div class="message_form_options" message_id="<?php echo $message_id; ?>" <?php echo $show_form_options ?>>
										<p class="p-3 message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
											<label for="message_form" class="message_label"><span class="font-bold text-base"><?php _e( 'Forms', 'icegram' ); ?></span>
											</label>
										</p>
										<p class="px-3 pb-2 pt-8 message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
											
											<label for="message_form_style" class="message_label"><span for="message_form_style" class="message_label font-semibold text-sm"><?php _e( 'Style', 'icegram' ); ?></span></label>
												<select id="message_form_style" name="message_data[<?php echo $message_id; ?>][form_style]" class="icegram_chosen_page message_form_style form-select">
													<?php
													$available_form_styles = $this->available_form_styles();
													foreach ( $available_form_styles as $style ) {
														$bg_img = "background-image: url(" . $icegram->plugin_url . '/assets/images/' . strtolower( str_replace( ' ', '_', $style['name'] ) ) . ".png)";
														?>
														<option style="<?php echo $bg_img; ?>" <?php echo ( ! empty( $message_data['form_style'] ) && strtolower( str_replace( ' ', '_', $style['name'] ) ) == $message_data['form_style'] ) ? 'selected' : ''; ?> value="<?php echo esc_attr( strtolower( str_replace( ' ', '_', $style['name'] ) ) ) ?>"
															class="<?php echo strtolower( str_replace( ' ', '_', $style['name'] ) ) ?>" <?php echo ( ! empty( $message_data['form_style'] ) && esc_attr( strtolower( $style['name'] ) ) == $message_data['form_style'] ) ? 'selected' : ''; ?>><?php echo esc_html( $style['name'] ) ?></option>
														<?php } ?>
												</select>
										</p>

										<p class="p-3 form_layouts message_row <?php echo "ig_" . implode( ' ig_', $settings['embed_form'] ) ?>">
											<label for="message_form_layouts" class="message_label">
												<span class="message_label font-semibold text-sm"><?php _e( 'Position', 'icegram' ); ?></span>
											</label>
											<div class="form_radio_group pb-3">
												<span class="location <?php if ( ! empty( $form_layouts['left'] ) ) {
													echo "ig_" . implode( ' ig_', $form_layouts['left'] );
												} ?>">
													<label style="background-position:0px 23px;" for="form_layout_left_<?php echo $message_id; ?>" title="<?php _e( 'Left', 'icegram' ); ?>">
														<input class="message_form_layout form-radio" type="radio" id="form_layout_left_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][form_layout]"
														value="left" <?php echo ( ! empty( $message_data['form_layout'] ) && "left" == $message_data['form_layout'] ) ? 'checked' : ( empty( $message_data['form_layout'] ) && "left" == $default_form_layout ? 'checked' : '' ); ?> />
														<?php _e( 'Left', 'icegram' ); ?>
													</label>
												</span>

												<span class="location <?php if ( ! empty( $form_layouts['right'] ) ) {
													echo "ig_" . implode( ' ig_', $form_layouts['right'] );
												} ?>">
													<label style="background-position:-100px 23px;" for="form_layout_right_<?php echo $message_id; ?>" title="<?php _e( 'Right', 'icegram' ); ?>">
														<input class="message_form_layout form-radio" type="radio" id="form_layout_right_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][form_layout]"
														value="right" <?php echo ( ! empty( $message_data['form_layout'] ) && "right" == $message_data['form_layout'] ) ? 'checked' : ( empty( $message_data['form_layout'] ) && "right" == $default_form_layout ? 'checked' : '' ); ?> />
														<?php _e( 'Right', 'icegram' ); ?>
													</label>
												</span>

												<span class="location <?php if ( ! empty( $form_layouts['bottom'] ) ) {
													echo "ig_" . implode( ' ig_', $form_layouts['bottom'] );
												} ?>">
													<label style="background-position:-200px 23px;" for="form_layout_bottom_<?php echo $message_id; ?>" title="<?php _e( 'Bottom', 'icegram' ); ?>">
														<input class="message_form_layout form-radio" type="radio" id="form_layout_bottom_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][form_layout]"
														value="bottom" <?php echo ( ! empty( $message_data['form_layout'] ) && "bottom" == $message_data['form_layout'] ) ? 'checked' : ( empty( $message_data['form_layout'] ) && "bottom" == $default_form_layout ? 'checked' : '' ); ?> />
														<?php _e( 'Bottom', 'icegram' ); ?>
													</label>
												</span>

												<?php
												$inline_position_checked = ( ! empty( $message_data['form_layout'] ) && "inline" == $message_data['form_layout'] ) ? 'checked' : ( empty( $message_data['form_layout'] ) && "inline" == $default_form_layout ? 'checked' : '' );
												$show_color_options      = ! empty( $inline_position_checked ) ? 'style="display:none;"' : '';
												?>
												<span class="location <?php if ( ! empty( $form_layouts['inline'] ) ) {
													echo "ig_" . implode( ' ig_', $form_layouts['inline'] );
												} ?>">
													<label style="background-position:-300px 23px;" for="form_layout_inline_<?php echo $message_id; ?>" title="<?php _e( 'Inline', 'icegram' ); ?>">
														<input class="message_form_layout form-radio" type="radio" id="form_layout_inline_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][form_layout]" value="inline" <?php echo $inline_position_checked; ?> />
														<?php _e( 'Inline', 'icegram' ); ?>
													</label>
												</span>
											</div>

										</p>
										<?php

										$color_field_html = '<p class="px-3 pt-4 pb-2 message_form_color message_row ig_' . implode( ' ig_', $settings['embed_form'] ) . '" ' . $show_color_options . '>
										<label for="message_form_bg_color" class="message_label"><span class="message_label font-semibold text-sm">' . __( 'Colors', 'icegram' ) . '</span></label>
										<span class="pr-2">' . __('Background','icegram') . '</span><input type="text" class="message_field color-field form-input" data-color-label="' . __( 'Background Color', 'icegram' ) . '" name="message_data[' . $message_id . '][form_bg_color]" id="message_form_bg_color" value="' . $form_bg_color . '"  />
										<span class="pl-4 pr-2">' . __('Labels','icegram') . '</span><input type="text" class="message_field color-field form-input" data-color-label="' . __( 'Text Color', 'icegram' ) . '" name="message_data[' . $message_id . '][form_text_color]" id="message_form_text_color" value="' . $form_text_color . '" style="margin-left:5em !important" />
										</p>';
										echo $color_field_html;
										?>
								</div>
									<?php
									do_action( 'icegram_after_button_label', $message_id, $message_data );
									do_action( 'icegram_styling_options', $message_id, $message_data );
							
										
									$text_color           = ( ! empty( $message_data['text_color'] ) ) ? $message_data['text_color'] : '';
									$bg_color             = ( ! empty( $message_data['bg_color'] ) ) ? $message_data['bg_color'] : '';
									$cta_bg_color         = ( ! empty( $message_data['cta_bg_color'] ) ) ? $message_data['cta_bg_color'] : '';
									$cta_text_color       = ( ! empty( $message_data['cta_text_color'] ) ) ? $message_data['cta_text_color'] : '';
									$colors_options_check = ( ! empty( $message_data['use_theme_defaults'] ) )
									? checked( $message_data['use_theme_defaults'], 'yes', 0 )
									: ( ( ! empty( $bg_color ) || ! empty( $text_color ) || ! empty( $cta_bg_color ) || ! empty( $cta_text_color ) ) ? '' : 'checked="checked"' );

									$show_color_options = ( ! empty( $colors_options_check ) ) ? 'style="display: none;"' : '';
									$color_field_html   = '<p class="px-3 py-2 message_row ig_' . implode( ' ig_', $settings['bg_color'] ) . '">
									<label for="message_bg_color" class="message_label"><span class="message_label sub_option_label font-semibold" > ' . __( 'Body', 'icegram' ) . '</span></label>
									<input type="text" class="message_field color-field form-input" data-color-label="' . __( 'Background', 'icegram' ) . '" name="message_data[' . $message_id . '][bg_color]" id="message_bg_color" value="' . $bg_color . '"  />
									<input type="text" class="message_field color-field form-input" data-color-label="' . __( 'Text', 'icegram' ) . '" name="message_data[' . $message_id . '][text_color]" id="message_text_color" value="' . $text_color . '"  />
									</p>
									<p class="px-3 py-2 message_row ig_' . implode( ' ig_', $settings['label'] ) . '">
									<label for="message_cta_bg_color" class="message_label"><span class="message_label sub_option_label font-semibold text-sm" >' . __( 'Button', 'icegram' ) . '</span></label>
									
									<input type="text" class="message_field color-field form-input" data-color-label="' . __( 'Background', 'icegram' ) . '" name="message_data[' . $message_id . '][cta_bg_color]" id="message_cta_bg_color" value="' . $cta_bg_color . '" />
									<input type="text" class="message_field color-field form-input" data-color-label="' . __( 'Text', 'icegram' ) . '" name="message_data[' . $message_id . '][cta_text_color]" id="message_cta_text_color" value="' . $cta_text_color . '" />
									</p>';
									// </div>';
									$color_field      = apply_filters( 'icegram_color_fields', array( 'html' => $color_field_html, 'message_id' => $message_id, 'message_data' => $message_data, 'settings' => $settings ) );
									$color_field_html = '<div class="message_colors_options_container" ' . $show_color_options . '><br>' . $color_field['html'] . '</div>';


									$colors_options_html = '<p class="px-3 py-4 message_row ig_' . implode( ' ig_', $settings['bg_color'] ) . '">
									<label for="message_use_theme_defaults" class="message_label"><span class="font-bold text-base">' . __( 'Colors', 'icegram' ) . '</span></label> </p>
									<p class="message_row ig_' . implode( ' ig_', $settings['bg_color'] ) . '">
									<label class="">&nbsp;</label>
										<span style="position: relative;" class="message_label pt-3">
											<input class="w-full show_color_options ig-check-toggle" type="checkbox" name="message_data[' . $message_id . '][use_theme_defaults]" id="message_use_theme_defaults" value="yes" ' . $colors_options_check . '/> 
												<span class="ig-mail-toggle-line inline-block"></span>
												<span class="ig-mail-toggle-dot top-3.5"></span><span class="relative bottom-1">' . __( 'Use theme\'s default colors', 'icegram' ) . '</span>
										</span>
									</label> <br>' . $color_field_html . '
									</p>';
									echo $colors_options_html;
									
									?>
									<p class="px-3 <?php echo 'toast' == $settings['position'] ? '' : 'pt-6' ?> pb-4 message_row position <?php echo "ig_" . implode( ' ig_', $settings['position'] ) ?>">
											<label for="message_position" class="message_label"><span class="font-bold text-base"><?php _e( 'Position', 'icegram' ); ?></span></label>
											<span class="message_field location-selector message_label">
												<input class="form-radio" type="radio" id="radio01_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="00" <?php echo ( ! empty( $message_data['position'] ) && "00" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio01_<?php echo $message_id; ?>" title="Top Left">
													<span class="location <?php if ( ! empty( $positions['00'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['00'] );
													} ?> top left" data-position="top left"></span>
												</label>
												<input class="form-radio" type="radio" id="radio02_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="01" <?php echo ( ! empty( $message_data['position'] ) && "01" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio02_<?php echo $message_id; ?>" title="Top">
													<span class="location <?php if ( ! empty( $positions['01'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['01'] );
													} ?> top" data-position="top"></span>
												</label>
												<input class="form-radio" type="radio" id="radio03_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="02" <?php echo ( ! empty( $message_data['position'] ) && "02" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio03_<?php echo $message_id; ?>" title="Top Right">
													<span class="location <?php if ( ! empty( $positions['02'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['02'] );
													} ?> top right" data-position="top right"></span>
												</label>
												<input class="form-radio" type="radio" id="radio04_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="10" <?php echo ( ! empty( $message_data['position'] ) && "10" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio04_<?php echo $message_id; ?>" title="Middle Left">
													<span class="location <?php if ( ! empty( $positions['10'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['10'] );
													} ?> middle left" data-position="middle left"></span>
												</label>
												<input class="form-radio" type="radio" id="radio05_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="11" <?php echo ( ! empty( $message_data['position'] ) && "11" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio05_<?php echo $message_id; ?>" title="Middle">
													<span class="location <?php if ( ! empty( $positions['11'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['11'] );
													} ?> middle middle" data-position="middle middle"></span>
												</label>
												<input class="form-radio" type="radio" id="radio06_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="12" <?php echo ( ! empty( $message_data['position'] ) && "12" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio06_<?php echo $message_id; ?>" title="Middle Right">
													<span class="location <?php if ( ! empty( $positions['12'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['12'] );
													} ?> middle right" data-position="middle right"></span>
												</label>
												<input class="form-radio" type="radio" id="radio07_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="20" <?php echo ( ! empty( $message_data['position'] ) && "20" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio07_<?php echo $message_id; ?>" title="Bottom Left">
													<span class="location <?php if ( ! empty( $positions['20'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['20'] );
													} ?> bottom left" data-position="bottom left"></span>
												</label>
												<input class="form-radio" type="radio" id="radio08_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="21" <?php echo ( ! empty( $message_data['position'] ) && "21" == $message_data['position'] || ! isset( $message_data['position'] ) ) ? 'checked' : ''; ?> />
												<label for="radio08_<?php echo $message_id; ?>" title="Bottom">
													<span class="location <?php if ( ! empty( $positions['21'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['21'] );
													} ?> bottom" data-position="bottom"></span>
												</label>
												<input class="form-radio" type="radio" id="radio09_<?php echo $message_id; ?>" name="message_data[<?php echo $message_id; ?>][position][ig_default]" value="22" <?php echo ( ! empty( $message_data['position'] ) && "22" == $message_data['position'] ) ? 'checked' : ''; ?> />
												<label for="radio09_<?php echo $message_id; ?>" title="Bottom Right">
													<span class="location <?php if ( ! empty( $positions['22'] ) ) {
														echo "ig_" . implode( ' ig_', $positions['22'] );
													} ?> bottom right" data-position="bottom right"></span>
												</label>
											</span>
									</p>
									<?php do_action('icegram_styling_settings', $message_id, $message_data); ?>
								</div>
								<div id="ig_message_behavior" class="message-tabs px-4">
									<?php
									do_action( 'icegram_behavior_settings', $message_id, $message_data );
									?>
								</div>
							</div>
						</div>
					</div>			
				</div>
					
				<input type="hidden" name="message_data[<?php echo $message_id; ?>][id]" value="<?php echo $message_id; ?>">
				<input type="hidden" class="message_id" name="message_id" value="<?php echo $message_id; ?>">
			<?php

			if ( ! empty( $action['message_id'] ) ) {
				?></div>
				<?php
			} else {

				?>
				<p class="message_row">
					<label class="message_label">&nbsp;</label>
					<span>
						<span class="shortcode_description admin_field_icon"></span>
						<?php
						echo sprintf( __( 'You may add <code>[%s]</code> where you want to show this message.', 'icegram' ), 'icegram messages="' . $post->ID . '"' );
						?>
					</span></p>
					<?php
				}

			}

		// Used to save the settings which are being made in the message form and added to message page appropriately 
			function update_message_settings( $post_id, $post ) {

				if ( empty( $post_id ) || empty( $post ) || empty( $_POST['message_data'] ) || empty( $_POST['message_data'][ $post_id ] ) ) {
					return;
				}
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					return;
				}
				if ( is_int( wp_is_post_revision( $post ) ) ) {
					return;
				}
				if ( is_int( wp_is_post_autosave( $post ) ) ) {
					return;
				}
				if ( empty( $_POST['icegram_message_meta_nonce'] ) || ! wp_verify_nonce( $_POST['icegram_message_meta_nonce'], 'icegram_message_save_data' ) ) {
					return;
				}
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
				if ( $post->post_type != 'ig_message' ) {
					return;
				}

				$message_data = $_POST['message_data'][ $post_id ];
				$type         = $message_data['type'];

				if ( isset( $message_data['theme'][ $type ] ) ) {
					$message_data['theme'] = $message_data['theme'][ $type ];
				} else {
					unset( $message_data['theme'] );
				}
				if ( isset( $message_data['animation'][ $type ] ) ) {
					$message_data['animation'] = $message_data['animation'][ $type ];
				} else {
					unset( $message_data['animation'] );
				}
				if ( isset( $message_data['position'][ $type ] ) ) {
					$message_data['position'] = $message_data['position'][ $type ];
				} elseif ( isset( $message_data['position']['ig_default'] ) ) {
					$message_data['position'] = $message_data['position']['ig_default'];
				}
				if( empty( $message_data['rainmaker_form_code'] ) && ! empty( $message_data['form_html_original'] ) && strpos( $message_data['form_html_original'], 'rainmaker_form') ) {
					$rm_shortcode_pos = strpos($message_data['form_html_original'], '"')+1;
					$rm_shortcode = substr($message_data['form_html_original'], $rm_shortcode_pos , strripos($message_data['form_html_original'], "\\") - $rm_shortcode_pos);
					$message_data['rainmaker_form_code'] = $rm_shortcode ;
				}

				$message_data = apply_filters( 'icegram_update_message_data', $message_data, $post_id );
				update_post_meta( $post_id, 'icegram_message_data', $message_data );
				update_post_meta( $post_id, 'icegram_message_preview_data', $message_data );

			}

		// Additionally save message body content in post_content of post table
			function save_message_in_post_content( $post_data ) {

				if ( empty( $_POST['icegram_message_meta_nonce'] ) || ! wp_verify_nonce( $_POST['icegram_message_meta_nonce'], 'icegram_message_save_data' ) ) {
					return $post_data;
				}
				if ( ! empty( $_POST['post_type'] ) && $_POST['post_type'] == 'ig_message' && ! empty( $_POST['message_data'] ) ) {
					$message_id                = sanitize_text_field( $_POST['ID'] );
					$post_data['post_content'] = isset( $_POST['message_data'][ $message_id ]['message'] ) ? $_POST['message_data'][ $message_id ]['message'] : '';

					if ( isset( $_POST['message_data'][ $message_id ]['post_title'] ) ) {

						if ( ! empty( $_POST['message_data'][ $message_id ]['post_title'] ) ) {
							$post_data['post_title'] = sanitize_text_field( $_POST['message_data'][ $message_id ]['post_title'] );
						} else {
							$post_data['post_title'] = wp_kses_post( $_POST['message_data'][ $message_id ]['headline'] );
						}

					}
				}

				return $post_data;
			}

		// Add message columns to message dashboard
			function edit_columns( $existing_columns ) {

				$date = $existing_columns['date'];
				unset( $existing_columns['date'] );

				$existing_columns['message_type']      = __( 'Type', 'icegram' );
				$existing_columns['message_theme']     = __( 'Theme', 'icegram' );
				$existing_columns['message_thumbnail'] = __( 'Thumbnail', 'icegram' );
				$existing_columns['date']              = $date;

				return apply_filters( 'icegram_manage_message_columns', $existing_columns );

			}

		// Add message columns data to message dashboard
			function custom_columns( $column ) {
				global $post, $icegram;

				if ( ( is_object( $post ) && $post->post_type != 'ig_message' ) ) {
					return;
				}

				$message_data = get_post_meta( $post->ID, 'icegram_message_data', true );
				if ( empty( $message_data['type'] ) ) {
					return;
				}
				$class_name = 'Icegram_Message_Type_' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $message_data['type'] ) ) );
				if ( ! class_exists( $class_name ) ) {
					return;
				}
				$type  = ucwords( str_replace( "-", ' ', $message_data['type'] ) );
				$theme = ucwords( str_replace( "-", ' ', $message_data['theme'] ) );

				$bg_img = $icegram->message_types[ $message_data['type'] ]['themes'][ $message_data['theme'] ]['baseurl'] . $message_data['theme'] . ".png";

				switch ( $column ) {
					case 'message_type':
					echo esc_attr( $type );
					break;

					case 'message_theme':
					echo esc_attr( $theme );
					break;

					case 'message_thumbnail':
					// echo "<img src='$bg_img' style='max-width: 200px; max-height: 100px;'>";
					echo "<img src='" . esc_attr( $bg_img ) . "' style='max-width: 100%; max-height: 100px;'>";
					break;

					default:
					do_action( 'icegram_manage_message_custom_column', $column, $message_data );
					break;

				}

			}

		// Create array for settings based on message types
			function message_settings_to_show() {

				global $icegram;
				$settings = array();
				foreach ( $icegram->message_types as $type => $value ) {
					foreach ( $value['settings'] as $setting => $property ) {
						$settings[ $setting ][] = $type;
					}
				}

				return apply_filters( 'icegram_message_settings_to_show', $settings );

			}

		// Create array for positions available for all message types		
			function message_form_layouts_to_show() {

				global $icegram;
				$form_layouts = array();
				foreach ( $icegram->message_types as $type => $value ) {
					if ( empty( $value['settings']['form_layout'] ) ) {
						continue;
					}

					if ( ! empty( $value['settings']['form_layout']['values'] ) ) {
						foreach ( $value['settings']['form_layout']['values'] as $form_layout ) {
							$form_layouts[ $form_layout ][] = $type;
						}
					}
				}

			// return apply_filters( 'icegram_message_form_layouts_to_show', $form_layouts );
				return $form_layouts;

			}


		// Create array for positions available for all message types		
			function message_positions_to_show() {

				global $icegram;
				$positions = array();
				foreach ( $icegram->message_types as $type => $value ) {
					if ( empty( $value['settings']['position'] ) ) {
						continue;
					}

					if ( ! empty( $value['settings']['position']['values'] ) ) {
						foreach ( $value['settings']['position']['values'] as $position ) {
							$positions[ $position ][] = $type;
						}
					}
				}

				return apply_filters( 'icegram_message_positions_to_show', $positions );

			}

		// Default message data for newly created message
			function default_message_data( $message_type = '' ) {

				global $icegram;
				$default_themes = array();
				foreach ( $icegram->message_types as $type => $value ) {
					if ( isset( $value['settings']['theme']['default'] ) ) {
						$default_themes[ $type ] = $value['settings']['theme']['default'];
					}
				}
				if ( ! empty( $message_type ) ) {
					$default_message = $icegram->message_types[ $message_type ];
				} else {
					$default_message = reset( $icegram->message_types );
				}
				$default_message_data = array(
					'type'       => $default_message['type'],
					'position'   => ( ! empty( $default_message['settings']['position']['values'][0] ) ) ? $default_message['settings']['position']['values'][0] : '',
					'text_color' => ( ! empty( $default_message['settings']['text_color']['default'] ) ) ? $default_message['settings']['text_color']['default'] : '',
					'bg_color'   => ( ! empty( $default_message['settings']['bg_color']['default'] ) ) ? $default_message['settings']['bg_color']['default'] : '',
					'theme'      => $default_themes
				);

				return apply_filters( 'icegram_default_message_data', $default_message_data );
			}

		// Form styles for the form
		//TODO :: check this and do changes if required
			function available_form_styles() {
				$available_form_styles = array(
					array( 'name' => 'Style 0' ),
					array( 'name' => 'Style 1' ),
					array( 'name' => 'Style 2' ),
					array( 'name' => 'Style 3' ),
					array( 'name' => 'Style 4' )
				);

				return $available_form_styles;
			}

		// All headline to generate randomly for messages
			function available_headlines( $available_headlines = array() ) {
				$available_headlines = array_merge( $available_headlines, array(
					__( 'Here Is A Method That Is Helping ____ To ____', 'icegram' ),
					__( '__ Little Known Ways To ____', 'icegram' ),
					__( 'Get Rid Of ____ Once And For All', 'icegram' ),
					__( 'How To ____ So You Can ____', 'icegram' ),
					__( 'They Didn\'t Think I Could ____, But I Did', 'icegram' ),
					__( 'How ____ Made Me ____', 'icegram' ),
					__( 'Are You ____ ?', 'icegram' ),
					__( 'Warning: ____ !', 'icegram' ),
					__( 'Do You Make These Mistakes With ____ ?', 'icegram' ),
					__( '7 Ways To ____', 'icegram' ),
					__( 'If You\'re ____, You Can ____', 'icegram' ),
					__( 'Turn your ____ into a ____', 'icegram' ),
					__( 'Want To Be A ____?', 'icegram' ),
					__( 'The Ugly Truth About Your Beautiful ____', 'icegram' ),
					__( 'The Secret to ____ Is Simply ____!', 'icegram' ),
					__( 'The Quickest Way I Know To ____', 'icegram' ),
					__( 'The Lazy Man\'s Way To ____', 'icegram' ),
					__( 'The Amazing Story Of ____ That Requires So Little Of ____ You Could ____', 'icegram' ),
					__( 'The Amazing Secret Of The ____ Genius Who Is Afraid Of ____', 'icegram' ),
					__( 'The 10 Wackiest Ideas That ____... And How You Can Too!', 'icegram' ),
					__( 'The Inside Secrets To ____ With Crazy, Outlandish And Outrageous ____', 'icegram' ),
					__( '____ Like A ____', 'icegram' ),
					__( 'Remember When You Could Have ____, And You Didn\'t?', 'icegram' ),
					__( 'Is The ____ Worth $x To You?', 'icegram' ),
					__( 'Increase your ____, reduce ____, maintain ____ and ____ with ____', 'icegram' ),
					__( 'If You Can ____ You Can ____', 'icegram' ),
					__( 'I Discovered How To ____... Now I\'m Revealing My Secret', 'icegram' ),
					__( 'How To Turn Your ____ Into The Most ____', 'icegram' ),
					__( 'How To Take The Headache Out Of ____', 'icegram' ),
					__( 'How To ____ ... Legally', 'icegram' ),
					__( 'How To ____ That ____', 'icegram' ),
					__( 'How To Discover The ____ That Lies Hidden In Your ____', 'icegram' ),
					__( 'How To ____ Even When Your Not ____', 'icegram' ),
					__( '____ With No ____!', 'icegram' ),
					__( 'Greatest Goldmine of ____ Ever Jammed Into One Big ____', 'icegram' ),
					__( 'Free ____ Tells How To Get Better ____', 'icegram' ),
					__( 'FREE ____ Worth $____ for the first 100 People to take Advantage of this Offer', 'icegram' ),
					__( 'Don\'t Try This With Any Other ____', 'icegram' ),
					__( 'Do You Honestly Want To ____?', 'icegram' ),
					__( 'Discover The Magic ____ That Will Bring You ____ & ____!', 'icegram' ),
					__( '____ Man Reveals A Short-Cut To ____', 'icegram' ),
					__( 'Confessions Of A ____', 'icegram' ),
					__( 'Are You Ready To ____?', 'icegram' ),
					__( 'An Open Letter To Everyone Who ____ More Than ____ Per ____', 'icegram' ),
					__( 'An Amazing ____ You Can Carry In Your ____', 'icegram' ),
					__( '21 Secret ____ that will ____... NOW!', 'icegram' )
				) );

				return $available_headlines;
			}

			function add_message_action( $actions, $post ) {
				if ( $post->post_type != 'ig_message' ) {
					return $actions;
				}
				$actions['duplicate_message'] = '<a class="ig-duplicate-message"  href="post.php?message_id=' . $post->ID . '&action=duplicate-message" >' . __( 'Duplicate', 'icegram' ) . '</a>';

				return $actions;
			}

			function duplicate_message() {
				if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'duplicate-message' && ! empty( $_REQUEST['message_id'] ) ) {
					Icegram::duplicate( sanitize_text_field( $_REQUEST['message_id'] ) );
				}
			}
		}
	}