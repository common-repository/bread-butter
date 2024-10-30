<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class Shortcode extends BaseController {

    public static $signIn = 'breadbutter-signin';
    public static $continueWith = 'breadbutter-continuewith';
    public static $continueWithButton = 'breadbutter-continuewith-button';
    public static $logoutButton = 'breadbutter-logout-button';
    public static $continueWithCall = 'breadbutter-continuewith-call';
    public static $custonEventButton = 'breadbutter-customevent-button';
    public static $custonEvent = 'breadbutter-customevent';


    public static $contactUsWidget = 'breadbutter-contactus';
    public static $contactUsWidgetForm = 'breadbutter-contactus-form';
    public static $contactUsButton = 'breadbutter-contactus-button';
    public static $newsletterWidget = 'breadbutter-newsletter';
    public static $newsletterButton = 'breadbutter-newsletter-button';

    public static $profileWidget = 'breadbutter-profile-widget';

    public static $optInWidget = 'breadbutter-optin-widget';

    public static $loggedInContent = 'breadbutter-logged-in-content';
    public static $loggedOutContent = 'breadbutter-logged-out-content';

    public static $index = 0;

    public function register() {
        add_action('init', array($this, 'initShortcode'), 1);
//        add_action('wp_logout', array($this, 'endSession'), 1);
//        add_action('wp_login', array($this, 'endSession'), 1);
    }

    public function initShortcode() {
        add_shortcode(self::$signIn, array($this, 'signInShortcode'));
        add_shortcode(self::$continueWith, array($this, 'continueWithShortcode'));
        add_shortcode(self::$continueWithButton, array($this, 'continueWithShortcodeButton'));
        add_shortcode(self::$continueWithCall, array($this, 'continueWithShortcodeCall'));
        add_shortcode(self::$logoutButton, array($this, 'logoutButton'));
        add_shortcode(self::$custonEventButton, array($this, 'customEventShortCodeButton'));
        add_shortcode(self::$custonEvent, array($this, 'customEventShortCode'));
        add_shortcode(self::$loggedInContent, array($this, 'loggedInContent'));
        add_shortcode(self::$loggedOutContent, array($this, 'loggedOutContent'));

        add_shortcode(self::$contactUsWidget, array($this, 'contactUsWidget'));
        add_shortcode(self::$contactUsWidgetForm, array($this, 'contactUsWidgetForm'));
        add_shortcode(self::$contactUsButton, array($this, 'contactUsButton'));

        add_shortcode(self::$newsletterWidget, array($this, 'newsletterWidget'));
        add_shortcode(self::$newsletterButton, array($this, 'newsletterButton'));

        add_shortcode(self::$profileWidget, array($this, 'profileWidget'));
        add_shortcode(self::$optInWidget, array($this, 'optInWidget'));
    }

    public function contactUsWidget($attr) {
        wp_enqueue_script('bb_contactus_script', $this->plugin_url . 'assets/breadbutter.contactus.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.contactus.js'));
//        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
//            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));
        if (empty($attr)) {
            $attr = array();
        }

        $attributes = base64_encode(json_encode($attr));

        $html = "";

        $html .= "
        <script>
        let bb_contactus_widget = true;
        let bb_contactus_attributes = '{$attributes}';
        </script>
        ";

        return $html;
    }

    public function contactUsWidgetForm($attr) {
        wp_enqueue_script('bb_contactus_script', $this->plugin_url . 'assets/breadbutter.contactus.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.contactus.js'));
//        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
//            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));
        if (empty($attr)) {
            $attr = array();
        }

        $class = "bb-contactus-widget bb-hidden";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }


        $id = 'breadbutter-wp-contactus-form' . ++self::$index;

        $attributes = base64_encode(json_encode($attr));

        $html = "<div id='{$id}' class=\"{$class}\" rel='{$attributes}'></div>";

        return $html;
    }

    public function contactUsButton($attr, $content) {
        wp_enqueue_script('bb_contactus_script', $this->plugin_url . 'assets/breadbutter.contactus.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.contactus.js'));
