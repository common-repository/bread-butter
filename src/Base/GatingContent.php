<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Base;

use \BreadButter_WP_Plugin\Base\BaseController;

class GatingContent extends BaseController {

    public function register() {
        add_filter('the_content', [$this, 'filterContent']);
        add_filter( 'pre_get_posts', [$this, 'limitSearch']);
        add_action('wp_enqueue_scripts', array($this, 'gatingHead'));
    }

    private function convertArray($list) {
        if (empty($list)) {
            $list = array();
        } else if (is_string($list) && strlen($list) > 0) {
            $list = array($list);
        }
        return $list;
    }

    public function limitSearch($query) {
        if ( $query->is_search() && $query->is_main_query()) {
//            $gated_pages = get_option('breadbutter_gating_content_pages', array());
//            $preview_pages = get_option('breadbutter_gating_content_preview_pages', array());
//
//            $gated_pages = $this->convertArray($gated_pages);
//            $preview_pages = $this->convertArray($preview_pages);
//
//            $query->set( 'post__not_in', array_merge($gated_pages, $preview_pages));
        }
    }

    public function gatingHead() {
        if ((is_search() && is_main_query()) || !is_singular()) {
            return;
        }

        
        if ($this->isContentGated()) {
            $locale_header = get_option('breadbutter_gating_content_gating_text_header');
            $locale_subheader = get_option('breadbutter_gating_content_gating_text_subheader');

            $restricted_msg = '';
            $override_preview_dest_url = get_option('breadbutter_gating_content_gating_override_dest', false);
            $override_preview_dest_url_var = '';

            $scroll_limit = get_option('breadbutter_gating_content_gating_scroll_limit', 0);
            $time_limit = get_option('breadbutter_gating_content_gating_time_limit', 0);


            $image_type = get_option('breadbutter_gating_content_gating_custom_image_type');
            $custom_image = get_option('breadbutter_gating_content_gating_custom_image');

            $post_meta = $this->isContentGatedByPost();
            if ($post_meta) {
                if (!empty($post_meta['breadbutter_post_content_gating_scroll_limit']) &&
                    is_string($post_meta['breadbutter_post_content_gating_scroll_limit'][0]) &&
                    strlen($post_meta['breadbutter_post_content_gating_scroll_limit'][0]) > 0) {
                    $scroll_limit = $post_meta['breadbutter_post_content_gating_scroll_limit'][0];
                } else {
                    $scroll_limit = 0;
                }
                
                if (!empty($post_meta['breadbutter_post_content_gating_time_limit']) &&
                    is_string($post_meta['breadbutter_post_content_gating_time_limit'][0]) &&
                    strlen($post_meta['breadbutter_post_content_gating_time_limit'][0]) > 0) {
                    $time_limit = $post_meta['breadbutter_post_content_gating_time_limit'][0];
                } else {
                    $time_limit = 0;
                }
                
                if (!empty($post_meta['breadbutter_post_content_gating_header']) &&
                    !empty($post_meta['breadbutter_post_content_gating_header'][0])) {
                    $locale_header = $post_meta['breadbutter_post_content_gating_header'][0];
                } else {
                    $locale_header = false;
                }

                if (!empty($post_meta['breadbutter_post_content_gating_subheader']) &&
                    !empty($post_meta['breadbutter_post_content_gating_subheader'][0])) {
                    $locale_subheader = $post_meta['breadbutter_post_content_gating_subheader'][0];
                } else {
                    $locale_subheader = false;
                }

                if (!empty($post_meta['breadbutter_post_content_gating_custom_image']) &&
                    !empty($post_meta['breadbutter_post_content_gating_custom_image'][0])) {
                    $custom_image = $post_meta['breadbutter_post_content_gating_custom_image'][0];
                } else {
                    $custom_image = false;
                }

                if (!empty($post_meta['breadbutter_post_content_gating_custom_image_type']) &&
                    !empty($post_meta['breadbutter_post_content_gating_custom_image_type'][0])) {
                    $image_type = $post_meta['breadbutter_post_content_gating_custom_image_type'][0];
                } else {
                    $image_type = false;
                }

                $override_preview_dest_url = $post_meta['breadbutter_post_content_gating_override_dest'][0];
            }

            if (!empty($locale_header) || !empty($locale_subheader)) {

                $locale = "{\"CONTENT_GATING\": {";

                if (!empty($locale_header)) {
                    $locale .= "\"TITLE\": \"$locale_header\",";
                }
                if (!empty($locale_subheader)) {
                    $locale .= "\"SUBTITLE\": \"$locale_subheader\"";
                }

                $locale .= "}";
                $locale .= "}";

                $restricted_msg = 'var BB_CONTENT_GATING_POST_LOCALE = ' . $locale . ';';
            }
            if ($override_preview_dest_url) {
                $override_preview_dest_url_var = "var BB_GATING_OVERRIDE_REG_DESTINATION_URL = true;";
            }
            $cg_image = '';
            if (!empty($image_type)) {
                $cg_image .= "const BB_GATING_IMAGE_TYPE = '$image_type';";
            }
            if (!empty($custom_image)) {
                $cg_image .= "const BB_GATING_IMAGE_URL = '$custom_image';";
            }

            $limit = '';
            $scrolling = intval($scroll_limit) / 100;
            $limit .= "const BB_CONTENT_GATING_SCROLL_LIMIT=$scrolling;";
            $time = floatval($time_limit);
            $limit .= "const BB_CONTENT_GATING_TIME_LIMIT=$time;";

            $override_preview_dest_url_var .= $limit;
            echo "<script>const BB_POST_HAS_CONTENT_GATED = 1; $override_preview_dest_url_var $restricted_msg $cg_image</script>";
            $enq = new Enqueue();
            $enq->loadBreadbutter();
        } else if ($this->isContentPreviewExists()) {
            $locale_text_1 = get_option('breadbutter_gating_content_preview_text_1');
            $locale_text_2 = get_option('breadbutter_gating_content_preview_text_2');
            $locale_text_3 = get_option('breadbutter_gating_content_preview_text_3');
            $locale_text_3_2 = get_option('breadbutter_gating_content_preview_text_3_2');
            $locale_more = get_option('breadbutter_gating_content_preview_label');

            $scroll_limit = get_option('breadbutter_gating_content_preview_scroll_limit', 0);
            $time_limit = get_option('breadbutter_gating_content_preview_time_limit', 0);
            $clickable_content = get_option('breadbutter_gating_content_preview_clickable_content', false);
            $fixed_height = get_option('breadbutter_gating_content_preview_height', false);
            $override_preview_dest_url = get_option('breadbutter_gating_content_preview_override_dest', false);


            $post_meta = $this->isContentPreviewByPost();
            if ($post_meta) {
                if (!empty($post_meta['breadbutter_post_content_preview_text_1']) &&
                    !empty($post_meta['breadbutter_post_content_preview_text_1'][0])) {
                    $locale_text_1 = $post_meta['breadbutter_post_content_preview_text_1'][0];
                } else {
                    $locale_text_1 = false;
                }

                if (!empty($post_meta['breadbutter_post_content_preview_text_2']) &&
                    !empty($post_meta['breadbutter_post_content_preview_text_2'][0])) {
                    $locale_text_2 = $post_meta['breadbutter_post_content_preview_text_2'][0];
                } else {
                    $locale_text_2 = false;
                }


                if (!empty($post_meta['breadbutter_post_content_preview_text_3']) &&
                    !empty($post_meta['breadbutter_post_content_preview_text_3'][0])) {
                    $locale_text_3 = $post_meta['breadbutter_post_content_preview_text_3'][0];
                } else {
                    $locale_text_3 = false;
                }


                if (!empty($post_meta['breadbutter_post_content_preview_text_3_2']) &&
                    !empty($post_meta['breadbutter_post_content_preview_text_3_2'][0])) {
                    $locale_text_3_2 = $post_meta['breadbutter_post_content_preview_text_3_2'][0];
                } else {
                    $locale_text_3_2 = false;
                }


                if (!empty($post_meta['breadbutter_post_content_preview_text_more']) &&
                    !empty($post_meta['breadbutter_post_content_preview_text_more'][0])) {
                    $locale_more = $post_meta['breadbutter_post_content_preview_text_more'][0];
                } else {
                    $locale_more = false;
                }

                if (!empty($post_meta['breadbutter_post_content_preview_scroll_limit']) &&
                    is_string($post_meta['breadbutter_post_content_preview_scroll_limit'][0]) &&
                    strlen($post_meta['breadbutter_post_content_preview_scroll_limit'][0]) > 0) {
                    $scroll_limit = $post_meta['breadbutter_post_content_preview_scroll_limit'][0];
                } else {
                    $scroll_limit = 0;
                }
                if (!empty($post_meta['breadbutter_post_content_preview_time_limit']) &&
                    is_string($post_meta['breadbutter_post_content_preview_time_limit'][0]) &&
                    strlen($post_meta['breadbutter_post_content_preview_time_limit'][0]) > 0) {
                    $time_limit = $post_meta['breadbutter_post_content_preview_time_limit'][0];
                } else {
                    $scroll_limit = 0;
                }
                if (!empty($post_meta['breadbutter_post_content_preview_height']) &&
                    is_string($post_meta['breadbutter_post_content_preview_height'][0]) &&
                    strlen($post_meta['breadbutter_post_content_preview_height'][0]) > 0) {
                    $fixed_height = $post_meta['breadbutter_post_content_preview_height'][0];
                } else {
                    $fixed_height = false;
                }

                $override_preview_dest_url = $post_meta['breadbutter_post_content_preview_override_dest'][0];
                $clickable_content = $post_meta['breadbutter_post_content_preview_clickable_content'][0];
            }

            $limit = '';
            $scrolling = intval($scroll_limit) / 100;
            $limit .= "const BB_CONTENT_PREVIEW_SCROLL_LIMIT=$scrolling;";
            $time = floatval($time_limit);
            $limit .= "const BB_CONTENT_PREVIEW_TIME_LIMIT=$time;";
            if ($clickable_content) {
                $limit .= "const BB_CONTENT_PREVIEW_CLICLKABLE_CONTENT=true;";
            } else {
                $limit .= "const BB_CONTENT_PREVIEW_CLICLKABLE_CONTENT=false;";
            }

            $override_preview_dest_url_var = '';
            if ($override_preview_dest_url) {
                $override_preview_dest_url_var = "var BB_PREVIEW_OVERRIDE_REG_DESTINATION_URL = true;";
            } else {
                $override_preview_dest_url_var = "var BB_PREVIEW_OVERRIDE_REG_DESTINATION_URL = false;";
            }

            if (is_numeric($fixed_height)) {
                $limit .= "const BB_CONTENT_PREVIEW_FIXED_HEIGHT=$fixed_height;";
            }
            $locale = json_decode('{}', 1);
            if (!empty($locale_text_1) || !empty($locale_text_2) || !empty($locale_text_3) || !empty($locale_text_3_2) || !empty($locale_more)) {
                $restricted_msg = '';
                $locale = array(
                    "POPUP" => array()
                );

                if (!empty($locale_text_1)) {
                    $locale["POPUP"]["TEXT_1"] = $locale_text_1;
                }
                if (!empty($locale_text_2)) {
                    $locale["POPUP"]["TEXT_2"] = $locale_text_2;
                }
                if (!empty($locale_text_3)) {
                    $locale["POPUP"]["TEXT_3"] = $locale_text_3;
                }
                if (!empty($locale_text_3_2)) {
                    $locale["POPUP"]["TEXT_3_2"] = $locale_text_3_2;
                }
                if (!empty($locale_more)) {
                    $locale["POPUP"]["MORE"] = $locale_more;
                }
            }
            if (empty($locale)) {
                $locale = '{}';
            } else {
                $locale = json_encode($locale);
            }
            $restricted_msg = 'var BB_CONTENT_PREVIEW_POST_LOCALE = ' . $locale . ';';

            $override_preview_dest_url_var .= $limit;
            echo "<script>var BB_POST_HAS_CONTENT_PREVIEW = 1; $override_preview_dest_url_var $restricted_msg</script>";
            $enq = new Enqueue();
            $enq->loadBreadbutter();
        } else if ($this->isContentRestricted()) {
            $locale_text_1 = get_option('breadbutter_gating_content_blur_paragraph_1');
            $locale_text_2 = get_option('breadbutter_gating_content_blur_paragraph_2');
            $locale_text_3 = get_option('breadbutter_gating_content_blur_paragraph_3');
            $locale_text_3_2 = get_option('breadbutter_gating_content_blur_paragraph_32');
            $locale_more = get_option('breadbutter_gating_content_blur_more');
            $restricted_msg = '';
            if (!empty($locale_text_1) || !empty($locale_text_2) || !empty($locale_text_3) || !empty($locale_text_3_2) || !empty($locale_more)) {
                #$locale = "{\"POPUP\": {\"TEXT_1\": \"$locale_text_1\", \"TEXT_2\": \"$locale_text_2\", \"TEXT_3\": \"$locale_text_3\", \"TEXT_3_2\": \"$locale_text_3_2\", \"MORE\": \" $locale_more\"}}";
                $locale = array(
                    "POPUP" => array()
                );
                if (!empty($locale_text_1)) {
                    $locale["POPUP"]["TEXT_1"] = $locale_text_1;
                }
                if (!empty($locale_text_2)) {
                    $locale["POPUP"]["TEXT_2"] = $locale_text_2;
                }
                if (!empty($locale_text_3)) {
                    $locale["POPUP"]["TEXT_3"] = $locale_text_3;
                }
                if (!empty($locale_text_3_2)) {
                    $locale["POPUP"]["TEXT_3_2"] = $locale_text_3_2;
                }
                if (!empty($locale_more)) {
                    $locale["POPUP"]["MORE"] = $locale_more;
                }

                $locale = json_encode($locale);
                $restricted_msg = 'var BB_RESTRICTED_POST_LOCALE = ' . $locale . ';';
            }
            $override_dest_url = get_option('breadbutter_gating_content_override_dest', false);
            $override_dest_url_var = '';
            if ($override_dest_url) {
                $override_dest_url_var = "var BB_OVERRIDE_REG_DESTINATION_URL = true;";
            }
            $ver = filemtime($this->plugin_path . 'assets/gating.css');
            echo '<link rel="stylesheet" id="gating_styles-css" href="' . $this->plugin_url . 'assets/gating.css?ver=' . $ver . '" media="all">';
            echo "<script>var BB_POST_IS_RESTRICTED = 1; $override_dest_url_var $restricted_msg</script>";
            $ver_js = filemtime($this->plugin_path . 'assets/gating.js');

            wp_enqueue_script('bb_logon_script_gating', $this->plugin_url . 'assets/gating.js',
                array('jquery'), $ver_js);
            $enq = new Enqueue();
            $enq->loadBreadbutter();
        }
    }

