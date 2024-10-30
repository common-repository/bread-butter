<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages;

use \BreadButter_WP_Plugin\Api\SettingsApi;
use \BreadButter_WP_Plugin\Base\BaseController;
use \BreadButter_WP_Plugin\Pages\LoginForm;

class LoginTemplate extends BaseController {

    public $settings;
    public $callbacks;
    public $token;
    public $response = array();
    public $error_code;

    public static $FORM_START = array('woocommerce_login_form_start', 'woocommerce_register_form_start', 'login_form_top');
    public static $FORM_ICON_END = array('woocommerce_login_form', 'woocommerce_register_form');
    public static $FORM_BUTTON_END = array('woocommerce_login_form_end', 'woocommerce_register_form_end');
    public static $FORM_END_RETURN = array('login_form_bottom');

    public function register() {
        $this->settings = new SettingsApi();

        $this->registerStart();
        $this->registerEnd();
    }

    public function registerStart() {
        foreach(self::$FORM_START as $load) {
//            add_action($load, array($this, 'addLogonScript'));
        }
    }

    public function registerEnd() {
        $theme_style = get_option('breadbutter_button_theme');
        switch($theme_style) {
            case 'tiles':
                foreach(self::$FORM_BUTTON_END as $load) {
                    add_action($load, array($this, 'injectLogonClient'));
                }
                break;
            case 'round-icons':
                foreach(self::$FORM_ICON_END as $load) {
                    add_action($load, array($this, 'injectLogonClient'));
                }
                break;
        }
        foreach(self::$FORM_END_RETURN as $load) {
            add_action($load, array($this, 'addLogonClient'));
        }

    }

    public function injectLogonClient() {
        echo $this->addLogonClient();
    }

    public function addLogonClient() {
        wp_enqueue_style('login_style', $this->plugin_url . '/assets/login.css',
            array(), filemtime($this->plugin_path . 'assets/login.css'));

        $ret = '';
        $button = LoginForm::getRenderedLoginButtons();
        $ret .= $button['content'];
        $ret .= LoginForm::getLogonScripts($button['index']);
        return $ret;
    }
}