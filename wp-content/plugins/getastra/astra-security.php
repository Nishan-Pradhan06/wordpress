<?php

/*
  Plugin Name: Astra Security Suite – Firewall & Malware Scan
  Plugin URI: https://www.getastra.com/
  Description: Website Security Simplified
  Version: 0.2
  Author: Astra Security
  Author URI: https://www.getastra.com/
 */


defined('ABSPATH') or ('Plugin file cannot be accessed directly.');
define('ASTRA_PLUGIN_PATH', plugin_dir_path(__FILE__));

if (!class_exists('Astra_Wp')) {
    class Astra_Wp
    {
        protected $firewall_path = "";
        protected $autoload_file = "Astra.php";
        protected $config_file = "astra-config.php";
        protected $install_file = "astra-install-class.php";
    
        /**
         * User login failed
         *
         * @param string $username User name
         *
         * @return bool
         */
        public function cz_action_user_login_failed($username)
        {
            include_once $this->firewall_path . 'libraries/API_connect.php';
            $client_api = new Api_connect();
            $ret = $client_api->send_request("has_loggedin", array("username" => $username, "success" => 0,), "wordpress");

            return true;
        }
    
        /**
         * User login successful
         *
         * @param int $user_info User info
         * @param int $u         User object
         *
         * @return bool
         */
        public function cz_action_user_login_success($user_info, $u)
        {
            include_once $this->firewall_path . 'libraries/API_connect.php';

            $user = $u->data;

            unset($user->user_pass, $user->ID, $user->user_nicename, $user->user_url, $user->user_registered, $user->user_activation_key, $user->user_status);

            if (current_user_can('manage_options')) {
                $user->admin = 1;
            }

            $client_api = new Api_connect();
            $ret = $client_api->send_request("has_loggedin", array("user" => $user, "success" => 1,), "wordpress");

            return true;
        }
        
        /**
         *  Url of the file
         *
         * @return string
         */
        protected function api_file_url()
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $current_url = "https://";
            } else {
                $current_url = "http://";
            }

            $current_url .= str_replace(realpath($_SERVER["DOCUMENT_ROOT"]), $_SERVER['HTTP_HOST'], realpath(dirname(__FILE__)));

            return $current_url;
        }
    
        /**
         * Astra_Wp constructor.
         */
        public function __construct()
        {
            add_action('wp_ajax_nopriv_Astra_install', array( $this,'astra_install' ));
            add_action('wp_ajax_Astra_install', array( $this,'astra_install' ));

            $this->firewall_path = ASTRA_PLUGIN_PATH . '/astra/';
            if (file_exists($this->firewall_path . $this->autoload_file)) {
                if (!is_admin()) {
                    include_once $this->firewall_path . $this->autoload_file;
                    $astra = new Astra();
                } else {
                    include_once 'astra-admin.php';
                }
                add_action('wp_login', array(&$this, 'cz_action_user_login_success'), 10, 2);
                add_action('wp_login_failed', array(&$this, 'cz_action_user_login_failed'), 10, 1);
            } else {
                //$this->setup_astra();
                include_once 'astra-admin.php';
            }
        }
    
    
        /**
         * Astra class initialization
         *
         * @return void
         */
        public function astra_install()
        {
            //echo ASTRA_PLUGIN_PATH;
            if (file_exists(ASTRA_PLUGIN_PATH . $this->install_file)) {
                include_once ASTRA_PLUGIN_PATH . $this->install_file;
                $installAstra = new Astra_Install_Class();
                $installAstra->index();
                wp_die();
            }
        }
    }

    new Astra_Wp;
}
