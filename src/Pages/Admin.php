<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages;

use \BreadButter_WP_Plugin\Api\SettingsApi;
use \BreadButter_WP_Plugin\Api\Cleaner;
use \BreadButter_WP_Plugin\Base\BaseController;
use \BreadButter_WP_Plugin\Api\Callbacks\AdminCallbacks;
use \BreadButter_WP_Plugin\Pages\Features\Newsletter;
use \BreadButter_WP_Plugin\Pages\Features\ContactUs;
use \BreadButter_WP_Plugin\Pages\Features\ContentGating;
use \BreadButter_WP_Plugin\Pages\Features\ContentPreview;

class Admin extends BaseController {

    public $settings;
    public $cleaner;
    public $pages;
    public $callbacks;

    public $features;

    public function setupFeatures() {
        $this->features = array();
        $this->features[] = new Newsletter();
        $this->features[] = new ContactUs();
        $this->features[] = new ContentPreview();
        $this->features[] = new ContentGating();
    }

    public function unregister() {
        $this->cleaner = new Cleaner();
        $this->settings = new SettingsApi();

        $this->setupFeatures();
        $this->removeSettings();
    }

    public function register() {
        $api_path = get_option('logon_api_path');
        if (empty($api_path)) {
            update_option('logon_api_path', 'https://api.breadbutter.io');
        }
        $style = get_option('breadbutter_button_theme');
        if (empty($style)) {
            update_option('breadbutter_button_theme', 'tiles');
        }
        $this->settings = new SettingsApi();
        $this->cleaner = new Cleaner();
        $this->callbacks = new AdminCallbacks();
        $this->pages = array(
            array(
                'page_title' => 'Bread & Butter',
                'menu_title' => 'Bread & Butter',
                'capability' => 'manage_options',
                'menu_slug' => 'breadbutter_connect',
                'callback' => array($this->callbacks, 'adminDashboard'),
                'icon_url' => $this->plugin_url . '/assets/favicon.png',
                'position' => 110
            )
        );

        $this->setupFeatures();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addPages($this->pages)->register();
        add_action('admin_init', array($this, 'setSettings'));

    }

