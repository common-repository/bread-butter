<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class SettingsLinks extends BaseController {

    public function register() {
        add_filter("plugin_action_links_$this->plugin", array($this, 'addSettingsLinks'));
    }

    public function addSettingsLinks($links) {
        $settings_link = '<a href="admin.php?page=breadbutter_connect">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}