    public function filterContent($content) {
        if ($this->isContentRestricted()) {
            $message = get_option('breadbutter_gating_content_message');
            if ($message) {
                $dom = new \DomDocument();
                @$dom->loadHTML($message);
                $xpath = new \DOMXpath($dom);
                $nodes = $xpath->evaluate('//*[count(*) = 0]');
                $last_index = count($nodes) - 1;
                $link = $dom->createElement('a', 'Sign In');
                $href_attribute = $dom->createAttribute('href');
                $href_attribute->value = '#';
                $class_attribute = $dom->createAttribute('class');
                $class_attribute->value = 'js-bb-gated-sign-in-link bb-gated-sign-in-link';
                $style_attribute = $dom->createAttribute('style');
                $style_attribute->value = 'margin: 0 8px;';
                $link->appendChild($href_attribute);
                $link->appendChild($class_attribute);
                $link->appendChild($style_attribute);
                if (count($nodes[$last_index]->childNodes) > 1) {
                    $sub_last_index = count($nodes[$last_index]->childNodes) - 1;
                    $last_node = $nodes[$last_index]->childNodes[$sub_last_index];
                    $last_node->appendChild($link);
                } else {
                    $last_node = $nodes[$last_index];
                    $last_node->appendChild($link);
                }
                $message = $dom->saveHTML();
            }
            else {
                $message = '<p><a href="#" class="js-bb-gated-sign-in-link bb-gated-sign-in-link" style="margin: 0 8px;">Sign In</a></p>';
            }
            $content = $message;
        }
        if (strpos($content, 'bb-gate-to-signed-in-section') !== false && !is_user_logged_in()) {
            $dom = new \DomDocument();
            @$dom->loadHTML($content);

            $xpath = new \DOMXpath($dom);
            $gated = $xpath->query("//*[contains(@class,'bb-gate-to-signed-in-section')]");
            foreach($gated as $section) {
                $section->parentNode->removeChild($section);
            }
            $content = $dom->saveHTML();
        }

        return $content;
    }

