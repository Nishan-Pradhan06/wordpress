<?php
/*
Plugin Name: myStickymenu
Plugin URI: https://premio.io/
Description: Simple sticky (fixed on top) menu implementation for navigation menu and Welcome bar for announcements and promotion. After install go to Settings / myStickymenu and change Sticky Class to .your_navbar_class or #your_navbar_id.
Version: 2.6.2
Author: Premio
Author URI: https://premio.io/downloads/mystickymenu/
Text Domain: mystickymenu
Domain Path: /languages
License: GPLv2 or later
*/

defined('ABSPATH') or die("Cannot access pages directly.");
define( 'MYSTICKY_VERSION', '2.6.2' );
define('MYSTICKYMENU_URL', plugins_url('/', __FILE__));  // Define Plugin URL
define('MYSTICKYMENU_PATH', plugin_dir_path(__FILE__));  // Define Plugin Directory Path

require_once("mystickymenu-fonts.php");
require_once("welcome-bar.php");

if( is_admin() ) {
    include_once 'class-review-box.php';
    include_once 'class-upgrade-box.php';
}

class MyStickyMenuBackend
{
    private $options;

	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'mysticky_load_transl') );
		add_action( 'admin_init', array( $this, 'mysticky_default_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mysticky_admin_script' ) );
		add_filter( 'plugin_action_links_mystickymenu/mystickymenu.php', array( $this, 'mystickymenu_settings_link' )  );
		add_action( 'activated_plugin', array( $this, 'mystickymenu_activation_redirect' ) );
		add_action( "wp_ajax_sticky_menu_update_status", array($this, 'sticky_menu_update_status'));
	    add_action( "wp_ajax_mystickymenu_update_popup_status", array($this, 'mystickymenu_popup_status'));
		add_action( 'admin_footer', array( $this, 'mystickymenu_deactivate' ) );
		add_action( 'wp_ajax_mystickymenu_plugin_deactivate', array( $this, 'mystickymenu_plugin_deactivate' ) );
		add_action( 'wp_ajax_stickymenu_widget_delete', array( $this, 'stickymenu_widget_delete' ) );
		add_action( 'wp_ajax_mystickymenu_widget_status', array( $this, 'mystickymenu_widget_status' ) );
		add_action( 'wp_ajax_stickymenu_status_update', array( $this, 'stickymenu_status_update' ) );
		add_action( 'wp_ajax_mystickymenu_delete_contact_lead', array( $this, 'mystickymenu_delete_contact_lead' ) );
		add_action( 'wp_ajax_my_sticky_menu_bulks', array( $this, 'my_sticky_menu_bulks' ) );	
		
		add_action( 'wp_ajax_mystickymenu_admin_send_message_to_owner', array( $this, 'mystickymenu_admin_send_message_to_owner' ) );
	}
	
	
	
	
	public function stickymenu_status_update(){
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		$mysticky_options = get_option( 'mysticky_option_name' );
		if( isset($_POST['stickymenu_status']) && $_POST['stickymenu_status'] != ''  ){
			
			$stickymenu_status = $_POST['stickymenu_status'];
			$mysticky_options['stickymenu_enable'] = $stickymenu_status;
			update_option('mysticky_option_name',$mysticky_options);
		}
		wp_die();
	}

    public function mystickymenu_popup_status() {
        if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'mystickymenu_update_popup_status')) {
            update_option("mystickymenu_intro_box", "hide");
        }
        echo esc_attr("1");
        die;
    }
	
	public function mystickymenu_widget_status() {
		
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		
		if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_status']) && $_POST['widget_status'] != ''  ) {
			$welcomebars_widgets = get_option( 'mystickymenu-welcomebars' );
			$widget_id = $_POST['widget_id'];
			$welcomebars_widget_no = '-' . $widget_id ;
			
			if( $widget_id == 0 || $welcomebars_widgets[$widget_id] == 'default' ){
				$stickymenu_widget = get_option('mysticky_option_welcomebar');
				$welcomebars_widget_no = '';	
			}
			$widget_status = $_POST['widget_status'];
			$stickymenu_widget['mysticky_welcomebar_enable'] = $widget_status;
			
			update_option( 'mysticky_option_welcomebar',$stickymenu_widget);
		}
		wp_die();
	}
	
	public function stickymenu_widget_delete(){
		
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_delete']) && $_POST['widget_delete'] == 1  ) {
			$welcomebars_widgets = get_option( 'mystickymenu-welcomebars' );
			$widget_id = $_POST['widget_id'];			
			foreach( $welcomebars_widgets as $key => $widget_value ){
				$element_widget_no = '';
				if ( $key != 0 ) {
					$element_widget_no = '-' . $key;
				}
				delete_option( 'mysticky_option_welcomebar' . $element_widget_no );					
			}
			
			delete_option( 'mystickymenu-welcomebars' );
		}
		wp_die(); 
	}

    public function sticky_menu_update_status() {
        if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'myStickymenu_update_nonce')) {
            $status = self::sanitize_options($_REQUEST['status']);
            $email = self::sanitize_options($_REQUEST['email']);

            update_option("mystickymenu_update_message", 2);

            if($status == 1) {
                $url = 'https://premioapps.com/premio/signup/email.php';
				$apiParams = [
					'plugin' => 'myStickymenu',
					'email'  => $email,
				];

				// Signup Email for Chaty
				$apiResponse = wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => true]);

				if (is_wp_error($apiResponse)) {
					wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => false]);
				}

				$response['status'] = 1;
            }
        }
        die;
    }
	

	public function mystickymenu_delete_contact_lead(){
		global $wpdb;
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(0); 
		}
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		
		 if ( isset($_POST['ID']) && $_POST['ID'] != '' ) {
			$ID = sanitize_text_field($_POST['ID']);
		 	$table = $wpdb->prefix . 'mystickymenu_contact_lists';
		 	$delete_sql = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d",$ID);
		 	$delete = $wpdb->query($delete_sql);
		 }
		
		if ( isset($_POST['all_leads']) && $_POST['all_leads'] == 1 ) {
			$table = $wpdb->prefix . 'mystickymenu_contact_lists';
			$delete = $wpdb->query("TRUNCATE TABLE $table");
		}
		wp_die();	
		
	}


	public function my_sticky_menu_bulks(){
		global $wpdb;
		check_ajax_referer( 'mystickymenu', 'wpnonce' );
		if( isset($_POST['wpnonce']) ){
			$bulks = isset($_POST['bulks']) ? $_POST['bulks'] : array();
			foreach( $bulks as $key => $bulk ){
				$ID = sanitize_text_field($bulk);
				$table = $wpdb->prefix . 'mystickymenu_contact_lists';
				$delete_sql = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d",$ID);
				$delete = $wpdb->query($delete_sql);		
			}
		}
		wp_die();
	}
	
	public function mystickymenu_admin_send_message_to_owner() {
		$response = array();
		$response['status'] = 0;
		$response['error'] = 0;
		$response['errors'] = array();
		$response['message'] = "";
		$errorArray = [];
		$errorMessage = __("%s is required", "mystickymenu");
		$postData = $_POST;
		if(!isset($postData['textarea_text']) || trim($postData['textarea_text']) == "") {
			$error = array(
				"key"   => "textarea_text",
				"message" => __("Please enter your message","wcp")
			);
			$errorArray[] = $error;
		}
		if(!isset($postData['user_email']) || trim($postData['user_email']) == "") {
			$error = array(
				"key"   => "user_email",
				"message" => sprintf($errorMessage,__("Email","wcp"))
			);
			$errorArray[] = $error;
		} else if(!filter_var($postData['user_email'], FILTER_VALIDATE_EMAIL)) {
			$error = array(
				'key' => "user_email",
				"message" => "Email is not valid"
			);
			$errorArray[] = $error;
		}
		if(empty($errorArray)) {
			if(!isset($_REQUEST['nonce']) || empty($_REQUEST['nonce'])) {
				$error = array(
					'key' => "nonce",
					"message" => "Your request is not valid"
				);
				$errorArray[] = $error;
			} else if(!wp_verify_nonce($_REQUEST['nonce'], "mystickymenu_send_message_to_owner")) {
				$error = array(
					'key' => "nonce",
					"message" => "Your request is not valid"
				);
				$errorArray[] = $error;
			}
		}
		if(empty($errorArray)) {
			global $current_user;
			$text_message = $postData['textarea_text'];
			$email = $postData['user_email'];
			$domain = site_url();
			$user_name = $current_user->first_name." ".$current_user->last_name;

			$response['status'] = 1;

			/* sending message to Crisp */
			$post_message = array();

			$message_data = array();
			$message_data['key'] = "Plugin";
			$message_data['value'] = "My Sticky Menu";
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Domain";
			$message_data['value'] = $domain;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Email";
			$message_data['value'] = $email;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Message";
			$message_data['value'] = $text_message;
			$post_message[] = $message_data;

			$api_params = array(
				'domain' => $domain,
				'email' => $email,
				'url' => site_url(),
				'name' => $user_name,
				'message' => $post_message,
				'plugin' => "MSE",
				'type' => "Need Help",
			);

			/* Sending message to Crisp API */

			$crisp_response = wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

			if (is_wp_error($crisp_response)) {
				wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
			}
		} else {
			$response['error'] = 1;
			$response['errors'] = $errorArray;
		}
		echo json_encode($response);
		wp_die();
	}
	
	
	public function mystickymenu_settings_link($links){
		$settings_link = '<a href="admin.php?page=my-stickymenu-welcomebar">Settings</a>';
		$links['go_pro'] = '<a href="'.admin_url("admin.php?page=my-stickymenu-upgrade&type=upgrade").'" style="color: #FF5983; font-weight: bold; display: inline-block; border: solid 1px #FF5983; border-radius: 4px; padding: 0 5px;">'.__( 'Upgrade', 'stars-testimonials' ).'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	
	public function mystickymenu_activation_redirect( $plugin) {
		if( $plugin == plugin_basename( __FILE__ ) ) {
		    $is_shown = get_option("mystickymenu_update_message");
		    if($is_shown === false) {
		        add_option("mystickymenu_update_message", 1);
            }
            $option = get_option("mystickymenu_intro_box");
            if($option === false) {
                add_option("mystickymenu_intro_box", "show");
            }
			
			$welcomebar_widgets = get_option("mysticky_option_welcomebar");
			if ( $welcomebar_widgets ) {
				wp_redirect( admin_url( 'admin.php?page=my-stickymenu-welcomebar' ) ) ;
			} else {
				wp_redirect( admin_url( 'admin.php?page=my-stickymenu-welcomebar&widget=0' ) ) ;
			}
			
			exit;
		}
	}

    public function mysticky_admin_script($hook) {
		
		if ( !isset($_GET['page']) || ( isset($_GET['page']) && $_GET['page'] != 'my-stickymenu-settings' && $_GET['page'] != 'my-stickymenu-welcomebar' && $_GET['page'] != 'my-stickymenu-new-welcomebar' && $_GET['page'] != 'my-stickymenu-upgrade' && $_GET['page'] != 'msm-recommended-plugins' && $_GET['page'] != 'my-sticky-menu-leads' )) {
			return;
		}

		wp_enqueue_style('mystickymenuAdminStyle', plugins_url('/css/mystickymenu-admin.css', __FILE__), array(), MYSTICKY_VERSION );
		wp_style_add_data( 'mystickymenuAdminStyle', 'rtl', 'replace' );
		wp_enqueue_style( 'wp-color-picker' );		
		//wp_enqueue_script( 'wp-color-picker-alpha', plugins_url('/js/wp-color-picker-alpha.min.js', __FILE__), array( 'wp-color-picker' ), MYSTICKY_VERSION );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_style('jquery-ui');
		
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-slider');
		//wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'my-script-handle', plugins_url('js/iris-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

        if($hook == "mystickymenu_page_my-stickymenu-upgrade") {
            wp_enqueue_script( 'my-select2', plugins_url('js/select2.min.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
            wp_enqueue_style('my-css-select2', plugins_url('css/select2.min.css', __FILE__), array(), MYSTICKY_VERSION );
            wp_enqueue_style('my-css-admin-settings', plugins_url('css/admin-setting.css', __FILE__), array(), MYSTICKY_VERSION );

            wp_style_add_data( 'my-css-admin-settings', 'rtl', 'replace' );
        }

		wp_enqueue_script('mystickymenuAdminScript', plugins_url('/js/mystickymenu-admin.js', __FILE__), array( 'jquery', 'jquery-ui-slider' ), MYSTICKY_VERSION);
		
		$locale_settings = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'mystickymenu_url' => MYSTICKYMENU_URL,
			'ajax_nonce' => wp_create_nonce('mystickymenu'),					
		);
		
		wp_localize_script('mystickymenuAdminScript', 'mystickymenu', $locale_settings);
		
	}

	public function mysticky_load_transl(){
		load_plugin_textdomain('mystickymenu', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
	}

	function sanitize_options($value) {
		$value = stripslashes($value);
		$value = filter_var($value, FILTER_SANITIZE_STRING);
		return $value;
	}

	public function add_plugin_page(){
		if ( isset($_GET['hide_msmrecommended_plugin']) && $_GET['hide_msmrecommended_plugin'] == 1) {
			update_option('hide_msmrecommended_plugin',true);				
		}
		$hide_msmrecommended_plugin = get_option('hide_msmrecommended_plugin');
		// This page will be under "Settings"
		add_menu_page(
			'Settings Admin',
			'myStickymenu',
			'manage_options',
			'my-stickymenu-welcomebar',
			array( $this, 'mystickystickymenu_admin_welcomebar_page' )
		);
		add_submenu_page(
			'my-stickymenu-welcomebar',
			'Settings Admin',
			'Dashboard',
			'manage_options',
			'my-stickymenu-welcomebar',
			array( $this, 'mystickystickymenu_admin_welcomebar_page' )
		);
		
		add_submenu_page(
			'my-stickymenu-welcomebar',
			'Settings Admin',
			'+ Create New Welcome Bar',
			'manage_options',
			'my-stickymenu-new-welcomebar',				
			array( $this, 'mystickystickymenu_admin_new_welcomebar_page' )
		);

		add_submenu_page(
			'my-stickymenu-welcomebar',
			'Settings Admin',
			'Contact Form Leads',
			'manage_options',
			'my-sticky-menu-leads',
			array( $this, 'mystickymenu_admin_leads_page' )
		);
		
		add_submenu_page(
			'my-stickymenu-welcomebar',
			'Settings Admin',
			'Sticky menu settings',
			'manage_options',
			'my-stickymenu-settings',
			array( $this, 'create_admin_page' )
		);
		
		
		if ( !$hide_msmrecommended_plugin){
			add_submenu_page(
				'my-stickymenu-welcomebar',
				'msm-recommended-plugins',
				'Recommended Plugins',
				'manage_options',
				'msm-recommended-plugins',
				array( $this, 'mystickymenu_recommended_plugins' )
			);
		}
		add_submenu_page(
			'my-stickymenu-welcomebar',
			'Upgrade to Pro',
			'Upgrade to Pro',
			'manage_options',
			'my-stickymenu-upgrade',
			array( $this, 'mystickymenu_admin_upgrade_to_pro' )
		);
	}

	public function create_admin_page(){

		$upgarde_url = admin_url("admin.php?page=my-stickymenu-upgrade");
		// Set class property
		if (isset($_POST['mysticky_option_name']) && !empty($_POST['mysticky_option_name']) && isset($_POST['nonce'])) {
			if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'mysticky_option_backend_update')) {
				$post = $_POST['mysticky_option_name'];
				foreach($post as $key=>$value) {
					$post[$key] = self::sanitize_options($value);
				}
				
				$post['device_desktop'] = 'on';
				$post['device_mobile'] = 'on';
				update_option( 'mysticky_option_name', $post);
				$this->mysticky_clear_all_caches();
				
				
				if(isset($_POST['submit']) && $_POST['submit'] == 'SAVE & VIEW DASHBOARD'){
					?>
					<script>
						window.location.href = <?php echo "'".admin_url("admin.php?page=my-stickymenu-welcomebar")."'";?>;
					</script>
					<?php		
				}
				echo '<div class="updated settings-error notice is-dismissible "><p><strong>' . esc_html__('Settings saved.','mystickymenu'). '</p></strong></div>';
			} else {
				wp_verify_nonce($_GET['nonce'], 'wporg_frontend_delete');
				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickymenu'). '</p></strong></div>';
			}
		}		

		$mysticky_options = get_option( 'mysticky_option_name');
		$is_old = get_option("has_sticky_header_old_version");
		$is_old = ($is_old == "yes")?true:false;
		$nonce = wp_create_nonce('mysticky_option_backend_update');
        $pro_url = "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=";
		
        $is_shown = get_option("mystickymenu_update_message");
        if($is_shown == 1) {
			
			include_once MYSTICKYMENU_PATH . '/update.php';
		} else {

            $option = get_option("mystickymenu_intro_box");
            if($option == "show") {
                include_once dirname(__FILE__) . "/mystickymenu-popup.php";
            }
            ?>
        <style>
            div#wpcontent {
                background: rgba(101,114,219,1);
                background: -moz-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(101,114,219,1)), color-stop(67%, rgba(238,134,198,1)), color-stop(100%, rgba(238,134,198,1)));
                background: -webkit-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -o-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -ms-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: linear-gradient(135deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6572db', endColorstr='#ee86c6', GradientType=1 );
            }
        </style>
		<div id="mystickymenu" class="wrap mystickymenu">
			
			<div id="sticky-header-settings" class="sticky-header-content">
				
				<form class="mysticky-form" id="mystickymenuform" method="post" action="#">
				<div class="mystickymenu-heading">
					<div class="mysticky-stickymenu-header-title mystickymenu-content-section">
						<h3><?php _e('Sticky menu', 'myStickymenu'); ?></h3>
						<label for="mysticky-stickymenu-form-enabled" class="mysticky-welcomebar-switch stickymenu-switch">
							<input type="checkbox" id="mysticky-stickymenu-form-enabled" name="mysticky_option_name[stickymenu_enable]" value="1" <?php checked( @$mysticky_options['stickymenu_enable'], '1' );?> />
							<span class="slider"></span>
						</label>
						<div class="mysticky-stickymenu-backword-page">
							<a href="<?php echo admin_url("admin.php?page=my-stickymenu-welcomebar");?>"><span class="dashicons dashicons-arrow-left-alt2 back-dashboard" style="color: unset;font-size: 17px;"></span> <?php _e('Back to Dashboard', 'myStickymenu'); ?></a>
						</div>
					</div>
					<div class="myStickymenu-header-title">
						<h3><?php esc_attr_e('How To Make a Sticky Header', 'mystickymenu'); ?></h3>
					</div>
					<p><?php _e("Add sticky menu / header to any theme. <br />Simply change 'Sticky Class' to HTML element class desired to be sticky (div id can be used as well).", 'mystickymenu'); ?></p>
				</div>
				<div class="mystickymenu-content-section sticky-class-sec">
					<table>
						<tr>
							<td>
								<label class="mysticky_title"><?php _e("Sticky Class", 'mystickymenu')?></label>
								<br /><br />
								<?php $nav_menus  = wp_get_nav_menus();
								$menu_locations = get_nav_menu_locations();
								$locations      = get_registered_nav_menus();
								?>
								<select name="mysticky_option_name[mysticky_class_id_selector]" id="mystickymenu-select">
									<option value=""><?php _e( 'Select Sticky Menu', 'mystickymenu' ); ?></option>

									<?php foreach ( (array) $nav_menus as $_nav_menu ) : ?>
										<option value="<?php echo esc_attr( $_nav_menu->slug ); ?>" <?php selected( $_nav_menu->slug, $mysticky_options['mysticky_class_id_selector'] ); ?>>
											<?php
											echo esc_html( $_nav_menu->name );

											if ( ! empty( $menu_locations ) && in_array( $_nav_menu->term_id, $menu_locations ) ) {
												$locations_assigned_to_this_menu = array();
												foreach ( array_keys( $menu_locations, $_nav_menu->term_id ) as $menu_location_key ) {
													if ( isset( $locations[ $menu_location_key ] ) ) {
														$locations_assigned_to_this_menu[] = $locations[ $menu_location_key ];
													}
												}

												/**
												 * Filters the number of locations listed per menu in the drop-down select.
												 *
												 * @since 3.6.0
												 *
												 * @param int $locations Number of menu locations to list. Default 3.
												 */
												$assigned_locations = array_slice( $locations_assigned_to_this_menu, 0, absint( apply_filters( 'wp_nav_locations_listed_per_menu', 3 ) ) );

												// Adds ellipses following the number of locations defined in $assigned_locations.
												if ( ! empty( $assigned_locations ) ) {
													printf(
														' (%1$s%2$s)',
														implode( ', ', $assigned_locations ),
														count( $locations_assigned_to_this_menu ) > count( $assigned_locations ) ? ' &hellip;' : ''
													);
												}
											}
											?>
										</option>
									<?php endforeach; ?>
									<option value="custom" <?php selected( 'custom', $mysticky_options['mysticky_class_id_selector'] ); ?>><?php esc_html_e( 'Other Class Or ID', 'mystickymenu' );?></option>
								</select>

								<input type="text" size="18" id="mysticky_class_selector" class="mystickyinput" name="mysticky_option_name[mysticky_class_selector]" value="<?php echo esc_attr($mysticky_options['mysticky_class_selector']);?>"  />
								
								<p class="description mystuckymenu-class-id">
									<span class="dashicons dashicons-info"></span>&nbsp;
									<span>
									<?php echo sprintf(__('Need help finding your ID/Class? Install <a href="%s" target="_blank">CSS Peeper</a> to quickly get your navigation menu ID/Class. Here\'s a quick <a href="%s" target="_blank">video <span class="dashicons dashicons-controls-play"></span></a> of how you can do it.', 'mystickymenu'), 'https://chrome.google.com/webstore/detail/css-peeper/mbnbehikldjhnfehhnaidhjhoofhpehk?hl=en', 'https://www.youtube.com/watch?v=uuNqSkBPnLU');?>	
									</span>
								</p>
							</td>
							<td>
								<div class="mysticky_device_upgrade">
									<label class="mysticky_title"><?php _e("Devices", 'mystickymenu')?></label>
									<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
									
									<ul class="mystickymenu-input-multicheckbox">
										<li>
										<label>
											<input id="disable_css" name="mysticky_option_name[device_desktop]" type="checkbox"  checked  disabled />
											<?php _e( 'Desktop', 'mystickymenu' );?>
										</label>
										</li>
										<li>
										<label>
											<input id="disable_css" name="mysticky_option_name[device_mobile]" type="checkbox" checked disabled />
											<?php _e( 'Mobile', 'mystickymenu' );?>
										</label>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="mystickymenu-content-section">
					<h3><?php esc_html_e( 'Settings', 'mystickymenu' );?></h3>
					<table class="form-table">
						<tr>
							<td>
								<label for="myfixed_zindex" class="mysticky_title"><?php _e("Sticky z-index", 'mystickymenu')?></label>
							</td>
							<td>
								<input type="number" min="0" max="2147483647" step="1" class="mysticky-number" id="myfixed_zindex" name="mysticky_option_name[myfixed_zindex]" value="<?php echo esc_attr($mysticky_options['myfixed_zindex']);?>" />
							</td>
							<td>
								<label class="mysticky_title myssticky-remove-hand"><?php _e("Fade or slide effect", 'mystickymenu')?></label>
							</td>
							<td>
								<label>
								<input name="mysticky_option_name[myfixed_fade]" value= "slide" type="radio" <?php checked( @$mysticky_options['myfixed_fade'], 'slide' );?> />
								<?php _e("Slide", 'mystickymenu'); ?>
								</label>
								<label>
								<input name="mysticky_option_name[myfixed_fade]" value="fade" type="radio"  <?php checked( @$mysticky_options['myfixed_fade'], 'fade' );?> />
								<?php _e("Fade", 'mystickymenu'); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="myfixed_disable_small_screen" class="mysticky_title"><?php _e("Disable at Small Screen Sizes", 'mystickymenu')?></label>
								<p class="description"><?php esc_attr_e('Less than chosen screen width, set 0 to disable','mystickymenu');?></p>
							</td>
							<td>
								<div class="px-wrap">
									<input type="number" class="" min="0" step="1" id="myfixed_disable_small_screen" name="mysticky_option_name[myfixed_disable_small_screen]" value="<?php echo esc_attr($mysticky_options['myfixed_disable_small_screen']);?>" />
									<span class="input-px">PX</span>
								</div>
							</td>
							<td>
								<label for="mysticky_active_on_height" class="mysticky_title"><?php _e("Make visible on Scroll", 'mystickymenu')?></label>
								<p class="description"><?php esc_attr_e('If set to 0 auto calculate will be used.','mystickymenu');?></p>
							</td>
							<td>
								<div class="px-wrap">
									<input type="number" class="small-text" min="0" step="1" id="mysticky_active_on_height" name="mysticky_option_name[mysticky_active_on_height]" value="<?php echo esc_attr($mysticky_options['mysticky_active_on_height']);?>" />
									<span class="input-px">PX</span>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<label for="mysticky_active_on_height_home" class="mysticky_title"><?php _e("Make visible on Scroll at homepage", 'mystickymenu')?></label>
								<p class="description"><?php _e( 'If set to 0 it will use initial Make visible on Scroll value.', 'mystickymenu' );?></p>
							</td>
							<td>
								<div class="px-wrap">
									<input type="number" class="small-text" min="0" step="1" id="mysticky_active_on_height_home" name="mysticky_option_name[mysticky_active_on_height_home]" value="<?php echo esc_attr($mysticky_options['mysticky_active_on_height_home']);;?>" />
									<span class="input-px">PX</span>
								</div>
							</td>
							<td>
								<label for="myfixed_bgcolor" class="mysticky_title myssticky-remove-hand"><?php _e("Sticky Background Color", 'mystickymenu')?></label>
							</td>
							<td>
								<input type="text" id="myfixed_bgcolor" name="mysticky_option_name[myfixed_bgcolor]" class="my-color-field" data-alpha="true" value="<?php echo esc_attr($mysticky_options['myfixed_bgcolor']);;?>" />

							</td>
						</tr>
						<tr>
							<td>
								<label for="myfixed_transition_time" class="mysticky_title"><?php _e("Sticky Transition Time", 'mystickymenu')?></label>
							</td>
							<td>
								<input type="number" class="small-text" min="0" step="0.1" id="myfixed_transition_time" name="mysticky_option_name[myfixed_transition_time]" value="<?php echo esc_attr($mysticky_options['myfixed_transition_time']);?>" />
							</td>
							<td>
								<label for="myfixed_textcolor" class="mysticky_title myssticky-remove-hand"><?php _e("Sticky Text Color", 'mystickymenu')?></label>
							</td>
							<td>
								<input type="text" id="myfixed_textcolor" name="mysticky_option_name[myfixed_textcolor]" class="my-color-field" data-alpha="true" value="<?php echo (isset($mysticky_options['myfixed_textcolor'])) ? $mysticky_options['myfixed_textcolor'] : '';?>" />

							</td>
						</tr>
						<tr>
							<td>
								<label for="myfixed_opacity" class="mysticky_title myssticky-remove-hand"><?php _e("Sticky Opacity", 'mystickymenu')?></label>
								<p class="description"><?php _e( 'numbers 1-100.', 'mystickymenu');?></p>
							</td>
							<td>
								<input type="hidden" class="small-text mysticky-slider" min="0" step="1" max="100" id="myfixed_opacity" name="mysticky_option_name[myfixed_opacity]"  value="<?php echo esc_attr($mysticky_options['myfixed_opacity']);;?>"  />
								<div id="slider">
								  <div id="custom-handle" class="ui-slider-handle"><?php //echo esc_attr($mysticky_options['myfixed_opacity']);?></div>
								</div>

							</td>
						</tr>
					</table>
				</div>

				<div class="mystickymenu-content-section <?php echo !$is_old?"mystickymenu-content-upgrade":""?>" >

					<div class="mystickymenu-content-option">
						<label class="mysticky_title css-style-title"><?php _e("Hide on Scroll Down", 'mystickymenu'); ?></label>
						<?php if(!$is_old) { ?><span class="myStickymenu-upgrade"><a class="sticky-header-upgrade" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span><?php } ?>
						<p>
						<label class="mysticky_text">
							<input id="myfixed_disable_scroll_down" name="mysticky_option_name[myfixed_disable_scroll_down]" type="checkbox" <?php checked( @$mysticky_options['myfixed_disable_scroll_down'], 'on' );?> <?php echo !$is_old?"disabled":"" ?> />
							<?php _e("Disable sticky menu at scroll down", 'mystickymenu'); ?>
							</label>
						</p>
					</div>
					<div class="mysticky-page-target-setting mystickymenu-content-option">
						<label class="mysticky_title"><?php esc_attr_e('Page targeting', 'myStickymenu'); ?></label>
						<div class="mystickymenu-input-section mystickymenu-page-target-wrap">
							<div class="mysticky-welcomebar-setting-content-right">
								<div class="mysticky-page-options" id="mysticky-welcomebar-page-options">
									<?php $page_option = (isset($mysticky_options['mysticky_page_settings'])) ? $mysticky_options['mysticky_page_settings'] : array();
									$url_options = array(
										'page_contains' => 'pages that contain',
										'page_has_url' => 'a specific page',
										'page_start_with' => 'pages starting with',
										'page_end_with' => 'pages ending with',
									);

									if(!empty($page_option) && is_array($page_option)) {
										$count = 0;
										foreach($page_option as $k=>$option) {
											$count++;
											?>
											<div class="mysticky-page-option <?php echo ( $k==count($page_option) ) ? "last":""; ?>">
												<div class="url-content">
													<div class="mysticky-welcomebar-url-select">
														<select name="mysticky_option_name[mysticky_page_settings][<?php echo esc_attr($count); ?>][shown_on]" id="url_shown_on_<?php echo esc_attr($count);  ?>_option">
															<option value="show_on" <?php echo ($option['shown_on']=="show_on" ) ? "selected":"" ?> ><?php esc_html_e( 'Show on', 'mysticky' )?></option>
															<option value="not_show_on" <?php echo ($option['shown_on']=="not_show_on" )? "selected":""; ?>><?php esc_html_e( "Don't show on", "mysticky" );?></option>
														</select>
													</div>
													<div class="mysticky-welcomebar-url-option">
														<select class="mysticky-url-options" name="mysticky_option_name[mysticky_page_settings][<?php echo esc_attr($count);; ?>][option]" id="url_rules_<?php echo esc_attr($count);  ?>_option">
															<option disabled value=""><?php esc_html_e( "Select Rule", "mysticky" );?></option>
															<?php foreach($url_options as $key=>$value) {
																$selected = ( isset($option['option']) && $option['option']==$key )?" selected='selected' ":"";
																echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
															} ?>
														</select>
													</div>
													<div class="mysticky-welcomebar-url-box">
														<span class='mysticky-welcomebar-url'><?php echo site_url("/"); ?></span>
													</div>
													<div class="mysticky-welcomebar-url-values">
														<input type="text" value="<?php echo esc_attr($option['value']) ?>" name="mysticky_option_name[mysticky_page_settings][<?php echo esc_attr($count); ?>][value]" id="url_rules_<?php echo esc_attr($count);; ?>_value" />
													</div>
													<div class="mysticky-welcomebar-url-buttons">
														<a class="mysticky-remove-rule" href="javascript:;">x</a>
													</div>
													<div class="clear"></div>
												</div>
											</div>
											<?php
										}
									}
									?>
								</div>
								<a href="javascript:void(0);" class="create-rule" id="mysticky_create-rule"><?php esc_html_e( "Add Rule", "mystickymenu" );?></a>
							</div>
							<input type="hidden" id="mysticky_welcomebar_site_url" value="<?php echo site_url("/") ?>" />
							<div class="mysticky-page-options-html" style="display: none;">
								<div class="mysticky-page-option">
									<div class="url-content">
										<div class="mysticky-welcomebar-url-select">
											<select name="" id="url_shown_on___count___option">
												<option value="show_on"><?php esc_html_e("Show on", "mysticky" );?></option>
												<option value="not_show_on"><?php esc_html_e("Don't show on", "mysticky" );?></option>
											</select>
										</div>
										<div class="mysticky-welcomebar-url-option">
											<select class="mysticky-url-options" name="" id="url_rules___count___option">
												<option selected="selected" disabled value=""><?php esc_html_e("Select Rule", "mysticky" );?></option>
												<?php foreach($url_options as $key=>$value) {
													echo '<option value="'.$key.'">'.$value.'</option>';
												} ?>
											</select>
										</div>
										<div class="mysticky-welcomebar-url-box">
											<span class='mysticky-welcomebar-url'><?php echo site_url("/"); ?></span>
										</div>
										<div class="mysticky-welcomebar-url-values">
											<input type="text" value="" name="mysticky_option_name[mysticky_page_settings][__count__][value]" id="url_rules___count___value" disabled />
										</div>
										<div class="clear"></div>
									</div>
									<span class="myStickymenu-upgrade"><a class="sticky-header-upgrade" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span>
								</div>
							</div>
						</div>
					</div>
					<div class="mystickymenu-content-option">
						<label class="mysticky_title css-style-title"><?php _e("CSS style", 'mystickymenu'); ?></label>
						<span class="mysticky_text"><?php _e( 'Add/edit CSS style. Leave it blank for default style.', 'mystickymenu');?></span>
						<div class="mystickymenu-input-section">
							<textarea type="text" rows="4" cols="60" id="myfixed_cssstyle" name="mysticky_option_name[myfixed_cssstyle]"  <?php echo !$is_old?"disabled":"" ?> ><?php echo @$mysticky_options['myfixed_cssstyle'];?></textarea>
						</div>
						<p><?php esc_html_e( "CSS ID's and Classes to use:", "mystickymenu" );?></p>
						<p>
							#mysticky-wrap { }<br/>
							#mysticky-nav.wrapfixed { }<br/>
							#mysticky-nav.wrapfixed.up { }<br/>
							#mysticky-nav.wrapfixed.down { }<br/>
							#mysticky-nav .navbar { }<br/>
							#mysticky-nav .navbar.myfixed { }<br/>
						</p>
					</div>

					<div class="mystickymenu-content-option">
						<label class="mysticky_title" for="disable_css"><?php _e("Disable CSS style", 'mystickymenu'); ?></label>
						<div class="mystickymenu-input-section">
							<label>
								<input id="disable_css" name="mysticky_option_name[disable_css]" type="checkbox"   <?php echo !$is_old?"disabled":"" ?> <?php checked( @$mysticky_options['disable_css'], 'on' );?> />
								<?php _e( 'Use this option if you plan to include CSS Style manually', 'mystickymenu' );?>
							</label>
						</div>
						<p></p>
					</div>

					<div class="mystickymenu-content-option">
						<label class="mysticky_title"><?php _e("Disable at", 'mystickymenu'); ?></label>
						<?php if(!$is_old) { ?><span class="myStickymenu-upgrade"><a class="sticky-header-upgrade" href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><?php _e( 'Upgrade Now', 'mystickymenu' );?></a></span><?php } ?>
						<div class="mystickymenu-input-section">
							<ul class="mystickymenu-input-multicheckbox">
								<li>
									<label>
										<input id="mysticky_disable_at_front_home" name="mysticky_option_name[mysticky_disable_at_front_home]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?>  <?php checked( @$mysticky_options['mysticky_disable_at_front_home'], 'on' );?>/>
										<span><?php esc_attr_e('front page', 'mystickymenu' );?></span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_blog" name="mysticky_option_name[mysticky_disable_at_blog]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?>  <?php checked( @$mysticky_options['mysticky_disable_at_blog'], 'on' );?>/>
										<span><?php esc_attr_e('blog page', 'mystickymenu' );?></span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_page" name="mysticky_option_name[mysticky_disable_at_page]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?> <?php checked( @$mysticky_options['mysticky_disable_at_page'], 'on' );?> />
										<span><?php esc_attr_e('pages', 'mystickymenu' );?> </span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_tag" name="mysticky_option_name[mysticky_disable_at_tag]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?> <?php checked( @$mysticky_options['mysticky_disable_at_tag'], 'on' );?> />
										<span><?php esc_attr_e('tags', 'mystickymenu' );?> </span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_category" name="mysticky_option_name[mysticky_disable_at_category]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?>  <?php checked( @$mysticky_options['mysticky_disable_at_category'], 'on' );?>/>
										<span><?php esc_attr_e('categories', 'mystickymenu' );?></span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_single" name="mysticky_option_name[mysticky_disable_at_single]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?> <?php checked( @$mysticky_options['mysticky_disable_at_single'], 'on' );?> />
										<span><?php esc_attr_e('posts', 'mystickymenu' );?> </span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_archive" name="mysticky_option_name[mysticky_disable_at_archive]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?> <?php checked( @$mysticky_options['mysticky_disable_at_archive'], 'on' );?> />
										<span><?php esc_attr_e('archives', 'mystickymenu' );?> </span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_search" name="mysticky_option_name[mysticky_disable_at_search]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?> <?php checked( @$mysticky_options['mysticky_disable_at_search'], 'on' );?> />
										<span><?php esc_attr_e('search', 'mystickymenu' );?> </span>
									</label>
								</li>
								<li>
									<label>
										<input id="mysticky_disable_at_404" name="mysticky_option_name[mysticky_disable_at_404]" type="checkbox"  <?php echo !$is_old?"disabled":"" ?>  <?php checked( @$mysticky_options['mysticky_disable_at_404'], 'on' );?>/>
										<span><?php esc_attr_e('404', 'mystickymenu' );?> </span>
									</label>
								</li>
							</ul>
							
							<?php 
							if  (isset ( $mysticky_options['mysticky_disable_at_page'] ) == true )  {			
								echo '<div class="mystickymenu-input-section">';
								_e('<span class="description"><strong>Except for this pages:</strong> </span>', 'mystickymenu');
						
								printf(
									'<input type="text" size="26" class="mystickymenu_normal_text" id="mysticky_enable_at_pages" name="mysticky_option_name[mysticky_enable_at_pages]" value="%s"  /> ',  
									isset( $mysticky_options['mysticky_enable_at_pages'] ) ? esc_attr( $mysticky_options['mysticky_enable_at_pages']) : '' 
								); 
								
								_e('<span class="description">Comma separated list of pages to enable. It should be page name, id or slug. Example: about-us, 1134, Contact Us. Leave blank if you realy want to disable sticky menu for all pages.</span>', 'mystickymenu');
								echo '</div>';								
							}
							
							if  (isset ( $mysticky_options['mysticky_disable_at_single'] ) == true )  {
			
								echo '<div class="mystickymenu-input-section">';
								_e('<span class="description"><strong>Except for this posts:</strong> </span>', 'mystickymenu');
						
								printf(
									'<input type="text" size="26" class="mystickymenu_normal_text" id="mysticky_enable_at_posts" name="mysticky_option_name[mysticky_enable_at_posts]" value="%s" /> ',  
									isset( $mysticky_options['mysticky_enable_at_posts'] ) ? esc_attr( $mysticky_options['mysticky_enable_at_posts']) : '' 
								); 
								
								_e('<span class="description">Comma separated list of posts to enable. It should be post name, id or slug. Example: about-us, 1134, Contact Us. Leave blank if you realy want to disable sticky menu for all posts.</span>', 'mystickymenu');
								echo '</div>';								
								
							}
							?>
							<p></p>
						</div>
					</div>
				</div>
				
				<!-- Mysticky Menu: Save & Save Dashbaord Submission Validation Popup -->

				<div class="mystickymenu-action-popup new-center" id="mysticky-sticky-save-confirm" style="display:none;">
					<div class="mystickymenu-action-popup-header">
						<h3><?php esc_html_e("Turn on Sticky Menu","mystickymenu"); ?></h3>
						<span class="dashicons dashicons-no-alt close-button" data-from = "stickymenu-confirm"></span>
					</div>
					<div class="mystickymenu-action-popup-body">
						<p><?php esc_html_e("Sticky Menu is not turned on. Turn on Sticky Menu to activate sticky menu on your website.","mystickymenu"); ?></p>
					</div>
					<div class="mystickymenu-action-popup-footer">
						<button type="button" class="btn-enable btn-nevermind-status" id="stickymenu_status_dolater" ><?php esc_html_e("Just save & keep it off","mystickymenu"); ?></button>
						<button type="button" class="btn-disable-cancel" id="stickymenu_status_ok" ><?php esc_html_e("Save & Turn on Sticky Menu","mystickymenu"); ?></button>
					</div>
				</div>
				<div class="mystickymenupopup-overlay" id="stickymenu-option-overlay-popup"></div>

				<!-- End Save & Save Dashbaord Submission Validation Popup -->

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary btn-save-stickymenu" value="<?php esc_attr_e('Save', 'mystickymenu');?>">
					
					<input type="submit" name="submit" id="submit" class="button button-primary save_view_dashboard" style="width: auto;" value="<?php _e('SAVE & VIEW DASHBOARD', 'mystickymenu');?>">
				</p>
				<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
				<input type="hidden" id="save_stickymenu" value=""/>
				</form>
				<form class="mysticky-hideformreset" method="post" action="">
					<input name="reset_mysticky_options" class="button button-secondary confirm" type="submit" value="<?php esc_attr_e('Reset', 'mystickymenu');?>" >
					<input type="hidden" name="action" value="reset" />
					<?php $nonce = wp_create_nonce('mysticky_option_backend_reset_nonce'); ?>
					<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
				</form>
				<p class="myStickymenu-review"><a href="https://wordpress.org/support/plugin/mystickymenu/reviews/" target="_blank"><?php esc_attr_e('Leave a review','mystickymenu'); ?></a></p>
			</div>
        </div>
        <?php }
	}
	
	
	public function mystickystickymenu_admin_welcomebar_page() {
		//require_once MYSTICKYMENU_PATH . 'help.php';

		$is_shown = get_option("mystickymenu_update_message");
        if($is_shown == 1) {
			include_once MYSTICKYMENU_PATH . '/update.php';
			return;
		} 
		
		/* 
			DATE : 2022-08-04
			Welcome bar save data function
		*/
		
		if (isset($_POST['mysticky_option_welcomebar']) && !empty($_POST['mysticky_option_welcomebar']) && isset($_POST['nonce'])) {
			if(!empty($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'mysticky_option_welcomebar_update')) {		
				

				$widgets = get_option( 'mysticky_option_welcomebar' );
				
				$is_first_widget = 0;
				if( isset($widgets) && $widgets == '' ){
					$is_first_widget = 1;
				}
				
				$welcomebars_widgets[0] = 'Welcome Bar #0';
				update_option( 'mystickymenu-welcomebars', $welcomebars_widgets );
				
				$mysticky_option_welcomebar = filter_var_array( $_POST['mysticky_option_welcomebar'], FILTER_SANITIZE_STRING );
				$mysticky_option_welcomebar['mysticky_welcomebar_bar_text'] = wp_kses_post($_POST['mysticky_option_welcomebar']['mysticky_welcomebar_bar_text']);
				$mysticky_option_welcomebar['mysticky_welcomebar_height'] = 60;
				$mysticky_option_welcomebar['mysticky_welcomebar_device_desktop'] = 'desktop';
				$mysticky_option_welcomebar['mysticky_welcomebar_device_mobile'] = 'mobile';
				$mysticky_option_welcomebar['mysticky_welcomebar_trigger'] = 'after_a_few_seconds';
				$mysticky_option_welcomebar['mysticky_welcomebar_triggersec'] = '0';
				$mysticky_option_welcomebar['mysticky_welcomebar_expirydate'] = '';
				$mysticky_option_welcomebar['mysticky_welcomebar_page_settings'] = '';
				
				update_option( 'mysticky_option_welcomebar', $mysticky_option_welcomebar);
				
				$this->mysticky_clear_all_caches();
				
				if($is_first_widget == 1){
					echo '<div class="main-popup-mystickymenu-bg first-widget-popup"><div class="main-popup-mystickymenu-bg mystickymenu_container_popupbox"><div class="firstwidget-popup-contain"><img src="'. MYSTICKYMENU_URL .'/images/firstwidget_header.svg"><h4>Your first welcome bar is up! 🎉</h4> <p> Yay - we’re happy you chose MyStickyMenu for your website. If you run into anything, the <a href=" https://premio.io/help/mystickymenu/?utm_source=firstbar" target="_blank" style="text-decoration: underline !important;"><strong>help center</strong></a> is always here for you.</p><a href="'.admin_url("admin.php?page=my-stickymenu-welcomebar").'" class="mystickymenu btn-black btn-back-dashboard">Back to Dashboard</a></div><div class="popup-modul-close-btn firstwidget-model"><a href="javascript:void(0)" class="close-chaty-maxvisitor-popup" id="close-first-popup"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 5L5 15" stroke="#4A4A4A" stroke-width="2.08" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 5L15 15" stroke="#4A4A4A" stroke-width="2.08" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div></div><div class="mystickymenupopup-overlay" id="first_widget_overlay" style="display:block;"></div>';
				}
				
				echo '<div class="updated settings-error notice is-dismissible "><p><strong>' . esc_html__('Settings saved.','mystickymenu'). '</p></strong></div>';
			} else {
				wp_verify_nonce($_GET['nonce'], 'wporg_frontend_delete');
				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickymenu'). '</p></strong></div>';
			}
		} 
		
		
		
		if (isset($_POST['mysticky_welcomebar_reset']) && !empty($_POST['mysticky_welcomebar_reset']) && isset($_POST['nonce_reset'])) {
			if(!empty($_POST['nonce_reset']) && wp_verify_nonce($_POST['nonce_reset'], 'mysticky_option_welcomebar_reset')) {	
				$mysticky_option_welcomebar_reset = mysticky_welcomebar_pro_widget_default_fields();				
				update_option( 'mysticky_option_welcomebar', $mysticky_option_welcomebar_reset);
				$this->mysticky_clear_all_caches();
				echo '<div class="updated settings-error notice is-dismissible "><p><strong>' . esc_html__('Reset Settings saved.','mystickymenu'). '</p></strong></div>';
			} else {
				wp_verify_nonce($_GET['nonce'], 'wporg_frontend_delete');
				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickymenu'). '</p></strong></div>';
			}
		}
		
		if(isset($is_first_widget) && $is_first_widget == 0 && isset($_POST['submit']) && $_POST['submit'] == 'SAVE & VIEW DASHBOARD'){
			?>
			<script>
				window.location.href = <?php echo "'".admin_url("admin.php?page=my-stickymenu-welcomebar")."'";?>;
			</script>
			<?php
		}

		$mysticky_options = get_option( 'mysticky_option_name');
		$is_old = get_option("has_sticky_header_old_version");
		$is_old = ($is_old == "yes") ? true : false;
		$nonce = wp_create_nonce('mysticky_option_backend_update');
        $pro_url = "https://go.premio.io/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=";
		
		?>
		<style>
            div#wpcontent {
                background: rgba(101,114,219,1);
                background: -moz-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(101,114,219,1)), color-stop(67%, rgba(238,134,198,1)), color-stop(100%, rgba(238,134,198,1)));
                background: -webkit-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -o-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -ms-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: linear-gradient(135deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6572db', endColorstr='#ee86c6', GradientType=1 );
            }
        </style>
		<div id="mystickymenu" class="wrap mystickymenu">
			
			<div id="sticky-header-welcome-bar" class="sticky-header-content">
				<?php 
					
					$welcomebars_widgets = get_option( 'mysticky_option_welcomebar' );
					if ( !isset($_GET['widget']) && isset( $_GET['page'] ) && $_GET['page'] == 'my-stickymenu-welcomebar' ) {
						include_once( 'stickymenu-dashboard.php');
					}elseif ( !isset($_GET['isedit']) && !isset($_GET['save']) && isset($welcomebars_widgets) && !empty($welcomebars_widgets) ) {
						?>
						<div id="mystickymenu" class="wrap mystickymenu mystickymenu-new-widget-wrap">		 
							<?php include_once dirname(__FILE__) . '/mystickymeny-new-welcomebar.php';?>
						</div>
						<?php
					}else{
						mysticky_welcome_bar_backend(); 	
					}
				?>
			</div>
		</div>
		<?php
	}
	
	public function mystickystickymenu_admin_new_welcomebar_page() {	
		$welcomebars_widgets = get_option( 'mysticky_option_welcomebar' );
		if( isset($welcomebars_widgets) && !empty($welcomebars_widgets)){
			?>
			<div id="mystickymenu" class="wrap mystickymenu mystickymenu-new-widget-wrap">		 
				<?php include_once dirname(__FILE__) . '/mystickymeny-new-welcomebar.php';?>
			</div>
			<?php	
		}else{ ?>
			<div id="mystickymenu" class="wrap mystickymenu">
				<div id="sticky-header-welcome-bar" class="sticky-header-content">
					<?php mysticky_welcome_bar_backend(); ?>
				</div>
			</div>
			<?php
		}
		
	}
	
	public function mystickymenu_recommended_plugins() {
		include_once 'recommended-plugins.php';
	}
	
	public function mystickymenu_admin_upgrade_to_pro() {
        $pro_url = "https://go.premio.io/checkount/?edd_action=add_to_cart&download_id=2199&edd_options[price_id]=";
        ?>
		<style>
            div#wpcontent {
                background: rgba(101,114,219,1);
                background: -moz-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(101,114,219,1)), color-stop(67%, rgba(238,134,198,1)), color-stop(100%, rgba(238,134,198,1)));
                background: -webkit-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -o-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: -ms-linear-gradient(-45deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                background: linear-gradient(135deg, rgba(101,114,219,1) 0%, rgba(238,134,198,1) 67%, rgba(238,134,198,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6572db', endColorstr='#ee86c6', GradientType=1 );
            }
        </style>
		<div id="mystickymenu" class="wrap mystickymenu">
			<?php include_once "upgrade-to-pro.php"; ?>
        </div>
        <?php
	}
		
	public function mysticky_default_options() {

		global $options;
		$menu_locations = get_nav_menu_locations();		
		$menu_object = isset($menu_locations['menu-1']) ? wp_get_nav_menu_object( $menu_locations['menu-1'] ) : array();
		
		if ( is_object($menu_object) && $menu_object->slug != '' ) {
			$mysticky_class_id_selector = $menu_object->slug;
		} else {
			$mysticky_class_id_selector = 'custom';
		}
		
		$mystickyClass = '.navbar';		
		$template_name = get_template();
		switch( $template_name ){
			case 'ashe':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '#main-nav';
				break;
			case 'astra':
			case 'hello-elementor':
			case 'sydney':
			case 'twentysixteen':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = 'header.site-header';
				break;
			case 'generatepress':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = 'nav.main-navigation';
				break;
			case 'transportex':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '.transportex-menu-full';
				break;
			case 'hestia':
			case 'neve':	
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = 'header.header';
				break;
			case 'mesmerize':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '.navigation-bar';
				break;
			case 'oceanwp':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = 'header#site-header';
				break;
			case 'shapely':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '#site-navigation';
				break;
			case 'storefront':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '.storefront-primary-navigation';
				break;
			case 'twentynineteen':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '#site-navigation';
				break;				
			case 'twentyseventeen':
				$mysticky_class_id_selector = 'custom';
				$mystickyClass = '.navigation-top';
				break;
			default:
				break;
		}
		
		$default = array(
				'mysticky_class_id_selector'	=> $mysticky_class_id_selector,
				'mysticky_class_selector' 		=> $mystickyClass,
				'device_desktop' 				=> 'on',
				'device_mobile' 				=> 'on',
				'myfixed_zindex' 				=> '99990',
				'myfixed_bgcolor' 				=> '#f7f5e7',
				'myfixed_opacity' 				=> '90',
				'myfixed_transition_time' 		=> '0.3',
				'myfixed_disable_small_screen' 	=> '0',
				'myfixed_disable_large_screen' 	=> '0',
				'mysticky_active_on_height' 	=> '0',
				'mysticky_active_on_height_home'=> '0',
				'myfixed_fade' 					=> 'slide',
				'myfixed_cssstyle' 				=> '#mysticky-nav .myfixed { margin:0 auto; float:none; border:0px; background:none; max-width:100%; }'
			);

		if ( get_option('mysticky_option_name') == false && current_user_can( 'manage_options' ) ) {
			$status = get_option("sticky_header_status");
			if($status == false) {
				update_option("sticky_header_status", "done");
				update_option("has_sticky_header_old_version", "no");
			}
			update_option( 'mysticky_option_name', $default );
		} else {
			$status = get_option("sticky_header_status");
			if($status == false) {
				update_option("sticky_header_status", "done");
				update_option("has_sticky_header_old_version", "yes");
			}
		}

		if(isset($_POST['reset_mysticky_options']) && current_user_can( 'manage_options' )) {
			if(isset($_REQUEST['nonce']) && !empty($_REQUEST['nonce'])  && wp_verify_nonce($_REQUEST['nonce'], 'mysticky_option_backend_reset_nonce')) {
				update_option('mysticky_option_name', $default);
			} else {

			}
		}
		
		if ( !get_option( 'update_mysticky_version_2_6') && current_user_can( 'manage_options' )) {
			$mysticky_option_name = get_option( 'mysticky_option_name' );
			$mysticky_option_name['mysticky_class_id_selector'] = 'custom';
			if ($mysticky_option_name['myfixed_fade'] == 'on'){
				$mysticky_option_name['myfixed_fade'] = 'slide';
			}else{
				$mysticky_option_name['myfixed_fade'] = 'fade';
			}
			update_option( 'mysticky_option_name', $mysticky_option_name );
			update_option( 'update_mysticky_version_2_6', true );
		}
		
		if ( !get_option( 'update_mysticky_version_2_5_7') && current_user_can( 'manage_options' )) {
			$mysticky_option_name = get_option( 'mysticky_option_name' );
			$mysticky_option_name['stickymenu_enable'] = 1;			
			update_option( 'mysticky_option_name', $mysticky_option_name );
			update_option( 'update_mysticky_version_2_5_7', true );
		}
	}
	
	/*
	 * clear cache when any option is updated
	 *
	 */
	public function mysticky_clear_all_caches(){

		try {
			global $wp_fastest_cache;

			// if W3 Total Cache is being used, clear the cache
			if (function_exists('w3tc_flush_all')) {
				w3tc_flush_all();
			}
			/* if WP Super Cache is being used, clear the cache */
			if (function_exists('wp_cache_clean_cache')) {
				global $file_prefix, $supercachedir;
				if (empty($supercachedir) && function_exists('get_supercache_dir')) {
					$supercachedir = get_supercache_dir();
				}
				wp_cache_clean_cache($file_prefix);
			}

			if (class_exists('WpeCommon')) {
				//be extra careful, just in case 3rd party changes things on us
				if (method_exists('WpeCommon', 'purge_memcached')) {
					//WpeCommon::purge_memcached();
				}
				if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
					//WpeCommon::clear_maxcdn_cache();
				}
				if (method_exists('WpeCommon', 'purge_varnish_cache')) {
					//WpeCommon::purge_varnish_cache();
				}
			}

			if (method_exists('WpFastestCache', 'deleteCache') && !empty($wp_fastest_cache)) {
				$wp_fastest_cache->deleteCache();
			}
			if (function_exists('rocket_clean_domain')) {
				rocket_clean_domain();
				// Preload cache.
				if (function_exists('run_rocket_sitemap_preload')) {
					run_rocket_sitemap_preload();
				}
			}

			if (class_exists("autoptimizeCache") && method_exists("autoptimizeCache", "clearall")) {
				autoptimizeCache::clearall();
			}

			if (class_exists("LiteSpeed_Cache_API") && method_exists("autoptimizeCache", "purge_all")) {
				LiteSpeed_Cache_API::purge_all();
			}

			if ( class_exists( '\Hummingbird\Core\Utils' ) ) {

				$modules   = \Hummingbird\Core\Utils::get_active_cache_modules();
				foreach ( $modules as $module => $name ) {
					$mod = \Hummingbird\Core\Utils::get_module( $module );

					if ( $mod->is_active() ) {
						if ( 'minify' === $module ) {
							$mod->clear_files();
						} else {
							$mod->clear_cache();
						}
					}
				}
			}

		} catch (Exception $e) {
			return 1;
		}
	}
	
	public function mystickymenu_deactivate() {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow ) {
			return;
		}
		include dirname(__FILE__) . "/mystickymenu-deactivate-form.php";
	}
	public function mystickymenu_plugin_deactivate() {
		global $current_user;
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(0); 
		}
		check_ajax_referer( 'mystickymenu_deactivate_nonce', 'nonce' );
		
		$postData = $_POST;
		$errorCounter = 0;
		$response = array();
		$response['status'] = 0;
		$response['message'] = "";
		$response['valid'] = 1;
		if(!isset($postData['reason']) || empty($postData['reason'])) {
			$errorCounter++;
			$response['message'] = "Please provide reason";
		} else if(!isset($postData['reason']) || empty($postData['reason'])) {
			$errorCounter++;
			$response['message'] = "Please provide reason";
		} else {
			$nonce = $postData['nonce'];
			if(!wp_verify_nonce($nonce, 'mystickymenu_deactivate_nonce')) {
				$response['message'] = __("Your request is not valid", "mystickymenu");
				$errorCounter++;
				$response['valid'] = 0;
			}
		}
		if($errorCounter == 0) {
			global $current_user;				
			$plugin_info = get_plugin_data( dirname(__FILE__) . "/mystickymenu.php" );
			$postData = $_POST;
			$email = "none@none.none";

			if (isset($postData['email_id']) && !empty($postData['email_id']) && filter_var($postData['email_id'], FILTER_VALIDATE_EMAIL)) {
				$email = $postData['email_id'];
			}
			$domain = site_url();
			$user_name = $current_user->first_name . " " . $current_user->last_name;

			$response['status'] = 1;

			/* sending message to Crisp */
			$post_message = array();

			$message_data = array();
			$message_data['key'] = "Plugin";
			$message_data['value'] = "My Sticky Menu";
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Plugin Version";
			$message_data['value'] = $plugin_info['Version'];
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Domain";
			$message_data['value'] = $domain;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Email";
			$message_data['value'] = $email;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "WordPress Version";
			$message_data['value'] = esc_attr(get_bloginfo('version'));
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "PHP Version";
			$message_data['value'] = PHP_VERSION;
			$post_message[] = $message_data;

			$message_data = array();
			$message_data['key'] = "Message";
			$message_data['value'] = $postData['reason'];
			$post_message[] = $message_data;

			$api_params = array(
				'domain' => $domain,
				'email' => $email,
				'url' => site_url(),
				'name' => $user_name,
				'message' => $post_message,
				'plugin' => "My Sticky Menu",
				'type' => "Uninstall",
			);

			/* Sending message to Crisp API */
			$crisp_response = wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

			if (is_wp_error($crisp_response)) {
				wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
			}
		}
		echo json_encode($response);
		wp_die();
	}

	/* *
	 * Mysticky Menu : Contact Lead function for show all the lead which send by user.	
	 * DATE : 2022-08-04
	 * */

	public function mystickymenu_admin_leads_page(){
		global $wpdb;
		$where_search = '';
		$table_name = $wpdb->prefix . "mystickymenu_contact_lists";
		$elements_widgets = get_option( 'mystickymenu-welcomebars' );
		
		$custom_fields = array();
		if ( !empty($elements_widgets)) {
			foreach( $elements_widgets as $key=>$value) {
				$widget_no = '-'.$key;
				if ( $key == 0 ) {
					$widget_no = '';
				}
			}
		}
		?>
	<!-- /**/ */ -->
	<div class="wrap mystickymenu-contact-wrap">
			<h2><?php _e( 'Contact Form Leads', 'mystickymenu' ); ?></h2>
			<p class="description">
				<strong><?php esc_html_e("Contact's data is saved locally do make backup or export before uninstalling plugin", 'mystickymenu');?></strong>
			</p>
			<div>
				<div class="mystickymenu-btnmbox">
					<div class="mystickymenu-btnbx">
						<strong><?php esc_html_e('Download & Export All Subscriber to CSV file:','mystickymenu' );?> </strong>
							<a href="<?php echo plugins_url('mystickymenu-contact-leads.php?download_file=mystickymenu_contact_leads.csv',__FILE__); ?>" class="wpappp_buton" id="wpappp_export_to_csv" value="Export to CSV" href="#"><?php esc_html_e('Download & Export to CSV', 'mystickymenu' );?></a>
					</div>
					<div class="mystickymenu-btnbx">
						<strong><?php esc_html_e('Delete All Subscibers from Database:','mystickymenu');?> </strong>
	
						<input type="button" class="wpappp_buton" id="mystickymenu_delete_all_leads" value="<?php esc_attr_e('Delete All Data', 'mystickymenu' );?>" />
					</div>	
				</div>
				<input type="hidden" id="delete_nonce" name="delete_nonce" value="<?php echo wp_create_nonce("mysticky_menu_delete_nonce") ?>" />
			</div>
	
			<?php 
				if ( isset($_REQUEST['search-contact']) && $_REQUEST['search-contact'] != '' ) {
					$where_search = "WHERE contact_name like '%" . $_REQUEST['search-contact'] . "%' OR contact_email like '%".$_REQUEST['search-contact']."%' OR contact_phone like '%".$_REQUEST['search-contact']."%'";
				}
			?>
			<div>					
				<div class="tablenav top">
					<form action="<?php echo admin_url("admin.php?page=my-sticky-menu-leads");?>" method="post">
					<div class="alignleft actions bulkactions">
						<select name="action" id="bulk-action-selector-top">
						<option value="">Bulk Actions</option>
						<option value="delete_message">Delete</option>
						</select>
						<input type="submit" id="doaction" class="button action" value="Apply">
						<?php wp_nonce_field( 'stickyelement-contatc-submit', 'stickyelement-contatc-submit' );  ?>
					</div>
					</form>
					<form action="<?php echo admin_url("admin.php?page=my-sticky-menu-leads");?>" method='get'>
						<input type="hidden" name="page" value='my-sticky-menu-leads'/>
						<p class="search-box">
							<label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'Search', 'mystickymenu');?></label>
							<input type="search" id="post-search-input" name="search-contact" value="<?php echo (isset($_GET['search-contact']) && $_GET['search-contact'] != '') ? esc_attr($_GET['search-contact']) : ''; ?>">
							<input type="submit" id="search-submit" class="button" value="<?php esc_html_e( 'Search', 'mystickymenu');?>">
						</p>								
					</form>
				</div>
					
					<table border="1" class="responstable">
						<tr>
							<th style="width:1%"><?php esc_html_e( 'Bulk', 'mystickymenu' );?></th>
							<th><?php esc_html_e( 'ID', 'mystickymenu');?></th>
							<th><?php esc_html_e( 'Widget Name', 'mystickymenu');?></th>
							<th><?php esc_html_e( 'Name', 'mystickymenu');?></th>
							<th><?php esc_html_e( 'Email', 'mystickymenu');?></th>
							<th><?php esc_html_e( 'Phone', 'mystickymenu');?></th>
							<th><?php esc_html_e( 'Date', 'mystickymenu');?></th>
							<th><?php esc_html_e( 'URL', 'mystickymenu');?></th>
							<th style="width:11%"><?php esc_html_e( 'Delete', 'mystickymenu');?></th>
						</tr>
					<?php 
						$customPagHTML     	= "";
						$total_query     	= "SELECT count(*) FROM ".$table_name ." {$where_search} ORDER BY ID DESC";
						$total             	= $wpdb->get_var( $total_query );
						$items_per_page 	= 20;
						$page             	= ( isset( $_GET['cpage'] ) ) ? abs( (int) $_GET['cpage'] ) : 1;
						$offset         	= ( $page * $items_per_page ) - $items_per_page;
						$query 				= "SELECT * FROM " . $table_name  ." {$where_search} ORDER BY ID DESC LIMIT {$offset}, {$items_per_page}";
						$result         	= $wpdb->get_results( $query );
						$total_page         = ceil($total / $items_per_page);
						if($result){

							foreach ( $result as $res ) { ?>
								<tr>
									<td><input id="cb-select-80" class="cb-select-blk" type="checkbox" name="delete_message[]" value="<?php echo esc_attr($res->ID);?>"></td>
									<td><a href="<?php echo esc_url(admin_url( 'admin.php?page=my-sticky-menu-leads&id=' . $res->ID ));?>"><?php echo $res->ID;?></a></td>
									<td><a href="<?php echo esc_url(admin_url( 'admin.php?page=my-sticky-menu-leads&id=' . $res->ID ));?>"><?php echo $res->widget_name;?></a></td>
									<td><?php echo $res->contact_name;?></td>
									<td><?php echo $res->contact_email;?></td>
									<td><?php echo $res->contact_phone;?></td>
									<td><?php echo ( isset($res->message_date) ) ? $res->message_date : '-' ;?></td>
									<td>
										<?php if ( $res->page_link) :?>
										<a class="external-link" href="<?php echo esc_url($res->page_link);?>" target="_blank"><span class="dashicons dashicons-external"></span></a>
										<?php endif;?>
									</td> 
									
									<td>
										<input type="button" data-delete="<?php echo $res->ID;?>" class="mystickymenu-delete-entry" value="<?php esc_attr_e('Delete', 'mystickymenu');?>" />
									</td>
								</tr>
							<?php }
						} else { ?>
							<tr>
								<td colspan="8" align="center">
									<p class="mystickymenu-no-contact"> <?php esc_html_e('No Contact Form Leads Found!','mystickymenu');?>
									</p>
								</td>
							</tr>
						<?php }	?>
	
					</table>

					<?php if($total_page > 1){ ?>
						<div class="contactleads-pagination">			
							<?php 
							$big = 999999999; // need an unlikely integer			
							echo paginate_links( array(
								'base' => add_query_arg( 'cpage', '%#%' ),
								'format' => '',
								'current' => $page,
								'total' =>  $total_page
							) );?>
						</div>
					<?php }?>
				</form>
			</div>
		</div>

		<!--  -->
		<?php
	}
}



