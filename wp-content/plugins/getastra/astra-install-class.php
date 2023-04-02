<?php

defined('ABSPATH') or ('Plugin file cannot be accessed directly.');

class Astra_Install_Class
{
    /**
     * Public parameters
     *
     * @param array $errors
     * @param array $activity
     * @param array  $licence
     * @param string    $token
     * @param string    $keys
     * @param string $siteKey
     * @param string $apiUrl
     */
    
    public $errors = array();
    public $activity = array();
    public $licence = array();
    public $token = '';
    public $keys = '';
    public $siteKey = '';
    public $apiUrl = '';
    
    
    /**
     * Function admin pages
     *
     * @return void
     */
    public function index()
    {
        if ($this->astra_core_module_installed_status()) {
            $message = __('Astra Security is already installed');
            $this->activity[] = $message;
            $status = $this->prepare_response(true);
            echo json_encode($status);
        }

        $params = $_REQUEST;

        if (!isset($params['api_url'])) {
            $message = 'API url not found in the POST request';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'api_url_missing', $message);
            echo json_encode($status);
        }
    
        $this->apiUrl = esc_url_raw(trim($params['api_url']));

        if (empty($this->apiUrl)) {
            $message = 'API url is not sent';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'api_url_invalid', $message);
            echo json_encode($status);
        }
    
        
        if ($this->is_valid_api_url($this->apiUrl)) {
            $message = 'API url is not valid';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'api_url_invalid', $message);
            echo json_encode($status);
        }
        
        if (!isset($params['site_key'])) {
            $message = 'Site key not found in the POST request';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'site_key_missing', $message);
            echo json_encode($status);
        }
    
        if (!ctype_alnum($params['site_key'])) {
            $message = 'Invalid site key found in the POST request';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'invalid_site_key', $message);
            echo json_encode($status);
        }
        
        $this->siteKey = trim($params['site_key']);

        if (empty($this->siteKey)) {
            $message = 'Site key is not valid';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'site_key_invalid', $message);
            echo json_encode($status);
        }

        if (!isset($params['keys'])) {
            $message = 'Astra encryption keys not found in the POST request';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'keys_missing', $message);
            echo json_encode($status);
        }
    
        if (!ctype_alnum($params['keys'])) {
            $message = 'Invalid Astra encryption keys found in the POST request';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'invalid_enc_keys', $message);
            echo json_encode($status);
        }

        $this->keys = trim($params['keys']);

        if (empty($this->keys)) {
            $message = 'Astra encryption keys are empty';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'keys_invalid', $message);
            echo json_encode($status);
        }

        $this->licence = $this->get_security_code();

        if (!is_array($this->licence)) {
            $message = 'POST.key does not contain encryption keys';
            $this->activity[] = $message;
            $status = $this->prepare_response(false, 'keys_failed', $message);
            echo json_encode($status);
        }

        $this->validate();

        if (!empty($this->errors)) {
            $message = 'Astra installation failed';
            $this->activity = array_merge($this->activity, $this->errors);
            $status = $this->prepare_response(false, 'install_failed', $message);
            echo json_encode($status);
        } else {
            $this->download_astra_zip();
            $this->final_response();
        }
    }
    
    
    public function is_valid_api_url($url)
    {
        if (strpos('getastra.com', parse_url($url)['host']) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return response to API
     *
     * @return void
     */
    public function final_response()
    {
        if (!empty($this->errors)) {
            $message = 'Astra installation failed';
            $this->activity = array_merge($this->activity, $this->errors);
            $status = $this->prepare_response(false, 'install_failed', $message);
            echo json_encode($status);
        } else {
            $message = 'Astra is installed';
            $this->activity[] = $message;
            $status = $this->prepare_response(true);
            echo json_encode($status);
        }
    }

    /**
     * Getting the codes from the response
     *
     * @return null or array
     */
    public function get_security_code()
    {
        include_once ASTRA_PLUGIN_PATH . 'encryption.php';
        $crypto = new Czar_Astra_Model_Encryption();
        $encryptedData = $crypto->decrypt($this->keys, $this->get_site_token());
        //@codingStandardsIgnoreStart
        $revGzDflateKey = gzinflate($encryptedData);
        //@codingStandardsIgnoreEnd
        $revJsonKey = json_decode($revGzDflateKey, true);
        return $revJsonKey;
    }

    /**
     * Checking required componenets installed/enabled on the client server or not
     *
     * @return void
     */
    public function validate()
    {
        if (version_compare(phpversion(), '5.3.0', '<')) {
            $this->save_error('min_req_php_version', "PHP version must be 5.3 and above");
        }

        if (!extension_loaded('zip')) {
            $this->save_error('min_req_zip_missing', "Please install ZIP extension on server");
        }

        if (!extension_loaded('pdo')) {
            $this->save_error("min_req_pdo_missing", "Please install PDO extension");
        }

        if (!extension_loaded('pdo_sqlite')) {
            $this->save_error("min_req_pdo_sqlite_missing", "Please install pdo_sqlite extension");
        }
    }

    /**
     * Download the zip package from the astra server and install on to client site
     *
     * @return bool
     */
    public function download_astra_zip()
    {
        $fileName = "secure-" . $this->siteKey . ".zip";

        $astraZipPath = ASTRA_PLUGIN_PATH . '/' . $fileName;
        //$validator = new Zend_Validate_File_Exists();
        if (!file_exists($astraZipPath)) {
            $dataArray = array();
            $dataArray['client_key'] = $this->licence['client_key'];
            $dataArray['api'] = "download_package";
            $str = serialize($dataArray);
            include_once ASTRA_PLUGIN_PATH . 'encryption.php';
            $crypto = new Czar_Astra_Model_Encryption();
            $encryptedData = $crypto->encrypt($str, $this->licence['secret_key']);
            
            $postdata = http_build_query(
                array(
                    'encRequest' => $encryptedData,
                    'access_code' => $this->licence['access_key'],
                )
            );
    
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded;'
                ),
                'body' => $postdata,
                'sslverify' => add_filter('https_local_ssl_verify', '__return_true')
            );
    
    
            $result = wp_remote_post($this->apiUrl, $args);
    
            $zipFile = wp_remote_retrieve_body($result);
    
            if (is_writable(dirname($astraZipPath))) {
                $dlHandler = fopen($astraZipPath, 'w');
                if (!fwrite($dlHandler, $zipFile)) {
                    return false;
                }
                
                fclose($dlHandler);
                if ($this->is_valid_zip($astraZipPath)) {
                    if ($this->astra_zip_open($astraZipPath)) {
                        $this->delete($astraZipPath);
                    }
                }
            } else {
                $this->save_error('install_folder_writable', 'Install folder is not writable');
            }
        } else {
            $this->save_error('update_file_exists', 'Unable to write the file. Reason: file already exists.');
            return true;
        }
    }
    
    
    /**
     * Extract the zip package and install on to client site
     *
     * @param string $astraZipPath Path of the zip file
     *
     * @return bool
     */
    public function astra_zip_open($astraZipPath)
    {
        $zip = new ZipArchive;

        if ($zip->open($astraZipPath) === true) {
            //@codingStandardsIgnoreStart
            $extractTo = dirname($astraZipPath);
            //@codingStandardsIgnoreEnd
            $this->activity[] = 'Will extract Update to:' . $extractTo;
            //@codingStandardsIgnoreStart
            if (is_writable($extractTo)) {
                //@codingStandardsIgnoreEnd
                $extracted = $zip->extractTo($extractTo);
                $zip->close();
                if ($extracted) {
                    $this->activity[] = 'ZIP successfully extracted';
                    return true;
                } else {
                    $this->save_error('zip_extract_failed', 'ZIP extraction not successful');
                    return false;
                }
            } else {
                $this->save_error('Folder is not writable: ' . $extractTo);
                return false;
            }
        }

        $this->save_error('zip_open_failed', 'Unable to open update zip File');
        return false;
    }


    /**
     * Delete the zip package after install
     *
     * @param string $astraZipPath Path of the zip file
     *
     * @return bool
     */
    public function delete($astraZipPath)
    {
        //@codingStandardsIgnoreStart
        if (file_exists($astraZipPath)) {
            if (unlink($astraZipPath)) {
                //@codingStandardsIgnoreEnd
                $this->activity[] = 'Just deleted astra zip';
                return true;
            } else {
                $this->save_error('zip_delete', 'File exists but unable to delete:' . $astraZipPath);
                return false;
            }
        } else {
            // Not required to delete as file doesn't exist in the first place
            return true;
        }
    }

    /**
     * Checking whether downloaded zip package is valid or not
     *
     * @param string $fp Path of the zip file
     *
     * @return bool
     */
    public function is_valid_zip($fp)
    {
        $zip = new ZipArchive;
        $res = $zip->open($fp, ZipArchive::CHECKCONS);
        if ($res !== true) {
            switch ($res) {
            case ZipArchive::ER_NOZIP:
                $this->save_error('zip_nozip', 'Not a Zip');
                $ret = false;
                break;
            case ZipArchive::ER_INCONS:
                $this->save_error('zip_incons', 'Consistency check failed');
                $ret = false;
                break;
            case ZipArchive::ER_CRC:
                $this->save_error('zip_crc', 'Error with CRC');
                $ret = false;
                break;
            default:
                $this->save_error('zip_checksum', 'Checksum Failed');
                $ret = false;
                break;
            }

            if ($ret) {
                $zip->close();
            }

            return $ret;
        } else {
            $this->activity[] = 'Update file is a valid ZIP';
            return true;
        }
    }

    /**
     * Checking whether returned token is sha1 encrypted or not
     *
     * @param string $str If the string is a valid sha1 hash
     *
     * @return bool
     */
    public function isSha1($str)
    {
        return (bool)preg_match('/^[0-9a-f]{40}$/i', $str);
    }
    
    /**
     * Prepare response to API
     *
     * @param string $status    Status of API call
     * @param string $errorCode Error code
     * @param string $message   Status message
     *
     * @return array
     */
    public function prepare_response($status, $errorCode = '', $message = '')
    {
        if (!empty($errorCode) || !empty($message)) {
            $this->save_error($errorCode, $message);
        }

        if (!empty($this->errors)) {
            $lastError = $this->errors[count($this->errors) - 1];
        } else {
            $lastError = array('', '');
        }

        $response = array(
            "success" => $status,
            "error_code" => $lastError[0],
            "error" => $lastError[1],
            "errors" => $this->errors,
            "activity" => $this->activity
        );
        //return $response;
        
        echo json_encode($response);
        wp_die();
    }
    
    /**
     * Set the errorCode
     *
     * @param string $errorCode Error code of the API call
     * @param string $message   Status message
     *
     * @return bool
     */
    public function save_error($errorCode, $message)
    {
        $this->errors[] = array($errorCode, $message);
        return true;
    }

    /**
     * Checks whether astra core module is installed or not
     *
     * @return bool
     */
    public function astra_core_module_installed_status()
    {
        $firewall_path = plugin_dir_path(__FILE__). 'astra/';
        //@codingStandardsIgnoreStart
        if (is_dir($firewall_path)) {
            return true;
        } else {
            return false;
        }
        //@codingStandardsIgnoreEnd
    }
    
    /**
     * Get site token
     *
     * @return string
     */
    public function get_site_token()
    {
        $token_db = get_option('astra_security_install_token', false);
        
        if ($token_db === false) {
            $token_db = ''; //get unique random token via php
            add_option('astra_security_instal_token', $token_db);
        }
        
        $dbname = DB_NAME.$token_db;
        $key = site_url('/');
        return sha1($dbname.$key.__DIR__);
    }
}
