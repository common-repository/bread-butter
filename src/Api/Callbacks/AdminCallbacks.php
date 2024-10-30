<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Api\Callbacks;

use BreadButter_WP_Plugin\Base\BaseController;
use BreadButter_WP_Plugin\Base\GatingContent;
use BreadButter_WP_Plugin\Base\Newsletter;

class AdminCallbacks extends BaseController {
    public $toggleBlockStatus = ['orange', 'green', 'black'];

    public function adminDashboard() {
        return require_once "$this->plugin_path/templates/admin.php";
    }

    public function logonOptionGroups($input) {
        return $input;
    }

    public function logonAppSecretValidation($input) {
        $secret = get_option('logon_app_secret');
        if (empty($input)) {
            $input = $secret;
        }
        return $input;
    }

    public function breadbutterAdvanceOptionGroups($input) {
        return $input;
    }
    public function breadbutterClientOptionGroups($input) {
        return $input;
    }

    public function breadbutterEnableContinueWith($input) {
        if ($input) {
            update_option('breadbutter_enable_user_profile_tools', 'on');
        }
        return $input;
    }

    public function breadbutterWithShowProfile($input) {
        if ($input) {
            update_option('breadbutter_enable_user_profile', 'on');
        }
        return $input;
    }

    public function logonSections() {
        echo 'App Information';
    }

    public function breadbutterSections() {
        echo '<div class="breadbutter-section">The following settings will apply to all Bread & Butter Widgets. Please note that the following can be changed per page, in the Block Editor. Settings in the Block Editor take precedence over the settings below. You can find the Block Editor by clicking the "toast" icon at top right, when editing a post or page.</div>';
    }

    public function breadbutterAdvanceSections() {
        echo '<div class="breadbutter-section">By Default, Bread & Butter will use the Callback URL defined in your App Settings in Bread & Butter. However, you can use the settings below to specify different Callback URLs or Destination URLs for your Wordpress site. Please note that any addresses entered below must be added to the corresponding Allow Lists in your App Settings in Bread & Butter.</div>';
    }

    public function breadbutterSubDomainSection() {
        echo '<div class="breadbutter-section">The following option enables a cookie that allows the Javascript Library to access a user\'s Device ID across subdomains. Enable this feature if you have the Javascript Library running on 2 different subdomains, using the same Bread & Butter App. This option will also need to be enabled in the Javascript Library configuration on the other subdomains.</div>';
    }

    public function breadbutterGoogleAnalyticsSection() {
        echo '<div class="breadbutter-section">Google Analytics.</div>';
    }

    public function logonAppId() {
        $value = esc_attr(get_option('logon_app_id'));
        echo '<input type="text" class="regular-text fixed-row" name="logon_app_id" value="' . $value . '" placeholder="APP ID"/>';
    }