//        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
//            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));

        if (empty($attr)) {
            $attr = array();
        }

        $class = "bb-contactus-widget-button";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }

        $id = 'breadbutter-wp-contactus-button' . ++self::$index;

        $attributes = base64_encode(json_encode($attr));
        $html = "<p><button id='{$id}' class='{$class}' rel='{$attributes}'>{$content}</button></p>";
        return $html;

    }

    public function newsletterWidget($attr) {
        wp_enqueue_script('bb_newsletter_script', $this->plugin_url . 'assets/breadbutter.newsletter.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.newsletter.js'));
//        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
//            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));
        if (empty($attr)) {
            $attr = array();
        }
        
        $class = "bb-newsletter-widget bb-hidden";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }

//        $custom_event_code = esc_attr(get_option('breadbutter_newsletter_event_code'));
        $custom_event_code = $this->getEventCode();
        if (!isset($attr['custom_event_code']) || empty($attr['custom_event_code'])) {
            $attr['custom_event_code'] = $custom_event_code;
        }

        $attributes = base64_encode(json_encode($attr));

        $html = "<div class=\"{$class}\"></div>";

        $html .= "
        <script>
        let bb_newsletter_attributes = '{$attributes}';
        </script>
        ";

        return $html;

    }

    public function newsletterButton($attr, $content) {
        wp_enqueue_script('bb_newsletter_script', $this->plugin_url . 'assets/breadbutter.newsletter.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.newsletter.js'));
//        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
//            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));

        if (empty($attr)) {
            $attr = array();
        }

        $class = "bb-newsletter-widget-button bb-hidden";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }
        
