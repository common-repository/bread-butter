<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class Session extends BaseController {
    public function register() {
        add_action('init', array($this, 'initSession'), 1);
        add_action('wp_logout', array($this, 'endSession'), 1);
//        add_action('wp_login', array($this, 'endSession'), 1);
    }

    public function initSession() {
//        echo session_id();
//        if (!session_id()) {
//            session_start();
//        }
    }

    public function endSession() {
        // session_destroy();
        $this->logs('end sessions: ' . COOKIEPATH . ':' . COOKIE_DOMAIN);
        setcookie('bb_auth_failed', true, 0, COOKIEPATH, COOKIE_DOMAIN);
    }

}