    public function getSettings() {
        $args = array(
            array(
                'group' => 'logon_option_groups',
                'name' => 'logon_app_id',
                'callback' => array($this->callbacks, 'logonOptionGroups')
            ),
            array(
                'group' => 'logon_option_groups',
                'name' => 'logon_app_secret',
                'callback' => array($this->callbacks, 'logonAppSecretValidation')
            ),
            array(
                'group' => 'logon_option_groups',
                'name' => 'logon_api_path',
                'default' => 'https://api.breadbutter.io',
                'callback' => array($this->callbacks, 'logonOptionGroups')
            ),
            // array(
            //     'group' => 'logon_option_groups',
            //     'name' => 'logon_theme_style',
            //     'default' => 'button',
            //     'callback' => array($this->callbacks, 'logonOptionGroups')
            // ),
            array(
                'group' => 'breadbutter_dashboard_groups',
                'name' => 'breadbutter_page_view_tracking',
                'default' => true,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_app_name',
                'default' => get_bloginfo('name'),
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_all_pages',
                'default' => "",
                'callback' => array($this->callbacks, 'breadbutterEnableContinueWith')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_show_profile',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterWithShowProfile')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_pages',
                'default' => [],
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
//                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_enable_user_profile',
                'default' => true,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_enable_user_profile_tools',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_user_profile_position_vertical',
                'default' => 'top',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_user_profile_position_vertical_px',
                'default' => 30,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_user_profile_position_horizontal',
                'default' => 'right',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_user_profile_position_horizontal_px',
                'default' => 30,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
//            array(
//                'group' => 'logon_option_groups',
//                'name' => 'breadbutter_continue_with_home_page',
//                'default' => false,
//                'callback' => array($this->callbacks, 'breadbutterEnableContinueWith')
//            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                // 'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_allow_sub_domain',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                // 'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_callback_url',
                'callback' => array($this->cleaner, 'sanitizeUrl')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                // 'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_destination_url',
                'callback' => array($this->cleaner, 'sanitizeUrl')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                // 'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_ga_measurement_id',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_position_vertical',
                'default' => 'top',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_position_vertical_px',
                'default' => 30,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_position_horizontal',
                'default' => 'right',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_position_horizontal_px',
                'default' => 30,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_success_seconds',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_success_header',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_success_text',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            //
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_header_new_user_no_display',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_header_new_user_display',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_header_return_user_no_display',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_header_return_user_display',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            //
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_button_theme',
                'default' => 'tiles',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
//            array(
//                'group' => 'breadbutter_client_option_groups',
//                'name' => 'breadbutter_expand_email_address',
//                'default' => true,
//                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
//            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_show_login_focus',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                'name' => 'breadbutter_show_login_buttons_on_login_page',
                'default' => true,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                'name' => 'breadbutter_continue_with_popup_delay_seconds',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_message',
                'default' => 'This content is restricted! Please sign in to view it.',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_pages',
                'default' => [],
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_override_dest',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_blur_paragraph_1',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_blur_paragraph_2',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_blur_paragraph_3',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_blur_paragraph_32',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_gating_content_groups',
                'name' => 'breadbutter_gating_content_blur_more',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups'),
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                'name' => 'breadbutter_use_widget_instead_wp_login',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                'name' => 'breadbutter_disabled_wp_admin_bar_for_subscribers',
                'default' => true,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                'name' => 'breadbutter_hide_continue_with_for_returning_users',
                'default' => true,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_advance_option_groups',
                'name' => 'breadbutter_enable_logging',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_blur_paragraph_1',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_blur_paragraph_2',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_blur_paragraph_3',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_blur_paragraph_3_2',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_client_option_groups',
                'name' => 'breadbutter_continue_with_blur_more',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_custom_events_group',
                'name' => 'breadbutter_custom_events_config',
                'default' => '[]',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_user_custom_data_group',
                'name' => 'breadbutter_user_custom_data_header',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_user_custom_data_group',
                'name' => 'breadbutter_user_custom_data_sub_header',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_user_custom_data_group',
                'name' => 'breadbutter_user_custom_data_config',
                'default' => '[]',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            //newsletter
            array(
                'group' => 'breadbutter_secure_forms_group',
                'name' => 'breadbutter_secure_forms_config',
                'default' => '[]',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
        );

        foreach ($this->features as $feature) {
            $args = array_merge($args, $feature->getSettings());
        }
        return $args;
    }

    public function removeSettings() {
        $args = $this->getSettings();
        $this->settings->removeSettings($args);
    }

    public function setSettings() {
        $args = $this->getSettings();
        $this->settings->setSettings($args);
    }

    public function setSections() {
        $args = array(
            array(
                'id' => 'logon_admin_index_app_id',
                // 'title' => 'App Settings',
                // 'callback' => array($this->callbacks, 'logonSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'logon_admin_index_app_secret',
                // 'title' => 'Conversion Settings:',
                // 'callback' => array($this->callbacks, 'logonSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'logon_admin_index_api_path',
                // 'title' => 'App Settings',
                // 'callback' => array($this->callbacks, 'logonSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'logon_admin_index',
                // 'title' => 'App Settings',
                // 'callback' => array($this->callbacks, 'logonSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_dashboard',
                // 'title' => 'BreadButter Client',
                // 'callback' => array($this->callbacks, 'breadbutterSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_ui_config',
                // 'title' => 'BreadButter Client',
                // 'callback' => array($this->callbacks, 'breadbutterSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_ui_config_post',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_user_profile_tools_1',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_user_profile_tools_pos',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_ui_config_continue_with_section',
                // 'title' => 'BreadButter Client',
                // 'callback' => array($this->callbacks, 'breadbutterSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_ui_config_continue_with_header_section',
                // 'title' => 'BreadButter Client',
                // 'callback' => array($this->callbacks, 'breadbutterSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_advance_config',
                // 'title' => 'Advanced Settings (Optional)',
                // 'callback' => array($this->callbacks, 'breadbutterAdvanceSections'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_advance_config_allow_sub_domain',
                'callback' => array($this->callbacks, 'breadbutterSubDomainSection'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_advance_config_google_analytics',
                // 'callback' => array($this->callbacks, 'breadbutterGoogleAnalyticsSection'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'logon_admin_index_continue_with_home_page',
                'page' => 'breadbutter_connect',
            ),
            array(
                'id' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_config_message',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_config_pages',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_gating_content_config_blur_text',
                'callback' => array($this->callbacks, 'breadbutterGatingContentBlurTextSection'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_ui_config_blur_settings',
                // 'callback' => array($this->callbacks, 'breadbutterBlurConfigSection'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_ui_config_theme_settings',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_custom_events_section',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_user_custom_data_section',
                'page' => 'breadbutter_connect'
            ),
            //newsletter
            array(
                'id' => 'breadbutter_secure_forms_section',
                'page' => 'breadbutter_connect'
            ),
        );
        foreach ($this->features as $feature) {
            $args = array_merge($args, $feature->getSections());
        }
        $this->settings->setSections($args);
    }
    public function setFields() {
        $args = array(
            array(
                'id' => 'logon_app_id',
                'title' => 'APP ID',
                'callback' => array($this->callbacks, 'logonAppId'),
                'page' => 'breadbutter_connect',
                'section' => 'logon_admin_index_app_id',
                'args' => array(
                    'label_for' => 'logon_app_id'
                )
            ),
            array(
                'id' => 'logon_app_secret',
                'title' => 'App Secret',
                'callback' => array($this->callbacks, 'logonAppSecret'),
                'page' => 'breadbutter_connect',
                'section' => 'logon_admin_index_app_secret',
                'args' => array(
                    'label_for' => 'logon_app_secret'
                )
            ),
            array(
                'id' => 'logon_api_path',
                'title' => $this->dev ? 'API PATH' : '',
                'callback' => array($this->callbacks, 'logonApiPath'),
                'page' => 'breadbutter_connect',
                'section' => 'logon_admin_index_api_path',
                'args' => array(
                    'label_for' => 'logon_api_path'
                )
            ),
            // array(
            //     'id' => 'logon_theme_style',
            //     'title' => 'THEME',
            //     'callback' => array($this->callbacks, 'logonThemeStyle'),
            //     'page' => 'breadbutter_connect',
            //     'section' => 'logon_admin_index',
            //     'args' => array(
            //         'label_for' => 'logon_theme_style'
            //     )
            // ),
            array(
                'id' => 'breadbutter_conversion_tab',
                'callback' => array($this->callbacks, 'breadbutterConversionTab'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_dashboard',
                'args' => array(
                    'label_for' => 'breadbutter_conversion_tab'
                )
            ),
            array(
                'id' => 'breadbutter_page_view_tracking',
                // 'title' => 'Page View Tracking',
                'callback' => array($this->callbacks, 'breadbutterPageViewTracking'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_dashboard',
                'args' => array(
                    'label_for' => 'breadbutter_page_view_tracking'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_all_pages',
                'title' => 'Enable \'Continue with\' On All Pages',
                'callback' => array($this->callbacks, 'breadbutterContinueWithAllPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_all_pages'
                )
            ),

            array(
                'id' => 'breadbutter_continue_with_show_profile',
                'title' => 'Show Logged In Profile',
                'callback' => array($this->callbacks, 'breadbutterContinueWithShowProfile'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_show_profile'
                )
            ),
            array(
                'id' => 'breadbutter_enable_user_profile',
                'title' => 'Enable User Profile',
                'callback' => array($this->callbacks, 'breadbutterenableUserProfile'),
//                'page' => 'breadbutter_connect',
//                'section' => 'breadbutter_ui_config',
                'args' => array(
                    'label_for' => 'breadbutter_enable_user_profile'
                )
            ),
            array(
                'id' => 'breadbutter_enable_user_profile_tools',
                'title' => 'Enable User Profile Tool',
                'callback' => array($this->callbacks, 'breadbutterEnableUserProfileTools'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_profile_tools_1',
                'args' => array(
                    'label_for' => 'breadbutter_enable_user_profile_tools'
                )
            ),
            array(
                'id' => 'breadbutter_user_profile_position_vertical',
                'title' => 'User Profile Tool Position (Vertical)',
                'callback' => array($this->callbacks, 'breadbutterUserProfilePositionVertical'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_profile_tools_pos',
                'args' => array(
                    'label_for' => 'breadbutter_user_profile_position_vertical'
                )
            ),
            array(
                'id' => 'breadbutter_user_profile_position_vertical_px',
                'title' => 'Margin',
                'callback' => array($this->callbacks, 'breadbutterUserProfilePositionVerticalPx'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_profile_tools_pos',
                'args' => array(
                    'label_for' => 'breadbutter_user_profile_position_vertical_px'
                )
            ),
            array(
                'id' => 'breadbutter_user_profile_position_horizontal',
                'title' => 'User Profile Tool Position (Horizontal)',
                'callback' => array($this->callbacks, 'breadbutterUserProfilePositionHorizontal'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_profile_tools_pos',
                'args' => array(
                    'label_for' => 'breadbutter_user_profile_position_horizontal'
                )
            ),
            array(
                'id' => 'breadbutter_user_profile_position_horizontal_px',
                'title' => 'Margin',
                'callback' => array($this->callbacks, 'breadbutterUserProfilePositionHorizontalPx'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_profile_tools_pos',
                'args' => array(
                    'label_for' => 'breadbutter_user_profile_position_horizontal_px'
                )
            ),
//            array(
//                'id' => 'breadbutter_continue_with_home_page',
//                'title' => "Enable 'Continue with' on Home page",
//                'callback' => array($this->callbacks, 'breadbutterContinueWithHomePage'),
//                'page' => 'breadbutter_connect',
//                'section' => 'logon_admin_index_continue_with_home_page',
//                'args' => array(
//                    'label_for' => 'breadbutter_continue_with_home_page'
//                )
//            ),
//            array(
//                'id' => 'breadbutter_expand_email_address',
//                'title' => 'Show Email Address Field',
//                'callback' => array($this->callbacks, 'breadbutterExpandEmailAddress'),
//                'page' => 'breadbutter_connect',
//                'section' => 'breadbutter_ui_config',
//                'args' => array(
//                    'label_for' => 'breadbutter_expand_email_address'
//                )
//            ),
            // array(
            //     'id' => 'breadbutter_show_login_focus',
            //     'title' => 'Enable Blur Background',
            //     'callback' => array($this->callbacks, 'breadbutterShowLoginFocus'),
            //     'page' => 'breadbutter_connect',
            //     'section' => 'breadbutter_ui_config',
            //     'args' => array(
            //         'label_for' => 'breadbutter_show_login_focus'
            //     )
            // ),
            array(
                'id' => 'breadbutter_app_name',
                'title' => 'Display Name',
                'callback' => array($this->callbacks, 'breadbutterAppName'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_post',
                'args' => array(
                    'label_for' => 'breadbutter_app_name'
                )
            ),
            array(
                'id' => 'breadbutter_callback_url',
                'title' => 'Callback URL',
                'callback' => array($this->callbacks, 'breadbutterCallbackUrl'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config',
                'args' => array(
                    'label_for' => 'breadbutter_callback_url'
                )
            ),
            array(
                'id' => 'breadbutter_destination_url',
                'title' => 'Destination URL',
                'callback' => array($this->callbacks, 'breadbutterDestinationUrl'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config',
                'args' => array(
                    'label_for' => 'breadbutter_destination_url'
                )
            ),

            array(
                'id' => 'breadbutter_continue_with_pages',
                'title' => 'Continue with Pages',
                'callback' => array($this->callbacks, 'breadbutterContinueWithPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_pages'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_position_vertical',
                'title' => 'Continue With Position (Vertical)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithPositionVertical'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_position_vertical'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_position_vertical_px',
                'title' => 'Margin',
                'callback' => array($this->callbacks, 'breadbutterContinueWithPositionVerticalPx'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_position_vertical_px'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_position_horizontal',
                'title' => 'Continue With Position (Horizontal)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithPositionHorizontal'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_position_horizontal'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_position_horizontal_px',
                'title' => 'Margin',
                'callback' => array($this->callbacks, 'breadbutterContinueWithPositionHorizontalPx'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_position_horizontal_px'
                )
            ),

            array(
                'id' => 'breadbutter_continue_with_success_seconds',
                'title' => 'Success message seconds',
                'callback' => array($this->callbacks, 'breadbutterContinueWithSuccessSeconds'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_success_seconds'
                )
            ),

            array(
                'id' => 'breadbutter_continue_with_success_header',
                'title' => 'Success message header',
                'callback' => array($this->callbacks, 'breadbutterContinueWithSuccessHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_success_header'
                )
            ),

            array(
                'id' => 'breadbutter_continue_with_success_text',
                'title' => 'Success message',
                'callback' => array($this->callbacks, 'breadbutterContinueWithSuccessText'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_success_text'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_header_new_user_no_display',
                'title' => 'New User (no Display Name set)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithHeaderNewUserNoDisplay'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_header_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_header_new_user_no_display'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_header_new_user_display',
                'title' => 'New User (Display Name set)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithHeaderNewUserDisplay'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_header_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_header_new_user_display'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_header_return_user_no_display',
                'title' => 'Returning User (no Display Name set)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithHeaderReturnUserNoDisplay'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_header_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_header_return_user_no_display'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_header_return_user_display',
                'title' => 'Returning User (Display Name set)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithHeaderReturnUserDisplay'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_continue_with_header_section',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_header_return_user_display'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_blur_paragraph_1',
                'title' => 'First Paragraph',
                'callback' => array($this->callbacks, 'breadbutterContinueWithBlurParagraph1'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_blur_settings',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_blur_paragraph_1'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_blur_paragraph_2',
                'title' => 'Second Paragraph',
                'callback' => array($this->callbacks, 'breadbutterContinueWithBlurParagraph2'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_blur_settings',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_blur_paragraph_2'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_blur_paragraph_3',
                'title' => 'Third Paragraph',
                'callback' => array($this->callbacks, 'breadbutterContinueWithBlurParagraph3'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_blur_settings',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_blur_paragraph_3'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_blur_paragraph_3_2',
                'title' => 'Expanded Third Paragraph',
                'callback' => array($this->callbacks, 'breadbutterContinueWithBlurParagraph32'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_blur_settings',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_blur_paragraph_3_2'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_blur_more',
                'title' => 'Expand Label',
                'callback' => array($this->callbacks, 'breadbutterContinueWithBlurMore'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_blur_settings',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_blur_more'
                )
            ),
            array(
                'id' => 'breadbutter_button_theme',
                'title' => '',
                'callback' => array($this->callbacks, 'breadbutterButtonTheme'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_ui_config_theme_settings',
                'args' => array(
                    'label_for' => 'breadbutter_button_theme'
                )
            ),
            array(
                'id' => 'breadbutter_allow_sub_domain',
                'title' => 'Allow Subdomain',
                'callback' => array($this->callbacks, 'breadbutterAllowSubDomain'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_allow_sub_domain',
                'args' => array(
                    'label_for' => 'breadbutter_allow_sub_domain'
                )
            ),
            array(
                'id' => 'breadbutter_ga_measurement_id',
                'title' => 'Measurement ID',
                'callback' => array($this->callbacks, 'breadbutterGaMeasurementId'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_google_analytics',
                'args' => array(
                    'label_for' => 'breadbutter_ga_measurement_id'
                )
            ),
            array(
                'id' => 'breadbutter_continue_with_popup_delay_seconds',
                'title' => '‘Continue with’ popup delay (Seconds)',
                'callback' => array($this->callbacks, 'breadbutterContinueWithPopupDelaySeconds'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
                'args' => array(
                    'label_for' => 'breadbutter_continue_with_popup_delay_seconds'
                )
            ),
            array(
                'id' => 'breadbutter_show_login_buttons_on_login_page',
                'title' => 'Show SSO buttons on WordPress Login page',
                'callback' => array($this->callbacks, 'breadbutterShowLoginButtonsOnLoginPage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
                'args' => array(
                    'label_for' => 'breadbutter_show_login_buttons_on_login_page'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_message',
                'title' => 'Message for Signed Out Users',
                'callback' => array($this->callbacks, 'breadbutterGatingContentMessage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_message',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_message'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_pages',
                'title' => 'Gated Pages',
                'callback' => array($this->callbacks, 'breadbutterGatingContentPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_pages',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_pages'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_blur_paragraph_1',
                'title' => 'First Paragraph',
                'callback' => array($this->callbacks, 'breadbutterGatingContentBlurParagraph1'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_blur_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_blur_paragraph_1'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_blur_paragraph_2',
                'title' => 'Second Paragraph',
                'callback' => array($this->callbacks, 'breadbutterGatingContentBlurParagraph2'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_blur_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_blur_paragraph_2'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_blur_paragraph_3',
                'title' => 'Third Paragraph',
                'callback' => array($this->callbacks, 'breadbutterGatingContentBlurParagraph3'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_blur_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_blur_paragraph_3'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_blur_paragraph_32',
                'title' => 'Expanded Third Paragraph',
                'callback' => array($this->callbacks, 'breadbutterGatingContentBlurParagraph32'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_blur_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_blur_paragraph_32'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_blur_more',
                'title' => 'Expand label',
                'callback' => array($this->callbacks, 'breadbutterGatingContentBlurMore'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_blur_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_blur_more'
                )
            ),
            array(
                'id' => 'breadbutter_gating_content_override_dest',
                'title' => 'Override Registration Destination URL',
                'callback' => array($this->callbacks, 'breadbutterGatingContentOverrideDest'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_gating_content_config_blur_text',
                'args' => array(
                    'label_for' => 'breadbutter_gating_content_override_dest'
                )
            ),
            array(
                'id' => 'breadbutter_custom_events_config',
                'title' => '',
                'callback' => array($this->callbacks, 'breadbutterCustomEventsConfig'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_custom_events_section',
                'args' => array(
                    'label_for' => 'breadbutter_custom_events_config'
                )
            ),

            array(
                'id' => 'breadbutter_user_custom_data_header',
                'title' => 'Header',
                'callback' => array($this->callbacks, 'breadbutterUserCustomDataHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_custom_data_section',
                'args' => array(
                    'label_for' => 'breadbutter_user_custom_data_header'
                )
            ),
            array(
                'id' => 'breadbutter_user_custom_data_sub_header',
                'title' => 'Sub Header',
                'callback' => array($this->callbacks, 'breadbutterUserCustomDataSubHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_custom_data_section',
                'args' => array(
                    'label_for' => 'breadbutter_user_custom_data_sub_header'
                )
            ),
            array(
                'id' => 'breadbutter_user_custom_data_config',
                'title' => '',
                'callback' => array($this->callbacks, 'breadbutterUserCustomDataConfig'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_user_custom_data_section',
                'args' => array(
                    'label_for' => 'breadbutter_user_custom_data_config'
                )
            ),
            //newsletter
            array(
                'id' => 'breadbutter_secure_forms_config',
                'title' => '',
                'callback' => array($this->callbacks, 'breadbutterSecureFormsConfig'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_secure_forms_section',
                'args' => array(
                    'label_for' => 'breadbutter_secure_forms_config'
                )
            ),
            // array(
            //     'id' => 'breadbutter_use_widget_instead_wp_login',
            //     'title' => 'Replace WordPress Login page with Bread & Butter widget',
            //     'callback' => array($this->callbacks, 'breadbutterUseWidgetInsteadWpLogin'),
            //     'page' => 'breadbutter_connect',
            //     'section' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
            //     'args' => array(
            //         'label_for' => 'breadbutter_use_widget_instead_wp_login'
            //     )
            // ),
            array(
                'id' => 'breadbutter_disabled_wp_admin_bar_for_subscribers',
                'title' => 'Turn off WordPress Admin Bar for Subscriber users',
                'callback' => array($this->callbacks, 'breadbutterDisabledWPAdminBarForSubscribers'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
                'args' => array(
                    'label_for' => 'breadbutter_disabled_wp_admin_bar_for_subscribers'
                ),
            ),
            array(
                'id' => 'breadbutter_hide_continue_with_for_returning_users',
                'title' => "Hide 'Continue with' for returning users",
                'callback' => array($this->callbacks, 'breadbutterHideContinueWithForReturningUsers'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
                'args' => array(
                    'label_for' => 'breadbutter_hide_continue_with_for_returning_users'
                ),
            ),
            array(
                'id' => 'breadbutter_enable_logging',
                'title' => "Enable Logging",
                'callback' => array($this->callbacks, 'breadbutterEnableLogging'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_advance_config_show_login_buttons_on_login_page',
                'args' => array(
                    'label_for' => 'breadbutter_enable_logging'
                ),
            ),
        );

        foreach ($this->features as $feature) {
            $args = array_merge($args, $feature->getFields());
        }
        $this->settings->setFields($args);
    }
}