    public function breadbutterOrganizationID() {
        $value = esc_attr(get_option('breadbutter_organization_id'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_organization_id" value="' . $value . '" placeholder="Partner Code"/>';
    }

    public function logonAppSecret() {
        $secret = esc_attr(get_option('logon_app_secret'));
        if (!empty($secret)) {
            echo '<div class="regular-text fixed-row secret-update-button js-secret-update-button">Update Secret</div>';
            // echo '<input id="secret-update-field" type="text" class="regular-text fixed-row" name="logon_app_secret" value="' . $secret . '" placeholder="APP SECRET"/>';
        } else {
            echo '<input type="text" class="regular-text fixed-row" name="logon_app_secret" placeholder="App Secret"/>';
        }
    }

    public function logonApiPath() {
        $path = esc_attr(get_option('logon_api_path'));
        //'https://api.logonlabs.com'
        //'https://api.logon-dev-stable.com'
        //'https://api.logon-dev.com'
        //echo '<input type="text" class="regular-text" name="logon_api_path" value="' . $path . '" placeholder="API PATH"/>';

        if ($this->dev) {
            echo '<select class="select-row" name="logon_api_path">';
            echo '<option value="https://api.breadbutter.io" ' . ($path == 'https://api.breadbutter.io' ? 'selected' : '') . '>Production</option>';
            echo '<option value="https://api-stable.breadbutter.io" ' . ($path == 'https://api-stable.breadbutter.io' ? 'selected' : '') . '>Dev Stable</option>';
            echo '<option value="https://api-devlab.breadbutter.io" ' . ($path == 'https://api-devlab.breadbutter.io' ? 'selected' : '') . '>Dev</option>';
            echo '</select>';
        } else {
            echo '<input type="hidden" name="logon_api_path" value="https://api.breadbutter.io"/>';
        }
    }

    public function checkNewsletterOptions() {

        $header = get_option('breadbutter_newsletter_header');
        $main_message = get_option('breadbutter_newsletter_main_message');
        $success_message = get_option('breadbutter_newsletter_success_message');
        $success_header = get_option('breadbutter_newsletter_success_header');
        $custom_image_type = get_option('breadbutter_newsletter_custom_image_type');
        $custom_image = get_option('breadbutter_newsletter_custom_image');
        $delay = get_option('breadbutter_newsletter_delay_popup', 5) != 5;
        $event_id = get_option('breadbutter_newsletter_event_id');
        $override = get_option('breadbutter_newsletter_override_dest', false);

        $has_content = false;

        if (!empty($event_id)) {
            $has_content = true;
        }
        return $has_content;
    }

    public function generateSelectOption($id, $value, $key, $name, $disabled) {
        echo '<option value="'.$key.'" ' .  (($value == $key) ? 'selected' : '') . '>'. $name .'</option>';
    }

    public function breadbutterNewsletterType() {
        $id = 'breadbutter_newsletter_type';
        $value = esc_attr(get_option($id));

        $disabled = $this->checkNewsletterOptions();
//        function generate($id, $value, $key, $name, $disabled) {
//            $label_id = $id . '_' . $key;
//            echo '<div class="flex-row" style="margin-bottom: 0; padding: 1rem;">';
//            echo '<input type="radio" id="'. $label_id . '" name="'. $id .'" value="'. $key .'" ' . (($value == $key) ? 'checked' : '') . ' '. ($disabled ? 'disabled': '') .'/>' ;
//            echo '<label for="'. $label_id .'">'. $name .'</label>';
//            echo '</div>';
//        }

        echo '<select class="select-row" name="'. $id .'" '. ($disabled ? 'disabled': '') .'>';
        if (!$disabled) {
            echo '<option value="" name="empty_value"></option>';
        }
        $this->generateSelectOption($id, $value, 'contest', __('Contest', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'special_offer', __('Special Offer', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'newsletter_signup', __('Newsletter Signup', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'custom', __('Custom', 'breadbutter-connect'), $disabled);
        echo '</select>';
    }
    public function logonThemeStyle() {
        $style = esc_attr(get_option('logon_theme_style'));

        echo '<div class="flex-row" style="margin-bottom: 0; padding: 1rem;">';
        echo '<input type="radio" name="logon_theme_style" value="round-icons" ' . (($style == 'round-icons' || $style == 'icon') ? 'checked' : '') . '/>' ;
        echo '<img src="' . $this->plugin_url . 'assets/bb-round.png" border="0" height="45"/>';
        echo '</div>';
        // echo '<div class="flex-row">';
        // echo '<input type="radio" name="logon_theme_style" value="square-icons" ' . ($style == 'square-icons' ? 'checked' : '') . '/>' ;
        // echo '<img src="' . $this->plugin_url . 'assets/bb-square.png" border="0" />';
        echo '</div>';
        echo '<div class="flex-row" style="margin-bottom: 0; padding: 1rem;">';
        echo '<input type="radio" name="logon_theme_style" value="tiles" ' . (($style == 'tiles' || $style == 'button') ? 'checked' : '') . '/>';
        echo '<img src="' . $this->plugin_url . 'assets/bb-tile.png" border="0" height="180g"/>';
        echo '</div>';
    }

    public function toggleButton($name, $value, $disabled = false) {
        $checked = (!empty($value) ? 'checked' : '');
        $disabled = $disabled ? "disabled=1" : "";
        $form = "<div class='flex-row bb-dashboard-tile-button'>
            <div class='toggleOFF'>OFF</div>
            <div class='toggleWrapper'>
            <input type='checkbox' name='$name' class='mobileToggle' id='$name' $checked $disabled>
            <label for='$name'></label>
            </div>
            <div class='toggleON'>ON</div>
        </div>";
        echo $form;
    }

    public function toggleBlock($value) {
        echo "<div class='flex-col bb-dashboard-tile-show-box'>";
        echo "</div>";
    }

    public function breadbutterPageViewTracking() {
        $app_id = esc_attr(get_option('logon_app_id'));
        $code = '';
        if ($app_id) {
            $client = $this->getClient();
            $app_id = get_option('logon_app_id');
            $response = $client->getApp($app_id);
            if (isset($response['body']) && isset($response['body']['organization_id'])) {
                $code = $response['body']['organization_id'];
            }
        }
        $secret = esc_attr(get_option('logon_app_secret'));
//        $code = '';
//        $secret = '';
        if (empty($code)) {
            $class = 'agency-row';
        } else {
            $class = '';
        }
        $value = 1;
        echo "<div class='breadbutter-tab breadbutter-inline-box $class bb-dashboard-not-set js-breadbutter-tab-page-view-tracking'>";
        echo "<div class='flex-row'>";
        echo "<div class='flex-col bb-dashboard-tile-content-box'>";
        echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
        $this->toggleBlock($value);
        echo "Website Analytics: <span class='enable-text'>" . (empty($value)? "Disabled" : " Enabled") . "</span></div>";

        echo "<div class='bb-dashboard-tile-content bb-analytics-buttons'>
            <div>Get insight into how visitors are interacting with your site. Includes automated lead scoring, full visitor profiles, and user journey mapping. Exclusive to partners.</div>
            <div class='bb-flex-box'>
            <a class='bb-dashboard-tile-content-fill-button' href='https://app.breadbutter.io/app/#/dashboard' target='_BLANK'><span>Analyze conversions</span></a>
            <div class='bb-spacer'></div>            
            <a class='bb-dashboard-tile-content-fill-button' href='https://app.breadbutter.io/app/#/dashboard' target='_BLANK'><span>View my visitors</span></a>
            </div>
        </div>
        ";
        if (empty($code)) {
            if (empty($secret)) {
                echo "
        <div class='bb-dashboard-tile-content bb-agency-code'>
            <div>Get insight into how visitors are interacting with your site. Includes automated lead scoring, full visitor profiles, and user journey mapping. Exclusive to partners.</div>
            <div class='bb-flex-box'>
                <a class='bb-dashboard-tile-content-fill-button' href='https://breadbutter.io/contact/?source=getyouragencycode' target='_BLANK'><span>Get your code</span></a>
            </div>
        </div>
        ";
            } else {
                echo "
        <div class='bb-dashboard-tile-content bb-agency-code'>
            <div>Get insight into how visitors are interacting with your site. Includes automated lead scoring, full visitor profiles, and user journey mapping. Exclusive to partners.</div>
            <div class='bb-grid'>
                <label for='breadbutter_organization_id'>Partner Code</label>
                <input type='text' name='breadbutter_organization_id' value='$code'>
            </div>
            <div class='bb-flex-box'>
                <input type='submit' name='submit' id='submit' class='button button-primary' value='SAVE'>
                <div class='bb-spacer'></div>            
                <a class='bb-dashboard-tile-content-fill-button' href='https://breadbutter.io/contact/?source=getyouragencycode' target='_BLANK'><span>Get your code</span></a>
            </div>
        </div>
        ";
            }
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";

    }
    public function breadbutterContinueWithAllPages() {
        $value = esc_attr(get_option('breadbutter_continue_with_all_pages'));
        echo '<input type="checkbox" name="breadbutter_continue_with_all_pages" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterContinueWithShowProfile() {
        $id = 'breadbutter_continue_with_show_profile';
        $value = esc_attr(get_option($id));
        echo '<input type="checkbox" name="'.$id.'" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterenableUserProfile() {
        $value = esc_attr(get_option('breadbutter_enable_user_profile'));

        // 'off' means option was added into activated plugin.
        // in this case we have to leave it disable and user can manually activate it.
        // Ref: https://logonlabs.atlassian.net/browse/WPP-84
        if ('off' === $value) {
            $value = null;
        }
        echo '<input type="checkbox" name="breadbutter_enable_user_profile" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterEnableUserProfileTools() {
        $value = esc_attr(get_option('breadbutter_enable_user_profile_tools'));
        $id = 'breadbutter_enable_user_profile_tools';
        // 'off' means option was added into activated plugin.
        // in this case we have to leave it disable and user can manually activate it.
        // Ref: https://logonlabs.atlassian.net/browse/WPP-84
        if ('off' === $value) {
            $value = null;
        }
        echo '<input type="checkbox" name="'.$id.'" '. (!empty($value) ? 'checked' : '') .'/>';
    }


    public function breadbutterContinueWithHomePage() {
        $value = esc_attr(get_option('breadbutter_continue_with_home_page'));
        echo '<input type="checkbox" name="breadbutter_continue_with_home_page" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterConversionTab() {
        $status = 1;
        $secret = esc_attr(get_option('logon_app_secret'));
        if (!$status) {
            $value = 0;
        }
        else if (empty($secret)) {
            $status = 0;
            $value = 2;
        } else {
            $value = 1;
        }

        echo "<div class='breadbutter-tab breadbutter-inline-box bb-dashboard-not-set js-breadbutter-tab-conversion' data-app-secret-set='" . !empty($secret) . "'>";
        echo "<div class='flex-row'>";
        echo "<div class='flex-col bb-dashboard-tile-content-box'>";
        echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
        $this->toggleBlock($value);
        echo "One-click Conversions: <span class='enable-text'>" . (empty($status)? "Disabled" : " Enabled") . "</span></div>";
        echo "<div class='bb-dashboard-tile-content'>
            <div>Turn visitors into customers. Allow visitors to convert into your WordPress database subscribers using our built-in conversion tools.</div>";
        echo "
            <div class='bb-flex-box'>
            <div class='bb-spacer'></div>            
            <a class='bb-dashboard-tile-content-fill-button' href='https://app.breadbutter.io/app/#/tools' target='_BLANK'><span>Add more tools to my site now</span></a>
            <div class='bb-spacer'></div>            
            </div>
        ";
//        echo "<div class='bb-dashboard-tile-continue-with'>";
//            do_settings_section('breadbutter_connect', 'logon_admin_index_continue_with_home_page');
//            submit_button("SAVE");
//        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    public function breadbutterExpandEmailAddress() {
        $value = esc_attr(get_option('breadbutter_expand_email_address'));
        echo '<input type="checkbox" name="breadbutter_expand_email_address" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterShowLoginFocus() {
        $value = esc_attr(get_option('breadbutter_show_login_focus'));
        echo '<input type="checkbox" name="breadbutter_show_login_focus" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function addSpacer() {
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan=2>";
        echo "<div class='bb-spacer'></div>";
    }

    public function breadbutterAppName() {
        $name = esc_attr(get_option('breadbutter_app_name'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_app_name" value="'. $name .'"/>';
        $this->addSpacer();
    }

    public function breadbutterContinueWithPopupDelaySeconds() {
        $id = 'breadbutter_continue_with_popup_delay_seconds';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'"/>';
    }

    public function breadbutterCallbackUrl() {
        $value = esc_attr(get_option('breadbutter_callback_url'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_callback_url" value="'. $value .'"/>';
    }

    public function breadbutterDestinationUrl() {
        $value = esc_attr(get_option('breadbutter_destination_url'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_destination_url" value="'. $value .'"/>';
    }

    public function breadbutterGaMeasurementId() {
        $value = esc_attr(get_option('breadbutter_ga_measurement_id'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_ga_measurement_id" value="'. $value .'"/>';
    }

    public function breadbutterAllowSubDomain() {
        $value = esc_attr(get_option('breadbutter_allow_sub_domain'));
        echo '<input type="checkbox" name="breadbutter_allow_sub_domain" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterShowLoginButtonsOnLoginPage() {
        $value = esc_attr(get_option('breadbutter_show_login_buttons_on_login_page'));
        echo '<input type="checkbox" name="breadbutter_show_login_buttons_on_login_page" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterUseWidgetInsteadWpLogin() {
        $value = esc_attr(get_option('breadbutter_use_widget_instead_wp_login'));
        echo '<input type="checkbox" name="breadbutter_use_widget_instead_wp_login" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterDisabledWPAdminBarForSubscribers() {
        $value = esc_attr(get_option('breadbutter_disabled_wp_admin_bar_for_subscribers'));
        echo '<input type="checkbox" name="breadbutter_disabled_wp_admin_bar_for_subscribers" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterHideContinueWithForReturningUsers() {
        $value = esc_attr(get_option('breadbutter_hide_continue_with_for_returning_users'));
        echo '<input type="checkbox" name="breadbutter_hide_continue_with_for_returning_users" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterEnableLogging() {
        $value = esc_attr(get_option('breadbutter_enable_logging'));
        echo '<input type="checkbox" name="breadbutter_enable_logging" '. (!empty($value) ? 'checked' : '') .'/>';
        // echo '<div> Will be available here: ' . $this->plugin_url . 'logging.txt</div>';
    }

    public function breadbutterContinueWithPositionVertical() {
        $id = 'breadbutter_continue_with_position_vertical';
        $value = esc_attr(get_option($id));
        echo '<select class="select-row" name="'. $id .'">';
        echo '<option value="top" ' . ($value == 'top' ? 'selected' : '') . '>Top</option>';
        echo '<option value="bottom" ' . ($value == 'bottom' ? 'selected' : '') . '>Bottom</option>';
        echo '</select>';

    }
    public function breadbutterContinueWithPositionVerticalPx() {
        $value = esc_attr(get_option('breadbutter_continue_with_position_vertical_px'));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 class="regular-text number-row" name="breadbutter_continue_with_position_vertical_px" value="'. $value .'"/><span> px</span>';
    }
    public function breadbutterContinueWithPositionHorizontal() {
        $value = esc_attr(get_option('breadbutter_continue_with_position_horizontal'));

        echo '<select class="select-row" name="breadbutter_continue_with_position_horizontal">';
        echo '<option value="left" ' . ($value == 'left' ? 'selected' : '') . '>Left</option>';
        echo '<option value="right" ' . ($value == 'right' ? 'selected' : '') . '>Right</option>';
        echo '</select>';

    }
    public function breadbutterContinueWithPositionHorizontalPx() {
        $value = esc_attr(get_option('breadbutter_continue_with_position_horizontal_px'));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 class="regular-text number-row" name="breadbutter_continue_with_position_horizontal_px" value="'. $value .'"/><span> px</span>';
        $this->addSpacer();
    }
    public function breadbutterUserProfilePositionVertical() {
        $id = 'breadbutter_user_profile_position_vertical';
        $value = esc_attr(get_option($id));

        echo '<select class="select-row" name="'.$id.'">';
        echo '<option value="top" ' . ($value == 'top' ? 'selected' : '') . '>Top</option>';
        echo '<option value="bottom" ' . ($value == 'bottom' ? 'selected' : '') . '>Bottom</option>';
        echo '</select>';

    }
    public function breadbutterUserProfilePositionVerticalPx() {
        $id = 'breadbutter_user_profile_position_vertical_px';
        $value = esc_attr(get_option($id));

        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 class="regular-text number-row" name="'.$id.'" value="'. $value .'"/><span> px</span>';
    }
    public function breadbutterUserProfilePositionHorizontal() {
        $id = 'breadbutter_user_profile_position_horizontal';
        $value = esc_attr(get_option($id));

        echo '<select class="select-row" name="'.$id.'">';
        echo '<option value="left" ' . ($value == 'left' ? 'selected' : '') . '>Left</option>';
        echo '<option value="right" ' . ($value == 'right' ? 'selected' : '') . '>Right</option>';
        echo '</select>';

    }
    public function breadbutterUserProfilePositionHorizontalPx() {
        $id = 'breadbutter_user_profile_position_horizontal_px';
        $value = esc_attr(get_option($id));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 class="regular-text number-row" name="'.$id.'" value="'. $value .'"/><span> px</span>';
        $this->addSpacer();
    }

    public function breadbutterContinueWithHeaderNewUserNoDisplay() {
        $value = esc_attr(get_option('breadbutter_continue_with_header_new_user_no_display'));
        
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_header_new_user_no_display" value="'. $value .'"
        placeholder="'.__( 'Sign in for your best experience', 'breadbutter-connect' ).'" />';
    }

    public function breadbutterContinueWithHeaderNewUserDisplay() {
        $value = esc_attr(get_option('breadbutter_continue_with_header_new_user_display'));
        
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_header_new_user_display" value="'. $value .'"
        placeholder="'.__( 'Sign in for your best experience', 'breadbutter-connect' ).'" />';
    }

    public function breadbutterContinueWithHeaderReturnUserNoDisplay() {
        $value = esc_attr(get_option('breadbutter_continue_with_header_return_user_no_display'));
        
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_header_return_user_no_display" value="'. $value .'"
        placeholder="'.__( 'Sign in for your best experience', 'breadbutter-connect' ).'" />';
    }

    public function breadbutterContinueWithHeaderReturnUserDisplay() {
        $value = esc_attr(get_option('breadbutter_continue_with_header_return_user_display'));
        
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_header_return_user_display" value="'. $value .'"
        placeholder="'.__( 'Welcome back to %APP_NAME%', 'breadbutter-connect' ).'" />';
    }

    public function breadbutterButtonTheme() {
        $style = esc_attr(get_option('breadbutter_button_theme'));

        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan=2>";

        echo '<div class="flex-row breadbutter-button-theme-choices">';
        echo '<div class="flex-col">';
        echo '<div class="flex-1">';
            echo '<img src="' . $this->plugin_url . 'assets/bb-round.png" border="0" height="45"/>';
        echo '</div>';
        echo '<input type="radio" name="breadbutter_button_theme" value="round-icons" ' . (($style == 'round-icons' || $style == 'icon') ? 'checked' : '') . '/>' ;
        echo '</div>';
        echo '<div class="flex-col">';
        echo '<div class="flex-1">';
            echo '<img src="' . $this->plugin_url . 'assets/bb-tile.png" border="0" height="180g"/>';
        echo '</div>';
        echo '<input type="radio" name="breadbutter_button_theme" value="tiles" ' . (($style == 'tiles' || $style == 'button') ? 'checked' : '') . '/>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    public function breadbutterGatingContentMessage() {
        $value = get_option('breadbutter_gating_content_message', 'This content is restricted! Please sign in to view it.');
        wp_editor($value, 'edito_id_breadbutter_gating_content_message', [
            'textarea_name' => 'breadbutter_gating_content_message',
        ]);
    }

    public function breadbutterGatingContentPages() {
        $this->applyPageSelection('breadbutter_gating_content_pages');
    }

    public function breadbutterGatingContentPreviewPages() {
        $this->applyPageSelection('breadbutter_gating_content_preview_pages');
    }

    public function breadbutterGatingContentGatingPages() {
        $this->applyPageSelection('breadbutter_gating_content_gating_pages');
    }

    public function breadbutterGatingContentEnabled() {
        $id = 'breadbutter_gating_content_gating_config_enabled';
        $value = esc_attr(get_option($id));
        echo '<input type="checkbox" name="'.$id.'" '. (!empty($value)  ? 'checked' : '') .'/>';
    }

    public function breadbutterGatingPreviewEnabled() {
        $id = 'breadbutter_gating_content_preview_config_enabled';
        $value = esc_attr(get_option($id));
        echo '<input type="checkbox" name="'.$id.'" '. (!empty($value)  ? 'checked' : '') .'/>';
    }
    public function applyPageSelection($id) {
        $value = get_option($id, []);
        $posts = get_posts(['numberposts' => -1, 'post_type' => 'any']);
        $selected_all = is_array($value) && in_array(AdminCallbacks::$allOption, $value) ? 'selected' : '';
        $option = '
            <option value="' . AdminCallbacks::$allOption . '" ' . $selected_all . '>All Pages</option>
        ';

//        $categories = get_categories(['hide_empty'=> false]);
//        $tags = get_tags();
//        print_r($categories);
//        foreach($categories as $category) {
//            echo $category->name;
//            echo " ";
//        }
//        echo "<br>";
//        foreach($tags as $tag) {
//            echo $tag->name;
//            echo " ";
//        }
//        echo "<br>";
//        print_r($tags);
//        $post_types = AdminCallbacks::getPostTypes();
//        foreach($post_types as $post_type) {
//            $selected = is_array($value) && in_array($post_type, $value) ? 'selected' : '';
//            $option .= '
//                <option value="' . $post_type . '" ' . $selected . '>All ' . $post_type . ' posts</option>
//            ';
//        }

        $front_page_id = get_option('page_on_front');
        foreach($posts as $post) {
            if ($front_page_id == $post->ID) {
                $selected = is_array($value) && in_array($post->ID, $value) ? 'selected' : '';
                $option .= "<option value='{$post->ID}' $selected>{$post->post_title} (id: {$post->ID})</option>";
            }
        }
        foreach($posts as $post) {
            if ($front_page_id != $post->ID) {
                $selected = is_array($value) && in_array($post->ID, $value) ? 'selected' : '';
                $option .= "<option value='{$post->ID}' $selected>{$post->post_title} (id: {$post->ID})</option>";
            }
        }
        echo "<select class='js-bb-select2 bb-select2' name='" . $id . "[]' multiple>$option</select>";
    }

    public function breadbutterGatingContentGatingHeader() {
        $id = 'breadbutter_gating_content_gating_text_header';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="' . $id . '" value="'. $value .'" style="width: 98%;"/>';
    }

    public function breadbutterGatingContentGatingSubHeader() {
        $id = 'breadbutter_gating_content_gating_text_subheader';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="' . $id . '" value="'. $value .'" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentPreviewParagraph() {
        $id = 'breadbutter_gating_content_preview_text_3';
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely.', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'.$id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }

    public function breadbutterGatingContentPreviewExpandedParagraph() {
        $id = 'breadbutter_gating_content_preview_text_3_2';
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. You may be asked to share your profile information, which will be stored securely. You can change your authentication method at any time.', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'.$id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }

    public function breadbutterGatingContentPreviewExpandLabel() {
        $id = 'breadbutter_gating_content_preview_label';
        $placeholder = __('more>>', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'.$id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }

    public function breadbutterGatingContentPreviewParagraph1() {
        $id = 'breadbutter_gating_content_preview_text_1';
        $placeholder = __('Sign in to continue', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'.$id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentPreviewParagraph2() {
        $id = 'breadbutter_gating_content_preview_text_2';
        $placeholder = __('\'Continue with\' your trusted login method.<br/>We\'ll get you going in no time.', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'.$id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentPreviewScrollLimit() {
        $id = 'breadbutter_gating_content_preview_scroll_limit';
        $value = esc_attr(get_option($id));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 max=99 style="width: 50%" class="regular-text number-row" name="'.$id.'" value="'. $value .'"/><span>%</span>';
    }

    public function breadbutterGatingContentGatingScrollLimit() {
        $id = 'breadbutter_gating_content_gating_scroll_limit';
        $value = esc_attr(get_option($id));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 max=99 style="width: 50%" class="regular-text number-row" name="'.$id.'" value="'. $value .'"/><span>%</span>';
    }

    public function breadbutterGatingContentPreviewTimeLimit() {
        $id = 'breadbutter_gating_content_preview_time_limit';
        $value = esc_attr(get_option($id));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 style="width: 50%" class="regular-text number-row" name="'.$id.'" value="'. $value .'"/>';
    }

    public function breadbutterGatingContentGatingTimeLimit() {
        $id = 'breadbutter_gating_content_gating_time_limit';
        $value = esc_attr(get_option($id));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 style="width: 50%" class="regular-text number-row" name="'.$id.'" value="'. $value .'"/>';
    }

    public function breadbutterGatingContentPreviewHeight() {
        $id = 'breadbutter_gating_content_preview_height';
        $value = esc_attr(get_option($id));
        if (!is_numeric($value)) {
        }
        echo '<input type="number" min=0 max=99 style="width: 50%" class="regular-text number-row" name="'.$id.'" ' . ((is_numeric($value)) ? 'value="'. $value .'"' : '') . '/><span>%</span>';
    }

    public function breadbutterGatingContentBlurParagraph1() {
        $value = esc_attr(get_option('breadbutter_gating_content_blur_paragraph_1'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_gating_content_blur_paragraph_1" value="'. $value .'" 
            placeholder="Sign in to continue" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentBlurParagraph2() {
        $value = esc_attr(get_option('breadbutter_gating_content_blur_paragraph_2'));
        $placeholder = __('\'Continue with\' your trusted login method.<br/>We\'ll get you going in no time.', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_gating_content_blur_paragraph_2" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentBlurParagraph3() {
        $value = esc_attr(get_option('breadbutter_gating_content_blur_paragraph_3'));
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. ', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_gating_content_blur_paragraph_3" value="'. $value .'" 
            placeholder="'.$placeholder.'" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentBlurParagraph32() {
        $value = esc_attr(get_option('breadbutter_gating_content_blur_paragraph_32'));
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. You may be asked to share your profile information, which will be stored securely. You can change your authentication method at any time.', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_gating_content_blur_paragraph_32" value="'. $value .'" 
            placeholder="'.$placeholder.'" style="width: 98%;"/>';
    }
    public function breadbutterGatingContentBlurMore() {
        $value = esc_attr(get_option('breadbutter_gating_content_blur_more'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_gating_content_blur_more" value="'. $value .'" 
            placeholder="more>>" style="width: 98%;"/>';
    }

    public function breadbutterContinueWithBlurParagraph1() {
        $value = esc_attr(get_option('breadbutter_continue_with_blur_paragraph_1'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_blur_paragraph_1" value="'. $value .'" 
            placeholder="Sign in to continue" style="width: 98%;"/>';
    }

    public function breadbutterContinueWithBlurParagraph2() {
        $value = esc_attr(get_option('breadbutter_continue_with_blur_paragraph_2'));
        $placeholder = __('\'Continue with\' your trusted login method.<br/>We\'ll get you going in no time.', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_blur_paragraph_2" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }

    public function breadbutterContinueWithBlurParagraph3() {
        $value = esc_attr(get_option('breadbutter_continue_with_blur_paragraph_3'));
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. ', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_blur_paragraph_3" value="'. $value .'" 
            placeholder="'.$placeholder.'" style="width: 98%;"/>';
    }

    public function breadbutterContinueWithBlurParagraph32() {
        $value = esc_attr(get_option('breadbutter_continue_with_blur_paragraph_3_2'));
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. You may be asked to share your profile information, which will be stored securely. You can change your authentication method at any time.', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_blur_paragraph_3_2" value="'. $value .'" 
            placeholder="'.$placeholder.'" style="width: 98%;"/>';
    }

    public function breadbutterContinueWithBlurMore() {
        $value = esc_attr(get_option('breadbutter_continue_with_blur_more'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_continue_with_blur_more" value="'. $value .'" 
            placeholder="more>>" style="width: 98%;"/>';
    }

    public function breadbutterBlurConfigSection() {
        echo '<div class="breadbutter-section">Blur Screen text</div>';
        echo '<div class="breadbutter-section-subtitle">Set new text that will appear on the blur screen, when active on any page on your site:</div>';
    }

    public function breadbutterGatingContentBlurTextSection() {
        echo '<div class="breadbutter-section">Set custom text for the authentication prompt for gated pages:</div>';
    }

    public function breadbutterGatingContentPreviewTextSection() {
        echo '<div class="breadbutter-section">Set custom text that appears below the authentication prompt for content preview pages:</div>';
    }

    public function breadbutterCustomEventsConfig() {
        $value = get_option('breadbutter_custom_events_config', '[]');
        // echo '<input type="text" class="regular-text fixed-row" name="breadbutter_custom_events_page" value="" style="width: 98%;"/>';
        echo '<input type="hidden" name="breadbutter_custom_events_config" value=\''. $value .'\' />';
    }

    public function breadbutterUserCustomDataConfig() {
        $value = get_option('breadbutter_user_custom_data_config', '[]');
        // echo '<input type="text" class="regular-text fixed-row" name="breadbutter_custom_events_page" value="" style="width: 98%;"/>';
        echo '<input type="hidden" name="breadbutter_user_custom_data_config" value=\''. $value .'\' />';
    }

    public function breadbutterUserCustomDataHeader() {
        $value = esc_attr(get_option('breadbutter_user_custom_data_header'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_user_custom_data_header" value="'. $value .'"
        placeholder="Thanks for Signing Up" />';
    }

    public function breadbutterUserCustomDataSubHeader() {
        $value = esc_attr(get_option('breadbutter_user_custom_data_sub_header'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_user_custom_data_sub_header" value="'. $value .'"
        placeholder="We just have a few questions we\'d like to ask you" />';
    }

    public function breadbutterNewsletterPages() {
        $this->applyPageSelection('breadbutter_newsletter_pages');
    }

    public function breadbutterNewsletterEnabled() {
        $id = 'breadbutter_newsletter_homepage_enabled';
        $value = esc_attr(get_option($id));
        echo '<input type="checkbox" name="'.$id.'" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterContactUsPagesEnabled() {
        $id = 'breadbutter_contactus_pages_enabled';
        $selected_pages = get_option('breadbutter_contactus_pages', []);
        $value = false;
        if (is_array($selected_pages) && in_array(self::$allOption, $selected_pages)) {
            $value = true;
        }
        echo '<input type="checkbox" id="'.$id.'" name="'.$id.'" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterContactUsDashboardPosition() {
        $id = 'breadbutter_contactus_dashboard_position';
        $v_value = esc_attr(get_option('breadbutter_contactus_position_vertical'));
        $h_value = esc_attr(get_option('breadbutter_contactus_position_horizontal'));
        $value = ''.$v_value.'_'.$h_value.'';
        $disabled = false;
        echo '<select class="select-row" name="'. $id .'" '. ($disabled ? 'disabled': '') .'>';
        $this->generateSelectOption($id, $value, 'bottom_right', __('Bottom Right', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'bottom_left', __('Bottom Left', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'top_left', __('Top Left', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'top_right', __('Top Right', 'breadbutter-connect'), $disabled);
        echo '</select>';
    }

    public function breadbutterContactUsPages() {
        $this->applyPageSelection('breadbutter_contactus_pages');
    }

    public function breadbutterContinueWithPages() {
        $this->applyPageSelection('breadbutter_continue_with_pages');
    }

    private function addMailchimpIcon() {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="51.549" height="55" viewBox="0 0 51.549 55" class="integration-logo"><path data-name="Path 22863" d="M49.528 34.808c.784 0 2.021.907 2.021 3.107a16.557 16.557 0 01-1.1 5.183c-3.272 7.808-11 12.166-20.208 11.891-8.578-.261-15.9-4.811-19.108-12.235a8.426 8.426 0 01-5.457-2.2 8.113 8.113 0 01-2.777-5.2 8.923 8.923 0 01.509-4.11l-1.8-1.526C-6.641 22.711 19.134-6.075 27.355 1.156l2.8 2.763 1.54-.646c7.234-3.024 13.09-1.572 13.104 3.244 0 2.488-1.581 5.4-4.124 8.042a8.251 8.251 0 012.09 3.712 12.177 12.177 0 01.44 3.024l.1 3.437 1.017.275a9.12 9.12 0 014 1.993 3.866 3.866 0 011.127 2.2 4.259 4.259 0 01-.76 3.101 11.632 11.632 0 01.44 1.168l.385 1.333zm.1 3.835c.206-1.306-.082-1.8-.481-2.048a1.382 1.382 0 00-.907-.165 13.093 13.093 0 00-.866-2.969 19 19 0 01-6.227 3.107 24.766 24.766 0 01-8.3.894c-1.8-.137-3-.674-3.437.8a19.4 19.4 0 008.468.866.163.163 0 01.165.137.167.167 0 01-.1.165s-3.34 1.553-8.66-.1c.137 1.251 1.361 1.815 1.938 2.048a9.547 9.547 0 001.54.412c6.585 1.141 12.743-2.639 14.132-3.6.1-.069.165 0 .082.137l-.137.179c-1.691 2.2-6.255 4.756-12.193 4.756-2.584 0-5.169-.921-6.117-2.337-1.485-2.172-.082-5.361 2.378-5.031l1.072.124a22.418 22.418 0 0011.176-1.8c3.354-1.567 4.619-3.3 4.426-4.674a2.005 2.005 0 00-.581-1.143 7.222 7.222 0 00-3.162-1.512 19.614 19.614 0 01-1.278-.371c-.687-.234-1.045-.412-1.113-1.718l-.179-3.4c-.055-1.443-.234-3.409-1.443-4.22a2.032 2.032 0 00-1.045-.344 2.511 2.511 0 00-.619.069 3.218 3.218 0 00-1.622.921 5.555 5.555 0 01-4.22 1.416c-.852-.041-1.76-.179-2.791-.234l-.6-.041a5.564 5.564 0 00-5.361 4.894c-.6 4.1 2.337 6.214 3.2 7.464a.992.992 0 01.234.55.917.917 0 01-.3.591 10.529 10.529 0 00-1.87 11.039c2.158 5.059 8.839 7.423 15.369 5.279a16.543 16.543 0 002.474-1.058 12.676 12.676 0 003.794-2.846 11.366 11.366 0 003.157-6.237zM38.764 26.001a4.433 4.433 0 01-.715-1.76c-.275-1.32-.247-2.268.509-2.392s1.127.674 1.4 1.98a3.8 3.8 0 01-.055 2.158 4.419 4.419 0 00-1.127 0zm-6.516 1.031a4.491 4.491 0 00-2.1-.467c-1.21.082-2.268.619-2.571.577-.124-.014-.179-.069-.192-.137-.055-.234.3-.6.66-.866a3.821 3.821 0 013.739-.454 3.165 3.165 0 011.4 1.141c.137.206.151.371.069.454-.139.132-.468-.019-1.005-.253zm-1.1.619a2.056 2.056 0 011.856.619.223.223 0 01.027.22c-.082.137-.247.11-.6.069a4.5 4.5 0 00-2.282.234 2.212 2.212 0 01-.522.137.165.165 0 01-.165-.165.96.96 0 01.344-.55 2.387 2.387 0 011.347-.55zm5.416 2.309a.865.865 0 01-.55-1.072c.165-.357.687-.44 1.182-.206a.747.747 0 11-.632 1.278zm3.093-2.722c.4 0 .7.454.687.99s-.316.962-.715.962-.687-.44-.687-.99.331-.967.716-.967zm-20.3-11.795a.093.093 0 00.124.137 20.758 20.758 0 0116.4-3.285c.1.027.165-.151.069-.206a11.59 11.59 0 00-4.99-1.32c-.082 0-.124-.082-.069-.137a5.177 5.177 0 01.935-.962.094.094 0 00-.069-.165 13.246 13.246 0 00-5.966 2.117.086.086 0 01-.137-.1 6.3 6.3 0 01.619-1.553c.055-.069-.041-.151-.11-.11a24.317 24.317 0 00-6.809 5.58zM8.742 26.711A36.677 36.677 0 0120.22 10.49a59.035 59.035 0 018.028-5.554s-2.241-2.612-2.914-2.8c-4.165-1.135-13.17 5.096-18.916 13.33-2.323 3.34-5.636 9.252-4.055 12.29a12.9 12.9 0 001.9 1.842 7.083 7.083 0 014.479-2.887zm3.107 13.939c3.011-.509 3.794-3.794 3.3-7.011-.55-3.657-3.024-4.949-4.674-5.031a4.911 4.911 0 00-1.237.082c-2.983.6-4.66 3.148-4.33 6.461a6.657 6.657 0 006.091 5.568 4.546 4.546 0 00.849-.069zm1.14-3.739c.137-.041.3-.082.412.041a.276.276 0 01.014.289 1.256 1.256 0 01-1.168.591 1.629 1.629 0 01-1.457-1.691 4.111 4.111 0 01.3-1.65 1.54 1.54 0 00-1.785-2.09 1.5 1.5 0 00-.962.687 3.659 3.659 0 00-.412.962c-.137.371-.344.481-.495.454-.069 0-.165-.055-.234-.22a3.246 3.246 0 01.839-2.722 2.612 2.612 0 012.241-.825 2.691 2.691 0 012.035 1.5 3.428 3.428 0 01-.247 3.024l-.082.206a1.23 1.23 0 00-.041 1.155.791.791 0 00.674.33 2.78 2.78 0 00.368-.041z" fill-rule="evenodd"></path></svg>';
    }

    private function addZapierIcon() {
        return '<svg data-name="Group 17654" xmlns="http://www.w3.org/2000/svg" width="50.01" height="50" viewBox="0 0 50.01 50" class="integration-logo"><defs><clipPath id="BB_ZAPIER_svg__a"><path data-name="Rectangle 4206" fill="none" d="M0 0h50.009v50H0z"></path></clipPath></defs><g data-name="Group 17653" clip-path="url(#BB_ZAPIER_svg__a)"><path data-name="Path 22864" d="M31.259 25.017a14.932 14.932 0 01-.962 5.274 14.932 14.932 0 01-5.274.962H25a14.932 14.932 0 01-5.274-.962 14.932 14.932 0 01-.962-5.274v-.025a15 15 0 01.959-5.274A14.932 14.932 0 0125 18.757h.022a14.932 14.932 0 015.274.962 14.932 14.932 0 01.962 5.274v.025zm18.406-4.179H35.084l10.3-10.321a25.077 25.077 0 00-5.894-5.894L29.174 14.944V.347A25.163 25.163 0 0025.02 0h-.028a25.163 25.163 0 00-4.154.347v14.581L10.517 4.622a25.09 25.09 0 00-5.885 5.888l10.315 10.328H.347S0 23.581 0 25v.019a25.054 25.054 0 00.351 4.145h14.6L4.622 39.492a25.194 25.194 0 005.894 5.894l10.325-10.33v14.6a25.117 25.117 0 004.148.344h.034a25.117 25.117 0 004.148-.347v-14.6l10.315 10.334a24.961 24.961 0 005.888-5.887L35.065 29.162h14.6a25.151 25.151 0 00.344-4.151v-.025a25.114 25.114 0 00-.344-4.148" fill="#ff4a00"></path></g></svg>';
    }

    private function addVCCompactColor() {
        return '<ul role="listbox" class="vc-compact-colors">
    <li role="option" aria-label="#4D4D4D" class="vc-compact-color-item" style="background: rgb(77, 77, 77);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#999999" class="vc-compact-color-item" style="background: rgb(153, 153, 153);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#FFFFFF" class="vc-compact-color-item vc-compact-color-item--white" style="background: rgb(255, 255, 255);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#F44E3B" class="vc-compact-color-item" style="background: rgb(244, 78, 59);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#FE9200" class="vc-compact-color-item" style="background: rgb(254, 146, 0);"><div class="vc-compact-dot"></div></li>
    <li role="option" aria-label="#FCDC00" class="vc-compact-color-item" style="background: rgb(252, 220, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#DBDF00" class="vc-compact-color-item" style="background: rgb(219, 223, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#A4DD00" class="vc-compact-color-item" style="background: rgb(164, 221, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#68CCCA" class="vc-compact-color-item" style="background: rgb(104, 204, 202);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#73D8FF" class="vc-compact-color-item" style="background: rgb(115, 216, 255);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#AEA1FF" class="vc-compact-color-item" style="background: rgb(174, 161, 255);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#FDA1FF" class="vc-compact-color-item" style="background: rgb(253, 161, 255);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#333333" class="vc-compact-color-item" style="background: rgb(51, 51, 51);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#808080" class="vc-compact-color-item" style="background: rgb(128, 128, 128);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#CCCCCC" class="vc-compact-color-item" style="background: rgb(204, 204, 204);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#D33115" class="vc-compact-color-item" style="background: rgb(211, 49, 21);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#E27300" class="vc-compact-color-item" style="background: rgb(226, 115, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#FCC400" class="vc-compact-color-item" style="background: rgb(252, 196, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#B0BC00" class="vc-compact-color-item" style="background: rgb(176, 188, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#68BC00" class="vc-compact-color-item" style="background: rgb(104, 188, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#16A5A5" class="vc-compact-color-item" style="background: rgb(22, 165, 165);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#009CE0" class="vc-compact-color-item" style="background: rgb(0, 156, 224);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#7B64FF" class="vc-compact-color-item" style="background: rgb(123, 100, 255);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#FA28FF" class="vc-compact-color-item" style="background: rgb(250, 40, 255);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#000000" class="vc-compact-color-item" style="background: rgb(0, 0, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#666666" class="vc-compact-color-item" style="background: rgb(102, 102, 102);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#B3B3B3" class="vc-compact-color-item" style="background: rgb(179, 179, 179);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#9F0500" class="vc-compact-color-item" style="background: rgb(159, 5, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#C45100" class="vc-compact-color-item" style="background: rgb(196, 81, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#FB9E00" class="vc-compact-color-item" style="background: rgb(251, 158, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#808900" class="vc-compact-color-item" style="background: rgb(128, 137, 0);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#194D33" class="vc-compact-color-item" style="background: rgb(25, 77, 51);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#0C797D" class="vc-compact-color-item" style="background: rgb(12, 121, 125);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#0062B1" class="vc-compact-color-item" style="background: rgb(0, 98, 177);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#653294" class="vc-compact-color-item" style="background: rgb(101, 50, 148);"><div class="vc-compact-dot" ></div></li>
    <li role="option" aria-label="#AB149E" class="vc-compact-color-item" style="background: rgb(171, 20, 158);"><div class="vc-compact-dot" ></div></li></ul>
</ul>';
    }


    public function breadbutterNewsletterEventID() {
        $color = '#FE9200';
        $name = '';
        $selected_integration = '';
        $zapier_endpoint = '';
        $api_key = '';
        $audience_id = '';
        $event_id = esc_attr(get_option('breadbutter_newsletter_event_id'));
        $integration_id = esc_attr(get_option('breadbutter_newsletter_integration_id'));
        $trigger_id = esc_attr(get_option('breadbutter_newsletter_trigger_id'));
        $action_id = esc_attr(get_option('breadbutter_newsletter_action_id'));

        $pass = true;
        if (!empty($event_id)) {
            $client = $this->getClient();
            $response = $this->getEventDetail($client, $event_id);
            if ($response) {
                $name = $response['name'];
                $color = $response['color'];
            }

            if (empty($name)) {
                $pass = false;
            } else {
                if (!empty($integration_id)) {
                    $response = $client->getAppIntegration($integration_id);
                    if ($response['body'] && !isset($response['body']['error'])) {
                        $selected_integration = $response['body']['type'];
                        if ($response['body']['data']) {
                            if (!empty($response['body']['data']['zapier_endpoint'])) {
                                $zapier_endpoint = $response['body']['data']['zapier_endpoint'];
                            }
                            if (!empty($response['body']['data']['api_key'])) {
                                $api_key = $response['body']['data']['api_key'];
                            }
                        }
                        $response_trigger = $client->getAppTriggers();
                        $results = $response_trigger['body']['results'];
                        $found_action = false;
                        foreach ($results as $result) {
                            if ($result['id'] == $trigger_id &&
                                $result['event_definition_id'] == $event_id &&
                                count($result['actions']) > 0 &&
                                $result['actions'][0]['id'] == $action_id) {
                                $found_action = true;
                                if (!empty($result['actions'][0]['data']) &&
                                    !empty($result['actions'][0]['data']['audience_id'])) {
                                    $audience_id = $result['actions'][0]['data']['audience_id'];
                                }
                            }
                        }

                        if (!$found_action) {
                            $pass = false;
                        }

                    } else {
                        $pass = false;
                    }
                }
            }
        }

        if (!$pass) {
            $color = '#FE9200';
            $name = '';
            $selected_integration = '';
            $zapier_endpoint = '';
            $api_key = '';
            $audience_id = '';
            $event_id = false;
            $integration_id = false;
            $trigger_id = false;
            $action_id = false;
        }


        echo '<input type="hidden" class="regular-text fixed-row" name="breadbutter_newsletter_event_id" value="'. $event_id .'" style="width: 98%;" required/>';
        echo '<input type="hidden" class="regular-text fixed-row" name="breadbutter_newsletter_integration_id" value="'. $integration_id .'" style="width: 98%;" required/>';
        echo '<input type="hidden" class="regular-text fixed-row" name="breadbutter_newsletter_trigger_id" value="'. $trigger_id .'" style="width: 98%;" required/>';
        echo '<input type="hidden" class="regular-text fixed-row" name="breadbutter_newsletter_action_id" value="'. $action_id .'" style="width: 98%;" required/>';

        $default_event = 'color="'.$color.'" style="background-color:'.$color.'"';
        if (!empty($name)) {
            $default_event .= ' name="' . $name .'"';
        }

        if (!empty($selected_integration)) {
            $selected_integration = 'bbi-name="'. $selected_integration .'"';
        }

        echo '
<div class="newsletter-event">
    <hr/>
    <div class="bb-event-code-holder">
        <div class="left-holder">
        <div class="label-holder">
            <label>Custom User Event</label>
            <span class="bb-hint"><div class="bb-hover">Creates a custom event in your Dashboard that is triggered when the user signs up for the Newsletter.</div></span>
        </div>
        <div><div class="event-name" '. $default_event .'>'.$name.'</div></div>
        </div>
        <div class="right-holder">
        <div class="label-holder"><label>Event Name</label></div>
        <div class="input-holder"><input type="text" name="event_name"></div>
        <div class="label-holder"><label>Event Colour</label></div>
        <div>
        ' . $this->addVCCompactColor() . '
        </div>
        </div>
    </div>
    <hr/>
    <div><h2>Connect your Opt-in Popup to a 3rd party integration (Optional)</h2></div>
    
    <div class="newsletter-integration" '. $selected_integration .'>
        <div class="newsletter-integration-choices">
            <div class="newsletter-integration-choice" name="mailchimp">
                <div class="newsletter-checkmark"></div>
                ' . $this->addMailchimpIcon() . '
                <span>Mailchimp</span>
            </div>
            <div class="newsletter-integration-choice" name="zapier">
                <div class="newsletter-checkmark"></div>
                ' . $this->addZapierIcon() . '
                <span>Zapier</span>
            </div>
        </div>
        <div class="newsletter-integration-options">
            <div class="newsletter-integration-option" name="zapier">
                <div class="label-holder">
                    <label>Zapier Endpoint</label>
                    <span class="bb-hint"><div class="bb-hover">Follow the Setup Guide above to get your Zapier Endpoint from Zapier.</div></span>
                </div>
                <div class="input-holder"><input type="text" name="zapier_endpoint" value="'.$zapier_endpoint.'"/></div>
                <div><a href="https://breadbutter.io/zapier-integration-for-newsletter/" target="_blank">Get your Zapier Endpoint</a></div>
            </div>
            <div class="newsletter-integration-option" name="mailchimp">
                <div class="label-holder">
                    <label>API Key</label>
                    <span class="bb-hint"><div class="bb-hover">In Mailchimp, go to Profile > Extras > API keys to get your API key.</div></span>
                </div>
                <div class="input-holder"><input type="text" name="api_key" value="'.$api_key.'"/></div>
                <div class="label-holder">
                    <label>Audience ID</label>
                    <span class="bb-hint"><div class="bb-hover">In Mailchimp, go to Audience > All contacts, then Settings > Audience name and defaults to get your Audience ID.</div></span>
                </div>
                <div class="input-holder"><input type="text" name="audience_id" value="'.$audience_id.'"/></div>
            </div>
        </div>
    </div>
    <div class="clear-settings">Clear user event & integration settings</div>
</div>
    
    ';
    }

    public function breadbutterNewsletterIntegrationID() {
    }

    public function breadbutterNewsletterTriggerID() {
    }

    public function breadbutterNewsletterActionID() {
    }

    public function breadbutterNewsletterEventCode() {
        $value = esc_attr(get_option('breadbutter_newsletter_event_code'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_newsletter_event_code" value="'. $value .'" style="width: 98%;" required/>';
    }


    public function breadbutterNewsletterHeader() {
        $value = esc_attr(get_option('breadbutter_newsletter_header'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_newsletter_header" value="'. $value .'" style="width: 98%;"/>';
    }

    public function breadbutterNewsletterMainMessage() {
        $value = esc_attr(get_option('breadbutter_newsletter_main_message'));
        echo '<input type="text" class="regular-text fixed-row" name="breadbutter_newsletter_main_message" value="'. $value .'" style="width: 98%;"/>';
    }

    public function breadbutterNewsletterSuccessHeader() {
        $id = 'breadbutter_newsletter_success_header';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" style="width: 98%;"/>';
    }
    public function breadbutterNewsletterSuccessMessage() {
        $id = 'breadbutter_newsletter_success_message';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id . '" value="'. $value .'" style="width: 98%;" />';
    }

    public function breadbutterNewsletterCustomImageType() {
        $id = 'breadbutter_newsletter_custom_image_type';
        $value = esc_attr(get_option($id));
        $disabled = false; //$this->checkNewsletterOptions();
        echo '<select class="select-row" name="'. $id .'" '. ($disabled ? 'disabled': '') .'>';
        $this->generateSelectOption($id, $value, 'default', __('Default Image', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'fill', __('Fill', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'center', __('Center', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'none', __('No Image', 'breadbutter-connect'), $disabled);
        echo '</select>';
    }

    public function breadbutterGatingContentGatingCustomImageType() {
        $id = 'breadbutter_gating_content_gating_custom_image_type';
        $value = esc_attr(get_option($id));
        $disabled = false; //$this->checkNewsletterOptions();
        echo '<select class="select-row" name="'. $id .'" '. ($disabled ? 'disabled': '') .'>';
        $this->generateSelectOption($id, $value, 'default', __('Default Image', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'fill', __('Fill', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'center', __('Center', 'breadbutter-connect'), $disabled);
        $this->generateSelectOption($id, $value, 'none', __('No Image', 'breadbutter-connect'), $disabled);
        echo '</select>';
    }

    public function breadbutterNewsletterCustomImage() {
        $id = 'breadbutter_newsletter_custom_image';
        $value = esc_attr(get_option($id));
//        $image_type_value = esc_attr(get_option('breadbutter_newsletter_custom_image_type'));
//        $disabled = false;
//        if ($image_type_value == 'no_image' || $image_type_value == 'default_image') {
//            $disabled = true;
//        }
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" style="width: 98%;"/>';
    }

    public function breadbutterContactUsCustomImage() {
        $id = 'breadbutter_contactus_custom_image';
        $value = esc_attr(get_option($id));
//        $image_type_value = esc_attr(get_option('breadbutter_newsletter_custom_image_type'));
//        $disabled = false;
//        if ($image_type_value == 'no_image' || $image_type_value == 'default_image') {
//            $disabled = true;
//        }
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" style="width: 98%;"/>';
        echo '<tr id="contactus-holder"><th></th><td><input id="contactus-image" name="file" type="file" />';
        echo '<progress id="contactus-image-progress"></progress></td></tr>';
    }
    public function breadbutterGatingContentGatingCustomImage() {
        $id = 'breadbutter_gating_content_gating_custom_image';
        $value = esc_attr(get_option($id));
//        $image_type_value = esc_attr(get_option('breadbutter_newsletter_custom_image_type'));
//        $disabled = false;
//        if ($image_type_value == 'no_image' || $image_type_value == 'default_image') {
//            $disabled = true;
//        }
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" style="width: 98%;"/>';
    }

    public function breadbutterNewsletterDelayPopup() {
        $value = esc_attr(get_option('breadbutter_newsletter_delay_popup'));
        echo '<input type="number" class="regular-text fixed-row" min="0" name="breadbutter_newsletter_delay_popup" value="'. $value .'" style="width: 10%;" required/>';
    }

    public function breadbutterSecureFormsConfig() {
        $value = get_option('breadbutter_secure_forms_config', '[]');
        // echo '<input type="text" class="regular-text fixed-row" name="breadbutter_custom_events_page" value="" style="width: 98%;"/>';
        echo '<input type="hidden" name="breadbutter_secure_forms_config" value=\''. $value .'\' />';
    }

    public function breadbutterGatingContentOverrideDest() {
        $value = esc_attr(get_option('breadbutter_gating_content_override_dest', false));
        echo '<input type="checkbox" name="breadbutter_gating_content_override_dest" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterGatingContentPreviewOverrideDest() {
        $id = 'breadbutter_gating_content_preview_override_dest';
        $value = esc_attr(get_option($id, false));
        echo '<input type="checkbox" name="' . $id . '" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterGatingContentGatingOverrideDest() {
        $id = 'breadbutter_gating_content_gating_override_dest';
        $value = esc_attr(get_option($id, false));
        echo '<input type="checkbox" name="' . $id . '" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterGatingContentPreviewClickableContent() {
        $id = 'breadbutter_gating_content_preview_clickable_content';
        $value = esc_attr(get_option($id, false));
        echo '<input type="checkbox" name="'.$id.'" '. (!empty($value) ? 'checked' : '') .'/>';
    }


    public function breadbutterNewsletterOverrideDest() {
        $value = esc_attr(get_option('breadbutter_newsletter_override_dest', false));
        echo '<input type="checkbox" name="breadbutter_newsletter_override_dest" '. (!empty($value) ? 'checked' : '') .'/>';
    }


    public function breadbutterContactUsIconNote() {
        $id = 'breadbutter_contactus_icon_note';
        $placeholder = __('Connect with a real person', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

    public function breadbutterContactUsHeader() {
        $id = 'breadbutter_contactus_header';
        $placeholder = __('Connect with a real person', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

    public function breadbutterContactUsSubHeader() {
        $id = 'breadbutter_contactus_sub_header';
        $placeholder = __('%FIRST_NAME%, how can we help?', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

    public function breadbutterContactUsButton() {
        $id = 'breadbutter_contactus_button';
        $placeholder = __('Send your message', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

    public function breadbutterContactUsSuccess() {
        $id = 'breadbutter_contactus_success';
        $placeholder = __('Thanks for reaching out, %FIRST_NAME%. We’ll get back to you as soon as possible.', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

    public function breadbutterContactUsShowPhone() {
        $id = 'breadbutter_contactus_show_phone';
        $value = esc_attr(get_option($id, false));
        echo '<input type="checkbox" name="' . $id . '" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterContactUsShowCompany() {
        $id = 'breadbutter_contactus_show_company';
        $value = esc_attr(get_option($id, false));
        echo '<input type="checkbox" name="' . $id . '" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterContactUsOverrideDest() {
        $id = 'breadbutter_contactus_override_dest';
        $value = esc_attr(get_option($id, false));
        echo '<input type="checkbox" name="' . $id . '" '. (!empty($value) ? 'checked' : '') .'/>';
    }

    public function breadbutterContactUsPositionVertical() {
        $value = esc_attr(get_option('breadbutter_contactus_position_vertical'));

        echo '<select class="select-row" name="breadbutter_contactus_position_vertical">';
        echo '<option value="top" ' . ($value == 'top' ? 'selected' : '') . '>Top</option>';
        echo '<option value="bottom" ' . ($value == 'bottom' ? 'selected' : '') . '>Bottom</option>';
        echo '</select>';

    }
    public function breadbutterContactUsPositionVerticalPx() {
        $value = esc_attr(get_option('breadbutter_contactus_position_vertical_px'));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 class="regular-text number-row" name="breadbutter_contactus_position_vertical_px" value="'. $value .'"/><span> px</span>';
    }
    public function breadbutterContactUsPositionHorizontal() {
        $value = esc_attr(get_option('breadbutter_contactus_position_horizontal'));

        echo '<select class="select-row" name="breadbutter_contactus_position_horizontal">';
        echo '<option value="left" ' . ($value == 'left' ? 'selected' : '') . '>Left</option>';
        echo '<option value="right" ' . ($value == 'right' ? 'selected' : '') . '>Right</option>';
        echo '</select>';

    }
    public function breadbutterContactUsPositionHorizontalPx() {
        $value = esc_attr(get_option('breadbutter_contactus_position_horizontal_px'));
        if (!is_numeric($value)) {
            $value = 0;
        }
        echo '<input type="number" min=0 class="regular-text number-row" name="breadbutter_contactus_position_horizontal_px" value="'. $value .'"/><span> px</span>';
    }

    public function breadbutterContactUsBlurParagraph1() {
        $id = 'breadbutter_contactus_blur_paragraph_1';
        $placeholder = __('Let’s get started with your contact information', 'breadbutter-connect');
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }
    public function breadbutterContactUsBlurParagraph2() {
        $id = 'breadbutter_contactus_blur_paragraph_2';
        $value = esc_attr(get_option($id));
        $placeholder = __('We only receive what is needed to get in touch with you', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" 
            placeholder="'. $placeholder .'" style="width: 98%;"/>';
    }
    public function breadbutterContactUsBlurParagraph3() {
        $id = 'breadbutter_contactus_blur_paragraph_3';
        $value = esc_attr(get_option($id));
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. ', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" 
            placeholder="'.$placeholder.'" style="width: 98%;"/>';
    }
    public function breadbutterContactUsBlurParagraph32() {
        $id = 'breadbutter_contactus_blur_paragraph_3_2';
        $value = esc_attr(get_option($id));
        $placeholder = __('You will be redirected to authenticate with your chosen account. We don’t see your password, only confirmation that you authenticated successfully and securely. You may be asked to share your profile information, which will be stored securely. You can change your authentication method at any time.', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" 
            placeholder="'.$placeholder.'" style="width: 98%;"/>';
    }
    public function breadbutterContactUsBlurMore() {
        $id = 'breadbutter_contactus_blur_more';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" 
            placeholder="more>>" style="width: 98%;"/>';
    }

    public function breadbutterContactUsSignoutSection() {
        echo '<div class="breadbutter-section">Set custom text for the signed out state:</div>';
    }

    public function breadbutterContactUsSigninSection() {
        echo '<div class="breadbutter-section">Set custom text for the signed in state. %FIRST_NAME% can be added to any of these fields:</div>';
    }

    public function breadbutterContactUsPositionSection() {
        echo '<div class="breadbutter-section">Position on page:</div>';
    }

    public function breadbutterContinueWithSuccessSeconds() {
        $id = 'breadbutter_continue_with_success_seconds';
        $value = esc_attr(get_option($id));
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'"/>';
    }

    public function breadbutterContinueWithSuccessHeader() {
        $id = 'breadbutter_continue_with_success_header';
        $value = esc_attr(get_option($id));
        $placeholder = __('Perfect, you\'re in!', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

    public function breadbutterContinueWithSuccessText() {
        $id = 'breadbutter_continue_with_success_text';
        $value = esc_attr(get_option($id));
        $placeholder = __('', 'breadbutter-connect');
        echo '<input type="text" class="regular-text fixed-row" name="'. $id .'" value="'. $value .'" placeholder="'.$placeholder.'" />';
    }

}