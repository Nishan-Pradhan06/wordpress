<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class Icegram
 */
if ( ! class_exists( 'Icegram' ) ) {
	class Icegram {

		var $plugin_url;
		var $plugin_path;
		var $version;
		var $_wpautop_tags;
		var $message_types;
		var $message_type_objs;
		var $shortcode_instances;
		var $available_headlines;
		var $mode;
		var $cache_compatibility;

		public static $current_page_id;

		public function __construct() {
			global $ig_feedback, $ig_tracker, $ig_usage_tracker;

			$this->version             = IG_PLUGIN_VERSION;
			$this->shortcode_instances = array();
			$this->mode                = 'local';
			$this->plugin_url          = untrailingslashit( plugins_url( '/', __FILE__ ) );
			$this->plugin_path         = untrailingslashit( plugin_dir_path( __FILE__ ) );
			$this->include_classes( IG_FEEDBACK_TRACKER_VERSION );
			$this->cache_compatibility = get_option( 'icegram_cache_compatibility', 'no' );

			if ( is_admin() ) {
				$ig_feedback->render_deactivate_feedback();
			}

			if ( is_admin() && current_user_can( 'edit_posts' ) ) {
				$ig_campaign_admin = Icegram_Campaign_Admin::getInstance();
				$ig_message_admin  = Icegram_Message_Admin::getInstance();
				add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_styles_and_scripts' ) );
				add_action( 'admin_print_styles', array( &$this, 'remove_preview_button' ) );
				add_filter( 'post_row_actions', array( &$this, 'remove_row_actions' ), 10, 2 );

				add_action( 'admin_menu', array( &$this, 'admin_menus' ) );
				add_action( 'admin_init', array( &$this, 'welcome' ) );
				add_action( 'admin_init', array( &$this, 'dismiss_admin_notice' ) );

				add_action( 'admin_init', array( &$this, 'import_gallery_item' ) );

				add_action( 'icegram_settings_after', array( &$this, 'es_subscribe_form' ) );
				add_action( 'icegram_about_changelog', array( &$this, 'es_subscribe_form' ) );
				add_action( 'icegram_settings_after', array( &$this, 'icegram_houskeeping' ) );
				add_action( 'admin_notices', array( &$this, 'add_admin_notices' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( IG_PLUGIN_FILE ), array( $this, 'ig_plugin_settings_link' ), 11, 2 );
				add_filter( 'plugin_row_meta', array( $this, 'add_plugin_support_links' ), 10, 4 );
				add_filter( 'manage_edit-ig_campaign_columns', array( $this, 'custom_ig_campaign_column' )  ,10,1);
				add_action( 'manage_ig_campaign_posts_custom_column', array( $this, 'edit_columns' ), 2 );
				// Ajax handler for campaign status toggle.
				add_action( 'wp_ajax_ig_toggle_campaign_status', array( $this, 'toggle_campaign_status' ) );
				add_action( 'admin_bar_menu', array( $this, 'ig_show_documentation_link_in_admin_bar' ), 999 );
				add_action( 'admin_head', array( $this, 'ig_documentation_link_admin_bar_css' ), 999 );

				add_filter( 'ig_escape_allowed_tags', array( $this, 'ig_add_escape_allowed_tags' ) );

			} else {
				add_action( 'wp_footer', array( &$this, 'icegram_load_data' ) );
			}
			if ( $this->cache_compatibility === 'no' ) {
				add_action( 'wp_footer', array( &$this, 'display_messages' ) );
			}
			add_shortcode( 'icegram', array( &$this, 'execute_shortcode' ) );
			add_shortcode( 'ig_form', array( &$this, 'execute_form_shortcode' ) );
			// WPML compatibility
			add_filter( 'icegram_identify_current_page', array( &$this, 'wpml_get_parent_id' ), 10 );

			add_filter( 'icegram_branding_data', array( &$this, 'branding_data_remove' ), 10 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'identify_current_page' ) );
			add_filter( 'icegram_get_valid_campaigns_sql', array( &$this, 'append_to_valid_campaigns_sql' ), 10, 2 );
			add_action( 'icegram_print_js_css_data', array( &$this, 'print_js_css_data' ), 10, 1 );
			// common
			add_action( 'init', array( &$this, 'register_campaign_post_type' ) );
			add_action( 'init', array( &$this, 'register_message_post_type' ) );

			add_action( 'icegram_loaded', array( &$this, 'load_compat_classes' ) );

			// execute shortcode in sidebar
			add_filter( 'widget_text', array( &$this, 'ig_widget_text_filter' ) );

			add_filter( 'rainmaker_validate_request', array( &$this, 'form_submission_validate_request' ), 10, 2 );
			add_filter( 'icegram_data', array( $this, 'two_step_mobile_popup' ), 100, 1 );


			if ( defined( 'DOING_AJAX' ) ) {
				if ( $this->cache_compatibility === 'yes' ) {
					add_action( 'wp_ajax_display_messages', array( &$this, 'display_messages' ) );
					add_action( 'wp_ajax_nopriv_display_messages', array( &$this, 'display_messages' ) );
				}
				add_action( 'wp_ajax_icegram_event_track', array( &$this, 'icegram_event_track' ) );
				add_action( 'wp_ajax_nopriv_icegram_event_track', array( &$this, 'icegram_event_track' ) );
				add_action( 'wp_ajax_es_list_subscribe', array( &$this, 'es_list_subscribe' ) );
				add_action( 'wp_ajax_icegram_run_housekeeping', array( &$this, 'run_housekeeping' ) );
				add_action( 'wp_ajax_save_gallery_data', array( &$this, 'save_gallery_data' ) );

			}


		}


		function ig_plugin_settings_link( $links, $file ) {
			global $icegram;
			if ( $file == plugin_basename( IG_PLUGIN_FILE ) ) {

				$campaigns_link = '<a href="edit.php?post_type=ig_campaign">' . __( 'Campaigns', 'icegram' ) . '</a>';
				$docs_link 		= '<a href="https://www.icegram.com/knowledgebase_category/icegram/" target="_blank">' . __( 'Docs', 'icegram' ) . '</a>';
				$premium      	= '<a style="color:green;font-weight: bold;" href="https://www.icegram.com/pricing/" target="_blank">' . __( 'Go Premium', 'icegram' ) . '</a>';
				if ( ! $icegram->is_premium_installed() ) {
				 	array_unshift( $links, $premium );
					array_unshift( $links, $docs_link );
				}
				
				array_unshift( $links, $campaigns_link );
			}

			return $links;

		}

		/**
		 * Add additional links under plugins meta on plugins page
		 *
		 * @param array  $plugin_meta Plugin meta.
		 * @param string $plugin_file Plugin file.
		 * @param array  $plugin_data Plugin's data.
		 * @param string $status Plugin's status.
		 * @return array Plugin meta with additional links.
		 */
		function add_plugin_support_links( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			
			if ( plugin_basename( IG_PLUGIN_FILE ) === $plugin_file ) {
				$plugin_meta[] = '<a href="https://wordpress.org/support/plugin/icegram/reviews/#new-post" title="' . __( 'Rate Icegram', 'icegram' ) . '" target="_blank">' . __( 'Rate Icegram', 'icegram' ) . '</a>';
				$plugin_meta[] = '<a href="https://www.icegram.com/contact/" title="' . __( 'Support', 'icegram' ) . ' " target="_blank">' . __( 'Support', 'icegram' ) . '</a>';
			}
			return $plugin_meta;
		}

		public function load_compat_classes() {
			$compat_classes = (array) glob( $this->plugin_path . '/classes/compat/class-icegram-compat-*.php' );
			if ( empty( $compat_classes ) ) {
				return;
			}

			$active_plugins = (array) get_option( 'active_plugins', array() );
			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}
			$active_plugins            = array_unique( array_merge( array_values( $active_plugins ), array_keys( $active_plugins ) ) );
			$active_plugins_with_slugs = array();
			foreach ( $active_plugins as $key => $value ) {
				$slug = dirname( $value );
				if ( $slug == '.' ) {
					unset( $active_plugins[ $key ] );
				} else {
					$active_plugins[ $key ] = $slug;
				}
			}

			foreach ( $compat_classes as $file ) {
				if ( is_file( $file ) ) {
					$slug = str_replace( 'class-icegram-compat-', '', str_replace( ".php", "", basename( $file ) ) );
					if ( in_array( $slug, $active_plugins ) ) {
						include_once( $file );
						$class_name = 'Icegram_Compat_' . str_replace( '-', '_', $slug );
						if ( class_exists( $class_name ) ) {
							new $class_name();
						}
					}
				}
			}
		}

		/**
		 * Show promotion
		 */
		public function add_admin_notices() {
			global $icegram;

			$screen = get_current_screen();
			if ( ! in_array( $screen->id, array( 'ig_campaign', 'ig_message', 'edit-ig_message', 'edit-ig_campaign' ), true ) ) {
				return;
			}

			include_once( 'ig-offer.php' );

			//include_once IG_PLUGIN_DIR . 'lite/notices/admin-notices.php';

		}

		public function dismiss_admin_notice() {
			if ( isset( $_GET['ig_dismiss_admin_notice'] ) && $_GET['ig_dismiss_admin_notice'] == '1' && isset( $_GET['ig_option_name'] ) ) {
				$option_name = sanitize_text_field( $_GET['ig_option_name'] );
				update_option( $option_name . '_icegram', 'yes', false );

				//bfcm 2022 offer
				if ( 'ig_offer_bfcm_2022' === $option_name ) {
					$url = "https://www.icegram.com/pricing/?utm_source=in_app&utm_medium=ig_banner&utm_campaign=offer_bfcm_2022";
					header( "Location: {$url}" );
					exit();
				} elseif( 'ig_new_admin_ui' === $option_name ) {
					$url = "https://www.icegram.com/wp-content/uploads/2022/06/IG-admin-UI.png";
					header( "Location: {$url}" );
					exit();
				} else {
					$referer = wp_get_referer();
					wp_safe_redirect( $referer );
					exit();
				}
			}
		}

		/**
		 * Check if sale period
		 *
		 * @return boolean
		 */
		public static function is_offer_period( $offer_name = '' ) {

			$is_offer_period = false;
			if ( ! empty( $offer_name ) ) {
				$current_utc_time = time();
				$current_ist_time = $current_utc_time + ( 5.5 * HOUR_IN_SECONDS ); // Add IST offset to get IST time
				
				$offer_start_time 	= $current_ist_time;
				$offer_end_time 	= $current_ist_time;
				
				if ( 'bfcm' === $offer_name ) {
					$offer_start_time = strtotime( '2022-11-23 12:30:00' ); // Offer start time in IST
					$offer_end_time   = strtotime( '2022-11-30 12:30:00' ); // Offer end time in IST
				}
	
				$is_offer_period = $current_ist_time >= $offer_start_time && $current_ist_time <= $offer_end_time;
			}
			
			return $is_offer_period;
		}

		public function es_subscribe_form() {
			?>
	        <div class="wrap">
				<?php
				if ( stripos( get_current_screen()->base, 'settings' ) !== false ) {
					echo "<h2>" . __( 'Free Add-ons, Proven Marketing Tricks and  Updates', 'icegram' ) . "</h2>";
				}
				$current_user   = wp_get_current_user();
				$customer_email = $current_user->user_email;
				?>
	            <table class="form-table">
	                <tr>
	                    <th scope="row"><span class="text-base text-gray-500"><?php _e( 'Get add-ons and tips...', 'icegram' ) ?></span></th>
	                    <td>
	                    	<form name="ig_subscription_form" id="ig-subscription-form">
								<input type="hidden"  id="sign-up-list" name="list" value="d44945bf9155"/>
							    <input type="hidden" id="sign-up-form-source" name="form-source" value=""/>
								<input class="ltr form-input py-1" type="text" name="name" id="ig-sign-up-name" placeholder="Name"/>
		                        <input class="regular-text ltr form-input py-1" type="text" name="email" id="ig-sign-up-email" placeholder="Email" value="<?php echo $customer_email ?>"/>                    
		                        <input type="submit" name="submit" id="ig-sign-up-submit" class="button button-primary cursor-pointer align-middle px-4 py-0.5 -mt-1 mx-2 hover:shadow-md rounded-md font-medium bg-indigo-600 text-white" value="Subscribe">
		                        <br><br>
		                        <input type="checkbox" name="es-gdpr-agree" id="es-gdpr-agree" value="1" class="form-checkbox" required="required">
		                        <label for="es-gdpr-agree"><?php echo sprintf( __( 'I have read and agreed to our %s.', 'icegram' ), '<a href="https://www.icegram.com/privacy-policy/" target="_blank">' . __( 'Privacy Policy', 'icegram' ) . '</a>' ); ?></label>
		                        <br>
		                        <div id="ig-subscribe-response"></div>
							</form>
	                    </td>
	                </tr>
	            </table>
	        </div>
	        <style type="text/css">
	        	.ig-subscribe-response-success{
	        		color:green;
	        		padding-top:0.7rem;
	        		font-size: 1rem;
	        	}
	        	.ig-subscribe-response-error{
	        		color:red;
	        		padding-top:0.7rem;
	        		font-size: 1rem;
	        	}
	        </style>
	        <script type="text/javascript">
				jQuery(function () {
					jQuery("form[name=ig_subscription_form]").submit(function (e) {
						e.preventDefault();
						let name = jQuery('#ig-subscription-form #ig-sign-up-name').val();
						let email = jQuery('#ig-subscription-form #ig-sign-up-email').val();
						let form_source = jQuery('#ig-subscription-form #sign-up-form-source').val();
						let list = jQuery('#ig-subscription-form #sign-up-list').val();
						
						jQuery('#ig-subscribe-response').html('');
						params = jQuery("form[name=ig_subscription_form]").serializeArray();
						
						jQuery.ajax({
							method: 'POST',
							type: 'text',
							url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
							data: {
								action: 'es_list_subscribe',
								security: '<?php echo wp_create_nonce( 'ig-es-subscription-form' );?>',
								name: name,
								email: email,
								list: list,
								form_source: form_source,	
							},
							success: function (response) {		
								if( 'success' === response.status ) {
									jQuery('#ig-subscribe-response').html(response.message_text).addClass('ig-subscribe-response-success');
								} else {
									if( 'undefined' == typeof response.message_text ){
										response.message_text = 'Please try again later!';
									}
									jQuery('#ig-subscribe-response').html(response.message_text).addClass('ig-subscribe-response-error');
								}
							}
						});
					});
				});
	        </script>
			<?php
		}

		/**
		 * Send a sign up request to ES installed on IG site.
		 * 
		 * @since 2.1.6
		 * 
		 * @param array $request_data
		 */
		public function es_list_subscribe() {
			
			check_ajax_referer( 'ig-es-subscription-form', 'security' );
			
			$response = array(
				'status' => 'error',
			);

			$name  = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$email = ! empty( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			$list  = ! empty( $_POST['list'] ) ? sanitize_text_field( $_POST['list'] ) : '';

			if ( ! empty( $list ) && is_email( $email ) ) {

				$url_params = array(
					'ig_es_external_action' => 'subscribe',
					'name'                  => $name,
					'email'                 => $email,
					'list'                  => $list,
				);

				$ip_address = self::ig_get_ip();
				if ( ! empty( $ip_address ) && 'UNKNOWN' !== $ip_address ) {
					$url_params['ip_address'] = $ip_address;
				}

				$ig_url = 'https://www.icegram.com/';
				$ig_url = add_query_arg( $url_params, $ig_url );

				$args = array(
					'timeout' => 15,
				);

				// Make a get request.
				$api_response = wp_remote_get( $ig_url, $args );

				if ( ! is_wp_error( $api_response ) ) {
					$body = ! empty( $api_response['body'] ) && self::is_valid_json( $api_response['body'] ) ? json_decode( $api_response['body'], true ) : '';
					
					$success_message = __('Your subscription was successful! Kindly check your mailbox and confirm your subscription.', 'icegram');
					if ( ! empty( $body ) ) {

						// If we have received an id in response then email is successfully queued at mailgun server.
						if ( ! empty( $body['status'] ) && 'SUCCESS' === $body['status'] ) {
							$response['status'] = 'success';
							$response['message']      = $body['message'];
							$response['message_text'] = $success_message;
							
						} elseif ( ! empty( $body['status'] ) && 'ERROR' === $body['status'] ) {
							$response['status']       = 'error';
							$response['message']      = $body['message'];
							$response['message_text'] = $body['message_text'];
						}
					} else {
						$response['status'] = 'success';
						$response['message']      = 'es_optin_success_message';
						$response['message_text'] = $success_message;
					}
				} else {
					$response['status'] = 'error';
				}
			}

			wp_send_json( $response );
		}

		public function icegram_houskeeping() {
			?>
	        <div class="wrap">
				<?php
				if ( stripos( get_current_screen()->base, 'settings' ) !== false ) {
				?>
	            <form name="icegram_housekeeping" action="#" method="POST" accept-charset="utf-8">
	                <h2><?php _e( 'Housekeeping', 'icegram' ) ?></h2>
	                <p class="ig_housekeeping">
	                    <label for="icegram_remove_shortcodes">
	                        <input type="checkbox" name="icegram_remove_shortcodes" value="yes"/>
							<?php _e( 'Remove all Icegram Engage shortcodes', 'icegram' ); ?>
	                    </label>
	                    <br/><br/>
	                    <label for="icegram_remove_all_data">
	                        <input type="checkbox" name="icegram_remove_all_data" value="yes"/>
							<?php _e( 'Remove all Icegram Engage campaigns and messages', 'icegram' ); ?>
	                    </label>
	                    <br/><br/>
	                    <img alt="" src="<?php echo admin_url( 'images/wpspin_light.gif' ) ?>" class="ig_loader" style="vertical-align:middle;display:none"/>
	                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Clean Up', 'icegram' ); ?>">
	                <div id="icegram_housekeeping_response"></div>
	                </p>
	            </form>

	        </div>
	        <script type="text/javascript">
				jQuery(function () {
					jQuery("form[name=icegram_housekeeping]").submit(function (e) {
						if (confirm("<?php _e( 'You won\'t be able to recover this data once you proceed. Do you really want to perform this action?', 'icegram' ); ?>") == true) {
							e.preventDefault();
							jQuery('.ig_loader').show();
							jQuery('#icegram_housekeeping_response').text("");
							params = jQuery("form[name=icegram_housekeeping]").serializeArray();
							params.push({name: 'action', value: 'icegram_run_housekeeping'});
							params.push({name: 'security', value: '<?php echo wp_create_nonce( 'ig_run_housekeeping' ); ?>'});

							jQuery.ajax({
								method: 'POST',
								type: 'text',
								url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
								data: params,
								success: function (response) {
									jQuery('.ig_loader').hide();
									jQuery('#icegram_housekeeping_response').text("<?php _e( 'Done!', 'icegram' ); ?>");
								}
							});
						}
					});
				});
	        </script>
			<?php
		}
		}

		public function run_housekeeping() {
			check_ajax_referer( 'ig_run_housekeeping', 'security' );
			global $wpdb, $current_user;
			$params = $_POST;
			$_POST  = array();
			if ( current_user_can( 'manage_options' ) && ! empty( $params['icegram_remove_shortcodes'] ) && $params['icegram_remove_shortcodes'] == 'yes' ) {
				// first get all posts with [icegram] shortcode in them
				$sql   = "SELECT * FROM `$wpdb->posts` WHERE  `post_content` LIKE  '%[icegram %]%' and `post_type` != 'revision' ";
				$posts = $wpdb->get_results( $sql, OBJECT );
				if ( ! empty( $posts ) && is_array( $posts ) ) {
					foreach ( $posts as $post ) {
						$post_content = $post->post_content;
						// remove shortcode with regexp now
						$re           = "/\\[icegram(.)*\\]/i";
						$post_content = preg_replace( $re, '', $post_content );
						// save post content back
						if ( $post_content && $post_content != $post->post_content ) {
							wp_update_post( array(
								'ID'           => $post->ID,
								'post_content' => $post_content
							) );
						}
					}
				}
			}

			if ( ! empty( $params['icegram_remove_all_data'] ) && $params['icegram_remove_all_data'] == 'yes' ) {
				$posts = get_posts( array( 'post_type' => array( 'ig_campaign', 'ig_message' ) ) );
				if ( ! empty( $posts ) && is_array( $posts ) ) {
					foreach ( $posts as $post ) {
						wp_delete_post( $post->ID, true );
					}
				}
				do_action( 'icegram_remove_all_data' );
			}
			$_POST = $params;
		}

		public function icegram_event_track() {
			if ( ! empty( $_POST['ig_local_url_cs'] ) && isset( $_SERVER['HTTP_ORIGIN'] ) ) {
				$parts    = parse_url( sanitize_text_field( $_POST['ig_local_url_cs'] ) );
				$base_url = $parts["scheme"] . "://" . $parts["host"];
				header( 'Access-Control-Allow-Origin: ' . $base_url );
				header( 'Access-Control-Allow-Credentials: true' );
			}

			if ( ! empty( $_POST['event_data'] ) ) {
				foreach ( $_POST['event_data'] as $event ) {
					switch ( $event['type'] ) {
						case 'shown':
							if ( is_array( $event['params'] ) && ! empty( $event['params']['message_id'] ) ) {
								$messages_shown[] = $event['params']['message_id'];
								if ( ! empty( $event['params']['expiry_time'] ) ) {
									if ( $event['params']['expiry_time'] == 'today' ) {
										$event['params']['expiry_time'] = strtotime( '+1 day', mktime( 0, 0, 0 ) );
									} elseif ( $event['params']['expiry_time'] == 'current_session' ) {
										$event['params']['expiry_time'] = 0;
									} else {
										$event['params']['expiry_time'] = strtotime( $event['params']['expiry_time'] );
									}

									$event['default'] = true;
									$event            = apply_filters( 'icegram_check_event_track', $event );
									if ( $event['default'] ) {
										setcookie( 'icegram_campaign_shown_' . floor( $event['params']['campaign_id'] ), true, $event['params']['expiry_time'], '/' );
									}
								}
							}
							break;
						case 'clicked':
							if ( is_array( $event['params'] ) && ! empty( $event['params']['message_id'] ) ) {
								$messages_clicked[] = $event['params']['message_id'];
								if ( ! empty( $event['params']['expiry_time_clicked'] ) ) {
									if ( $event['params']['expiry_time_clicked'] == 'today' ) {
										$event['params']['expiry_time_clicked'] = strtotime( '+1 day', mktime( 0, 0, 0 ) );
									} elseif ( $event['params']['expiry_time_clicked'] == 'current_session' ) {
										$event['params']['expiry_time_clicked'] = 0;
									} else {
										$event['params']['expiry_time_clicked'] = strtotime( $event['params']['expiry_time_clicked'] );
									}
									//setcookie('icegram_messages_clicked_'.$event['params']['message_id'],true , $event['params']['expiry_time_clicked'] , '/' );
									setcookie( 'icegram_campaign_clicked_' . floor( $event['params']['campaign_id'] ), true, $event['params']['expiry_time_clicked'], '/' );
								}
							}
							break;

						default:
							break;
					}

					// Emit event for other plugins to handle it
					do_action( 'icegram_event_track', $event );
					do_action( 'icegram_event_track_' . $event['type'], $event['params'] );
				}
			}
			exit();
		}

		static function activate() {
			// Redirect to welcome screen
			delete_option( '_icegram_activation_redirect' );
			add_option( '_icegram_activation_redirect', 'pending' );

			do_action( 'ig_activated' );
		}
		
		static function deactivate() {
			
			do_action( 'ig_deactivated' );
		}

		public function welcome() {

			$this->db_update();
			// Bail if no activation redirect transient is set
			if ( false === get_option( '_icegram_activation_redirect' ) ) {
				return;
			}

			// Delete the redirect transient
			delete_option( '_icegram_activation_redirect' );
			
			if ( self::show_campaign_creation_guide() ) {
				wp_redirect('post-new.php?post_type=ig_campaign');
			} else {
				wp_redirect( admin_url( 'edit.php?post_type=ig_campaign&page=icegram-support' ) );
			}

		}

		function db_update() {
			$current_db_version = get_option( 'icegram_db_version' );
			if ( ! $current_db_version || version_compare( $current_db_version, '1.2', '<' ) ) {
				include( 'updates/icegram-update-1.2.php' );
			}
		}

		public function admin_menus() {

			$dashboard_title 		 = __( 'Dashboard', 'icegram' );
			$ig_dashboard    = add_submenu_page( 'edit.php?post_type=ig_campaign', $dashboard_title, $dashboard_title, 'manage_options', 'icegram-dashboard', array( $this, 'dashboard_screen' ), 0 );


			$welcome_page_title  = __( 'Welcome to Icegram', 'icegram' );
			$gallery_page_title  = '<span style="color:#f18500;font-weight:bolder;">' . __( 'Template Gallery', 'icegram' ) . '<span>';
			$gallery             = add_submenu_page( 'edit.php?post_type=ig_campaign', $gallery_page_title, $gallery_page_title, 'manage_options', 'icegram-gallery', array( $this, 'gallery_screen' ) );
			$settings_page_title = __( 'Settings', 'icegram' );

			$menu_title = __( 'Docs & Support', 'icegram' );
			$about      = add_submenu_page( 'edit.php?post_type=ig_campaign', $welcome_page_title, $menu_title, 'manage_options', 'icegram-support', array( $this, 'about_screen' ) );
			$settings   = add_submenu_page( 'edit.php?post_type=ig_campaign', $settings_page_title, $settings_page_title, 'manage_options', 'icegram-settings', array( $this, 'settings_screen' ) );

			wp_register_style( 'icegram-activation', $this->plugin_url . '/assets/css/admin.min.css' );

			add_action( 'admin_print_styles-' . $about, array( $this, 'admin_css' ) );
			add_action( 'admin_print_styles-' . $settings, array( $this, 'admin_css' ) );
			
			if( ! self::is_max() ) {
				$upgrade_page_title  = '<span style="color:#f18500;font-weight:bolder;">' . __( '🔥 Go Max', 'icegram' ) . '</span>';
				$upgrade    = add_submenu_page( 'edit.php?post_type=ig_campaign', $upgrade_page_title, $upgrade_page_title, 'manage_options', 'icegram-upgrade', array( $this, 'upgrade_screen' ) );
				add_action( 'admin_print_styles-' . $upgrade, array( $this, 'admin_css' ) );
			}
		}

		public function admin_css() {
			wp_enqueue_style( 'icegram-activation' );
		}

		public function about_screen() {
			include( 'about-icegram.php' );
		}

		public function settings_screen() {
			include( 'settings.php' );
		}

		public function upgrade_screen() {
			Icegram_Pricing::ig_show_pricing();
		}

		/**
		 * Include Dashboard screen
		 *
		 * @since 3.1.2
		 */
		public function dashboard_screen(){
			include('ig-dashboard.php');
		}

		public function check_for_gallery_items( $force_update = false ) {
			global $icegram;

			if ( $force_update === true || false === ( $ig_last_gallery_item_update = get_transient( 'ig_last_gallery_item_update' ) ) ) {
				// $url_for_gallery_item = 'https://www.icegram.com/gallery/wp-json/wp/v2/galleryitem?per_page=200&page=1';
				$url_for_gallery_item = 'https://www.icegram.com/gallery/wp-json/wp/v2/galleryitem?filter[posts_per_page]=200';
				$ig_gallery_json      = wp_remote_get( $url_for_gallery_item );
				
				if ( ! is_wp_error( $ig_gallery_json ) ) {
					$ig_gallery_json = ( wp_remote_retrieve_body( $ig_gallery_json ) );
					if ( ! empty( $ig_gallery_json ) ) {
						update_option( 'ig_last_gallery_items', $ig_gallery_json );
					}
				} else {
					update_option( 'requested_gallery_item_with_ajax', 'yes' );
					?>
	                <script type="text/javascript">
						jQuery(document).ready(function () {
							jQuery.ajax({
								url: '<?php echo esc_url( $url_for_gallery_item ); ?>',
								method: 'GET',
								dataType: 'json',
								success: function (response) {
									if (response != undefined && response != '') {
										//ajax to save data
										jQuery.ajax({
											url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
											method: 'POST',
											dataType: 'json',
											data: {
												action: 'save_gallery_data',
												galleryitems: JSON.stringify(response),
												security: '<?php echo wp_create_nonce( 'gallery-save-data' ); // WPCS: XSS ok. ?>'
											},
											success: function (res) {
												if (res != undefined && res != '' && res.success != undefined && res.success == 'yes') {
													// All done.
												}
											}
										});
									}
								},
								error: function (response) {
									console.log(response, 'res');

								}
							});
						});
	                </script>
				<?php }
				$url_for_categories = 'https://www.icegram.com/gallery/wp-json/wp/v2/custom_cat?filter[orderby]=parent&order=desc';
				$options            = array(
					'timeout' => 15,
					'method'  => 'GET',
					'body'    => ''
				);
				$response           = wp_remote_request( $url_for_categories, $options );
				$response_code      = wp_remote_retrieve_response_code( $response );
				// $body = json_decode($response['body'] ,true);
				if ( $response_code == 200 ) {
					$categories = json_decode( $response['body'], true );
					$cat_list   = array();
					foreach ( $categories as $category ) {
						if ( $category['parent'] == 0 ) {
							$cat_list[ $category['term_id'] ]['name']    = $category['name'];
							$cat_list[ $category['term_id'] ]['slug']    = $category['slug'];
							$cat_list[ $category['term_id'] ]['term_id'] = $category['term_id'];
						} else {
							$cat_list[ $category['parent'] ]['list'][] = $category;
						}
					}
					$featured_cat = $cat_list[53];
					unset( $cat_list[53] );
					array_unshift( $cat_list, $featured_cat );
					update_option( 'ig_cat_list', $cat_list );
				} else {
					?>
	                <script type="text/javascript">
						jQuery(document).ready(function () {
							jQuery.ajax({
								url: '<?php echo esc_url( $url_for_categories ); ?>',
								method: 'GET',
								dataType: 'json',
								success: function (response) {

									if (response != undefined && response != '') {
										//ajax to save data
										jQuery.ajax({
											url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
											method: 'POST',
											dataType: 'json',
											data: {
												action: 'save_gallery_data',
												categories: response,
												security: '<?php echo wp_create_nonce( 'gallery-save-data' ); // WPCS: XSS ok. ?>'
											},
											success: function (res) {
												if (res != undefined && res != '' && res.success != undefined && res.success == 'yes') {
													// All done.
												}
											}
										});
									}
								},
								error: function (response) {
									console.log(response, 'res');

								}
							});
						});
	                </script>
					<?php
				}
				set_transient( 'ig_last_gallery_item_update', current_time( 'timestamp' ), 24 * HOUR_IN_SECONDS ); // 1 day
			}
		}

		public function save_gallery_data() {
			check_ajax_referer( 'gallery-save-data', 'security' );
			if ( current_user_can( 'manage_options' ) ) {
				if ( ! empty( $_REQUEST ) && ! empty( $_REQUEST['galleryitems'] ) ) {
					$ig_gallery_json = stripslashes( $_REQUEST['galleryitems'] );
					update_option( 'ig_last_gallery_items', $ig_gallery_json );
				}
				if ( ! empty( $_REQUEST ) && ! empty( $_REQUEST['categories'] ) ) {
					$categories = $_REQUEST['categories'];
					$cat_list   = array();
					foreach ( $categories as $category ) {
						if ( $category['parent'] == 0 ) {
							$cat_list[ $category['term_id'] ]['name'] = $category['name'];
							$cat_list[ $category['term_id'] ]['slug'] = $category['slug'];
						} else {
							$cat_list[ $category['parent'] ]['list'][] = $category;
						}
					}
					update_option( 'ig_cat_list', $cat_list );
				}
			}

		}

		public static function gallery_screen() {
			global $icegram;
			//check for new gallery item
			$ig_last_gallery_item_update = get_transient( 'ig_last_gallery_item_update' );
			
			if ( empty( $ig_last_gallery_item_update ) ) {
				$icegram->check_for_gallery_items( true );
			}
			$ig_gallery_items = get_option( 'ig_last_gallery_items', true );
			$cat_list         = get_option( 'ig_cat_list', true );
			include( 'gallery.php' );
			wp_register_script( 'ig_gallery_js', $icegram->plugin_url . '/assets/js/gallery.min.js', array( 'jquery', 'backbone', 'wp-backbone', 'wp-a11y', 'wp-util' ), $icegram->version, true );
			if ( ! wp_script_is( 'ig_gallery_js' ) ) {
				wp_enqueue_script( 'ig_gallery_js' );
				$imported_gallery_items = get_option( 'ig_imported_gallery_items', true );
				$ig_plan                = get_option( 'ig_engage_plan' );
				$ig_plan                = ( ! empty( $ig_plan ) ) ? ( ( $ig_plan == 'plus' ) ? 1 : ( ( $ig_plan == 'pro' ) ? 2 : ( ( $ig_plan == 'max' ) ? 3 : 0 ) ) ) : 0;
				$ig_gallery_json        =
					wp_localize_script( 'ig_gallery_js', '_wpThemeSettings', array(
						'themes'          => json_decode( $ig_gallery_items, true ),
						'settings'        => array(
							'canInstall'    => ( ! is_multisite() && ( 'install_themes' ) ),
							'isInstall'     => true,
							'installURI'    => ( ! is_multisite() && ( 'install_themes' ) ) ? admin_url( 'theme-install.php' ) : null,
							'confirmDelete' => __( "Are you sure you want to delete this theme?\n\nClick 'Cancel' to go back, 'OK' to confirm the delete.", 'icegram' ),
							'adminUrl'      => parse_url( admin_url(), PHP_URL_PATH ),
							'ig_plan'       => $ig_plan,
							'cat_list'      => $cat_list
						),
						'l10n'            => array(
							'addNew'            => __( 'Add New Gallery Templates', 'icegram' ),
							'search'            => __( 'Search Gallery Templates', 'icegram' ),
							'searchPlaceholder' => __( 'Search Design Templates', 'icegram' ), // placeholder (no ellipsis)
							'themesFound'       => __( 'Number of Gallery Templates found: %d', 'icegram' ),
							'noThemesFound'     => __( 'No Gallery Templates found. Try a different search.', 'icegram' ),
						),
						'installedThemes' => $imported_gallery_items
					) );
			}
		}

		public function branding_data_remove( $icegram_branding_data ) {
			if ( ! empty( $icegram_branding_data ) && 'yes' != get_option( 'icegram_share_love', 'no' ) ) {
				$icegram_branding_data['powered_by_logo'] = '';
				$icegram_branding_data['powered_by_text'] = '';
			}

			return $icegram_branding_data;
		}

		//Execute Form shortcode
		function execute_form_shortcode( $atts = array() ) {
			return '<div class="ig_form_container layout_inline"></div>';
		}

		function execute_shortcode( $atts = array(), $content = null ) {
			// When shortcode is called, it will only prepare an array with conditions
			// And add a placeholder div
			// Display will happen in footer via display_messages()
			$i                               = count( $this->shortcode_instances );
			$this->shortcode_instances[ $i ] = shortcode_atts( array(
				'campaigns'   => '',
				'messages'    => '',
				'skip_others' => 'no'
			), $atts );

			$class[] = "ig_shortcode_container";
			$html[]  = "<div id='icegram_shortcode_{$i}'";
			if ( ! empty( $atts['campaigns'] ) && ! empty( $content ) ) {
				$this->shortcode_instances[ $i ]['with_content'] = true;
				$class[]                                         = "trigger_onclick";
			}
			foreach ( $atts as $key => $value ) {
				$value  = str_replace( ",", " ", $value );
				$html[] = " data-{$key}=\"" . htmlentities( $value ) . "\" ";
			}
			$class  = implode( " ", $class );
			$html[] = "class='" . $class . "' >" . $content . "</div>";

			return implode( " ", $html );
		}

		// Do not index Icegram campaigns / messages...
		// Not using currently - made custom post types non public...
		function icegram_load_data() {
			global $post;
			$icegram_pre_data['ajax_url']                        = admin_url( 'admin-ajax.php' );
			$icegram_pre_data['post_obj']                        = $_GET;
			$icegram_pre_data['post_obj']['is_home']             = ( is_home() || is_front_page() ) ? true : false;
			$icegram_pre_data['post_obj']['page_id']             = is_object( $post ) && isset( $post->ID ) ? $post->ID : 0;
			$icegram_pre_data['post_obj']['action']              = 'display_messages';
			$icegram_pre_data['post_obj']['shortcodes']          = $this->shortcode_instances;
			$icegram_pre_data['post_obj']['cache_compatibility'] = $this->cache_compatibility;
			$icegram_pre_data['post_obj']['device']              = $this->get_platform();

			wp_register_script( 'icegram_main_js', $this->plugin_url . '/assets/js/main.min.js', array( 'jquery' ), $this->version, true );
			if ( 'yes' === $this->cache_compatibility ) {
				if ( ! wp_script_is( 'icegram_main_js' ) ) {
					wp_enqueue_script( 'icegram_main_js' );
				}
			}
			wp_localize_script( 'icegram_main_js', 'icegram_pre_data', $icegram_pre_data );
		}

		function display_messages() {

			$skip_others               = $preview_mode = false;
			$campaign_ids              = $message_ids = array();
			$this->shortcode_instances = ( $this->cache_compatibility == 'yes' && ! empty( $_REQUEST['shortcodes'] ) ) ? $_REQUEST['shortcodes'] : $this->shortcode_instances;
			// Pull in message and campaign IDs from shortcodes - if set
			if ( ! empty( $this->shortcode_instances ) ) {
				foreach ( $this->shortcode_instances as $i => $value ) {
					$cids = array_map( 'trim', (array) explode( ',', intval( $value['campaigns'] ) ) );
					$mids = array_map( 'trim', (array) explode( ',', intval( $value['messages'] ) ) );
					if ( ! empty( $value['skip_others'] ) && $value['skip_others'] == 'yes' && ( ! empty( $cids ) || ! empty( $mids ) ) ) {
						$skip_others = true;
					}
					$campaign_ids = array_merge( $campaign_ids, $cids );
					$message_ids  = array_merge( $message_ids, $mids );
				}
			}
			if ( ! empty( $_REQUEST['campaign_preview_id'] ) && intval( $_REQUEST['campaign_preview_id'] ) && ( 'edit_posts' ) ) {
				$campaign_ids = array( intval( $_REQUEST['campaign_preview_id'] ) );
				$preview_mode = true;
			}

			$messages = $this->get_valid_messages( $message_ids, $campaign_ids, $preview_mode, $skip_others );

			if ( empty( $messages ) ) {
				//wp_die(0);
				return;
			}

			$messages_to_show_ids = array();
			foreach ( $messages as $key => $message_data ) {

				if ( ! is_array( $message_data ) || empty( $message_data ) ) {
					continue;
				}
				
				$messages[$key]['headline'] = isset( $message_data['headline'] ) ? wp_kses_post( $message_data['headline'] ) : '';
				$messages[$key]['label'] = isset( $message_data['label'] ) ? wp_kses_post( $message_data['label'] ) : '';
				$messages[$key]['title'] = isset( $message_data['title'] ) ? wp_kses_post( $message_data['title'] ) : '';

				// Don't show a seen message again - if needed
				// change to campaign targetting in v1.9.1
				if ( ! empty( $message_data['id'] ) &&
				     empty( $_GET['campaign_preview_id'] ) &&
				     ! empty( $message_data['retargeting'] ) &&
				     $message_data['retargeting'] == 'yes'
				) {
					if ( ! empty( $_COOKIE[ 'icegram_messages_shown_' . $message_data['id'] ] ) || ! empty( $_COOKIE[ 'icegram_campaign_shown_' . floor( $message_data['campaign_id'] ) ] ) ) {
						unset( $messages[ $key ] );
						continue;
					}
				}
				if ( ! empty( $message_data['id'] ) &&
				     empty( $_GET['campaign_preview_id'] ) &&
				     ! empty( $message_data['retargeting_clicked'] ) &&
				     $message_data['retargeting_clicked'] == 'yes'
				) {
					if ( ! empty( $_COOKIE[ 'icegram_messages_clicked_' . $message_data['id'] ] ) || ! empty( $_COOKIE[ 'icegram_campaign_clicked_' . floor( $message_data['campaign_id'] ) ] ) ) {
						unset( $messages[ $key ] );
						continue;
					}
				}

				// Avoid showing the same message twice
				if ( in_array( $message_data['id'], $messages_to_show_ids ) ) {
					unset ( $messages[ $key ] );
					continue;
				} else {
					$messages_to_show_ids[] = $message_data['id'];
				}

				$this->process_message_body( $messages[ $key ] );
			}
			if ( empty( $messages ) ) {
				return;
			}

			// Load icegram_main_js only when the $messages are still
	        // not empty at this stage.
			if ( ! wp_script_is( 'icegram_main_js' ) ) {
				wp_enqueue_script( 'icegram_main_js' );
			}

			$icegram_default = apply_filters( 'icegram_branding_data',
				array(
					'icon'            => $this->plugin_url . '/assets/images/icegram-logo-branding-64-grey.png',
					'powered_by_logo' => $this->plugin_url . '/assets/images/icegram-logo-branding-64-grey.png',
					'powered_by_text' => __( 'Powered by Icegram', 'icegram' )
				) );
			$messages        = apply_filters( 'icegram_messages_to_show', $messages );
			$icegram_data    = apply_filters( 'icegram_data', array(
				'messages'   => array_values( $messages ),
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'preview_id' => ! empty( $_GET['campaign_preview_id'] ) ? sanitize_text_field( $_GET['campaign_preview_id'] ) : '',
				'defaults'   => $icegram_default
			) );
			if ( empty( $icegram_data['preview_id'] ) ) {
				unset( $icegram_data['preview_id'] );
			}

			do_action( 'icegram_print_js_css_data', $icegram_data );


			// Load CF7 & Garvity Forms CSS & JS only if form loaded
			$compat_form_types = array( 'contact-form-7', 'gravityform', 'wpforms' );

			foreach ( $icegram_data['messages'] as $message_id ) {
				foreach ( $compat_form_types as $type ) {
					if ( strpos( $message_id['form_html_original'], $type ) ) {
						do_action( 'icegram_data_printed', $type );
					}
				}
			}
		}

		function two_step_mobile_popup( $icegram_data ) {

			$temp = array();
			foreach ( $icegram_data['messages'] as $message_id => $message ) {

				if ( ! empty( $message['ig_mobile_popup'] ) && $message['ig_mobile_popup'] == true ) {
					$action_bar                        = $message;
					$action_bar['type']                = 'action-bar';
					$action_bar['theme']               = 'hello';
					$action_bar['position']            = '21';
					$action_bar['message']             = '';
					$action_bar['label']               = __( 'Show More', 'icegram' );
					$action_bar['id']                  = $action_bar['id'] . '_00';
					$action_bar['use_custom_code']     = 'yes';
					$action_bar['form_html']           = '';
					$action_bar['form_html_original']  = '';
					$action_bar['rainmaker_form_code'] = '';
					$action_bar['link']                = '';
					$action_bar['redirect_to_link']    = '';
					$action_bar['cta']                 = '';
					$action_bar['alt_cta']             = '';
					$action_bar['add_alt_cta']         = '';
					$action_bar['custom_css']          = '#ig_this_message .ig_close{display:none;}';
					$action_bar['custom_js']           = "<script type='text/javascript'>jQuery('#icegram_message_" . $action_bar['id'] . "').on('click', '.ig_button', function(){icegram.get_message_by_id('" . $action_bar['id'] . "').hide(); icegram.get_message_by_id('" . $message['id'] . "').show(); });</script>";
					unset( $action_bar['ig_mobile_popup'] );
					$temp[] = $action_bar;
				}
			}
			$icegram_data['messages'] = array_merge( $icegram_data['messages'], $temp );
			unset( $temp );

			return $icegram_data;
		}


		function print_js_css_data( $icegram_data ) {

			$this->collect_js_and_css( $icegram_data );
			if ( $this->cache_compatibility === 'yes' ) {
				echo json_encode( $icegram_data );
				wp_die();
			} else {
				wp_localize_script( 'icegram_main_js', 'icegram_data', $icegram_data );
			}
		}

		function collect_js_and_css( &$icegram_data ) {

			$types_shown = array();
			$scripts     = array();
			$css         = array();
			foreach ( $icegram_data['messages'] as $key => $message_data ) {
				$types_shown[] = $message_data['type'];
			}

			$types_shown = array_unique( $types_shown );
			$ver_prefix  = '?var=' . $this->version;

			$scripts[] = $this->plugin_url . "/assets/js/icegram.min.js" . $ver_prefix;
			$css[]     = $this->plugin_url . "/assets/css/frontend.min.css" . $ver_prefix;
			//minify and combine only for default msg type
			$ig_core_message_types = array( 'popup', 'action-bar', 'toast', 'messenger' );
			// Load JS and default CSS
			foreach ( $types_shown as $message_type ) {
				if ( ! in_array( $message_type, $ig_core_message_types ) ) {
					$scripts[] = $this->message_types[ $message_type ]['baseurl'] . "main.js" . $ver_prefix;
					$css[]     = $this->message_types[ $message_type ]['baseurl'] . "default.css" . $ver_prefix;
				} else {
					$css[] = $this->message_types[ $message_type ]['baseurl'] . 'themes/' . $message_type . ".min.css" . $ver_prefix;
				}
			}

			//TODO :: add theme pack theme css files too.
			// Load theme CSS
			foreach ( $icegram_data['messages'] as $key => $message ) {
				if ( ! empty( $this->message_types[ $message['type'] ]['themes'][ $message['theme'] ] ) ) {
					$theme = $this->message_types[ $message['type'] ]['themes'][ $message['theme'] ]['baseurl'] . $message['theme'] . '.css' . $ver_prefix;
				} else {
					$theme_default                             = $this->message_types[ $message['type'] ] ['settings']['theme']['default'];
					$theme                                     = $this->message_types[ $message['type'] ]['themes'][ $theme_default ]['baseurl'] . $theme_default . '.css' . $ver_prefix;
					$icegram_data['messages'][ $key ]['theme'] = $theme_default;
				}
				if ( ! preg_match( '/icegram\/message-types/i', $theme ) ) {
					$css [] = $theme;
				}
			}
			$css                     = array_unique( $css );
			$icegram_data['scripts'] = apply_filters( 'add_icegram_script', $scripts );
			$icegram_data['css']     = apply_filters( 'add_icegram_css', $css );
			return $icegram_data;
		}

		// Process
		function process_message_body( &$message_data ) {
			global $wp_scripts;
			global $wp_styles;

			if ( $this->cache_compatibility == 'yes' ) {
				$q_script = ! empty( $wp_scripts->queue ) ? $wp_scripts->queue : array();
				$q_style  = ! empty( $wp_styles->queue ) ? $wp_styles->queue : array();
			}
			$content = $message_data['message'];
			$content = convert_chars( convert_smilies( wptexturize( $content ) ) );
			if ( isset( $GLOBALS['wp_embed'] ) ) {
				$content = $GLOBALS['wp_embed']->autoembed( $content );
			}
			$content                 = $this->after_wpautop( wpautop( $this->before_wpautop( $content ) ) );
			$content                 = do_shortcode( shortcode_unautop( $content ) );
			$message_data['message'] = $content;

			//do_shortcode in headline
			$message_data['headline'] = do_shortcode( shortcode_unautop( $message_data['headline'] ) );
			//shortcode support for Third party forms and Rainmaker
			$form_html_original = ! empty( $message_data["rainmaker_form_code"] )
				? ( '[rainmaker_form id="' . $message_data["rainmaker_form_code"] . '"]' )
				: ( ! empty( $message_data['form_html_original'] ) ? $message_data['form_html_original'] : '' );
			$form_html_original = ! empty( $message_data["es_form_code"] )
				? ( '[email-subscribers-form id="' . $message_data["es_form_code"] . '"]' )
				: $form_html_original;

			if ( ! empty( $form_html_original ) ) {
				$message_data['form_html'] = do_shortcode( shortcode_unautop( $form_html_original ) );
			}
			//TODO :: Handle case for inline style and script
			if ( $this->cache_compatibility == 'yes' ) {
				$handles = ! empty( $wp_scripts->queue ) ? array_diff( $wp_scripts->queue, $q_script ) : array();
				unset( $q_script );
				if ( ! empty( $handles ) ) {
					if ( empty( $message_data['assets'] ) ) {
						$message_data['assets'] = array();
					}

					ob_start();
					$wp_scripts->do_items( $handles );
					$message_data['assets']['scripts'] = array_filter( explode( '<script', ob_get_clean() ) );
				}

				//TODO :: do_items if required
				$handles = ! empty( $wp_styles->queue ) ? array_diff( $wp_styles->queue, $q_style ) : array();
				unset( $q_style );
				if ( ! empty( $handles ) ) {
					if ( empty( $message_data['assets'] ) ) {
						$message_data['assets'] = array();
					}

					foreach ( $handles as $handle ) {
						ob_start();
						$wp_styles->do_item( $handle );
						$message_data['assets']['styles'][ $handle ] = ob_get_clean();
					}
				}
			}
		}

		function enqueue_admin_styles_and_scripts() {

			$screen = get_current_screen();
			if ( in_array( $screen->id, array( 'ig_campaign', 'ig_message', 'edit-ig_campaign', 'edit-ig_message', 'ig_campaign_page_icegram-gallery', 'ig_campaign_page_icegram-dashboard' ), true ) ) {
				wp_enqueue_style( 'icegram_tailwind_style', $this->plugin_url . '/dist/main.css', array(), $this->version );
				
				// Select2 CSS
				if ( ! wp_style_is( 'select2', 'registered' ) ) {
					wp_register_style( 'select2', $this->plugin_url . '/assets/css/select2.min.css', array(), '4.0.13' );
				}

				if ( ! wp_style_is( 'select2' ) ) {
					wp_enqueue_style( 'select2' );
				}

				// Select2 JS
				if ( ! wp_script_is( 'select2', 'registered' ) ) {
					wp_register_script( 'select2', $this->plugin_url . '/assets/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
				}

				if ( ! wp_script_is( 'select2' ) ) {
					wp_enqueue_script( 'select2' );
				}

			}

			if ( ! in_array( $screen->id, array( 'ig_campaign', 'ig_message', 'edit-ig_campaign' ), true ) ) {
				return;
			}

			if( 'edit-ig_campaign' === $screen->id ){
				wp_enqueue_style( 'icegram-activation' );
			}

			// Register scripts
			wp_register_script( 'icegram_writepanel', $this->plugin_url . '/assets/js/admin.min.js', array( 'jquery', 'wp-color-picker' ), $this->version );

			wp_enqueue_script( 'icegram_writepanel' );

			wp_enqueue_script( 'tailwind_admin_ui', $this->plugin_url . '/assets/js/campaign-admin-new.js', array( 'jquery', 'wp-color-picker' ), $this->version );

			$icegram_writepanel_params = array( 
				'ajax_url' 				=> admin_url( 'admin-ajax.php' ), 
				'search_message_nonce' 	=> wp_create_nonce( "search-messages" ), 
				'ig_nonce' 				=> wp_create_nonce( "ig-nonce" ), 
				'home_url' 				=> home_url( '/' ), 
				'i18n_data' 			=> array(
					'ajax_error_message'  => __( 'An error has occured. Please try again later.', 'icegram' ),
				), 
			);
			$this->available_headlines = apply_filters( 'icegram_available_headlines', array() );
			$icegram_writepanel_params = array_merge( $icegram_writepanel_params, array( 'available_headlines' => $this->available_headlines ) );

			wp_localize_script( 'icegram_writepanel', 'icegram_writepanel_params', $icegram_writepanel_params );

			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'icegram_admin_styles', $this->plugin_url . '/assets/css/admin.min.css', array(), $this->version );

			if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}

		}

		//execute shortcode in text widget
		function ig_widget_text_filter( $content ) {
			if ( ! preg_match( '/\[[\r\n\t ]*icegram?[\r\n\t ].*?\]/', $content ) ) {
				return $content;
			}

			$content = do_shortcode( $content );

			return $content;
		}

		public static function get_platform() {
			$mobile_detect = new Ig_Mobile_Detect();
			$mobile_detect->setUserAgent();
			if ( $mobile_detect->isMobile() ) {
				return ( $mobile_detect->isTablet() ) ? 'tablet' : 'mobile';
			} elseif ( $mobile_detect->isTablet() ) {
				return 'tablet';
			}

			return 'laptop';
		}

		function get_message_data( $message_ids = array(), $preview = false ) {
			global $wpdb;
			$message_data            = array();
			$original_message_id_map = array();
			$meta_key                = $preview ? 'icegram_message_preview_data' : 'icegram_message_data';
			$message_data_query      = "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '$meta_key'";
			if ( ! empty( $message_ids ) && is_array( $message_ids ) ) {
				// For WPML compatibility
				if ( function_exists( 'icl_object_id' ) ) {
					$wpml_settings       = get_option( 'icl_sitepress_settings' );
					$original_if_missing = ( is_array( $wpml_settings ) && array_key_exists( 'show_untranslated_blog_posts', $wpml_settings ) && ! empty( $wpml_settings['show_untranslated_blog_posts'] ) ) ? true : false;

					foreach ( $message_ids as $i => $id ) {
						$translated                             = icl_object_id( $id, 'ig_message', $original_if_missing );
						$message_ids[ $i ]                      = $translated;
						$original_message_id_map[ $translated ] = $id;
					}
				}
				$message_ids = array_filter( array_unique( $message_ids ) );
				if ( ! empty( $message_ids ) ) {
					$message_data_query   .= " AND post_id IN ( " . implode( ',', $message_ids ) . " )";
					$message_data_results = $wpdb->get_results( $message_data_query, 'ARRAY_A' );
					foreach ( $message_data_results as $message_data_result ) {
						$data = maybe_unserialize( $message_data_result['meta_value'] );
						if ( ! empty( $data ) ) {
							$message_data[ $message_data_result['post_id'] ] = $data;
							// For WPML compatibility
							if ( ! empty( $original_message_id_map[ $message_data_result['post_id'] ] ) ) {
								$message_data[ $message_data_result['post_id'] ]['original_message_id'] = $original_message_id_map[ $message_data_result['post_id'] ];
							}
						}
					}
				}
			}

			return $message_data;
		}

		function get_valid_messages( $message_ids = array(), $campaign_ids = array(), $preview_mode = false, $skip_others = false ) {
			list( $message_ids, $campaign_ids, $preview_mode, $skip_others ) = apply_filters( 'icegram_get_valid_messages_params', array( $message_ids, $campaign_ids, $preview_mode, $skip_others ) );

			$valid_messages = $valid_campaigns = $message_campaign_map = array();

			$campaign_ids = array_filter( array_unique( (array) $campaign_ids ) );
			$message_ids  = array_filter( array_unique( (array) $message_ids ) );
			if ( ! empty( $campaign_ids ) ) {
				$valid_campaigns = $this->get_valid_campaigns( $campaign_ids, true, $preview_mode );
			}

			// When skip_others is true, we won't load campaigns / messages from db
			if ( ! $skip_others && ! $preview_mode ) {
				$campaigns = $this->get_valid_campaigns();
				if ( ! empty( $campaigns ) ) {
					foreach ( $campaigns as $id => $campaign ) {
						if ( ! array_key_exists( $id, $valid_campaigns ) ) {
							$valid_campaigns[ $id ] = $campaign;
						}
					}
				}
			}

			// Create a map to look up campaign id for a given message
			if ( ! empty( $valid_campaigns ) ) {
				foreach ( $valid_campaigns as $id => $campaign ) {
					if ( $preview_mode ) {
						$campaign->messages = get_post_meta( $id, 'campaign_preview', true );
					}
					if ( ! empty( $campaign->messages ) ) {
						foreach ( $campaign->messages as $msg ) {
							$message_ids[] = $msg['id'];
							if ( ! array_key_exists( $msg['id'], $message_campaign_map ) ) {
								$message_campaign_map[ $msg['id'] ] = $id;
							}
						}
					}
				}
			}

			// We don't display same message twice...
			$message_ids = array_unique( $message_ids );

			if ( empty( $message_ids ) ) {
				return array();
			}
			$valid_messages = $this->get_message_data( $message_ids, $preview_mode );

			foreach ( $valid_messages as $id => $message_data ) {
				// Remove message if required fields are missing
				if ( empty( $message_data ) || empty( $message_data['type'] ) ) {
					unset( $valid_messages[ $id ] );
					continue;
				}
				// Remove message if message type is uninstalled
				$class_name = 'Icegram_Message_Type_' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $message_data['type'] ) ) );
				if ( ! class_exists( $class_name ) ) {
					unset( $valid_messages[ $id ] );
					continue;
				}
				$message_data['delay_time']  = 0;
				$message_data['retargeting'] = '';
				$message_data['campaign_id'] = ( $preview_mode && ! empty( $_REQUEST['campaign_preview_id'] ) ) ? sanitize_text_field( $_REQUEST['campaign_preview_id'] ) : '';

				// Pull display time and retargeting rule from campaign if possible
				$message_id = ( ! empty( $message_data['original_message_id'] ) ) ? $message_data['original_message_id'] : $id;
				if ( ! empty( $message_campaign_map[ $message_id ] ) ) {
					//modify campaign id
					$message_data['campaign_id'] = apply_filters( 'modify_campaing_id', $message_campaign_map[ $message_id ], $message_id );
					$campaign                    = $valid_campaigns[ floor( $message_data['campaign_id'] ) ];
					if ( ! empty( $campaign ) && $campaign instanceof Icegram_Campaign ) {
						$message_meta_from_campaign = $campaign->get_message_meta_by_id( $message_id );
						if ( ! empty( $message_meta_from_campaign['time'] ) ) {
							$message_data['delay_time'] = $message_meta_from_campaign['time'];
						}

						//check if campaign is targeted to mobile at zero
						$device_rule = $campaign->get_rule_value( 'device' );
						if ( $this->get_platform() !== 'laptop' && ! empty( $device_rule['mobile'] ) && $device_rule['mobile'] == 'yes' && $message_data['delay_time'] == 0 && $message_data['type'] == 'popup' ) {
							$message_data['ig_mobile_popup'] = true;
							if ( ! empty( $message_data['triggers'] ) && ! empty( $message_data['triggers']['when_to_show'] ) ) {
								$message_data['ig_mobile_popup'] = ( $message_data['triggers']['when_to_show'] == 'duration_on_page' && $message_data['triggers']['duration_on_page'] == 0 ) ? true : false;
							}
						}
						//set delay time -1 if shortcode with content
						foreach ( $this->shortcode_instances as $i => $value ) {
							$campaign_ids = explode( ',', $value['campaigns'] );
							if ( ! empty( $value['with_content'] ) && in_array( $message_data['campaign_id'], $campaign_ids ) ) {
								$message_data['delay_time'] = - 1;
							}
						}
						$rule_value                          = $campaign->get_rule_value( 'retargeting' );
						$message_data['retargeting']         = ! empty( $rule_value['retargeting'] ) ? $rule_value['retargeting'] : '';
						$message_data['expiry_time']         = ! empty( $rule_value['retargeting'] ) ? $rule_value['expiry_time'] : '';
						$rule_value_retargeting_clicked      = $campaign->get_rule_value( 'retargeting_clicked' );
						$message_data['retargeting_clicked'] = ! empty( $rule_value_retargeting_clicked['retargeting_clicked'] ) ? $rule_value_retargeting_clicked['retargeting_clicked'] : '';
						$message_data['expiry_time_clicked'] = ! empty( $rule_value_retargeting_clicked['retargeting_clicked'] ) ? $rule_value_retargeting_clicked['expiry_time_clicked'] : '';
						$message_data = apply_filters( 'icegram_get_message_data', $message_data, $campaign );


					}
				}
				$valid_messages[ $id ] = $message_data;
			}
			$valid_messages = apply_filters( 'icegram_valid_messages', $valid_messages );

			return $valid_messages;
		}

		function get_valid_campaigns( $campaign_ids = array(), $skip_page_check = false, $preview_mode = false ) {
			global $wpdb;
			if ( empty( $campaign_ids ) ) {
				$sql = "SELECT pm.post_id 
	                    FROM {$wpdb->prefix}posts AS p 
	                    LEFT JOIN {$wpdb->prefix}postmeta AS pm ON ( pm.post_id = p.ID ) 
	                    WHERE p.post_status = 'publish' ";
				// Filter handler within this file (and possibly others) will append to this SQL
				// and provide arguments for wpdb->prepare if needed.
				// First element in the array is SQL, remaining are values for placeholders in SQL
				$sql_params = apply_filters( 'icegram_get_valid_campaigns_sql', array( $sql ), array() );

				$campaign_ids = $wpdb->get_col( $wpdb->prepare( array_shift( $sql_params ), $sql_params ) );
			}
			$valid_campaigns = array();
			foreach ( (array) $campaign_ids as $campaign_id ) {
				$campaign = new Icegram_Campaign( $campaign_id );
				if ( $preview_mode || $campaign->is_valid( array( 'skip_page_check' => $skip_page_check ) ) ) {
					$valid_campaigns[ $campaign_id ] = $campaign;
				} else {
					// Campgain is invalid!
				}

			}

			$valid_campaigns = apply_filters( 'icegram_valid_campaigns', $valid_campaigns );

			return $valid_campaigns;
		}

		function append_to_valid_campaigns_sql( $sql_params = array(), $options = array() ) {
			// Page check conditions
			//$pid = $_GET['page_id'];
			$pid          = Icegram::get_current_page_id();
			$sql          = " AND ( 
	                pm.meta_key = 'icegram_campaign_target_rules' AND (
	                ( pm.meta_value LIKE '%%%s%%' ) 
	                OR ( pm.meta_value LIKE '%%%s%%' AND pm.meta_value LIKE '%%%s%%' AND pm.meta_value LIKE '%%%s%%' )
	                ";
			$sql_params[] = 's:8:"sitewide";s:3:"yes";';
			$sql_params[] = 's:10:"other_page";s:3:"yes";';
			$sql_params[] = 's:7:"page_id";a:';
			$sql_params[] = serialize( (string) $pid );
			//local url
			$sql          .= " OR ( pm.meta_value LIKE '%%%s%%' )";
			$sql_params[] = 's:9:"local_url";s:3:"yes";';

			$sql          .= " OR ( pm.meta_value LIKE '%%%s%%' )";
			$sql_params[] = 's:10:"post_types";s:3:"yes";';

			if ( ! empty( $_REQUEST['cache_compatibility'] ) && $_REQUEST['cache_compatibility'] == 'yes' ) {
				$is_home = ( ! empty( $_REQUEST['is_home'] ) && $_REQUEST['is_home'] === 'true' ) ? true : false;
			} else {
				$is_home = ( is_home() || is_front_page() ) ? true : false;
			}
			if ( $is_home === true ) {
				$sql          .= " OR ( pm.meta_value LIKE '%%%s%%' )";
				$sql_params[] = 's:8:"homepage";s:3:"yes";';
			}
			$sql .= " ) )";

			$sql_params[0] .= $sql;

			//s:9:"logged_in";s:3:"all";

			return $sql_params;
		}

		// Include all classes required for Icegram plugin
		function include_classes( $feedback_version ) {
			global $ig_tracker, $ig_feedback;

			$name 				=	'Icegram'; 
			$text_domain 		= 	'icegram';
			$plugin 			= $text_domain;
			$plugin_prefix 		= 	'ig';
			$plan 				= 	get_option( 'ig_engage_plan', 'lite' );
			$plugin_file_path 	= 	IG_PLUGIN_DIR . 'icegram.php';
			$allowed_by_default =  	( 'lite' === $plan ) ? false : true;
			$event_prefix 		= 'igfree.';
			// Usage tracker code begin
			require dirname(__FILE__) . '/classes/feedback/class-ig-plugin-data-tracker.php';

			$ig_usage_tracker = 'Icegram_Plugin_Usage_Tracker_V_' . str_replace( '.', '_', IG_FEEDBACK_TRACKER_VERSION);
			if( class_exists( $ig_usage_tracker) ){
				new $ig_usage_tracker($name, $text_domain, $plugin_prefix, IG_PRODUCT_ID, $plan, $plugin_file_path, $ig_tracker, $allowed_by_default );
			}

			// Usage tracker code ends

			if ( self::is_max() ) {
				$name         = 'Icegram Engage MAX';
				$plugin       = 'icegram-engage-max';
				$event_prefix = 'igmax.';
			} elseif ( self::is_pro() ) {
				$name         = 'Icegram Engage Pro';
				$plugin       = 'icegram-engage-pro';
				$event_prefix = 'igpro.';
			}

			$feedback_version_for_file = str_replace( '.', '-', $feedback_version );
			$f                         = 'classes/feedback/class-ig-feedback.php';
			require_once( $f );

			$ig_feedback_class = 'IG_Feedback_V_' . str_replace( '.', '_', $feedback_version );
			$ig_feedback       = new $ig_feedback_class( $name, $plugin, $plugin_prefix, $event_prefix, false );

			require_once( 'classes/feedback.php' );

			$classes     = glob( $this->plugin_path . '/classes/*.php' );
			$all_plugins = $ig_tracker::get_plugins();

			foreach ( $classes as $file ) {
				// Files with 'admin' in their name are included only for admin section
				if ( is_file( $file ) && ( ( strpos( $file, '-admin' ) >= 0 && is_admin() ) ) ) {
					if ( ( strpos( $file, 'ig-upsale-admin.php' ) !== false ) && in_array( 'icegram-engage/icegram-engage.php', $all_plugins ) ) {
						continue;
					}
					include_once $file;
				} elseif ( ! is_admin() ) {
					include_once $file;
				}
			}

			// Load built in message types
			$icegram_message_type_basedirs = glob( $this->plugin_path . '/message-types/*' );
			// Allow other plugins to add new message types
			$icegram_message_type_basedirs = apply_filters( 'icegram_message_type_basedirs', $icegram_message_type_basedirs );
			// Set up different message type classes
			foreach ( $icegram_message_type_basedirs as $dir ) {
				$type       = basename( $dir );
				$class_file = $dir . "/main.php";
				if ( is_file( $class_file ) ) {
					include_once( $class_file );
				}
				$class_name = 'Icegram_Message_Type_' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $type ) ) );
				if ( class_exists( $class_name ) ) {
					$this->message_type_objs[ $type ] = new $class_name();
				}
			}
			do_action( 'ig_file_include' );
			$this->message_types = apply_filters( 'icegram_message_types', array() );

		}

		// Register Campaign post type
		function register_campaign_post_type() {
			$labels = array(
				'name'               => __( 'Campaigns', 'icegram' ),
				'singular_name'      => __( 'Campaign', 'icegram' ),
				'add_new'            => __( 'Add New Campaign', 'icegram' ),
				'add_new_item'       => __( 'Add New Campaign', 'icegram' ),
				'edit_item'          => __( 'Edit Campaign', 'icegram' ),
				'new_item'           => __( 'New Campaign', 'icegram' ),
				'all_items'          => __( 'All Campaigns', 'icegram' ),
				'view_item'          => __( 'View Campaign', 'icegram' ),
				'search_items'       => __( 'Search Campaigns', 'icegram' ),
				'not_found'          => __( 'No campaigns found', 'icegram' ),
				'not_found_in_trash' => __( 'No campaigns found in Trash', 'icegram' ),
				'parent_item_colon'  => __( '', 'icegram' ),
				'menu_name'          => __( 'Icegram Engage', 'icegram' )
			);

			$args = array(
				'labels'             => $labels,
				// 'menu_icon'          => 'dashicons-info',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'ig_campaign' ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'menu_icon'          => $this->plugin_url . '/assets/images/icegram-logo-branding-18-white.png',
				'supports'           => array( 'title', 'editor' )
			);

			register_post_type( 'ig_campaign', $args );
		}

		// Register Message post type
		function register_message_post_type() {
			$labels = array(
				'name'               => __( 'Messages', 'icegram' ),
				'singular_name'      => __( 'Message', 'icegram' ),
				'add_new'            => __( 'Create New', 'icegram' ),
				'add_new_item'       => __( 'Create New Message', 'icegram' ),
				'edit_item'          => __( 'Edit Message', 'icegram' ),
				'new_item'           => __( 'New Message', 'icegram' ),
				'all_items'          => __( 'Messages', 'icegram' ),
				'view_item'          => __( 'View Message', 'icegram' ),
				'search_items'       => __( 'Search Messages', 'icegram' ),
				'not_found'          => __( 'No messages found', 'icegram' ),
				'not_found_in_trash' => __( 'No messages found in Trash', 'icegram' ),
				'parent_item_colon'  => __( '', 'icegram' ),
				'menu_name'          => __( 'Messages', 'icegram' )
			);

			$args = array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => 'edit.php?post_type=ig_campaign',
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'ig_message' ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title' )
			);

			register_post_type( 'ig_message', $args );
		}


		function import( $data = array() ) {
			if ( empty( $data['campaigns'] ) && empty( $data['messages'] ) ) {
				return;
			}

			$default_theme      = $default_type = '';
			$first_message_type = current( $this->message_types );

			if ( is_array( $first_message_type ) ) {
				$default_type = $first_message_type['type'];
				if ( ! empty( $first_message_type['themes'] ) ) {
					$default_theme = key( $first_message_type['themes'] );
				}
			}

			$new_campaign_ids = array();
			foreach ( (array) $data['campaigns'] as $campaign ) {

				$args = array(
					'post_content' => ( ! empty( $campaign['post_content'] ) ) ? esc_attr( $campaign['post_content'] ) : '',
					'post_name'    => ( ! empty( $campaign['post_title'] ) ) ? sanitize_title( $campaign['post_title'] ) : '',
					'post_title'   => ( ! empty( $campaign['post_title'] ) ) ? $campaign['post_title'] : '',
					// 'post_status'    =>  ( !empty( $campaign['post_status'] ) ) ? $campaign['post_status'] : 'draft',
					'post_status'  => 'draft',
					'post_type'    => 'ig_campaign'
				);

				$new_campaign_id    = wp_insert_post( $args );
				$new_campaign_ids[] = $new_campaign_id;

				if ( ! empty( $campaign['target_rules'] ) ) {

					$defaults = array(
						'homepage'  => 'yes',
						'when'      => 'always',
						'from'      => '',
						'to'        => '',
						'mobile'    => 'yes',
						'tablet'    => 'yes',
						'laptop'    => 'yes',
						'logged_in' => 'all'
					);

					$target_rules = wp_parse_args( $campaign['target_rules'], $defaults );
					update_post_meta( $new_campaign_id, 'icegram_campaign_target_rules', $target_rules );
				}

				if ( ! empty( $campaign['messages'] ) ) {

					$messages = array();

					foreach ( $campaign['messages'] as $message ) {

						if ( ! is_array( $message ) ) {
							continue;
						}

						$args = array(
							'post_content' => ( ! empty( $message['message'] ) ) ? esc_attr( $message['message'] ) : '',
							'post_name'    => ( ! empty( $message['post_title'] ) ) ? sanitize_title( $message['post_title'] ) : '',
							'post_title'   => ( ! empty( $message['post_title'] ) ) ? $message['post_title'] : '',
							'post_status'  => ( ! empty( $message['post_status'] ) ) ? $message['post_status'] : 'publish',
							'post_type'    => 'ig_message'
						);

						$new_message_id = wp_insert_post( $args );
						$new_message    = array(
							'id'   => $new_message_id,
							'time' => ( ! empty( $message['time'] ) ) ? $message['time'] : 0
						);
						//for gallery + CTA another message
						if ( ! empty( $message['cta'] ) && $message['cta'] == 'cta_another_message' && ! empty( $message['cta_linked_message_id'] ) && $message['cta_linked_message_id'] == 'auto' ) {
							$prev_message                     = end( $messages );
							$message['cta_linked_message_id'] = $prev_message['id'];
							array_pop( $messages );
						}
						$messages[] = $new_message;

						unset( $message['post_content'] );
						unset( $message['time'] );

						$message['id'] = $new_message_id;

						$defaults             = array(
							'post_title'  => '',
							'type'        => $default_type,
							'theme'       => $default_theme,
							'animation'   => '',
							'headline'    => '',
							'label'       => '',
							'link'        => '',
							'icon'        => '',
							'message'     => '',
							'position'    => '',
							'text_color'  => '#000000',
							'bg_color'    => '#ffffff',
							'custom_code' => '',
							'id'          => ''
						);
						$icegram_message_data = wp_parse_args( $message, $defaults );
						if ( ! empty( $icegram_message_data ) ) {
							update_post_meta( $new_message_id, 'icegram_message_data', $icegram_message_data );
							update_post_meta( $new_message_id, 'icegram_message_preview_data', $icegram_message_data );
						}
					}//foreach

					if ( ! empty( $campaign['messages'] ) ) {
						update_post_meta( $new_campaign_id, 'messages', $messages );
						update_post_meta( $new_campaign_id, 'campaign_preview', $messages );
					}
				}//if
			}

			return $new_campaign_ids;

		}

		function import_gallery_item() {
			if ( ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'fetch_messages' && ! empty( $_REQUEST['campaign_id'] ) && ! empty( $_REQUEST['gallery_item'] ) ) {
				$url                    = 'https://www.icegram.com/gallery/wp-admin/admin-ajax.php?utm_source=ig_inapp&utm_campaign=ig_gallery&utm_medium=' . sanitize_text_field( $_REQUEST['campaign_id'] );
				$params                 = $_REQUEST;
				$imported_gallery_items = array();
				$options                = array(
					'timeout' => 15,
					'method'  => 'POST',
					'body'    => http_build_query( $params )
				);
				$response               = wp_remote_request( $url, $options );
				$response_code          = wp_remote_retrieve_response_code( $response );
				if ( $response_code == 200 ) {
					$new_campaign_ids = $this->import( json_decode( $response['body'], true ) );
					if ( ! empty( $new_campaign_ids ) ) {
						$imported_gallery_items   = get_option( 'ig_imported_gallery_items' );
						$imported_gallery_items[] = sanitize_text_field( $_REQUEST['campaign_id'] );
						update_option( 'ig_imported_gallery_items', $imported_gallery_items );
						$location = admin_url( 'post.php?post=' . $new_campaign_ids[0] . '&action=edit' );
						header( 'Location:' . $location );

						if( 'no' === get_option( 'ig_is_onboarding_completed', 'no' ) ){ 
							update_option( 'ig_is_onboarding_completed', 'yes' );
						}

						exit;
					} else {
						wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
					}
				}
			}
		}

		function import_sample_data( $data = array() ) {
			$new_campaign_ids = $this->import( $data );
			if ( ! empty( $new_campaign_ids ) ) {
				update_option( 'icegram_sample_data_imported', $new_campaign_ids );
			}
		}


		function get_sample_data() {

			return array(
				'campaigns' => array(
					array(
						'post_name'    => '',
						'post_title'   => 'My First Icegram Campaign',
						'target_rules' => array(
							'homepage'  => 'yes',
							'when'      => 'always',
							'from'      => '',
							'to'        => '',
							'mobile'    => 'yes',
							'tablet'    => 'yes',
							'laptop'    => 'yes',
							'logged_in' => 'all'
						),
						'messages'     => array(
							array(
								'post_title'  => 'Get 2x more Contacts with Your Website',
								'post_status' => 'publish',
								'time'        => '0',
								'type'        => 'action-bar',
								'theme'       => 'hello',
								'headline'    => 'Get 2x more Contacts with Your Website',
								'label'       => 'Show Me How',
								'link'        => '',
								'icon'        => '',
								'message'     => 'Instant Results Guaranteed',
								'position'    => '01',
								'text_color'  => '#000000',
								'bg_color'    => '#eb593c'
							),
							array(
								'post_title'  => '20% Off Coupon',
								'post_status' => 'publish',
								'time'        => '4',
								'type'        => 'messenger',
								'theme'       => 'social',
								'animation'   => 'slide',
								'headline'    => '20% Off - for you',
								'label'       => '',
								'link'        => '',
								'icon'        => '',
								'message'     => "Hey there! We are running a <strong>special 20% off this week</strong> for registered users - like you. 

	                                                                Use coupon <code>LOYALTY20</code> during checkout.",
								'position'    => '22',
								'text_color'  => '#000000',
								'bg_color'    => '#ffffff'
							),
							array(
								'post_title'  => 'How this blog makes over $34,800 / month for FREE.',
								'post_status' => 'publish',
								'time'        => '10',
								'type'        => 'popup',
								'theme'       => 'air-mail',
								'headline'    => 'How this blog makes over $34,800 / month for FREE.',
								'label'       => 'FREE INSTANT ACCESS',
								'link'        => '',
								'icon'        => '',
								'message'     => "This website earns over $30,000 every month, every single month, almost on autopilot. I have 4 other sites with similar results. All I do is publish new regular content every week.

	        <strong>Download my free kit to learn how I do this.</strong>

	        <ul>
	            <li>How to choose blog topics that create long term value</li>
	            <li>The type of blog post that will make your site go viral</li>
	            <li>How to free yourself from the routine tasks</li>
	            <li>Resources and tips to get started quickly</li>
	            <li>Private members club to connect with fellow owners</li>
	        </ul>",
								'text_color'  => '#000000',
								'bg_color'    => '#ffffff'

							),
							array(
								'post_title'  => 'Exclusive Marketing Report',
								'post_status' => 'publish',
								'time'        => '6',
								'type'        => 'toast',
								'theme'       => 'stand-out',
								'animation'   => 'pop',
								'headline'    => 'Exclusive Marketing Report',
								'label'       => '',
								'link'        => '',
								'icon'        => '',
								'message'     => 'FREE for every subscriber. Click here to download it.',
								'position'    => '02',
								'text_color'  => '#000000',
								'bg_color'    => '#ffffff'
							)

						)
					)
				)
			);
		}

		function remove_preview_button() {
			global $post_type;
			if ( $post_type == 'ig_message' || $post_type == 'ig_campaign' ) {
				?>
	            <style type="text/css">
	                
	                #preview-action, .post-type-ig_message .page-title-action, #message.updated.below-h2 {
	                    display: none;
	                }


	            </style>
				<?php
			}
		}


		function remove_row_actions( $actions, $post ) {

			if ( empty( $post->post_type ) || ( $post->post_type != 'ig_campaign' && $post->post_type != 'ig_message' ) ) {
				return $actions;
			}

			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['view'] );

			return $actions;

		}

		function identify_current_page() {
			global $post, $wpdb;

			$obj = get_queried_object();
			$id  = 0;
			if ( ! empty( $obj->has_archive ) ) {
				$sql = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s ", array( $obj->has_archive, 'page' ) );
				$id  = $wpdb->get_var( $sql );
			} elseif ( is_object( $post ) && isset( $post->ID ) ) {
				$id = $post->ID;
			}
			$id                    = apply_filters( 'icegram_identify_current_page', $id );
			self::$current_page_id = $id;
		}

		static function get_current_page_id() {
			global $post;
			if ( ! empty( $_REQUEST['page_id'] ) && is_numeric( $_REQUEST['page_id'] ) ) {
				$post = get_post( sanitize_text_field( $_REQUEST['page_id'] ) );
				setup_postdata( $post );
				// WPML check
				$id                    = apply_filters( 'icegram_identify_current_page', $post->ID );
				self::$current_page_id = $id;
			}

			return self::$current_page_id;
		}

		static function get_current_page_url() {
			if ( ! empty( $_REQUEST['cache_compatibility'] ) && $_REQUEST['cache_compatibility'] == 'yes' ) {
				$pageURL = ( ! empty( $_REQUEST['referral_url'] ) ) ? sanitize_text_field( $_REQUEST['referral_url'] ) : '';
			} else {
				$pageURL = 'http';
				if ( isset( $_SERVER["HTTPS"] ) ) {
					if ( $_SERVER["HTTPS"] == "on" ) {
						$pageURL .= "s";
					}
				}
				$pageURL .= "://";
				if ( isset( $_SERVER["SERVER_PORT"] ) && "80" != $_SERVER["SERVER_PORT"] ) {
					$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
				} else {
					$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
				}
			}

			return $pageURL;
		}

		function wpml_get_parent_id( $id ) {
			global $post;
			if ( function_exists( 'icl_object_id' ) && function_exists( 'icl_get_default_language' ) ) {
				$id = icl_object_id( $id, $post->post_type, true, icl_get_default_language() );
			}

			return $id;
		}


		/**
		 * Our implementation of wpautop to preserve script and style tags
		 */
		function before_wpautop( $pee ) {
			if ( trim( $pee ) === '' ) {
				$this->_wpautop_tags = array();

				return '';
			}

			$tags = array();
			// Pull out tags and add placeholders
			list( $pee, $tags['pre'] ) = $this->_wpautop_add_tag_placeholders( $pee, 'pre' );
			list( $pee, $tags['script'] ) = $this->_wpautop_add_tag_placeholders( $pee, 'script' );
			list( $pee, $tags['style'] ) = $this->_wpautop_add_tag_placeholders( $pee, 'style' );
			$this->_wpautop_tags = $tags;

			if ( ! empty( $pre_tags ) ) {
				$pee = $this->_wpautop_replace_tag_placeholders( $pee, $pre_tags );
			}
			if ( ! empty( $script_tags ) ) {
				$pee = $this->_wpautop_replace_tag_placeholders( $pee, $script_tags );
			}
			if ( ! empty( $style_tags ) ) {
				$pee = $this->_wpautop_replace_tag_placeholders( $pee, $style_tags );
			}

			return $pee;
		}

		function after_wpautop( $pee ) {
			if ( trim( $pee ) === '' || empty( $this->_wpautop_tags ) ) {
				return '';
			}

			// Replace placeholders with original content
			if ( ! empty( $this->_wpautop_tags['pre'] ) ) {
				$pee = $this->_wpautop_replace_tag_placeholders( $pee, $this->_wpautop_tags['pre'] );
			}
			if ( ! empty( $this->_wpautop_tags['script'] ) ) {
				$pee = $this->_wpautop_replace_tag_placeholders( $pee, $this->_wpautop_tags['script'] );
			}
			if ( ! empty( $this->_wpautop_tags['style'] ) ) {
				$pee = $this->_wpautop_replace_tag_placeholders( $pee, $this->_wpautop_tags['style'] );
			}

			$this->_wpautop_tags = array();

			return $pee;
		}

		function _wpautop_add_tag_placeholders( $pee, $tag ) {
			$tags = array();

			if ( false !== strpos( $pee, "<{$tag}" ) ) {
				$pee_parts = explode( "</{$tag}>", $pee );
				$last_pee  = array_pop( $pee_parts );
				$pee       = '';
				$i         = 0;

				foreach ( $pee_parts as $pee_part ) {
					$start = strpos( $pee_part, "<{$tag}" );

					// Malformed html?
					if ( false === $start ) {
						$pee .= $pee_part;
						continue;
					}

					$name          = "<{$tag} wp-{$tag}-tag-$i></{$tag}>";
					$tags[ $name ] = substr( $pee_part, $start ) . "</{$tag}>";

					$pee .= substr( $pee_part, 0, $start ) . $name;
					$i ++;
				}

				$pee .= $last_pee;
			}

			return array( $pee, $tags );
		}

		function _wpautop_replace_tag_placeholders( $pee, $tags ) {
			if ( ! empty( $tags ) ) {
				$pee = str_replace( array_keys( $tags ), array_values( $tags ), $pee );
			}

			return $pee;
		}

		static function duplicate_in_db( $original_id ) {
			// Get access to the database
			global $wpdb;
			// Get the post as an array
			$duplicate = get_post( $original_id, 'ARRAY_A' );
			// Modify some of the elements
			$duplicate['post_title']  = $duplicate['post_title'] . ' ' . __( 'Copy', 'icegram' );
			$duplicate['post_status'] = 'draft';
			// Set the post date
			$timestamp = current_time( 'timestamp', 0 );

			$duplicate['post_date'] = date( 'Y-m-d H:i:s', $timestamp );

			// Remove some of the keys
			unset( $duplicate['ID'] );
			unset( $duplicate['guid'] );
			unset( $duplicate['comment_count'] );

			// Insert the post into the database
			$duplicate_id = wp_insert_post( $duplicate );

			// Duplicate all taxonomies/terms
			$taxonomies = get_object_taxonomies( $duplicate['post_type'] );

			foreach ( $taxonomies as $taxonomy ) {
				$terms = wp_get_post_terms( $original_id, $taxonomy, array( 'fields' => 'names' ) );
				wp_set_object_terms( $duplicate_id, $terms, $taxonomy );
			}

			// Duplicate all custom fields
			$custom_fields = get_post_custom( $original_id );
			foreach ( $custom_fields as $key => $value ) {
				if ( $key === 'messages' ) {
					$messages = unserialize( $value[0] );
					foreach ( $messages as &$message ) {
						$clone_msg_id  = Icegram::duplicate_in_db( $message['id'] );
						$message['id'] = $clone_msg_id;
					}
					$value[0] = serialize( $messages );
				}
				add_post_meta( $duplicate_id, $key, maybe_unserialize( $value[0] ) );
			}

			return $duplicate_id;
		}

		static function duplicate( $original_id ) {
			$duplicate_id = Icegram::duplicate_in_db( $original_id );
			$location     = admin_url( 'post.php?post=' . $duplicate_id . '&action=edit' );
			header( 'Location:' . $location );
			exit;			
		}

		public static function form_submission_validate_request( $request_data ) {
			if ( ! empty( $request_data ) ) {
				// Check for Remote Rainmaker form submission request
				$request_data['ig_is_remote'] = false;
				$request_data['is_remote']    = false;
				if ( ! empty( $request_data['ig_mode'] ) && $request_data['ig_mode'] === 'remote' ) {
					$ig_remote_url = $request_data['ig_remote_url'];
					if ( ! empty( $request_data['ig_campaign_id'] ) ) {
						$rules = get_post_meta( $request_data['ig_campaign_id'], 'icegram_campaign_target_rules', true );
						if ( ! empty( $rules['remote_urls'] ) && is_array( $rules['remote_urls'] ) ) {
							foreach ( $rules['remote_urls'] as $remote_url_pattern ) {
								$valid = Icegram_Campaign::is_valid_url( $remote_url_pattern, $ig_remote_url );
								if ( $valid ) {
									$request_data['ig_is_remote'] = true;
									$request_data['is_remote']    = true;
									break;
								}
							}
							//TODO :: discard the the remote request and data
							// if($request_data['ig_is_remote'] == false){
							//  return array();
							// }
						}

					}
				}
			}

			return $request_data;
		}

		public static function get_ig_meta_info() {
			$total_campaigns         = wp_count_posts( 'ig_campaign' );
			$total_campaigns_publish = $total_campaigns->publish;
			$total_campaigns_draft   = $total_campaigns->draft;

			$meta_info = array(
				'total_campaigns_publish' => $total_campaigns_publish,
				'total_campaigns_draft'   => $total_campaigns_draft,
			);

			return $meta_info;
		}

		/**
		 * Render Quick Feedback Widget
		 *
		 * @param $params
		 *
		 * @since 1.10.38
		 */
		public function render_feedback_widget( $params ) {
			global $ig_feedback;

			$feedback = $ig_feedback;

			if ( ! $feedback->can_show_feedback_widget() ) {
				return;
			}

			$default_params = array(
				'set_transient' => true,
				'force'         => false,
				'show_once'     => false
			);

			$params = wp_parse_args( $params, $default_params );

			if ( ! empty( $params['event'] ) ) {

				$event = $feedback->event_prefix . $params['event'];
				$force = ! empty( $params['force'] ) ? $params['force'] : false;

				$can_show = false;

				if ( $force ) {
					$can_show = true;
				} else {
					if ( ! $feedback->is_event_transient_set( $event ) ) {
						$can_show = true;

						$feedback_data = $feedback->get_event_feedback_data( $feedback->plugin_abbr, $event );
						if ( count( $feedback_data ) > 0 ) {
							$show_once              = $params['show_once'];
							$feedback_data          = array_reverse( $feedback_data );
							$last_feedback_given_on = $feedback_data[0]['created_on'];

							// If event feedback given within 45 days or show event only once?
							// Don't show now
							if ( $show_once || ( strtotime( $last_feedback_given_on ) > strtotime( '-45 days' ) ) ) {
								$can_show = false;
							}
						}
					}
				}

				if ( $can_show ) {
					if ( 'star' === $params['type'] ) {
						$feedback->render_stars( $params );
					} elseif ( 'emoji' === $params['type'] ) {
						$feedback->render_emoji( $params );
					} elseif ( 'feedback' === $params['type'] ) {
						$feedback->render_general_feedback( $params );
					} elseif ( 'fb' === $params['type'] ) {
						/**
						 * We are not calling home for this event and we want to show
						 * this Widget only once. So, we are storing feedback data now.
						 */
						$feedback->set_feedback_data( 'ig', $event );
						$feedback->render_fb_widget( $params );
					} elseif ( 'poll' === $params['type'] ) {
						$feedback->set_feedback_data( 'ig', $event );
						$feedback->render_poll_widget( $params );
					}
				}
			}
		}

		/**
		 * Adding custom column in Campaigns table 
		 *
		 * @since 2.1.5
		 *
		 */
		public function custom_ig_campaign_column($existing_columns){
			global $post;

			$custom_new_col['campaign_type']  = __( 'Type', 'icegram' );

			$existing_columns = $this->ig_array_insert_after( $existing_columns, 'title', $custom_new_col );

			$existing_columns['status'] = __( 'Status', 'icegram' );

			$screen = get_current_screen();
			
			//Hiding Post status from the title and Date column
			if ( in_array( $screen->id, array( 'edit-ig_campaign' ), true ) ) {
				add_filter('display_post_states', '__return_false' );
				add_filter( 'post_date_column_status', '__return_false' );
			}


			return $existing_columns;

		}

		/**
		 * Show custom columns data 
		 *
		 * @since 2.1.5
		 *
		 */
		public function edit_columns($column){
			global $post ,$icegram;
			if( ( is_object( $post ) && !in_array($post->post_type ,array('ig_campaign')  )) )
				return;
		
			switch ($column) {
				case 'campaign_type':
					if($post->post_type == 'ig_campaign'){
					 	$message_types = $this->get_message_types($post->ID);
					}
					
					if( ! empty( $message_types ) ) {
						foreach( $message_types as $key => $type ) { 
							$params = array();
							$params = apply_filters( 'icegram_message_type_params_'. $type, $params );
							$message_type = 'ig_'.$type;
							$label_bg_color = isset( $params['admin_style']['label_bg_color'] ) ? $params['admin_style']['label_bg_color'] : '';
							?>
							<style type="text/css">
							<?php
								echo ".message_header .$message_type { 
									background-color: {$label_bg_color}; 
								}";
							?>
							</style>
							<div style="display: inline; padding-right:0.3rem" class="message_header">
								<span class="message_header_label  <?php echo esc_html( $message_type ) ?>">  
									<?php echo esc_html( $type ); ?>
								</span>
							</div>
							<?php
						}
					} else{
						echo esc_html( '-' , 'icegram');
					}
					break;
				case 'status' : 
					?>
					<label for="<?php echo esc_attr( 'ig-campaign-status-toggle-' . $post->ID ); ?>" class="ig-campaign-status-toggle-label">
						<span style="position: relative;">
							<input id="<?php echo esc_attr( 'ig-campaign-status-toggle-' . $post->ID ); ?>" type="checkbox" class="ig-check-toggle" name="<?php echo esc_attr( 'ig-campaign-status-toggle-' . $post->ID ); ?>" value="<?php echo esc_attr( $post->ID ); ?>" 
								  <?php
									checked(
										'publish',
										$post->post_status
									);
									?>
							>
							<span class="ig-mail-toggle-line"></span>
							<span class="ig-mail-toggle-dot"></span>
						</span>
					</label>
			<?php
				default:
					break;
			}
			
		}

		/**
		 * Method to handle campaign status change
		 *
		 * @return string JSON response of the request
		 *
		 * @since 2.1.5
		 */
		public function toggle_campaign_status() {
			
			check_ajax_referer( 'ig-nonce', 'security' );
			
			$campaign_id         = ! empty( $_POST['campaign_id'] ) ? sanitize_text_field( $_POST['campaign_id'] ) : '';
			$new_campaign_status = ! empty( $_POST['new_campaign_status'] ) ? sanitize_text_field( $_POST['new_campaign_status'] ) : 'draft';

			if ( ! empty( $campaign_id ) ) {

				$status_updated = wp_update_post( array(
								'ID'          => $campaign_id,
								'post_status' => $new_campaign_status
							) );

				if ( $status_updated ) {
					wp_send_json_success();
				} else {
					wp_send_json_error();
				}
			}
		}

		/**
		 * Get all campaign types
		 *
		 * @since 2.1.5
	     *
	     */
		public function get_message_types( $campaign_id ){
			global $wpdb;
			if (empty($campaign_id)) {
				return null;
			}

			$message_ids = array();
			$campaign_messages = get_post_meta( $campaign_id, 'messages', true );
			if(!empty($campaign_messages)){
				foreach ($campaign_messages as $message) {
					$message_ids[] = intval($message['id']);
				}
			}
			if(!empty($message_ids)){
				$message_types = array();
				$campaign_message_types = $this->get_message_data( $message_ids, false);
				foreach( $campaign_message_types as $data ){
					if( isset( $data['type'] ) ){
						$message_types[] =  $data['type'];
					}
				}
				return $message_types;
			}
			return '';

		}

		/**
		 * Check if premium plugin installed
		 *
		 * @return boolean
		 *
		 * @since 1.10.39
		 */
		public function is_premium_installed() {
			global $ig_tracker;

			$icegram_premium = 'icegram-engage/icegram-engage.php';

			return $ig_tracker::is_plugin_installed( $icegram_premium );
		}

		/**
		 * Check if premium plugin active
		 *
		 * @return boolean
		 *
		 * @since 1.10.39
		 */
		public function is_premium_activated() {
			global $ig_tracker;

			$icegram_premium = 'icegram-engage/icegram-engage.php';

			return $ig_tracker::is_plugin_activated( $icegram_premium );
		}

		/**
		 * Is IG PRO?
		 *
		 * @return bool
		 *
		 * @since
		 */
		public function is_pro() {
			return file_exists( IG_PLUGIN_DIR . 'pro/icegram-pro.php' );
		}

		/**
		 * Is IG MAX ?
		 *
		 * @return bool
		 *
		 * @since
		 */
		public function is_max() {
			return file_exists( IG_PLUGIN_DIR . 'max/icegram-max.php' );
		}

		/**
		 * Is IG Premium?
		 *
		 * @return bool
		 *
		 * @since
		 */
		public function is_premium() {

			return self::is_max() || self::is_pro();
		}

		/**
		 * Check whether IG Premium features can be upselled or not
		 *
		 * @return bool
		 *
		 * @since 3.0.3
		 */
		public function can_upsell_features( $show_for_plans = array() ) {
			$ig_current_plan = apply_filters( 'icegram_plan', 'lite' );
			if ( in_array( $ig_current_plan, $show_for_plans ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Method to check if onboarding is completed
		 *
		 * @return string
		 *
		 */
		public function is_onboarding_completed() {

			$onboarding_complete = get_option( 'ig_is_onboarding_completed', 'no' );

			if ( 'yes' === $onboarding_complete ) {
				return true;
			}

			return false;

		}

		public function show_campaign_creation_guide() {
			
			if( 'no' === get_option( 'ig_is_onboarding_completed', 'no' ) ) {
				return true;
			}
			return false;
		}

		
		/**
		 * Insert $new in $array after $key
		 *
		 * @param $array
		 * @param $key
		 * @param $new
		 *
		 * @return array
		 *
		 * @since 2.1.5
		 */
		public function ig_array_insert_after( $array, $key, $new ) {
			$keys  = array_keys( $array );
			$index = array_search( $key, $keys );
			$pos   = false === $index ? count( $array ) : $index + 1;

			return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
		}

		/**
		 * Get Contact IP
		 *
		 * @return mixed|string|void
		 *
		 * @since 2.1.6
		 */
		public function ig_get_ip() {

			// Get real visitor IP behind CloudFlare network
			if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_CF_CONNECTING_IP'] );
			} elseif ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_X_REAL_IP'] );
			} elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED'] );
			} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_FORWARDED_FOR'] );
			} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
				$ip = sanitize_text_field( $_SERVER['HTTP_FORWARDED'] );
			} else {
				$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : 'UNKNOWN';
			}

			return $ip;
		}

		/**
		 * Check whether the string is a valid JSON or not.
		 *
		 * @param string $string String we want to test if it's json.
		 *
		 * @return bool
		 *
		 * @since 2.1.6
		 */
		public function is_valid_json( $string ) {
			return is_string( $string ) && is_array( json_decode( $string, true ) ) && ( json_last_error() === JSON_ERROR_NONE ) ? true : false;
		}

		/**
		 * Display documentation link on admin bar.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
		 *
		 * @return bool
		 *
		 * @since 2.1.6
		 */
		public function ig_show_documentation_link_in_admin_bar( $wp_admin_bar ) {

			$ig_is_page_for_notifications = apply_filters( 'ig-engage_is_page_for_notifications', false );
			
			if( $ig_is_page_for_notifications ){
				$ig_logo = '<img class="ig-logo" src="' . IG_PLUGIN_URL . 'lite/assets/images/IG-white_logo-transparent_back-512.png">';
				// Add the main site admin menu item.
				$wp_admin_bar->add_menu(
					array(
						'id'     => 'icegram-documentation-link',
						'href'   => 'https://www.icegram.com/knowledgebase_category/icegram/',
					    'parent' => 'top-secondary',
						'title'  => $ig_logo . '<p class="ig-doc-text">Icegram Documentation</p>',
						'meta'   => array( 'class' => 'ig-doc-link', 'target' => '_blank' ),
					)
				);	
			}
			return true;
		}

		/**
		 * Add documentation link admin bar css
		 *
		 * @since 2.1.6
		 */
		function ig_documentation_link_admin_bar_css() {
		?>
	        <style>
	            #wpadminbar .ig-doc-link > .ab-item .ig-logo {
	            	width: 1.5rem;
				    top: 0.2rem;
				    position: relative;
				    margin-right: 0.2rem;
				    height: 1.5rem;
	            }

	            #wpadminbar .ig-doc-link > .ab-item .ig-doc-text {
	            	display: inline;
				    position: relative;
				    bottom: 0.2rem;
	            }
	        </style>
		<?php
		}

		/**
		 * Add HTML tags to be excluded while escaping
		 *
		 * @return array $allowedtags
		 */
		function ig_add_escape_allowed_tags() {
			$context_allowed_tags = wp_kses_allowed_html( 'post' );
			$custom_allowed_tags  = array(
				'div'      => array(
					'x-data' => true,
					'x-show' => true,
				),
				'select'   => array(
					'class'    => true,
					'name'     => true,
					'id'       => true,
					'style'    => true,
					'title'    => true,
					'role'     => true,
					'data-*'   => true,
					'tab-*'    => true,
					'multiple' => true,
					'aria-*'   => true,
					'disabled' => true,
					'required' => 'required',
				),
				'optgroup' => array(
					'label' => true,
				),
				'option'   => array(
					'class'    => true,
					'value'    => true,
					'selected' => true,
					'name'     => true,
					'id'       => true,
					'style'    => true,
					'title'    => true,
					'data-*'   => true,
				),
				'input'    => array(
					'class'          => true,
					'name'           => true,
					'type'           => true,
					'value'          => true,
					'id'             => true,
					'checked'        => true,
					'disabled'       => true,
					'selected'       => true,
					'style'          => true,
					'required'       => 'required',
					'min'            => true,
					'max'            => true,
					'maxlength'      => true,
					'size'           => true,
					'placeholder'    => true,
					'autocomplete'   => true,
					'autocapitalize' => true,
					'autocorrect'    => true,
					'tabindex'       => true,
					'role'           => true,
					'aria-*'         => true,
					'data-*'         => true,
				),
				'label'    => array(
					'class' => true,
					'name'  => true,
					'type'  => true,
					'value' => true,
					'id'    => true,
					'for'   => true,
					'style' => true,
				),
				'form'     => array(
					'class'  => true,
					'name'   => true,
					'value'  => true,
					'id'     => true,
					'style'  => true,
					'action' => true,
					'method' => true,
					'data-*' => true,
				),
				'svg'      => array(
					'width'    => true,
					'height'   => true,
					'viewbox'  => true,
					'xmlns'    => true,
					'class'    => true,
					'stroke-*' => true,
					'fill'     => true,
					'stroke'   => true,
				),
				'path'     => array(
					'd'               => true,
					'fill'            => true,
					'class'           => true,
					'fill-*'          => true,
					'clip-*'          => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
					'stroke-width'    => true,
					'fill-rule'       => true,
				),

				'main'     => array(
					'align'    => true,
					'dir'      => true,
					'lang'     => true,
					'xml:lang' => true,
					'aria-*'   => true,
					'class'    => true,
					'id'       => true,
					'style'    => true,
					'title'    => true,
					'role'     => true,
					'data-*'   => true,
				),
				'textarea' => array(
					'id' => true,
					'autocomplete' => true,
					'required'	   => 'required',
					'placeholder'  => true,
					'class'		   => true,
				),
				'style'    => array(),
				'link'     => array(
					'rel'   => true,
					'id'    => true,
					'href'  => true,
					'media' => true,
				),
				'a'        => array(
					'x-on:click' => true,
				),
				'polygon'  => array(
					'class'  => true,
					'points' => true,
				),
			);

			$allowedtags = array_merge_recursive( $context_allowed_tags, $custom_allowed_tags );

			return $allowedtags;
		}

		/**
	 * Get useful article links
	 *
	 * @return array
	 *
	 * @since 4.4.2
	 */
	public static function get_useful_articles() {

		$articles = array(
			array(
				'title' => __( 'Boost WooCommerce Sales Using Icegram Engage', 'icegram' ),
				'link'  => 'https://www.icegram.com/boost-woocommerce-sales-using-icegram-engage/',
			),
			array(
				'title' => __( 'Avoid Intrusive Mobile Pop-Ups & Interstitials Penalty from Google…', 'icegram' ),
				'link'  => 'https://www.icegram.com/heres-how-to-avoid-intrusive-mobile-pop-ups-interstitials-penalty-from-google-and-safeguard-your-search-engine-rankings/',
			),
			array(
				'title' => __( 'Customize Icegram’s Engage Gallery Templates', 'icegram' ),
				'link'  => 'https://www.icegram.com/documentation/customize-icegrams-gallery-templates/',
			),
			array(
				'title' => __( 'Which metrics can be split tested with Icegram Engage?', 'icegram' ),
				'link'  => 'https://www.icegram.com/documentation/which-metrics-can-be-split-tested-with-the-split-testing-icegram-add-on/',
			),
			array(
				'title' => __( 'How to Track Analytics in Icegram Engage', 'icegram' ),
				'link'  => 'https://www.icegram.com/documentation/track-analytics-icegram/',
			),
		);

		return $articles;
	}

	}

}
