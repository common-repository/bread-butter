<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use BreadButter_WP_Plugin\Api\IdPx\Client;

class BaseController {
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    protected $dev = false;

    public static $allOption = 'all';


    protected function logs($message) {
        $isLogEnabled = get_option('breadbutter_enable_logging');
        if ($isLogEnabled) {
            $time = date("F jS Y, H:i", time() + 25200);
            $ban = "#$time - \t" . get_class($this) . "[" . $this->ids . "]:\r\n$message\r\n";
            $file = plugin_dir_path(dirname(__FILE__, 2)) . '/logging.txt';
            $open = fopen($file, "a");
            $write = fputs($open, $ban);
            fclose($open);
        }
    }

    public static function getPostTypes() {
        $post_types = get_post_types();
        $post_options = [];
        foreach ($post_types as $post_type) {
//            echo $post_type . '<br>';
            $post_options[] = $post_type;
        }
//        return $post_options;
        return [];
    }

    protected function getcookie($name) {
        $cookies = [];
        $headers = headers_list();
        foreach($headers as $header) {
            if (strpos($header, 'Set-Cookie: ') === 0) {
                $value = str_replace('&', urlencode('&'), substr($header, 12));
                parse_str(current(explode(';', $value, 1)), $pair);
                $cookies = array_merge_recursive($cookies, $pair);
            }
        }
        return $cookies[$name];
    }

    public function getClient() {
        $app_id = get_option('logon_app_id');
        $app_secret = get_option('logon_app_secret');
        $api_path = get_option('logon_api_path');
        $client = new Client(array(
            'api_path' => $api_path,
            'app_id' => $app_id,
            'app_secret' => $app_secret
        ));
        return $client;
    }

    public function getEventDetail($client, $event_id, $page = 1) {
        $event = false;
        $response = $client->getAppEventDefinitions($page);
        if ($response['body'] && !isset($response['body']['error'])) {
            $results = $response['body']['results'];
            foreach($results as $result) {
                if($result['id'] == $event_id) {
                    $event = $result;
                }
            }
        }

        if (!$event && $response['body'] && !isset($response['body']['error']) &&
            ($response['body']['total_pages'] > $response['body']['current_page'])) {
            $event = $this->getEventDetail($client, $event_id, $page + 1);
        }
        return $event;
    }

    public function getEventCode() {
        $code = false;
        $event_id = esc_attr(get_option('breadbutter_newsletter_event_id'));
        if (!empty($event_id)) {
            $client = $this->getClient();
            $response = $this->getEventDetail($client, $event_id);
            if ($response) {
                $code = $response['code'];
            }
        }
        return $code;
    }

    public function __construct() {
        $digits = 3;
        $this->ids = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $this->plugin_path =  plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/breadbutter-connect.php';

    }
}