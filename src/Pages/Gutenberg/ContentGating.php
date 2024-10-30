<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Gutenberg;

class ContentGating extends Base {
    public function setMeta() {
        $this->meta = array(
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_is_gated',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_header',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_subheader',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_custom_image_type',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 'default'
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_custom_image',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_override_dest',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_scroll_limit',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_gating_time_limit',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            )
        );
    }
}