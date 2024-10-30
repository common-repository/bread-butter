<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Gutenberg;

class Newsletter extends Base {
    public function setMeta() {
        $this->meta = array(
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_enabled',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_type',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_header',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_main_message',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_success_header',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_success_message',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_delay_popup',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_custom_image_type',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_custom_image',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_override_dest',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_newsletter_event_code',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            )
        );
    }
}