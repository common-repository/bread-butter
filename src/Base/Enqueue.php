<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class Enqueue extends BaseController {
    public function register() {
        add_filter("script_loader_tag", array($this, 'addModuleToScript'), 10, 3);
        add_action('wp_head', array($this, 'wordpressHead'));
        add_action('wp_enqueue_scripts', array($this, 'wordpressEnqueue'));
        add_action('admin_enqueue_scripts', array($this, 'adminEnqueue'));
        add_action('login_enqueue_scripts', array($this, 'loginEnqueue'));
        add_filter( 'wp_nav_menu_objects', array($this, 'searchContinueWith'));
        add_action('after_setup_theme', array($this, 'remove_admin_bar'), 1000);
        add_action( 'admin_init', array($this, 'activation_redirect') );
        add_action( 'admin_notices', array($this, 'add_admin_notice') );
    }

    public function remove_admin_bar() {
        $disabled_admin_bar = get_option('breadbutter_disabled_wp_admin_bar_for_subscribers');
        if (is_user_logged_in() && !empty($disabled_admin_bar)) {
            $roles = [ 'subscriber' ];
            $user = wp_get_current_user();
            $currentUserRoles = $user->roles;
            $isMatching = array_intersect( $currentUserRoles, $roles);
            if (!empty($isMatching))  {
                add_filter( 'show_admin_bar', '__return_false', 1000 );
            }
        }
    }

    public function wordpressHead() {
        global $wp;
        $logged_in = is_user_logged_in() ? 1 : 0;
        $logout_url = wp_logout_url('/'.$wp->request);// . wp_nonce_url();
        echo "<script>";
        echo "let BB_IS_USER_LOGGED_IN = {$logged_in};";
        echo "let BB_LOGOUT_URL = '{$logout_url}';";
        echo "</script>";
    }

    public function wordpressEnqueue() {
        wp_enqueue_script('jquery');
        $this->loadBreadbutter();
    }

    public function loadBreadbutter($admin_skip = false) {
        global $wp;
        if ($wp->request === 'wp-json/breadbutter-connect/v1/authorize') {
            return;
        }
        $app_id = get_option('logon_app_id');
        // $app_secret = get_option('logon_app_secret');
        $api_path = get_option('logon_api_path');

        if (empty($app_id)) {
            return;
        }

        $page_view_tracking = !empty(get_option('breadbutter_page_view_tracking'));
        $continue_with_all_pages = !empty(get_option('breadbutter_continue_with_all_pages'));
//        $continue_with_home_page = !empty(get_option('breadbutter_continue_with_home_page'));
//        $expand_email_address = !empty(get_option('breadbutter_expand_email_address'));
        $allow_sub_domain = !empty(get_option('breadbutter_allow_sub_domain'));
        $show_login_focus = false;

        $app_name = !empty(get_option('breadbutter_app_name')) ? get_option('breadbutter_app_name') : false;
        $destination_url = !empty(get_option('breadbutter_destination_url')) ? get_option('breadbutter_destination_url') : false;
        $callback_url = !empty(get_option('breadbutter_callback_url')) ? get_option('breadbutter_callback_url') : false;
        $ga_measurement_id = !empty(get_option('breadbutter_ga_measurement_id')) ? get_option('breadbutter_ga_measurement_id') : false;


        $position_vertical = get_option('breadbutter_continue_with_position_vertical');
        $position_vertical_px = get_option('breadbutter_continue_with_position_vertical_px');
        $position_horizontal = get_option('breadbutter_continue_with_position_horizontal');
        $position_horizontal_px = get_option('breadbutter_continue_with_position_horizontal_px');

        $locale_text_1 = get_option('breadbutter_continue_with_blur_paragraph_1', '');
        $locale_text_2 = get_option('breadbutter_continue_with_blur_paragraph_2', '');
        $locale_text_3 = get_option('breadbutter_continue_with_blur_paragraph_3', '');
        $locale_text_3_2 = get_option('breadbutter_continue_with_blur_paragraph_3_2', '');
        $locale_more = get_option('breadbutter_continue_with_blur_more', '');

        $popup_header1 = get_option('breadbutter_continue_with_header_new_user_no_display');
        $popup_header2 = get_option('breadbutter_continue_with_header_new_user_display');
        $popup_header_back1 = get_option('breadbutter_continue_with_header_return_user_no_display');
        $popup_header_back2 = get_option('breadbutter_continue_with_header_return_user_display');

        $button_theme = get_option('breadbutter_button_theme');

        $custom_events = get_option('breadbutter_custom_events_config');

        $user_custom_data = get_option('breadbutter_user_custom_data_config');
        $user_custom_data_header = get_option('breadbutter_user_custom_data_header');
        $user_custom_data_sub_header = get_option('breadbutter_user_custom_data_sub_header');

        $secure_forms = get_option('breadbutter_secure_forms_config');
        $user_profile = get_option('breadbutter_enable_user_profile');
        $hide_verified = get_option('breadbutter_hide_continue_with_for_returning_users');

        $show_logged_in = get_option('breadbutter_continue_with_show_profile');

        if (empty($position_vertical)) {
            $position_vertical = 'top';
        }
        if (empty($position_horizontal)) {
            $position_horizontal = 'right';
        }

        $continue_with_position = array();
        $continue_with_position[$position_vertical] = $position_vertical_px;
        $continue_with_position[$position_horizontal] = $position_horizontal_px;


        $user_profile_tools = get_option('breadbutter_enable_user_profile_tools');

        $up_position_vertical = get_option('breadbutter_user_profile_position_vertical');
        $up_position_vertical_px = get_option('breadbutter_user_profile_position_vertical_px');
        $up_position_horizontal = get_option('breadbutter_user_profile_position_horizontal');
        $up_position_horizontal_px = get_option('breadbutter_user_profile_position_horizontal_px');

        $continue_with_delay_seconds = get_option('breadbutter_continue_with_popup_delay_seconds');
        $continue_with_success_seconds = get_option('breadbutter_continue_with_success_seconds');
        $continue_with_success_header = get_option('breadbutter_continue_with_success_header');
        $continue_with_success_text = get_option('breadbutter_continue_with_success_text');

        if (empty($up_position_vertical)) {
            $up_position_vertical = 'top';
        }
        if (empty($up_position_horizontal)) {
            $up_position_horizontal = 'right';
        }
        $up_tool_position = array();
        $up_tool_position[$up_position_vertical] = $up_position_vertical_px;
        $up_tool_position[$up_position_horizontal] = $up_position_horizontal_px;

        if ($admin_skip) {
            $user_profile_tools = false;
        }


        $page_continue_with = get_option('breadbutter_continue_with_pages', []);
        $has_continue_with = 0;
        if (!$admin_skip) {
            if (is_array($page_continue_with) && in_array(self::$allOption, $page_continue_with)) {
                $has_continue_with = 1;
            }
            $post = get_post();
            if (is_array($page_continue_with) && $post && in_array($post->ID, $page_continue_with)) {
                $has_continue_with = 1;
            }
            if (is_array($page_continue_with) && isset($post->post_type) && in_array($post->post_type, $page_continue_with)) {
                $has_continue_with = 1;
            }
        }

        if (!empty($app_id) && !empty($api_path) || $admin_skip) {
            wp_enqueue_script('bb_logon_script', $this->plugin_url . 'assets/breadbutter.min.js',
                array('jquery'), filemtime($this->plugin_path . 'assets/breadbutter.min.js'));
            wp_enqueue_script('bb_config_script', $this->plugin_url . 'assets/breadbutter.config.js',
                array('bb_logon_script'), filemtime($this->plugin_path . 'assets/breadbutter.config.js'));
            $data = array(
                'app_id' => $app_id,
                'api_path' => $api_path,
                'page_view_tracking' => $page_view_tracking,
//                'expand_email_address' => $expand_email_address,
                'show_login_focus' => $show_login_focus,
                'allow_sub_domain' => $allow_sub_domain,
                'ga_measurement_id' => $ga_measurement_id,
                'continue_with_position' => $continue_with_position,
                'hide_verified' => $hide_verified,
                'user_profile_tools' => array(
                    'enabled' => $user_profile_tools,
                    'position' => $up_tool_position
                ),
                'has_continue_with' => $has_continue_with,
                'continue_with_success_seconds' => $continue_with_success_seconds,
                'continue_with_success_header' => $continue_with_success_header,
                'continue_with_success_text' => $continue_with_success_text
            );
            if (!empty($app_name)) {
                $data['app_name'] = $app_name;
            }
            if (!empty($destination_url)) {
                $data['destination_url'] = $destination_url;
            }
            if (!empty($callback_url)) {
                $data['callback_url'] = $callback_url;
            }

            if (!empty($show_logged_in)) {
                $data['show_logged_in_profile'] = $show_logged_in;
            }

            if (!empty($continue_with_all_pages) && !$admin_skip) {
                $data['continue_with_all_pages'] = $continue_with_all_pages;
            }

            $data['is_home_url'] = is_front_page();
//            if (!empty($continue_with_home_page) && !$admin_skip) {
//                $data['continue_with_home_page'] = $continue_with_home_page;
//            }

            // $locale = "{\"POPUP\": {\"TEXT_1\": \"$locale_text_1\", \"TEXT_2\": \"$locale_text_2\", \"TEXT_3\": \"$locale_text_3\", \"TEXT_3_2\": \"$locale_text_3_2\", \"MORE\": \" $locale_more\"}}";
            $locale = [
                "POPUP" => [
                ],
                "CUSTOM_DATA" => []
            ];

            if (!empty($locale_text_1)) {
                $locale['POPUP']['TEXT_1'] = $locale_text_1;
            }
            if (!empty($locale_text_2)) {
                $locale['POPUP']['TEXT_2'] = $locale_text_2;
            }
            if (!empty($locale_text_3)) {
                $locale['POPUP']['TEXT_3'] = $locale_text_3;
            }
            if (!empty($locale_text_3_2)) {
                $locale['POPUP']['TEXT_3_2'] = $locale_text_3_2;
            }
            if (!empty($locale_more)) {
                $locale['POPUP']['MORE'] = $locale_more;
            }
            if (!empty($popup_header1)) {
                $locale['POPUP']['HEADER_1'] = $popup_header1;
            }
            if (!empty($popup_header2)) {
                $locale['POPUP']['HEADER_2'] = $popup_header2;
            }
            if (!empty($popup_header_back1)) {
                $locale['POPUP']['HEADER_BACK_1'] = $popup_header_back1;
            }
            if (!empty($popup_header_back2)) {
                $locale['POPUP']['HEADER_BACK_2'] = $popup_header_back2;
            }


            if (!empty($user_custom_data_header)) {
                $locale['CUSTOM_DATA']['HEADER'] = $user_custom_data_header;
            }

            if (!empty($user_custom_data_sub_header)) {
                $locale['CUSTOM_DATA']['SUB_HEADER'] = $user_custom_data_sub_header;
            }

            $data['locale'] = $locale;

            if (!empty($button_theme)) {
                $data['button_theme'] = $button_theme;
            }

            if (!empty($custom_events)) {
                $data['custom_events'] = $custom_events;
            }

            if (!empty($user_custom_data)) {
                $data['custom_data'] = $user_custom_data;
            }

            if (!empty($secure_forms)) {
                $data['secure_forms'] = $secure_forms;
            }
            // 'off' means option was added into activated plugin.
            // in this case we have to leave it disable and user can manually activate it.
            // Ref: https://logonlabs.atlassian.net/browse/WPP-84
            if (!empty($user_profile) && $user_profile != 'off' ) {
                $data['use_ui'] = true;
            }

            if (!$admin_skip) {
                $data['page_settings'] = get_post_meta(get_the_ID());
            }

            if (is_string($continue_with_delay_seconds) && strlen($continue_with_delay_seconds) > 0) {
                $data['continue_with_delay_seconds'] = $continue_with_delay_seconds;
            }

            $data['wordpress_admin_ajax'] = admin_url('admin-ajax.php');

            wp_localize_script( 'bb_config_script', 'bb_config_params',
                $data
            );
        }
    }

    public function addModuleToScript($tag, $handle, $src) {
        if ("admin_script" === $handle) {
            $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        }

        return $tag;
    }

    public function adminEnqueue() {
        wp_enqueue_style('admin_style', $this->plugin_url . 'assets/admin.css',
            array(), filemtime($this->plugin_path . 'assets/admin.css'));
        wp_enqueue_script('admin_script', $this->plugin_url . 'assets/admin.js',
            array('jquery'), filemtime($this->plugin_path . 'assets/admin.js'));
        // select2.
        wp_enqueue_style('select2_style', $this->plugin_url . 'assets/select2.min.css',
            array(), filemtime($this->plugin_path . 'assets/select2.min.css'));
        wp_enqueue_script('select2_script', $this->plugin_url . 'assets/select2.min.js',
            array('jquery'), filemtime($this->plugin_path . 'assets/select2.min.js'));
        $this->loadBreadbutter(true);
    }

    public function loginEnqueue() {
        wp_enqueue_style('login_style', $this->plugin_url . '/assets/login.css',
            array(), filemtime($this->plugin_path . 'assets/login.css'));
        wp_enqueue_script('login_script', $this->plugin_url . '/assets/login.js',
            array('jquery'), filemtime($this->plugin_path . 'assets/login.js'));
        $this->loadBreadbutter();

    }

    public function searchContinueWith( $menu_items ) {
        foreach ( $menu_items as $menu_item ) {
            if ( isset($menu_item->classes[0]) && $menu_item->classes[0] == "bb-signup-signin") {
                wp_enqueue_script(
                    'bb_profile_widget_script',
                    $this->plugin_url . 'assets/breadbutter.profilewidget.js',
                    array('jquery', 'bb_config_script'),
                    filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.js')
                );
                wp_enqueue_style(
                    'bb_profile_widget_style',
                    $this->plugin_url . 'assets/breadbutter.profilewidget.css',
                    array(),
                    filemtime($this->plugin_path . 'assets/breadbutter.profilewidget.css')
                );
            }
        }
        
        return $menu_items;
    }

    /**
     * Redirects the user after plugin activation.
     */
    public function activation_redirect() {
        // Make sure it's the correct user
        if ( wp_get_current_user() && !empty(wp_get_current_user()->ID) && intval( get_option( 'breadbutter_activation_redirect', false ) ) === wp_get_current_user()->ID ) {
            // Make sure we don't redirect again after this one
            delete_option( 'breadbutter_activation_redirect' );
            wp_safe_redirect( admin_url( 'admin.php?page=breadbutter_connect' ) );
            exit;
        }
    }

    public function add_admin_notice() {
        $screen = get_current_screen();
//        if ($screen->id == 'plugins') {
        $app_id = get_option('logon_app_id');
        ?>

        <div class="error notice <?php echo (empty($app_id) ? '' : 'hidden') ?> is-dismissible" style="margin-top: 10px;" rel="breadbutter_connect_validation">
            <p>Action Required: Bread & Butter IO setup is not yet complete. <a href="<?php echo admin_url( 'admin.php?page=breadbutter_connect' ) ?>">Click here to complete your setup</a>.</p>
        </div>

        <?php
//        }
    }

}