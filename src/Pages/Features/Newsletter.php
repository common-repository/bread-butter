<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Features;

class Newsletter extends Base {
    public function setSettings() {
        $this->settings = array(
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_pages',
                'default' => [],
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_type',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_override_dest',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_header',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_homepage_enabled',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_main_message',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_success_header',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_success_message',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_custom_image_type',
                'default' => 'default',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_custom_image',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_event_id',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_integration_id',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_trigger_id',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_action_id',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_newsletter_groups',
                'name' => 'breadbutter_newsletter_delay_popup',
                'default' => 5,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            )
        );
    }

    public function setSections() {
        $this->sections = array(
            array(
                'id' => 'breadbutter_newsletter_config_pages',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_newsletter_config_homepage_enabled',
                'page' => 'breadbutter_connect'
            ),            
            array(
                'id' => 'breadbutter_newsletter_config_type',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_newsletter_config_widget',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_newsletter_config_timer',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_newsletter_config_image',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_newsletter_config_url',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_newsletter_config_event',
                'page' => 'breadbutter_connect'
            )
        );
    }

    public function setFields() {
        $this->fields = array(
            array(
                'id' => 'breadbutter_newsletter_pages',
                'title' => 'Opt-in pages',
                'callback' => array($this->callbacks, 'breadbutterNewsletterPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_pages',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_pages'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_homepage_enabled',
                'title' => 'Enable Opt-in Popup',
                'callback' => array($this->callbacks, 'breadbutterNewsletterEnabled'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_homepage_enabled',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_homepage_enabled'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_type',
                'title' => 'Choose Opt-in type',
                'callback' => array($this->callbacks, 'breadbutterNewsletterType'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_type',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_type'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_header',
                'title' => 'Header',
                'callback' => array($this->callbacks, 'breadbutterNewsletterHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_widget',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_header'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_main_message',
                'title' => 'Main Message',
                'callback' => array($this->callbacks, 'breadbutterNewsletterMainMessage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_widget',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_main_message'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_success_header',
                'title' => 'Success Header',
                'callback' => array($this->callbacks, 'breadbutterNewsletterSuccessHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_widget',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_success_header'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_success_message',
                'title' => 'Success Message',
                'callback' => array($this->callbacks, 'breadbutterNewsletterSuccessMessage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_widget',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_success_message'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_delay_popup',
                'title' => 'Popup Delay (Seconds)',
                'callback' => array($this->callbacks, 'breadbutterNewsletterDelayPopup'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_timer',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_delay_popup'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_custom_image_type',
                'title' => 'Header Image',
                'callback' => array($this->callbacks, 'breadbutterNewsletterCustomImageType'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_image',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_custom_image_type'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_custom_image',
                'title' => 'Header Image URL (Optional)',
                'callback' => array($this->callbacks, 'breadbutterNewsletterCustomImage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_image',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_custom_image'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_override_dest',
                'title' => 'Override Registration Destination URL',
                'callback' => array($this->callbacks, 'breadbutterNewsletterOverrideDest'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_url',
                'args' => array(
                    'label_for' => 'breadbutter_newsletter_override_dest'
                )
            ),
            array(
                'id' => 'breadbutter_newsletter_event_id',
                'callback' => array($this->callbacks, 'breadbutterNewsletterEventID'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_event'
            ),
            array(
                'id' => 'breadbutter_newsletter_integration_id',
                'callback' => array($this->callbacks, 'breadbutterNewsletterIntegrationID'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_event'
            ),
            array(
                'id' => 'breadbutter_newsletter_trigger_id',
                'callback' => array($this->callbacks, 'breadbutterNewsletterTriggerID'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_event'
            ),
            array(
                'id' => 'breadbutter_newsletter_action_id',
                'callback' => array($this->callbacks, 'breadbutterNewsletterActionID'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_newsletter_config_event'
            )
        );
    }
}