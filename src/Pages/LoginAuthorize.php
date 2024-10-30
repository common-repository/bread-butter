<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages;

use \BreadButter_WP_Plugin\Api\SettingsApi;
use \BreadButter_WP_Plugin\Base\BaseController;
use \BreadButter_WP_Plugin\Api\Callbacks\AdminCallbacks;
use \BreadButter_WP_Plugin\Api\IdPx\Client;
use WP_Error;

class LoginAuthorize extends BaseController {

    public $settings;
    public $callbacks;
    public $token;
    public $response = array();
    public $error_code;
    public $valid_session;

    const BB_SESSION_COOKIE = "BREADBUTTER_SESSION_COOKIE";
    const BB_APP_IDS = ["6128183c5a20bd2cb74d3998", "61200aeca12cb4cc7757aed8", "60a5637a50471a35730148de"];

    public function register() {
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();

        $rest = array(
            array(
                'url' => 'authorize',
                'method' => 'GET',
                'callback' => array($this, 'handleCode')
            )
        );
        $this->settings->addRestAPI($rest)->register();
    }

    public static function cleanToken($token) {
        $reg = '/^[a-zA-Z0-9\-]+$/';
        if (preg_match($reg, $token)) {
            return true;
        }
        return false;
    }

    private function setBreadButterSession($logonClient, $app_id, $token) {
        if (in_array($app_id, LoginAuthorize::BB_APP_IDS)) {
            $schema = explode("//", get_home_url());
            $domain = explode("/", $schema[1]);
            $domain = explode(':', $domain[0])[0];
            $this->logs("getAuthenticationCallback:");
            $location = $logonClient->getAuthenticationCallback($token);
            $this->logs($location);
            $url_components = parse_url($location);
            parse_str($url_components['query'], $params);
            $session_data = $params['data'];

            $domains = explode('.', $domain);
            $a = array_slice($domains, -2, 2, true);
            $sub_domain = implode('.', $a);
            $time = time() + (60 * 60 * 2);
            setcookie(LoginAuthorize::BB_SESSION_COOKIE, $session_data, $time, "/", $sub_domain);
            $this->logs("BB_SESSION set.");
        } else {
            $this->logs('IDs not in BB_APP_IDS');
        }
    }

    public function handleCode($request) {
        //http://localhost:8000/wp-json/breadbutter-connect/v1/authorize
        //wp_redirect( $this->redirectLocation() );
        //exit;

        $error_wp = false;
        $parameters = $request->get_query_params();
        $app_id = get_option('logon_app_id');
        $app_secret = get_option('logon_app_secret');
        $api_path = get_option('logon_api_path');

        $callback_url = get_option('breadbutter_callback_url');
        $local_callback = get_home_url() . '/wp-json/breadbutter-connect/v1/authorize';

        $token = $parameters['authentication_token'];
        $logged_in = false;

        $this->logs("login started w/ token: " . $token);

        if (empty($token)) {
            $error_wp = new WP_Error( 'no_token', 'Invalid token');
        } else if (!self::cleanToken($token)) {
            $error_wp = new WP_Error( 'invalid_token', 'Invalid token', $token);
        } else {
            if (empty($app_id) || empty($app_secret) || empty($api_path)) {
                $error_wp = new WP_Error('no_config', 'Invalid config');
            } else {
                if (!strpos(get_home_url(), $_SERVER['HTTP_HOST'])) {
                    wp_redirect(get_home_url() . $_SERVER['REQUEST_URI']);
                }

                $logonClient = new Client(array(
                    'api_path' => $api_path,
                    'app_id' => $app_id,
                    'app_secret' => $app_secret
                ));

                $response = $logonClient->getAuthentication($token);
                $valid_session = false;
                if ($response['body'] && isset($response['body']['error'])) {
                    $error_code = json_encode($response['body']['error']);
                    $error_wp = new WP_Error('error_breadbutter', 'Error response', array('error' => $error_code));
                } else if (!empty($response['body'])) {
                    $valid_session = $response['body'];
                    if ($valid_session['auth_success'] && !empty($valid_session['auth_data'])) {
                        $auth_data = $valid_session['auth_data'];
                        $email = sanitize_email($auth_data['email_address']);
                        $user = get_user_by('email', $email);
                        $profile_image = $auth_data['profile_image_url'];
                        if (!$user) {
                            $logged_in = $this->registerUser($auth_data, $profile_image);
                            $this->logs("login success, new user created and logged in.");
                        } else {
                            $logged_in = $this->loginUser($user->ID, $profile_image);
                            $this->logs("login success, user logged in.");
                        }

                        try {
                            $this->setBreadButterSession($logonClient, $app_id, $token);
                        } catch (Exception $exception) {
                            $this->logs(print_r($exception, 1));
                        }
                        // $error_wp = true;
                    } else if (!empty($valid_session['auth_error'])) {
                        $error = $valid_session['auth_error'];
                        $error_wp = new WP_Error('auth_fail_breadbutter', 'Authentication Failed', array('error' => $error));
                    } else if (empty($valid_session['auth_data'])) {
                        $error_wp = new WP_Error('empty_breadbutter', 'Empty Authentication Data', $response);
                    } else {
                        $error_wp = new WP_Error('invalid_breadbutter', 'Invalid response', $response);
                    }
                } else {
                    $error_wp = new WP_Error('empty_response', 'Empty response', $response);
                }
            }
        }
        if ($error_wp !== false) {
            $this->logs("Errors:");
            $this->logs(print_r($error_wp, 1));
            setcookie('bb_auth_failed', true, 0, COOKIEPATH, COOKIE_DOMAIN);
            if (strcmp($api_path, "https://api-devlab.breadbutter.io") == 0) {
                return $error_wp;
            } else {
                wp_redirect(get_home_url());
                exit;
            }
        }


        $url = $this->redirectLocation();
        if (isset($valid_session['options'])) {
            $options = $valid_session['options'];
            if (is_string($options['destination_url']) && !empty($options['destination_url']))  {
                $d_url = explode('?', $options['destination_url'])[0];
                if (strcasecmp($callback_url, $d_url) && strcasecmp($local_callback, $d_url)) {
                    $url = $options['destination_url'];
                }
            }
        }



        header("Content-Type: text/html");
        if ($logged_in) {
            do_action( 'wp_login', $logged_in->user_login, $logged_in, false);
        }
        if (current_user_can('administrator')) {
            // Go to admin page.
            $this->logs("login complete, admin role.");
            wp_redirect('/wp-admin');
        }
        else {
            $this->logs("login complete, redirect to " . $url);
            wp_redirect($url);
        }
        exit;
    }

