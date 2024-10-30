<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Features;

class ContentPreview extends Base {
    public function setSettings() {
        $this->settings = array(
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_config_enabled',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_pages',
                'default' => [],
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_text_1',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_text_2',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_text_3',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_text_3_2',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_label',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_override_dest',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_clickable_content',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_scroll_limit',
                'default' => 0,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_time_limit',
                'default' => 0,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_preview_group',
                'name' => 'breadbutter_gating_content_preview_height',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            )
        );
    }

    public function setSections() {
        $this->sections = array(
            array(
                'id' => 'breadbutter_gating_content_preview_config_enabled',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_config_pages',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_config_text',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewTextSection'),
                'page' => 'breadbutter_connect'
            ),
        );
    }

    public function setFields() {
        $this->fields = array(
            array(
                'id' => 'breadbutter_gating_content_preview_config_enabled',
                'title' => 'Enable Content Gating',
                'callback' => array($this->callbacks, 'breadbutterGatingPreviewEnabled'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_enabled',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_config_enabled'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_pages',
                'title' => 'Content Preview Pages',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_pages',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_pages'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_text_1',
                'title' => 'Header',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewParagraph1'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_text_1'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_text_2',
                'title' => 'Sub Header',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewParagraph2'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_text_2'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_text_3',
                'title' => 'Bottom Paragraph',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewParagraph'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_text_3'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_text_3_2',
                'title' => 'Expanded Bottom Paragraph',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewExpandedParagraph'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_text_3_2'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_label',
                'title' => 'Expand label',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewExpandLabel'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_label'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_override_dest',
                'title' => 'Override Registration Destination URL',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewOverrideDest'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_override_dest'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_clickable_content',
                'title' => 'Allow page to be clickable',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewClickableContent'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_clickable_content'
                )
            ),

            array(
                'id' => 'breadbutter_gating_content_preview_scroll_limit',
                'title' => 'Percent scrolled until enabled',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewScrollLimit'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_scroll_limit'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_time_limit',
                'title' => 'Seconds until enabled',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewTimeLimit'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_time_limit'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_preview_height',
                'title' => 'Maximum height',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPreviewHeight'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_preview_config_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_preview_height'
                )
            )
        );
    }
}