<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages;

use \BreadButter_WP_Plugin\Base\BaseController;

class LoginForm extends BaseController {

    public static $index = 0;

    public function register() {
        add_action('login_form', array($this, 'addLoginFormButtons'));
        add_action('register_form', array($this, 'addLoginFormButtons'));
        add_action('login_form', array($this, 'addLogonScripts'));
        add_action('register_form', array($this, 'addLogonScripts'));
    }

    public function addLogonScripts() {
        echo self::getLogonScripts(self::$index);
    }

    public function addLoginFormButtons() {
        echo self::getRenderedLoginButtons()['content'];
    }

    public static function getLogonScripts($id = false) {
        if ($id === false) {
            $id = self::$index;
        }

        $ret = '';
        $app_id = get_option('logon_app_id');
        $api_path = get_option('logon_api_path');
        $theme_style = get_option('breadbutter_button_theme');
        $app_secret = get_option('logon_app_secret');

        if (!empty($app_id) && !empty($api_path) && !empty($app_secret)) {
            // Keep it disable for now.
            $use_widget_instead = false;//get_option('breadbutter_use_widget_instead_wp_login');
            $script = '';
            // WP login page with BB integration.
            if ($use_widget_instead) {
                $script = "
                    <style>
                        #login { display: none;}
                        .breadbutter-popup { top: calc(50% - 173px) !important; right: calc(50% - 173px) !important;}
                        .breadbutter-popup.bb-mobile-device { top: 10px !important; right: 0 !important;}
                    </style>
                    <script>
                        BreadButter.config.onFormClose = function() {
                            document.querySelector('#login').style.display = 'block';
                            document.querySelector('#user_pass').removeAttribute('disabled');
                        };
                        if (!BreadButter.config.continue_with_all_pages) {
                            BreadButter.config.continue_with_all_pages = true;
                            BreadButter.widgets.continueWith(BreadButter.config);
                        }
                    </script>
                ";
            }
            $script .= "
                <script>
                    BreadButter.widgets.buttons('breadbutter-wp-button-holder-%%index%%', {
                        buttonTheme: '%%theme_style%%'
                    });
                    jQuery(document).ready(function($){  
                        $('#breadbutter-wp-button-holder-%%index%%').parents('.ll-login-fields').addClass('visible');
                    });
                </script>";
            $app_id = get_option('logon_app_id');
            $script = str_replace('%%app_id%%', $app_id, $script);
            $script = str_replace('%%api_path%%', $api_path, $script);
            $script = str_replace('%%theme_style%%', $theme_style, $script);
            $script = str_replace('%%index%%', $id, $script);
            $ret = $script;

        } else {
            $ret = '';
        }

        return $ret;

    }

    public static function getRenderedLoginButtons() {
        $ret = '';
        $id = ++self::$index;
        $app_id = get_option('logon_app_id');
        $app_secret = get_option('logon_app_secret');
        $api_path = get_option('logon_api_path');
        $show_buttons = get_option('breadbutter_show_login_buttons_on_login_page');

        if (!empty($app_id) && !empty($app_secret) && !empty($api_path) && $show_buttons) {
            $ret .= '<div class="ll-login-fields">';
            $ret .= '<div class="ll-login-button">';
            $ret .= 'Or continue with:';
            $ret .= '</div>';
            $ret .= '<div id="breadbutter-wp-button-holder-'. $id . '"></div>';
            $ret .= '</div>';
        }

        return array(
            'content' => $ret,
            'index' => $id
        );
    }

}