//        $custom_event_code = esc_attr(get_option('breadbutter_newsletter_event_code'));
        $custom_event_code = $this->getEventCode();

        if (!isset($attr['custom_event_code']) || empty($attr['custom_event_code'])) {
            $attr['custom_event_code'] = $custom_event_code;
        }

        $attributes = base64_encode(json_encode($attr));
        $html = "<p><button class=\"{$class}\" rel='{$attributes}'>{$content}</button></p>";
        return $html;

    }

    public function loggedInContent($attr, $content) {
        if (is_user_logged_in()) {
            return $content;
        }
        return '';
    }

    public function loggedOutContent($attr, $content) {
        if (!is_user_logged_in()) {
            return $content;
        }
        return '';
    }

    public function signInShortcode($attr, $content) {
        if (empty($attr)) {
            $attr = array();
        }
        $id = 'breadbutter-wp-signin-holder-' . ++self::$index;
        $ret = '<div id="'. $id . '" class="breadbutter-wp-signin-holder"></div>';
        $attr['id'] = $id;
        wp_enqueue_script('bb_signin_script', $this->plugin_url . 'assets/breadbutter.signin.js', array('bb_config_script'));
        wp_localize_script('bb_signin_script', 'bb_signin_params', $attr);

        return $ret;
    }

    public function customEventShortCode($attr) {
        $event = false;
        if (isset($attr['event']) && !empty($attr['event'])) {
            $event = $attr['event'];
        }
        if ($event) {
            $html = <<<HTML
<script>
null == window.breadbutterQueue && (window.breadbutterQueue = []), window.injectBreadButter = function (e) { "undefined" != typeof BreadButter && BreadButter.init ? e() : window.breadbutterQueue.push(e) };
injectBreadButter(function () {
    BreadButter.events.custom("$event");
});
</script>
HTML;
            return $html;
        }
        return "";

    }

    public function customEventShortCodeButton($attr, $content) {
        $callback = false;
        $redirect = false;
        if (isset($attr['callback']) && !empty($attr['callback'])) {
            $callback = $attr['callback'];
        } else if (isset($attr['redirect']) && !empty($attr['redirect'])) {
            $redirect = $attr['redirect'];
        }

        $class = "breadbutter-custom-event";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }

        $event = "";

        if (isset($attr['event']) && !empty($attr['event'])) {
            $event = $attr['event'];
        }

        if ($redirect) {
            $callback_function = "BreadButter.events.redirect('$event', '$redirect')";
        } else if ($callback) {
            $pattern = '/^[$a-zA-Z_]?[0-9a-zA-Z_$]*\([\w\W]*\)$/';
            $match = preg_match($pattern, $callback);
            if ($match) {
                $callback = substr($callback, 0, strpos($callback, '('));
            }
            $callback_function = "BreadButter.events.custom('$event', $callback)";
        } else {
            $callback_function = "BreadButter.events.custom('$event')";
        }

        return '<button class="' . $class . '" onclick="' . $callback_function . '">' . $content . '</button>';

    }

    public function continueWithShortcode($attr, $content) {
        wp_enqueue_script('bb_continuewith_script', $this->plugin_url . 'assets/breadbutter.continuewith.js', array('bb_config_script'));
        wp_localize_script('bb_continuewith_script', 'bb_continuewith_params', $attr);
    }

    public function continueWithShortcodeButton($attr, $content) {
        wp_enqueue_script('bb_continuewith_button_script', $this->plugin_url . 'assets/breadbutter.call.continuewith.js', array('bb_config_script'));
//        wp_localize_script('bb_continuewith_button_script', 'bb_continuewith_params', $attr);

        $encoded_json = base64_encode(json_encode($attr));
        $class = "breadbutter-continuewith-trigger";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }

        $callback = false;
        if (isset($attr['on_login']) && !empty($attr['on_login'])) {
            $callback = $attr['on_login'];
        }
        if (!is_user_logged_in()) {
            return '<button class="' . $class . '" onclick="callBreadButterContinueWith(\''.$encoded_json.'\')">' . $content . '</button>';
        } else if ($callback !== false) {
            $callback_function = $callback;
            $pattern = '/^[$a-zA-Z_]?[0-9a-zA-Z_$]*\([\w\W]*\)$/';
            $match = preg_match($pattern, $callback);
            if (!$match) {
                $callback_function = $callback . "()";
            }
            return '<button class="' . $class . '" onclick="' . $callback_function . '">' . $content . '</button>';
        }
        return '';
    }

    public function continueWithShortcodeCall($attr, $content) {
        wp_enqueue_script('bb_continuewith_button_script', $this->plugin_url . 'assets/breadbutter.call.continuewith.js', array('bb_config_script'));
        wp_localize_script('bb_continuewith_button_script', 'bb_continuewith_params', $attr);
    }

    public function logoutButton($attr, $content) {
        if (is_user_logged_in()) {
            $content = $content ? $content : 'Logout';
            $class = "breadbutter-logout-trigger";
            if (isset($attr['css_class']) && !empty($attr['css_class'])) {
                $class .= " " . $attr['css_class'];
            }

            return '<a class="' . $class . '" href="javascript: bbOnLogout()" >' . $content . '</a>';
        }
        return '';
    }

    public function profileWidget($attr, $content) {
        wp_enqueue_script('bb_profile_widget_script', $this->plugin_url . 'assets/breadbutter.profilewidget.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.js'));
        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));

        $class = "bb-profile-widget bb-hidden";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }

        return '<div class="'. $class .'"></div>';
    }

    public function optInWidget($attr, $content) {
        wp_enqueue_script('bb_optin_widget_script', $this->plugin_url . 'assets/breadbutter.optin.js',
            array('jquery', 'bb_config_script'), filemtime($this->plugin_path . 'assets/breadbutter.optin.js'));
//        wp_enqueue_style('bb_profile_widget_style', $this->plugin_url . 'assets/breadbutter.profilewidget.css',
//            array(), filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css'));

        $class = "bb-optin-widget bb-hidden";
        if (isset($attr['css_class']) && !empty($attr['css_class'])) {
            $class .= " " . $attr['css_class'];
        }


        return '<div class="'. $class .'"></div>';
    }

}