<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class Newsletter extends BaseController {

    public function register() {
        add_action('wp_enqueue_scripts', array($this, 'newsletterHead'));
    }

    public function newsletterHead() {
        if ($this->isPageSelected()) {
            $event_code = $this->getEventCode();

            $header = get_option('breadbutter_newsletter_header');
            $main_message = get_option('breadbutter_newsletter_main_message');
            $success_message = get_option('breadbutter_newsletter_success_message');
            $success_header = get_option('breadbutter_newsletter_success_header');
            $custom_image_type = get_option('breadbutter_newsletter_custom_image_type');
            $custom_image = get_option('breadbutter_newsletter_custom_image');
            $delay = get_option('breadbutter_newsletter_delay_popup', 5);

            if (!empty($event_code)) {
                $data = [
                    'custom_event_code' => $event_code,
                    'image_source' => $custom_image,
                    'image_type' => $custom_image_type,
                    'delay_seconds' => intval($delay),
                ];
                if (!empty($header)) {
                    $data['header_text'] = $header;
                }
                if (!empty($main_message)) {
                    $data['main_text'] = $main_message;
                }
                if (!empty($success_header)) {
                    $data['success_header'] = $success_header;
                }
                if (!empty($success_message)) {
                    $data['success_text'] = $success_message;
                }

                $override_dest_url = get_option('breadbutter_newsletter_override_dest', false);
                $override_dest_url_var = '';
                if ($override_dest_url) {
                    $override_dest_url_var = "var BB_OVERRIDE_REG_DESTINATION_URL = true;";
                }
                $string_data = 'var BB_POST_NEWSLETTER_DATA = ' . json_encode($data) . ';';
                echo "<script>var BB_POST_NEWSLETTER = 1; $override_dest_url_var $string_data</script>";
                $enq = new Enqueue();
                $enq->loadBreadbutter();
            }
        } else {
            $post_meta = $this->isPageSelectedByPost();
            if ($post_meta) {
                $event_code = $post_meta['breadbutter_post_newsletter_event_code'][0];
                if (empty($event_code)) {
                    $event_code = $this->getEventCode();
                }
                if (!empty($event_code)) {
                    // $type = $post_meta['breadbutter_post_newsletter_type'][0];
                    $header = $post_meta['breadbutter_post_newsletter_header'][0];
                    $main_message = $post_meta['breadbutter_post_newsletter_main_message'][0];
                    $success_header = $post_meta['breadbutter_post_newsletter_success_header'][0];
                    $success_message = $post_meta['breadbutter_post_newsletter_success_message'][0];
                    $custom_image = $post_meta['breadbutter_post_newsletter_custom_image'][0];
                    $custom_image_type = $post_meta['breadbutter_post_newsletter_custom_image_type'][0];
                    $delay =  $post_meta['breadbutter_post_newsletter_delay_popup'][0];
                    $override_dest_url = $post_meta['breadbutter_post_newsletter_override_dest'][0];

                    if (!is_string($delay) || strlen($delay) == 0) {
                        $delay = 5;
                    }

                    $data = [
                        'custom_event_code' => $event_code,
                        'header_text' => $header,
                        'main_text' => $main_message,
                        'success_text' => $success_message,
                        'success_header' => $success_header,
                        'image_source' => $custom_image,
                        'image_type' => $custom_image_type,
                        'delay_seconds' => intval($delay),
                    ];

                    $override_dest_url_var = '';
                    if ($override_dest_url) {
                        $override_dest_url_var = "var BB_OVERRIDE_REG_DESTINATION_URL = true;";
                    }
                    $string_data = 'var BB_POST_NEWSLETTER_DATA = ' . json_encode($data) . ';';
                    echo "<script>var BB_POST_NEWSLETTER = 1; $override_dest_url_var $string_data</script>";
                    $enq = new Enqueue();
                    $enq->loadBreadbutter();
                }
            }
        }
    }

    public function isPageSelected() {
        $app_id = get_option('logon_app_id');
        // $app_secret = get_option('logon_app_secret');
        if (empty($app_id)) {
            return false;
        }

        $selected_pages = get_option('breadbutter_newsletter_pages', []);
        if (is_array($selected_pages) && in_array(self::$allOption, $selected_pages)) {
            return true;
        }
        $post = get_post();
        if (is_array($selected_pages) && $post && in_array($post->ID, $selected_pages)) {
            return true;
        }
//        if (is_array($selected_pages) && in_array($post->post_type, $selected_pages)) {
//            return true;
//        }


        $home_page = get_option('breadbutter_newsletter_homepage_enabled',false);
        if ($home_page && is_front_page()) {
            return true;
        }

        return false;
    }

    public function isPageSelectedByPost() {
        $post = get_post();
        if ($post) {
            $post_meta = get_post_meta($post->ID);
            if (isset($post_meta['breadbutter_post_newsletter_enabled']) &&
                !empty($post_meta['breadbutter_post_newsletter_enabled'][0])
            ) {
                return $post_meta;
            }
        }
        return false;
    }
}
