<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Init;

class Deactivate {
    public static function deactivate() {
        Init::unregister_services();
        flush_rewrite_rules();
    }
}