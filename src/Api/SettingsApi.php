<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Api;

use WP_Query;

class SettingsApi {

    public $admin_pages = array();

    public $settings = array();
    public $posts = array();
    public $sections = array();
    public $fields = array();
    public $rest = array();
    public $meta = array();

    public function register() {
        if (!empty($this->admin_pages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));
        }

        if (!empty($this->settings)) {
            add_action('admin_init', array($this, 'registerCustomFields'));
        }

        if (!empty($this->posts)) {
            $this->createPluginPosts();
        }

        if (!empty($this->rest)) {
            $this->createRestAPI();
        }

        if (!empty($this->meta)) {
            add_action('init', array($this, 'createMeta'), 10);
        }

        add_action('updated_option', array($this, 'updateCallbackAndDestinationURL'));
    }

    public function addPages(array $pages) {
        $this->admin_pages = $pages;
        return $this;
    }

    public function addPosts(array $posts) {
        $this->posts = $posts;
        return $this;
    }

    public function addRestAPI(array $rests) {
        $this->rest = $rests;
        return $this;
    }

    public function addAdminMenu() {
        foreach ($this->admin_pages as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'],
                $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
        }
    }

    public function setSettings($options) {
        $this->settings = $options;
        return $this;
    }

    public function setSections($options) {
        $this->sections = $options;
        return $this;
    }

    public function setFields($options) {
        $this->fields = $options;
        return $this;
    }

    public function setMeta($options) {
        $this->meta = $options;
        return $this;
    }

    public function removeSettings($options) {
        foreach($options as $setting) {
            unregister_setting($setting['group'], $setting['name']);
        }
    }

    public function registerCustomFields() {
        foreach ($this->settings as $setting) {
            $args = array();
            if (isset($setting['callback'])) {
                $args['sanitize_callback'] = $setting['callback'];
            }
            if (isset($setting['default'])) {
                $args['default'] = $setting['default'];
            }
            $group = $setting['group'] ?? '';
            $name = $setting['name'] ?? '';
            register_setting($group, $name, $args);
            if (isset($setting['default'])) {
                add_option($setting['name'], $setting['default']);
            }
        }

        foreach ($this->sections as $section) {
            $title = isset($section['title']) ? $section['title'] : '';
            $callback = isset($section['callback']) ? $section['callback'] : '';
            add_settings_section($section['id'], $title, $callback, $section['page']);
        }

        foreach ($this->fields as $field) {
            $title = isset($field['title']) ? $field['title'] : '';
            $callback = isset($field['callback']) ? $field['callback'] : '';
            $page = $field['page'] ?? '';
            $section = $field['section'] ?? '';
            $args = $field['args'] ?? '';
            add_settings_field($field['id'], $title, $callback, $page, $section, $args);
        }
    }

    public function createPluginPosts() {
        foreach ($this->posts as $slug => $post) {
            $query = new WP_Query('pagename=' . $slug);
            if (!$query->have_posts()) {
                update_option($post['option_id'],
                    wp_insert_post(
                        array(
                            'post_content' => $post['content'],
                            'post_name' => $slug,
                            'post_title' => $post['title'],
                            'post_status' => 'publish',
                            'post_type' => 'page',
                            'ping_status' => 'closed',
                            'comment_status' => 'closed',
                        )
                    )
                );
            }
        }
    }

    public function createRestAPI() {
        foreach ($this->rest as $key => $rest) {
            add_action( 'rest_api_init', function () use ($rest) {
                register_rest_route( 'breadbutter-connect/v1', '/' . $rest['url'], array(
                    'methods' => $rest['method'],
                    'callback' => $rest['callback'],
                    'permission_callback' => !empty($rest['permission_callback']) ? $rest['permission_callback'] : '__return_true'
                ) );
            } );
        }
    }

    public function createMeta() {
        global $wp_post_types;
        $post_types = get_post_types(['capability_type' => 'post']);
        $post_options = [];
        foreach ($post_types as $post_type) {
            $post_type_support = get_all_post_type_supports($post_type);
            if (isset ($post_type_support['custom-fields']) && $post_type_support['custom-fields']) {
                $post_options[] = $post_type;
            }
//            print_r($wp_post_types[$post_type]);
//            echo "<br/>";
        }
//        print_r($post_types);
        foreach ($this->meta as $key => $meta) {
            foreach ($post_options as $target) {
                register_meta($target, $meta['name'], $meta['params']);
            }
        }
    }

    public function updateCallbackAndDestinationURL($option) {
        if ('logon_app_secret' == $option && isset($_POST['bb_wizard_type']) && $_POST['bb_wizard_type'] == 'secondary') {
            // Update Callback and Destination URLs.
            update_option('breadbutter_callback_url', get_home_url() . '/wp-json/breadbutter-connect/v1/authorize');
            update_option('breadbutter_destination_url', get_home_url());
        }
    }
}