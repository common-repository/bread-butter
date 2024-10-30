<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

class Activate {
    public static function activate() {
        flush_rewrite_rules();

        $app_id = get_option('logon_app_id');
        if (!$app_id) {
            update_option('breadbutter_enable_user_profile', true);
        }

        // Registers option to redirect on next admin load.
        // Saves user ID to ensure it only redirects for the user who activated the plugin.
        // Don't do redirects when multiple plugins are bulk activated
        if (
            ( isset( $_REQUEST['action'] ) && 'activate-selected' === $_REQUEST['action'] ) &&
            ( isset( $_POST['checked'] ) && count( $_POST['checked'] ) > 1 ) ) {
            return;
        }
        add_option( 'breadbutter_activation_redirect', wp_get_current_user()->ID );
    }
}