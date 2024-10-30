<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class ContactUs extends BaseController {

    public function register() {
        add_action('wp_enqueue_scripts', array($this, 'contactusHead'));
    }

    public function contactusHead() {
        $post_meta = $this->isPageSelectedByPost();
        if ($post_meta) {

            $icon_note = $post_meta['breadbutter_post_contactus_icon_message'][0];
            $header = $post_meta['breadbutter_post_contactus_header'][0];
            $locale_text_1 = $post_meta['breadbutter_post_contactus_paragraph_1'][0];
            $locale_text_2 = $post_meta['breadbutter_post_contactus_paragraph_2'][0];
            $locale_text_3 = $post_meta['breadbutter_post_contactus_paragraph_3'][0];


            $sub_header = $post_meta['breadbutter_post_contactus_sub_header'][0];
            $button = $post_meta['breadbutter_post_contactus_button_label'][0];
            $success = $post_meta['breadbutter_post_contactus_success_message'][0];

            $show_phone = $post_meta['breadbutter_post_contactus_show_phone'][0] || false;
            $show_company = $post_meta['breadbutter_post_contactus_show_company'][0] || false;

            $vertical = $post_meta['breadbutter_post_contactus_position_vertical'][0];
            $vertical_px = $post_meta['breadbutter_post_contactus_position_vertical_px'][0];
            $horizontal = $post_meta['breadbutter_post_contactus_position_horizontal'][0];
            $horizontal_px = $post_meta['breadbutter_post_contactus_position_horizontal_px'][0];

            $override_dest_url = $post_meta['breadbutter_post_contactus_override_dest'][0] || false;

            $custom_image = $post_meta['breadbutter_post_contactus_custom_image'][0];
            if (empty($custom_image)) {
                $custom_image = get_option('breadbutter_contactus_custom_image');
            }

            if (!$vertical_px) {
                $vertical_px = 10;
            }
            if (!$horizontal_px) {
                $horizontal_px = 10;
            }

            if (!$vertical) {
                $vertical = 'bottom';
            }
            if (!$horizontal) {
                $horizontal = 'right';
            }
            $data = [
                'show_phone' => $show_phone,
                'show_company_name' => $show_company,
                'continue_with_position' => [
                    $vertical => $vertical_px . 'px',
                    $horizontal => $horizontal_px . 'px'
                ]
            ];

            if (!empty($custom_image)) {
                $data['custom_image_url'] = $custom_image;
            }

            if (!empty($icon_note) || !empty($header)
                || !empty($sub_header)
                || !empty($button) || !empty($success)) {
                if (!isset($data['locale'])) {
                    $data['locale'] = [];
                }
                $data['locale']['CONTACT_US'] = [];
            }

            if (!empty($locale_text_1) || !empty($locale_text_2)
                || !empty($locale_text_3)) {
                if (!isset($data['locale'])) {
                    $data['locale'] = [];
                }
                $data['locale']['POPUP'] = [];
            }

            if (!empty($success)) {
                $data['locale']['CONTACT_US']['SUCCESS'] = $success;
            }
            if (!empty($icon_note)) {
                $data['locale']['CONTACT_US']['ICON_NOTE'] = $icon_note;
            }
            if (!empty($header)) {
                $data['locale']['CONTACT_US']['HEADER'] = $header;
            }
            if (!empty($sub_header)) {
                $data['locale']['CONTACT_US']['SUB_HEADER'] = $sub_header;
            }
            if (!empty($button)) {
                $data['locale']['CONTACT_US']['BUTTON'] = $button;
            }

            if (!empty($locale_text_1)) {
                $data['locale']['CONTACT_US']['TEXT_1'] = $locale_text_1;
            }
            if (!empty($locale_text_2)) {
                $data['locale']['CONTACT_US']['TEXT_2'] = $locale_text_2;
            }

            $override_dest_url_var = '';
            if ($override_dest_url) {
                $override_dest_url_var = "const BB_CONTACTUS_OVERRIDE_REG_DESTINATION_URL = true;";
            }
            $string_data = 'const BB_POST_CONTACTUS_DATA = ' . json_encode($data) . ';';
            echo "<script>const BB_POST_CONTACTUS = 1; $override_dest_url_var $string_data</script>";
            $enq = new Enqueue();
            $enq->loadBreadbutter();
        } else if ($this->isPageSelected()) {

            $icon_note = get_option('breadbutter_contactus_icon_note');
            $header = get_option('breadbutter_contactus_header');
            $locale_text_1 = get_option('breadbutter_contactus_blur_paragraph_1');
            $locale_text_2 = get_option('breadbutter_contactus_blur_paragraph_2');
            $locale_text_3 = get_option('breadbutter_contactus_blur_paragraph_3');


            $sub_header = get_option('breadbutter_contactus_sub_header');
            $button = get_option('breadbutter_contactus_button');
            $success = get_option('breadbutter_contactus_success');

            $show_phone = get_option('breadbutter_contactus_show_phone', false) || false;
            $show_company = get_option('breadbutter_contactus_show_company', false) || false;

            $vertical = get_option('breadbutter_contactus_position_vertical');
            $vertical_px = get_option('breadbutter_contactus_position_vertical_px');
            $horizontal = get_option('breadbutter_contactus_position_horizontal');
            $horizontal_px = get_option('breadbutter_contactus_position_horizontal_px');

            $override_dest_url = get_option('breadbutter_contactus_override_dest', false);

            $collapse_message = get_option('breadbutter_contactus_collapse_message');

            $custom_image = get_option('breadbutter_contactus_custom_image');

            $data = [
                'show_phone' => $show_phone,
                'show_company_name' => $show_company,
                'continue_with_position' => [
                    $vertical => $vertical_px . 'px',
                    $horizontal => $horizontal_px . 'px'
                ]
            ];

            if (!empty($custom_image)) {
                $data['custom_image_url'] = $custom_image;
            }

            if (!empty($icon_note) || !empty($header)
                || !empty($sub_header) || !empty($collapse_message)
                || !empty($button) || !empty($success)) {
                if (!isset($data['locale'])) {
                    $data['locale'] = [];
                }
                $data['locale']['CONTACT_US'] = [];
            }

            if (!empty($locale_text_1) || !empty($locale_text_2)
                || !empty($locale_text_3)) {
                if (!isset($data['locale'])) {
                    $data['locale'] = [];
                }
                $data['locale']['POPUP'] = [];
            }

            if (!empty($success)) {
                $data['locale']['CONTACT_US']['SUCCESS'] = $success;
            }
            if (!empty($icon_note)) {
                $data['locale']['CONTACT_US']['ICON_NOTE'] = $icon_note;
            }
            if (!empty($header)) {
                $data['locale']['CONTACT_US']['HEADER'] = $header;
            }
            if (!empty($sub_header)) {
                $data['locale']['CONTACT_US']['SUB_HEADER'] = $sub_header;
            }
            if (!empty($collapse_message)) {
                $data['locale']['CONTACT_US']['COLLAPSE'] = $collapse_message;
            }
            if (!empty($button)) {
                $data['locale']['CONTACT_US']['BUTTON'] = $button;
            }

            if (!empty($locale_text_1)) {
                $data['locale']['CONTACT_US']['TEXT_1'] = $locale_text_1;
            }
            if (!empty($locale_text_2)) {
                $data['locale']['CONTACT_US']['TEXT_2'] = $locale_text_2;
            }
//            if (!empty($locale_text_3)) {
//                $data['locale']['POPUP']['TEXT_3'] = $locale_text_3;
//            }

            $override_dest_url_var = '';
            if ($override_dest_url) {
                $override_dest_url_var = "const BB_CONTACTUS_OVERRIDE_REG_DESTINATION_URL = true;";
            }
            $string_data = 'const BB_POST_CONTACTUS_DATA = ' . json_encode($data) . ';';
            echo "<script>const BB_POST_CONTACTUS = 1; $override_dest_url_var $string_data</script>";
            $enq = new Enqueue();
            $enq->loadBreadbutter();
        }
    }

    public function isPageSelected() {
        $app_id = get_option('logon_app_id');
        if (empty($app_id)) {
            return false;
        }

        $selected_pages = get_option('breadbutter_contactus_pages', []);
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

        return false;
    }

    public function isPageSelectedByPost() {
        $post = get_post();
        if ($post) {
            $post_meta = get_post_meta($post->ID);
            if (isset($post_meta['breadbutter_post_contactus_enabled']) &&
                !empty($post_meta['breadbutter_post_contactus_enabled'][0])
            ) {
                return $post_meta;
            }
        }
        return false;
    }


}