    public function isContentRestricted() {
        return $this->isRestricted('breadbutter_gating_content_pages', 'breadbutter_post_is_restricred');
    }
    public function isContentGated() {
        return $this->isRestricted('breadbutter_gating_content_gating_pages', 'breadbutter_post_is_gated');
    }

    public function isContentPreviewExists() {
        return $this->isRestricted('breadbutter_gating_content_preview_pages', 'breadbutter_post_content_preview_enabled');
    }

    public function isRestricted($options, $meta) {
        $app_id = get_option('logon_app_id');
        if (empty($app_id)) {
            return false;
        }

        $gated_pages = get_option($options, []);
        if (!is_user_logged_in()) {
            if (is_array($gated_pages) && in_array(self::$allOption, $gated_pages)) {
                return true;
            }
            $post = get_post();
            if ($post) {
//                if (is_array($gated_pages) && in_array($post->post_type, $gated_pages)) {
//                    return true;
//                }
                if (is_array($gated_pages) && in_array($post->ID, $gated_pages)) {
                    return true;
                }
                $post_meta = get_post_meta($post->ID);
                if (isset($post_meta[$meta]) &&
                    !empty($post_meta[$meta][0])
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isContentGatedByPost() {
        $post = get_post();
        if ($post) {
            $post_meta = get_post_meta($post->ID);
            if (!is_user_logged_in() &&
                isset($post_meta['breadbutter_post_is_gated']) &&
                !empty($post_meta['breadbutter_post_is_gated'][0])
            ) {
                return $post_meta;
            }
        }
        return false;
    }

    public function isContentPreviewByPost() {
        $post = get_post();
        if ($post) {
            $post_meta = get_post_meta($post->ID);
            if (!is_user_logged_in() &&
                isset($post_meta['breadbutter_post_content_preview_enabled']) &&
                !empty($post_meta['breadbutter_post_content_preview_enabled'][0])
            ) {
                return $post_meta;
            }
        }
        return false;
    }

}