    public function redirectLocation() {
        global $current_user;
        return ( is_array( $current_user->roles ) && in_array( 'administrator', $current_user->roles ) ) ? admin_url() : site_url();
    }


    public function registerUser($data, $profile_image_url) {
        $new_pass = wp_generate_password();
        $new_hash = wp_hash_password($new_pass);
        $name = $data['first_name'] . ' ' . $data['last_name'];

        $user = array();
        $user['user_email'] = sanitize_email($data['email_address']);
        $user['user_login'] = sanitize_user($data['email_address']);
        $user['first_name'] = sanitize_text_field($data['first_name']);
        $user['last_name'] = sanitize_text_field($data['last_name']);
        $user['display_name'] = sanitize_text_field($name);
        $user['user_nicename'] = sanitize_text_field($name);
        $user['user_registered'] = current_time('mysql', 1);
        $user['user_pass'] = $new_hash;
        $user_id = wp_insert_user($user);

        return $this->loginUser($user_id, $profile_image_url);

    }

    public function uploadAvatarFromUrl($user_id, $profile_image_url) {
        if (empty($profile_image_url)) {
            return;
        }
        $imgdata = file_get_contents($profile_image_url);
        $mime_type = image_type_to_mime_type(exif_imagetype($profile_image_url));
        $type_file = explode('/', $mime_type);
        $avatar = time() . '.' . $type_file[1];

        var_dump($mime_type);

        $uploaddir = wp_upload_dir();
        $myDirUrl = $uploaddir["url"];

        file_put_contents($uploaddir["path"].'/'.$avatar,$imgdata);

        $filename = $myDirUrl.'/'.basename( $avatar );
        $wp_filetype = wp_check_filetype(basename($filename), null );
        $uploadfile = $uploaddir["path"] .'/'. basename( $filename );


        $attachment = array(
            "post_mime_type" => $wp_filetype["type"],
            "post_title" => preg_replace("/\.[^.]+$/", "" , basename( $filename )),
            "post_content" => "",
            "post_status" => "inherit",
            'guid' => $uploadfile,
        );

        require_once(ABSPATH . '/wp-load.php');
        require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
        require_once(ABSPATH . 'wp-admin' . '/includes/image.php');

        $attachment_id = wp_insert_attachment( $attachment, $uploadfile );
        $attach_data = wp_generate_attachment_metadata( $attachment_id, $uploadfile );
        wp_update_attachment_metadata( $attachment_id, $attach_data );

        update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$user_id);
        update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
    }

    public function loginUser($user_id, $profile_image_url) {
        // $this->uploadAvatarFromUrl($user_id, $profile_image_url);
        $this->logs("try to login user: " . $user_id);
        $user = get_user_by('id', $user_id);

        //var_dump($user);
        if ($user) {
            $this->logs("prepare for login: " . $user->user_login);
            $secure_cookie = is_ssl();
            $secure_cookie = apply_filters( 'secure_signon_cookie', $secure_cookie, $user->user_pass);
            wp_clear_auth_cookie();
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id, true, $secure_cookie);
            update_user_meta( $user->ID, 'user_active_status', 'active' );

            $this->logs($user_id . " setup for cookie: " . COOKIEHASH);
            $this->logs('wordpress_logged_in_' . COOKIEHASH . ": " . print_r($this->getcookie('wordpress_logged_in_' . COOKIEHASH), 1));
            // do_action( 'wp_login', $user->user_login, $user, false);
        }
        return $user;
        // global $current_user;
        // var_dump($current_user);


    }
}