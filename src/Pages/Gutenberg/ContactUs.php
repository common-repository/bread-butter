<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Gutenberg;

class ContactUs extends Base {
    public function setMeta() {
        $this->meta = array(
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_enabled',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_icon_message',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_custom_image',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_header',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_paragraph_1',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_paragraph_2',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_paragraph_3',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_sub_header',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_button_label',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_success_message',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_show_phone',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_show_company',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_position_vertical',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_position_vertical_px',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_position_horizontal',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_position_horizontal_px',
                'params' => array(
                    'type' => 'string',
                    'single' => true,
                    'show_in_rest' => true
                )
            ),
            array(
                'target' => 'post',
                'name' => 'breadbutter_post_contactus_override_dest',
                'params' => array(
                    'type' => 'boolean',
                    'single' => true,
                    'show_in_rest' => true,
                    'default' => 0
                )
            )
        );
    }
}