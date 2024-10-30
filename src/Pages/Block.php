<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages;

use \BreadButter_WP_Plugin\Api\SettingsApi;
use \BreadButter_WP_Plugin\Base\BaseController;
use \BreadButter_WP_Plugin\Pages\Gutenberg\ContactUs;
use \BreadButter_WP_Plugin\Pages\Gutenberg\ContentGating;
use \BreadButter_WP_Plugin\Pages\Gutenberg\ContentPreview;
use \BreadButter_WP_Plugin\Pages\Gutenberg\Newsletter;

class Block extends BaseController {

    public $settings;
    public $pages;
    public $callbacks;
    public $metalist;

    public function register() {
        $this->settings = new SettingsApi();
        $this->setupMetaList();
        $this->setMeta();
        $this->settings->register();

        add_action('enqueue_block_editor_assets', array($this, 'enqueueBlockEditorAssets'));
    }

    public function setupMetaList() {
        $this->metalist = array();
        $this->metalist[] = new ContactUs();
        $this->metalist[] = new ContentGating();
        $this->metalist[] = new ContentPreview();
        $this->metalist[] = new Newsletter();
    }

    public function setMeta() {
        $args = array(
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_enabled',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_as_destination_url',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => false
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_app_name',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_destination_url',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_callback_url',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_client_data',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => array($this, 'cleanJSON')
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_button_theme',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
//            array(
//                'target' => 'post',
//                'name' => 'breadbutter_post_expand_email_address',
//                'params' => array(
//                    'type' => 'boolean',
//                    'single' => true,
//                    'show_in_rest' => true,
//                    'default' => true
//                )
//            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_user_profile_tool',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => false
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_continue_with_popup_delay_seconds',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_continue_with_success_seconds',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_continue_with_success_header',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_continue_with_success_text',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_show_login_focus',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => false
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_force_reauthentication',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => false
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_continue_with',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_continue_with_position_vertical',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 'default'
                )
            ),

            array(
                'target' => 'post',
                'name' => 'breadbutter_continue_with_position_horizontal',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 'default'
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_continue_with_position_vertical_px',
                'params' => array(
                    'type' => 'number',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_continue_with_position_horizontal_px',
                'params' => array(
                    'type' => 'number',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_is_restricred',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
//            array(
//                'target' => 'post',
//                'name' => 'breadbutter_post_is_gated',
//                'params' => array(
//                    'type' => 'boolean',
//                    'single' => true,
//                    'show_in_rest' => true
//                )
//            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_header_1',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_header_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_header_back_1',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_header_back_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_blur_text_1',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_blur_text_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_blur_text_3',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_blur_text_3_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_blur_more_text',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => ''
                )
            ),
        );
        foreach ($this->metalist as $metalist) {
            $args = array_merge($args, $metalist->getMeta());
        }
        $this->settings->setMeta($args);
    }

    public function cleanJSON($input) {
        json_decode($input);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function enqueueBlockEditorAssets() {
        $post = get_post();
        $post_type_support = get_all_post_type_supports($post->post_type);
//        print_r($post);
//        echo "<br>";
//        print_r($post_type_support);
//        print_r($post_type_support['custom-fields']);

//        if ($post->post_type == 'post' || $post->post_type == 'page') {
        if ($post_type_support['custom-fields']) {
            wp_enqueue_script(
                'bb_siderbar_config',
                $this->plugin_url . 'assets/sidebar.js',
                array('wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post'),
                filemtime($this->plugin_path . '/assets/sidebar.js')
            );
        }
//        }
    }
}