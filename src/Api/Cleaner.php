<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Api;
use \BreadButter_WP_Plugin\Base\BaseController;

class Cleaner extends BaseController {
    public function sanitizeUrl($url) {
        return esc_url_raw($url);
    }
}