class MyStickyMenuFrontend
{

	public function __construct()
	{
		add_action( 'wp_head', array( $this, 'mysticky_build_stylesheet_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'mysticky_disable_at' ) );

		add_action('wp_ajax_stickymenu_contact_lead_form', array($this, 'stickymenu_contact_lead_form'));
		add_action('wp_ajax_nopriv_stickymenu_contact_lead_form', array($this, 'stickymenu_contact_lead_form'));
	}

	public function mysticky_build_stylesheet_content() {

		$mysticky_options = get_option( 'mysticky_option_name' );
		
		if (isset($mysticky_options['disable_css'])) {
			//do nothing
		} else {
			$mysticky_options['disable_css'] = false;
		}

		if  ($mysticky_options ['disable_css'] == false ) {

			echo '<style id="mystickymenu" type="text/css">';
			echo '#mysticky-nav { width:100%; position: static; }';
			echo '#mysticky-nav.wrapfixed { position:fixed; left: 0px; margin-top:0px;  z-index: '. $mysticky_options ['myfixed_zindex'] .'; -webkit-transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; -moz-transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; -o-transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $mysticky_options ['myfixed_opacity'] . ')"; filter: alpha(opacity=' . $mysticky_options ['myfixed_opacity'] . '); opacity:' . $mysticky_options ['myfixed_opacity'] / 100 . '; background-color: ' . $mysticky_options ['myfixed_bgcolor'] . ';}';
			
			echo '#mysticky-nav.wrapfixed .myfixed{ background-color: ' . $mysticky_options ['myfixed_bgcolor'] . '; position: relative;top: auto;left: auto;right: auto;}';
			
			if ( isset($mysticky_options ['myfixed_textcolor']) && $mysticky_options ['myfixed_textcolor'] != '' ) {
			echo '#mysticky-nav.wrapfixed ul li.menu-item a { color: ' . $mysticky_options ['myfixed_textcolor'] . ';}';
			}


			if  ($mysticky_options ['myfixed_disable_small_screen'] > 0 ){
			//echo '@media (max-width: '.$mysticky_options['myfixed_disable_small_screen'].'px) {#mysticky-nav.wrapfixed {position: static;} }';
			};
			if ( !isset( $mysticky_options['myfixed_cssstyle'] ) )  {
				echo '#mysticky-nav .myfixed { margin:0 auto; float:none; border:0px; background:none; max-width:100%; }';
			}
			if ( isset( $mysticky_options['myfixed_cssstyle'] ) && $mysticky_options['myfixed_cssstyle'] != '' )  {
				echo $mysticky_options ['myfixed_cssstyle'];
			}
			echo '</style>';
			$template_name = get_template();
			?>
			<style type="text/css">
				<?php if( $template_name == 'hestia' ) { ?>
					#mysticky-nav.wrapfixed {box-shadow: 0 1px 10px -6px #0000006b,0 1px 10px 0 #0000001f,0 4px 5px -2px #0000001a;}
					#mysticky-nav.wrapfixed .navbar {position: relative;background-color: transparent;box-shadow: none;}
				<?php } ?>
				<?php if( $template_name == 'shapely' ) { ?>
					#mysticky-nav.wrapfixed #site-navigation {position: relative;}
				<?php } ?>
				<?php if( $template_name == 'storefront' ) { ?>
					#mysticky-nav.wrapfixed > .site-header {margin-bottom: 0;}
					#mysticky-nav.wrapfixed > .storefront-primary-navigation {padding: 10px 0;}
				<?php } ?>
				<?php if( $template_name == 'transportex' ) { ?>
					#mysticky-nav.wrapfixed > .transportex-menu-full {margin: 0 auto;}
					.transportex-headwidget #mysticky-nav.wrapfixed .navbar-wp {top: 0;}
				<?php } ?>
				<?php if( $template_name == 'twentynineteen' ) { ?>
					#mysticky-nav.wrapfixed {padding: 10px;}
				<?php } ?>
				<?php if( $template_name == 'twentysixteen' ) { ?>
					#mysticky-nav.wrapfixed > .site-header {padding-top: 0;padding-bottom: 0;}
				<?php } ?>
				<?php if( $template_name == 'twentytwenty' ) { ?>
					#site-header {background: transparent;}
				<?php } ?>
			</style>
			<?php
		}
	}
	
	public function mystickymenu_google_fonts_url() {
		$welcomebar = get_option( 'mysticky_option_welcomebar' );
		
		$default_fonts = array('System Stack','Arial', 'Tahoma', 'Verdana', 'Helvetica', 'Times New Roman', 'Trebuchet MS', 'Georgia' );
		$fonts_url        = '';
		$fonts            = array();
		$font_args        = array();
		$base_url         =  "https://fonts.googleapis.com/css";		
		$fonts['family']['Lato'] = 'Lato:400,500,600,700';
		if ( isset($welcomebar['mysticky_welcomebar_font']) && $welcomebar['mysticky_welcomebar_font'] !='' && !in_array( $welcomebar['mysticky_welcomebar_font'], $default_fonts) ) {
			$fonts['family'][$welcomebar['mysticky_welcomebar_font']] = $welcomebar['mysticky_welcomebar_font'] . ':400,500,600,700';
		}
		if ( isset($welcomebar['mysticky_welcomebar_btnfont']) && $welcomebar['mysticky_welcomebar_btnfont'] !='' && !in_array( $welcomebar['mysticky_welcomebar_btnfont'], $default_fonts) ) {
			$fonts['family'][$welcomebar['mysticky_welcomebar_btnfont']] = $welcomebar['mysticky_welcomebar_btnfont'] . ':400,500,600,700';
		}
		
		/* Prepapre URL if font family defined. */
		if( !empty( $fonts['family'] ) ) {

			/* format family to string */
			if( is_array($fonts['family']) ){
				$fonts['family'] = implode( '|', $fonts['family'] );
			}

			$font_args['family'] = urlencode( trim( $fonts['family'] ) );

			if( !empty( $fonts['subsets'] ) ){

				/* format subsets to string */
				if( is_array( $fonts['subsets'] ) ){
					$fonts['subsets'] = implode( ',', $fonts['subsets'] );
				}

				$font_args['subsets'] = urlencode( trim( $fonts['subsets'] ) );
			}

			$fonts_url = add_query_arg( $font_args, $base_url );
		}
		
		return esc_url_raw( $fonts_url );
	}

	public function mystickymenu_script() {
		
		wp_enqueue_script( 'jquery' );
		
		$mysticky_options = get_option( 'mysticky_option_name' );
		
		if ( is_admin_bar_showing() ) {
			$top = "true";
		} else {
			$top = "false";
		}
		
		$welcomebar = get_option( 'mysticky_option_welcomebar' );		
		if ( isset($welcomebar['mysticky_welcomebar_enable']) && $welcomebar['mysticky_welcomebar_enable'] == 1 ) {
			wp_enqueue_style('google-fonts', $this->mystickymenu_google_fonts_url(),array(), MYSTICKY_VERSION );
		}
		
		if( !isset($mysticky_options['stickymenu_enable']) || isset($mysticky_options['stickymenu_enable']) && $mysticky_options['stickymenu_enable'] == 0){
			return;
		}
		// needed for update 1.7 => 1.8 ... will be removed in the future ()
		if (isset($mysticky_options['mysticky_active_on_height_home'])) {
			//do nothing
		} else {
			$mysticky_options['mysticky_active_on_height_home'] = $mysticky_options['mysticky_active_on_height'];
		}


		if  ($mysticky_options['mysticky_active_on_height_home'] == 0 ) {
			$mysticky_options['mysticky_active_on_height_home'] = $mysticky_options['mysticky_active_on_height'];
		}


		if ( is_front_page() && is_home() ) {

			$mysticky_options['mysticky_active_on_height'] = $mysticky_options['mysticky_active_on_height_home'];

		} elseif ( is_front_page()){

			$mysticky_options['mysticky_active_on_height'] = $mysticky_options['mysticky_active_on_height_home'];

		}
		wp_register_script('detectmobilebrowser', plugins_url( 'js/detectmobilebrowser.js', __FILE__ ), array('jquery'), MYSTICKY_VERSION, true);
		wp_enqueue_script( 'detectmobilebrowser' );
		
		wp_register_script('mystickymenu', plugins_url( 'js/mystickymenu.min.js', __FILE__ ), array('jquery'), MYSTICKY_VERSION, true);
		wp_enqueue_script( 'mystickymenu' );

		$myfixed_disable_scroll_down = isset($mysticky_options['myfixed_disable_scroll_down']) ? $mysticky_options['myfixed_disable_scroll_down'] : 'false';
		$mystickyTransition = isset($mysticky_options['myfixed_fade']) ? $mysticky_options['myfixed_fade'] : 'fade';
		$mystickyDisableLarge = isset($mysticky_options['myfixed_disable_large_screen']) ? $mysticky_options['myfixed_disable_large_screen'] : '0';

		$mystickyClass = ( $mysticky_options['mysticky_class_id_selector'] != 'custom') ? '.menu-' . $mysticky_options['mysticky_class_id_selector'] .'-container' : $mysticky_options['mysticky_class_selector'];
		
		if ( $mysticky_options['mysticky_class_id_selector'] != 'custom' ) {
			$template_name = get_template();
			switch( $template_name ){
				case 'ashe':
					$mystickyClass = '#main-nav';
					break;
				case 'astra':
				case 'hello-elementor':
				case 'sydney':
				case 'twentysixteen':
					$mystickyClass = 'header.site-header';
					break;
				case 'generatepress':
					$mystickyClass = 'nav.main-navigation';
					break;
				case 'transportex':
					$mystickyClass = '.transportex-menu-full';
					break;
				case 'hestia':
				case 'neve':				
					$mystickyClass = 'header.header';
					break;
				case 'mesmerize':
					$mystickyClass = '.navigation-bar';
					break;
				case 'oceanwp':
					$mystickyClass = 'header#site-header';
					break;
				case 'shapely':
					$mystickyClass = '#site-navigation';
					break;
				case 'storefront':
					$mystickyClass = '.storefront-primary-navigation';
					break;
				case 'twentynineteen':
					$mystickyClass = '#site-navigation';
					break;				
				case 'twentyseventeen':
					$mystickyClass = '.navigation-top';
					break;
				default:
					break;
			}
		}
		

		$mysticky_translation_array = array(
		    'mystickyClass' 			=> $mystickyClass,
			'activationHeight' 			=> $mysticky_options['mysticky_active_on_height'],
			'disableWidth' 				=> $mysticky_options['myfixed_disable_small_screen'],
			'disableLargeWidth' 		=> $mystickyDisableLarge,
			'adminBar' 					=> $top,
			'device_desktop'			=> true,
			'device_mobile' 			=> true,
			'mystickyTransition' 		=> $mystickyTransition,
			'mysticky_disable_down' 	=> $myfixed_disable_scroll_down,


		);
		wp_localize_script( 'mystickymenu', 'option', $mysticky_translation_array );		
	}

	public function mysticky_disable_at() {


		$mysticky_options = get_option( 'mysticky_option_name' );

		$mysticky_disable_at_front_home = isset($mysticky_options['mysticky_disable_at_front_home']);
		$mysticky_disable_at_blog = isset($mysticky_options['mysticky_disable_at_blog']);
		$mysticky_disable_at_page = isset($mysticky_options['mysticky_disable_at_page']);
		$mysticky_disable_at_tag = isset($mysticky_options['mysticky_disable_at_tag']);
		$mysticky_disable_at_category = isset($mysticky_options['mysticky_disable_at_category']);
		$mysticky_disable_at_single = isset($mysticky_options['mysticky_disable_at_single']);
		$mysticky_disable_at_archive = isset($mysticky_options['mysticky_disable_at_archive']);
		$mysticky_disable_at_search = isset($mysticky_options['mysticky_disable_at_search']);
		$mysticky_disable_at_404 = isset($mysticky_options['mysticky_disable_at_404']);
		$mysticky_enable_at_pages = isset($mysticky_options['mysticky_enable_at_pages']) ? $mysticky_options['mysticky_enable_at_pages'] : '';
		$mysticky_enable_at_posts = isset($mysticky_options['mysticky_enable_at_posts']) ? $mysticky_options['mysticky_enable_at_posts'] : '';

		// Trim input to ignore empty spaces
		$mysticky_enable_at_pages_exp = array_map('trim', explode(',', $mysticky_enable_at_pages));
		$mysticky_enable_at_posts_exp = array_map('trim', explode(',', $mysticky_enable_at_posts));




		if ( is_front_page() && is_home() ) { /* Default homepage */

			if ( $mysticky_disable_at_front_home == false ) {
				$this->mystickymenu_script();
			}
		} elseif ( is_front_page()){ /* Static homepage */

			if ( $mysticky_disable_at_front_home == false ) {
				$this->mystickymenu_script();
			}

		} elseif ( is_home()){ /* Blog page */

			if ( $mysticky_disable_at_blog == false ) {
				$this->mystickymenu_script();
			}

		} elseif ( is_page() ){ /* Single page*/

			if ( $mysticky_disable_at_page == false ) {
				$this->mystickymenu_script();
			}
			if ( is_page( $mysticky_enable_at_pages_exp  )  ){
				$this->mystickymenu_script();
			}

		} elseif ( is_tag()){ /* Tag page */

			if ( $mysticky_disable_at_tag == false ) {
				$this->mystickymenu_script();
			}

		} elseif ( is_category()){ /* Category page */

			if ( $mysticky_disable_at_category == false ) {
				$this->mystickymenu_script();
			}

		} elseif ( is_single()){ /* Single post */

			if ( $mysticky_disable_at_single == false ) {
				$this->mystickymenu_script();
			}

			if ( is_single( $mysticky_enable_at_posts_exp  )  ){
				$this->mystickymenu_script();
			}

		} elseif ( is_archive()){ /* Archive */

			if ( $mysticky_disable_at_archive == false ) {
				$this->mystickymenu_script();
			}

		} elseif ( is_search()){ /* Search */

			if ( $mysticky_disable_at_search == false ) {
				$this->mystickymenu_script();
			}

		} elseif ( is_404()){ /* 404 */

			if ( $mysticky_disable_at_404 == false ) {
				$this->mystickymenu_script();
			}
		}

	}

	/**
	 * Mysticky Menu: Contact Form Lead Submission Function
	 * DATE : 2022-08-04
	 * */

	public function stickymenu_contact_lead_form(){
		global $wpdb;
		global $wp;
		$stickymenus_widgets = get_option( 'mystickymenu-welcomebars' );
		$errors = array();
		$element_widget_no = $_POST['widget_id'];

		$element_widget_name = (isset($stickymenus_widgets[$element_widget_no]) && $stickymenus_widgets[$element_widget_no] != '' ) ? $stickymenus_widgets[$element_widget_no]  : '';

		$flag = true;
		if( isset($element_widget_name) && $element_widget_name != ''){
			if( !isset($_POST['contact_name']) || $_POST['contact_name'] == ''){
				$error = array(
					'key' => "contact-form-name",
					'message' => __( "This field is required", "mystickymenu" )
				);
				$errors[] = $error; 
				$flag = false;
			}else{
				$contact_lists_table = $wpdb->prefix . 'mystickymenu_contact_lists';
				$postArr = $_POST;	

				if( $element_widget_no == 0 ){
					$element_widget_no = '';
				}

				$welcomebar = get_option( 'mysticky_option_welcomebar' . $element_widget_no ); 
				
				foreach( $postArr as $key => $val ){
					if( $key != 'action' && $key != 'widget_id' && $key != 'save_form_lead' && $key != 'wpnonce'){
						$params[$key] = (isset($val) && $val != '') ? esc_sql( sanitize_text_field($val) ) : '';
					}
				}

				$params["widget_name"]  = esc_sql( sanitize_text_field($element_widget_name));
				$params["message_date"] = date('Y-m-d H:i:s');
				$params["contact_email"] = (isset($params["contact_email"]) && $params["contact_email"] != '' ) ? $params["contact_email"] : '';
				
				if( isset($params) && !empty($params) ){
					$wpdb->insert($contact_lists_table, $params);
					die;
				}
				
				
			}
		}

		if( $flag != true ){
			echo json_encode(array("status" => 0, "error" => 1, "errors" => $errors, "message" => $errors['message']));
		}
		die;
	}

}

if( is_admin() ) {
	require_once 'mystickymenu-affiliate.php';
} 

new MyStickyMenuBackend();
new MyStickyMenuFrontend();

register_activation_hook( __FILE__,  'mystickymenu_activate'  );
	
function mystickymenu_activate() {
	update_option( 'update_mysticky_version_2_5_7', true );

	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();
	
	$contact_lists_table = $wpdb->prefix . 'mystickymenu_contact_lists';
		
	if ($wpdb->get_var("show tables like '$contact_lists_table'") != $contact_lists_table) {

		$contact_lists_table_sql = "CREATE TABLE $contact_lists_table (
			ID int(11) NOT NULL AUTO_INCREMENT,
			contact_name varchar(255) NULL,
			contact_phone varchar(255) NULL,
			contact_email varchar(255) NULL,
			widget_name varchar(255) NULL,
			page_link varchar(522) NULL,
			message_date DATETIME NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (ID)
		) $charset_collate;";
		dbDelta($contact_lists_table_sql);
	}
}


add_action( 'admin_init' , 'mystickymenu_admin_init' );

function mystickymenu_admin_init(){

	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();
	
	$contact_lists_table = $wpdb->prefix . 'mystickymenu_contact_lists';
		
	if ($wpdb->get_var("show tables like '$contact_lists_table'") != $contact_lists_table) {

		$contact_lists_table_sql = "CREATE TABLE $contact_lists_table (
			ID int(11) NOT NULL AUTO_INCREMENT,
			contact_name varchar(255) NULL,
			contact_phone varchar(255) NULL,
			contact_email varchar(255) NULL,
			widget_name varchar(255) NULL,
			page_link varchar(522) NULL,
			message_date DATETIME NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (ID)
		) $charset_collate;";
		dbDelta($contact_lists_table_sql);
	}
}
