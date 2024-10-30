<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Features;

class ContentGating extends Base {
    public function setSettings() {
        $this->settings = array(
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_config_enabled',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_pages',
                'default' => [],
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_text_header',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_text_subheader',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_custom_image_type',
                'default' => 'default',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_custom_image',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_override_dest',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_scroll_limit',
                'default' => 0,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_gating_group',
                'name' => 'breadbutter_gating_content_gating_time_limit',
                'default' => 0,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
        );
    }

    public function setSections() {
        $this->sections = array(
            array(
                'id' => 'breadbutter_gating_content_gating_config_pages',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_config_enabled',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_config_text',
//                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewTextSection'),
                'page' => 'breadbutter_connect'
            ),
        );
    }

    public function setFields() {
        $this->fields = array(
            array(
                'id' => 'breadbutter_gating_content_gating_config_enabled',
                'title' => 'Enable Content Gating',
                'callback' => array($this->callbacks, 'breadbutterGatingContentEnabled'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_enabled',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_config_enabled'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_pages',
                'title' => 'Content Gating Pages',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_pages',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_pages'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_text_header',
                'title' => 'Header',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_text_header'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_text_subheader',
                'title' => 'Sub Header',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingSubHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_text_subheader'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_custom_image_type',
                'title' => 'Header Image',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingCustomImageType'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_custom_image_type'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_custom_image',
                'title' => 'Custom Image URL (Optional)',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingCustomImage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_custom_image'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_override_dest',
                'title' => 'Override Registration Destination URL',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingOverrideDest'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_override_dest'
                )
            ),

            array(
                'id' => 'breadbutter_gating_content_gating_scroll_limit',
                'title' => 'Percent scrolled until enabled',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingScrollLimit'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_scroll_limit'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_gating_time_limit',
                'title' => 'Seconds until enabled',
                'callback' => array($this->callbacks, 'breadbutterGatingContentGatingTimeLimit'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_gating_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_gating_time_limit'
                )
            ),
        );
    }
}