<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Gutenberg;

class ContentPreview extends Base {
    public function setMeta() {
        $this->meta = array(
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_enabled',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_text_1',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_text_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_text_3',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_text_3_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_text_more',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_override_dest',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_clickable_content',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_scroll_limit',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_time_limit',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_content_preview_height',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            )
        );
    }
}