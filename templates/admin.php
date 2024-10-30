<?php

$schema = explode("//", get_home_url());
$domain = explode("/", $schema[1]);
$cors_url = $schema[0] . '//' . $domain[0];
global $wp_settings_sections, $wp_settings_fields;


$api_path = get_option('logon_api_path', 'https://api.breadbutter.io');

$app_id = esc_attr(get_option('logon_app_id', ''));
$secret = esc_attr(get_option('logon_app_secret'));
//$app_id = '';
//$secret = null;
$api_path = get_option('logon_api_path', '');
$app_path_mapping = [
//    'https://api-devlab.breadbutter.io' => 'https://local.logon-dev.com:8080',
    'https://api-devlab.breadbutter.io' => 'https://app-devlab.breadbutter.io',
    'https://api-stable.breadbutter.io' => 'https://app-stable.breadbutter.io',
    'https://api.breadbutter.io' => 'https://app.breadbutter.io',
];
$app_gateway_mapping = [
    'https://api-devlab.breadbutter.io' => '60a56fd61e78c3168e340692',
    'https://api-stable.breadbutter.io' => '61200b20a12cb4cc7757aee5',
    'https://api.breadbutter.io' => '612818685a20bd2cb74d39ac',
];
$app_id_status = 1;
if (!$app_id_status) {
    // 1 case - orange + Disabled;
    $conversion_status = 0;
} else if (empty($secret)) {
    // 2 case - black + Disabled;
    $conversion_status = 2;
} else {
    // 3 case - green + Enabled;
    $conversion_status = 1;
}
$config_tab_title = ($conversion_status === 0 || $conversion_status === 2) ? 'Setup' : 'Dashboard';

function do_settings_section($page, $sec, $prepend = false, $append = false, $css = "")
{
    global $wp_settings_sections, $wp_settings_fields;

    if (!isset($wp_settings_sections[$page])) {
        return;
    }

    foreach ((array) $wp_settings_sections[$page] as $section) {
        if ($section['id'] != $sec) {
            continue;
        }
        if ($section['title']) {
            echo "<h2>{$section['title']}</h2>\n";
        }

        if ($section['callback']) {
            call_user_func($section['callback'], $section);
        }

        if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']])) {
            continue;
        }
        echo '<table class="form-table ' . $css . '" role="presentation">';
        if (!empty($prepend)) {
            echo $prepend;
        }
        do_settings_fields($page, $section['id']);
        if (!empty($append)) {
            echo $append;
        }
        echo '</table>';
    }
}

function do_fields_only($page, $section)
{
    global $wp_settings_fields;

    if (!isset($wp_settings_fields[$page][$section])) {
        return;
    }

    foreach ((array) $wp_settings_fields[$page][$section] as $field) {
        call_user_func($field['callback'], $field['args']);
    }
}

function do_settings_fields_only($page, $sec)
{
    global $wp_settings_sections, $wp_settings_fields;

    if (!isset($wp_settings_sections[$page])) {
        return;
    }

    foreach ((array) $wp_settings_sections[$page] as $section) {
        if ($section['id'] != $sec) {
            continue;
        }
        if ($section['callback']) {
            call_user_func($section['callback'], $section);
        }

        if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']])) {
            continue;
        }
        do_fields_only($page, $section['id']);
    }
}

function toggleBlock($value) {
//        $plugin_url = plugin_dir_url(dirname(__FILE__, 1));
//        echo "<div class='flex-col bb-dashboard-tile-show-box'>";
//        switch ($value) {
//            case 'on':
//                echo "<img src='$plugin_url/assets/bb_wp_green_check.png'>";
//                break;
////            default:
////                echo "<img src='$plugin_url/assets/bb_wp_black_cross.png'>";
////                break;
//            default:
//                echo "<img src='$plugin_url/assets/bb_wp_orange_warning.png'>";
//                break;
//        }
        echo "<div class='flex-col bb-dashboard-tile-show-box'>";
        echo "</div>";
}

function show_newsletter_sign_up() {
    $pages = get_option('breadbutter_newsletter_pages');
    $value = !empty($pages) ? 'on' : false;
    $enabled = !empty($value) ? " bb-dashboard-enabled" : "bb-dashboard-disabled" ;
    echo "<div id='breadbutter-dashboard-newsletter-sign-up' class='breadbutter-tab breadbutter-inline-box breadbutter-inline-box-dynamic {$enabled}'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
    toggleBlock($value);
    echo "Newsletter Sign up: <span class='enable-text'>". (empty($value)? "Disabled" : " Enabled") ."</span></div>";
    echo "<div class='bb-dashboard-tile-content'>
            <div>Prompt all first time visitors to join your mailing list or newsletter.</div>";
    echo "<div class='bb-flex-box'>";

    echo "<a class='bb-dashboard-tile-content-fill-button' href='#newsletter'>";
    echo "<span class='bb-dynamic-on'>Edit Setup</span>";
    echo "<span class='bb-dynamic-off'>Set Up Now</span>";
    echo "</a>";

    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function show_gate_content() {
    $page_1 = get_option('breadbutter_gating_content_pages');
    $page_2 = get_option('breadbutter_gating_content_preview_pages');
    $page_3 = get_option('breadbutter_gating_content_gating_pages');
    $value_1 = (!empty($page_1)) ? 'on' : false;
    $value_2 = (!empty($page_2)) ? 'on' : false;
    $value_3 = (!empty($page_3)) ? 'on' : false;

    $enabled_1 = !empty($value_1) ? " bb-dashboard-enabled-1" : "" ;
    $enabled_2 = !empty($value_2) ? " bb-dashboard-enabled-2" : "" ;
    $enabled_3 = !empty($value_3) ? " bb-dashboard-enabled-3" : "" ;
    $enabled = $enabled_1 . ' ' . $enabled_2 . ' ' . $enabled_3 . ' ';

    $enabled .= (!empty($value_1) ||!empty($value_2) ||!empty($value_3)) ? 'bb-dashboard-enabled' : 'bb-dashboard-disabled';

    $value = !empty($value_1) ||!empty($value_2) ||!empty($value_3) ? 'on' : false;
    echo "<div id='breadbutter-dashboard-gate-content' class='breadbutter-tab breadbutter-inline-box breadbutter-inline-box-dynamic {$enabled}'>";
    echo "<div class='flex-row'>";

    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
    toggleBlock($value);
    echo "Gate Content: <span class='enable-text'>". (empty($value)? "Disabled" : " Enabled") ."</span></div>";

    echo "<div class='bb-dashboard-tile-content'>
            <div>Require your users to sign before viewing specific pages</div>";
    echo "<div class='bb-flex-box'>";

    echo "<a class='bb-dashboard-tile-content-fill-button' href='#content-gating'>";
    echo "<span class='bb-dynamic-on'>Edit Setup</span>";
    echo "<span class='bb-dynamic-off'>Set Up Now</span>";
    echo "</a>";

    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function pipedrive_icon() {
    echo <<<HTML
    <svg data-v-48fb3456="" data-v-763a4569="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 168 215" class="integration-logo"><path d="M97.4 34c-17 0-26.9 7.7-31.6 12.9-.6-4.6-3.6-10.4-15.3-10.4H24.9v26.6h10.4c1.7 0 2.3.6 2.3 2.3V187h30.3v-48.9c4.7 4.3 13.7 10.3 27.9 10.3 29.6 0 50.3-23.4 50.3-57.2 0-34.1-19.6-57.2-48.7-57.2m-6.3 87.9c-16.3 0-23.7-15.6-23.7-30.2 0-22.9 12.4-31 24.2-31 14.3 0 24 12.3 24 30.7-.1 21.2-12.5 30.5-24.5 30.5" fill="#213332"></path></svg>
HTML;
}

function mailchimp_icon() {
    echo <<<HTML
    <svg data-v-48fb3456="" data-v-763a4569="" xmlns="http://www.w3.org/2000/svg" width="51.549" height="55" viewBox="0 0 51.549 55" class="integration-logo"><path data-name="Path 22863" d="M49.528 34.808c.784 0 2.021.907 2.021 3.107a16.557 16.557 0 01-1.1 5.183c-3.272 7.808-11 12.166-20.208 11.891-8.578-.261-15.9-4.811-19.108-12.235a8.426 8.426 0 01-5.457-2.2 8.113 8.113 0 01-2.777-5.2 8.923 8.923 0 01.509-4.11l-1.8-1.526C-6.641 22.711 19.134-6.075 27.355 1.156l2.8 2.763 1.54-.646c7.234-3.024 13.09-1.572 13.104 3.244 0 2.488-1.581 5.4-4.124 8.042a8.251 8.251 0 012.09 3.712 12.177 12.177 0 01.44 3.024l.1 3.437 1.017.275a9.12 9.12 0 014 1.993 3.866 3.866 0 011.127 2.2 4.259 4.259 0 01-.76 3.101 11.632 11.632 0 01.44 1.168l.385 1.333zm.1 3.835c.206-1.306-.082-1.8-.481-2.048a1.382 1.382 0 00-.907-.165 13.093 13.093 0 00-.866-2.969 19 19 0 01-6.227 3.107 24.766 24.766 0 01-8.3.894c-1.8-.137-3-.674-3.437.8a19.4 19.4 0 008.468.866.163.163 0 01.165.137.167.167 0 01-.1.165s-3.34 1.553-8.66-.1c.137 1.251 1.361 1.815 1.938 2.048a9.547 9.547 0 001.54.412c6.585 1.141 12.743-2.639 14.132-3.6.1-.069.165 0 .082.137l-.137.179c-1.691 2.2-6.255 4.756-12.193 4.756-2.584 0-5.169-.921-6.117-2.337-1.485-2.172-.082-5.361 2.378-5.031l1.072.124a22.418 22.418 0 0011.176-1.8c3.354-1.567 4.619-3.3 4.426-4.674a2.005 2.005 0 00-.581-1.143 7.222 7.222 0 00-3.162-1.512 19.614 19.614 0 01-1.278-.371c-.687-.234-1.045-.412-1.113-1.718l-.179-3.4c-.055-1.443-.234-3.409-1.443-4.22a2.032 2.032 0 00-1.045-.344 2.511 2.511 0 00-.619.069 3.218 3.218 0 00-1.622.921 5.555 5.555 0 01-4.22 1.416c-.852-.041-1.76-.179-2.791-.234l-.6-.041a5.564 5.564 0 00-5.361 4.894c-.6 4.1 2.337 6.214 3.2 7.464a.992.992 0 01.234.55.917.917 0 01-.3.591 10.529 10.529 0 00-1.87 11.039c2.158 5.059 8.839 7.423 15.369 5.279a16.543 16.543 0 002.474-1.058 12.676 12.676 0 003.794-2.846 11.366 11.366 0 003.157-6.237zM38.764 26.001a4.433 4.433 0 01-.715-1.76c-.275-1.32-.247-2.268.509-2.392s1.127.674 1.4 1.98a3.8 3.8 0 01-.055 2.158 4.419 4.419 0 00-1.127 0zm-6.516 1.031a4.491 4.491 0 00-2.1-.467c-1.21.082-2.268.619-2.571.577-.124-.014-.179-.069-.192-.137-.055-.234.3-.6.66-.866a3.821 3.821 0 013.739-.454 3.165 3.165 0 011.4 1.141c.137.206.151.371.069.454-.139.132-.468-.019-1.005-.253zm-1.1.619a2.056 2.056 0 011.856.619.223.223 0 01.027.22c-.082.137-.247.11-.6.069a4.5 4.5 0 00-2.282.234 2.212 2.212 0 01-.522.137.165.165 0 01-.165-.165.96.96 0 01.344-.55 2.387 2.387 0 011.347-.55zm5.416 2.309a.865.865 0 01-.55-1.072c.165-.357.687-.44 1.182-.206a.747.747 0 11-.632 1.278zm3.093-2.722c.4 0 .7.454.687.99s-.316.962-.715.962-.687-.44-.687-.99.331-.967.716-.967zm-20.3-11.795a.093.093 0 00.124.137 20.758 20.758 0 0116.4-3.285c.1.027.165-.151.069-.206a11.59 11.59 0 00-4.99-1.32c-.082 0-.124-.082-.069-.137a5.177 5.177 0 01.935-.962.094.094 0 00-.069-.165 13.246 13.246 0 00-5.966 2.117.086.086 0 01-.137-.1 6.3 6.3 0 01.619-1.553c.055-.069-.041-.151-.11-.11a24.317 24.317 0 00-6.809 5.58zM8.742 26.711A36.677 36.677 0 0120.22 10.49a59.035 59.035 0 018.028-5.554s-2.241-2.612-2.914-2.8c-4.165-1.135-13.17 5.096-18.916 13.33-2.323 3.34-5.636 9.252-4.055 12.29a12.9 12.9 0 001.9 1.842 7.083 7.083 0 014.479-2.887zm3.107 13.939c3.011-.509 3.794-3.794 3.3-7.011-.55-3.657-3.024-4.949-4.674-5.031a4.911 4.911 0 00-1.237.082c-2.983.6-4.66 3.148-4.33 6.461a6.657 6.657 0 006.091 5.568 4.546 4.546 0 00.849-.069zm1.14-3.739c.137-.041.3-.082.412.041a.276.276 0 01.014.289 1.256 1.256 0 01-1.168.591 1.629 1.629 0 01-1.457-1.691 4.111 4.111 0 01.3-1.65 1.54 1.54 0 00-1.785-2.09 1.5 1.5 0 00-.962.687 3.659 3.659 0 00-.412.962c-.137.371-.344.481-.495.454-.069 0-.165-.055-.234-.22a3.246 3.246 0 01.839-2.722 2.612 2.612 0 012.241-.825 2.691 2.691 0 012.035 1.5 3.428 3.428 0 01-.247 3.024l-.082.206a1.23 1.23 0 00-.041 1.155.791.791 0 00.674.33 2.78 2.78 0 00.368-.041z" fill-rule="evenodd"></path></svg>
HTML;
}

function ga_icon() {
    echo <<<HTML
    <svg data-v-48fb3456="" data-v-763a4569="" xmlns="http://www.w3.org/2000/svg" width="45" height="49.815" viewBox="0 0 45 49.815" class="integration-logo"><defs><clipPath id="BB_GA4_svg__a"><path data-name="Rectangle 4208" fill="none" d="M0 0h45v49.815H0z"></path></clipPath></defs><g data-name="Group 17658"><g data-name="Group 17657" clip-path="url(#BB_GA4_svg__a)"><path data-name="Path 22873" d="M45 43.562a6.237 6.237 0 01-6.141 6.247h-.744a6.318 6.318 0 01-5.4-6.458V6.506a6.394 6.394 0 015.4-6.458 6.187 6.187 0 016.882 5.4v38.114z" fill="#f9ab00"></path><path data-name="Path 22874" d="M6.144 37.527A6.141 6.141 0 110 43.668a6.078 6.078 0 016.141-6.141m16.2-18.74a6.479 6.479 0 00-6.033 6.455v16.626c0 4.447 2.012 7.2 4.87 7.835a6.239 6.239 0 007.305-4.87 5.155 5.155 0 00.106-1.271V25.034a6.237 6.237 0 00-6.141-6.247z" fill="#e37400"></path></g></g></svg>
HTML;
}

function show_pipedrive() {
    echo "<div class='breadbutter-tab breadbutter-inline-box'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-show-box'>";
    pipedrive_icon();
    echo "</div>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title'>Using Pipedrive?</div>";
    echo "<div class='bb-dashboard-tile-content'>
            <div>Create and update your leads automatically with live data collected from your website</div>";
    echo "<div class='bb-flex-box'>";

    echo "<a class='bb-dashboard-tile-content-fill-button' href='https://breadbutter.io/integrations-rules-pipedrive/' target='_BLANK'>";
    echo "<span>Connect Now</span>";
    echo "</a>";

    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
function show_mailchimp() {
    echo "<div class='breadbutter-tab breadbutter-inline-box'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-show-box'>";
    mailchimp_icon();
    echo "</div>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title'>Managing Mailchimp mailing lists?</div>";
    echo "<div class='bb-dashboard-tile-content'>
            <div>Automatically add and remove visitors from your audiences</div>";
    echo "<div class='bb-flex-box'>";

    echo "<a class='bb-dashboard-tile-content-fill-button' href='https://breadbutter.io/integrations-rules-mailchimp/' target='_BLANK'>";
    echo "<span>Connect Now</span>";
    echo "</a>";

    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

}
function show_google_analytics() {
    echo "<div class='breadbutter-tab breadbutter-inline-box'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-show-box'>";
    ga_icon();
    echo "</div>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title'>Using Google Analytics</div>";
    echo "<div class='bb-dashboard-tile-content'>
            <div>Sync conversion data with your Google Analytics account</div>";
    echo "<div class='bb-flex-box'>";

    echo "<a class='bb-dashboard-tile-content-fill-button' href='https://breadbutter.io/integrations-rules-google-analytics/' target='_BLANK'>";
    echo "<span>Connect Now</span>";
    echo "</a>";

    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

}

$plugin_url = plugin_dir_url(dirname(__FILE__, 1));
?>
<div class="breadbutter-wrap wrap">
    <h1>Bread & Butter <div class="breadbutter-icon"></div></h1>
    <script src="<?php echo $plugin_url ?>assets/webapp.js"></script>
    <?php settings_errors() ?>
    <div class="breadbutter-full-content">
        <div class="breadbutter-main-content">
            <h2 class="nav-tab-wrapper" id="breadbutter-tabs">
                <!--<a class="nav-tab nav-tab-active" id="dashboard-tab" href="#dashboard">Dashboard</a>-->
                <a class="nav-tab nav-tab-active" id="configuration-tab" href="#configuration">
                    <?php echo $config_tab_title; ?>
                </a>
                <a class="nav-tab" id="client-tab" href="#client">Display Settings</a>
                <a class="nav-tab" id="advance-tab" href="#advance">Advanced Settings</a>
                <a class="nav-tab" id="content-gating-tab" href="#content-gating">Content Gating</a>
                <a class="nav-tab" id="content-preview-tab" href="#content-preview">Content Preview</a>
                <a class="nav-tab" id="newsletter-tab" href="#newsletter">Opt-in Popup</a>
                <a class="nav-tab" id="contactus-tab" href="#contactus">Contact Us Tool</a>
                <a class="nav-tab" id="userprofile-tool-tab" href="#userprofile-tool">User Profile Tool</a>
            </h2>

            <div id="configuration" class="breadbutter-admin-tab">
                <?php if (empty($app_id) && empty($secret)) {?>
<!--                    <iframe id="breadbutter-wordpress-app" src="--><?php //echo $app_path_mapping[$api_path];?><!--/wordpress-setup" width="100%" height="900" style="margin-top: 20px"></iframe>-->
                    <div class="breadbutter-tab">
                        <div class="breadbutter-button-launch-wrapper">
                            <button id="breadbutter-launch-app">Start Bread & Butter WordPress Plugin Setup</button>
                        </div>
                        <div id="breadbutter-webapp-container">
                        </div>
                    </div>
                <?php }?>

                    <?php if (!empty($app_id) || !empty($secret)) {?>
                    <div>
                        <h2 class="bb-setup-is-working js-bb-setup-is-working">Your WordPress plugin is set up and working with Bread &amp; Butter</h2>
                        <!-- <form method="post" action="options.php" style="display:inline-block"> -->

                        <div class="breadbutter-inline-box-container">
                        <?php
                            // settings_fields('breadbutter_dashboard_groups');
                            settings_fields('logon_option_groups');
                            do_settings_fields_only('breadbutter_connect', 'breadbutter_dashboard');

                            do_settings_section('breadbutter_connect', 'logon_admin_index_organization_id', false, false, 'bb-hidden-config');
                            //                submit_button("SAVE");
                            ?>
                        </div>
                        <div class="breadbutter-inline-box-container">
                            <?php
                                include("tileset_dashboard.php");
                            ?>
                            </div>
<!--                        <form method="post" action="options.php" class="js-analytics-conversion-settings" id="dashboard-continuewith">-->
<!--                            <div class="breadbutter-inline-box-container">-->
<!--                                --><?php
//                                    include("continue_with_dashboard.php");
//                                ?>
<!--                            </div>-->
<!--                        </form>-->

<!--                        <form method="post" action="options.php" class="js-analytics-conversion-settings" id="dashboard-newsletter">-->
<!--                            <div class="breadbutter-inline-box-container">-->
<!--                                --><?php
//                                    include("newsletter_dashboard.php");
//                                ?>
<!--                            </div>-->
<!--                        </form>-->
<!--                        <form method="post" action="options.php" class="js-analytics-conversion-settings" id="dashboard-contactus">-->
<!--                            <div class="breadbutter-inline-box-container">-->
<!--                                --><?php
//                                    include("contact_us_dashboard.php");
//                                ?>
<!--                            </div>-->
<!--                        </form>-->
<!--                        <form method="post" action="options.php" class="js-analytics-conversion-settings" id="dashboard-gate-content">-->
<!--                            <div class="breadbutter-inline-box-container">-->
<!--                                --><?php
//                                    include("gate_content_dashboard.php");
//                                ?>
<!--                            </div>-->
<!--                        </form>                    -->


                        <div class="breadbutter-inline-box-container">
                            <?php
//                                show_newsletter_sign_up();
//                                show_pipedrive();
                                show_mailchimp();
                                show_google_analytics();
                            ?>
                        </div>
                    </div>

                    <?php } ?>
                <form method="post" name="logonApp" action="options.php" class="js-analytics-conversion-settings">
                    <div class="breadbutter-tab js-analytics-setting" style="display: none;">
                        <div class="top-row breadbutter-tab-header">
                            <span>Setting up Analytics</span>
                        </div>
                        <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-how-to-header"><span>To set up Analytics for your site, please follow these steps:</span></div>
                            <a href="https://breadbutter.io/wordpress-plugin-install-analytics/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                        </div>
                        <div class="content">
                            <?php
                            settings_fields('logon_option_groups');
                            // do_settings_section('breadbutter_connect', 'logon_admin_index');
                            ?>

                            <?php
                            $cors = "<tr>
                                    <th><label>CORS Allow URL</label></th>
                                    <td><div class='url-row'><div class='target-copy'>$cors_url</div><div class='copy'></div></div></td>
                                </tr>";
                            do_settings_section('breadbutter_connect', 'logon_admin_index_app_id', false, $cors, "breadbutter-app-id-table");

                            ?>

                            <?php submit_button("SAVE"); ?>
                        </div>
                    </div>
                    <div class="breadbutter-tab js-conversion-setting" style="display: none;">
                        <div class="top-row breadbutter-tab-header">
                            <span>Setting up Conversions</span>
                        </div>
                        <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-how-to-header"><span>To set up Conversions for your site, please follow these steps:</span></div>
                            <a href="https://breadbutter.io/wordpress-guide/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                        </div>
                        <div class="content">
                            <?php
                            settings_fields('logon_option_groups');
                            // do_settings_section('breadbutter_connect', 'logon_admin_index');
                            ?>

                            <?php
                            $callback_url = get_home_url() . '/wp-json/breadbutter-connect/v1/authorize';
                            $callback = "<tr>
                                    <th><label>Callback URL</label></th>
                                    <td><div class='url-row'><div class='target-copy'>$callback_url</div><div class='copy'></div></div></td>
                                </tr>";
                            do_settings_section('breadbutter_connect', 'logon_admin_index_app_secret', $callback);
                            ?>
                            <!-- <?php do_settings_section('breadbutter_connect', 'logon_admin_index_continue_with_home_page'); ?> -->

                            <?php
                            do_settings_section('breadbutter_connect', 'logon_admin_index_api_path');
                            submit_button("SAVE");
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <div id="client" class="breadbutter-admin-tab">
                <form method="post" action="options.php" class="js-continue-dashboard">
                    <div class="breadbutter-tab">
                        <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-header">The following global settings will apply to all Bread & Butter tools. Please note that the following can be changed per page, in the Bread & Butter Setup panel. Page level settings take precedence over the global settings below. You can find the Bread & Butter Setup panel by clicking the Bread & Butter logo at the top right when editing a post or page, or by clicking on the Options button and selecting Bread & Butter under Plugins.</div>
                            <a href="https://breadbutter.io/wordpress-customizing-continue-with/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                        </div>
                    </div>
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>General Settings</span>
                        </div>
                        <!-- <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-header"></div>
                        </div> -->
                        <div class="content">
                            <?php
                            settings_fields('breadbutter_client_option_groups');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config');
                            do_settings_section('breadbutter_connect', 'breadbutter_user_profile_tools_1');
                            do_settings_section('breadbutter_connect', 'breadbutter_user_profile_tools_pos', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_post');
                            submit_button("SAVE");
                            ?>

                        </div>
                    </div>

<!--                    <div class="breadbutter-tab">-->
<!--                        <div class="breadbutter-inline-box-container">-->
<!--                            --><?php
//                            include("continue_with_dashboard.php");
//                            ?>
<!--                        </div>-->
<!--                    </div>-->

                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>'Continue with' Tool</span>
                        </div>
                        <div class="top-row">
                            <div class="breadbutter-header">
                                <div class="breadbutter-subheader" style="text-align: center;">
                                    Add the ‘Continue with’ tool to any page on your site, allowing your users to authenticate and convert quickly and easily.
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <?php
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_section');
                            submit_button("SAVE");
                            ?>
                        </div>
                    </div>

                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>'Continue with' Header Text</span>
                        </div>
                        <div class="top-row">
                            <div class="breadbutter-header">
                                <div class="breadbutter-subheader" style="text-align: center;">
                                    Set the header text that appear at the top of the ‘Continue with’ tool on your website. Different content can be displayed for a new user vs. a returning user, or if you have a display name set in General Settings above.
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <?php
//                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_section', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_header_section');
                            submit_button("SAVE");
                            ?>

                        </div>
                    </div>
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>Authentication Prompt Text</span>
                        </div>
                        <!-- <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-header"></div>
                        </div> -->
                        <div class="content">
                            <?php
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_blur_settings');
                            submit_button("SAVE");
                            ?>
                        </div>
                    </div>
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>Button Style</span>
                        </div>
                        <!-- <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-header"></div>
                        </div> -->
                        <div class="content">
                            <?php
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_theme_settings');
                            submit_button("SAVE");
                            ?>

                        </div>
                    </div>
                </form>

                <div class="breadbutter-tab bb-dashboard-providers">
                    <div class="top-row breadbutter-tab-header">
                        <span>Enabled Identity Providers (IDPs)</span>
                    </div>
                    <div class="top-row">
                        <div class="breadbutter-header">
                            <div class="breadbutter-subheader" style="text-align: center;">
                                The following providers are currently enabled. To change the enabled providers, please go to your Bread & Butter Dashboard.
                            </div>
                        </div>
                    </div>
                    <div id="breadbutter-ui-sample-holder">
                        <div id="breadbutter-ui-sample" class="breadbutter-ui-sample"></div>
                    </div>
                    <div id="breadbutter-message" class="breadbutter-message">
                    </div>
                </div>
            </div>
            <div id="advance" class="breadbutter-admin-tab">
                <?php if (!empty($app_id) && !empty($secret)) {?>
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>WordPress Plugin Setup Wizard</span>
                        </div>
<!--                        <div class="breadbutter-button-wizard-wrapper">-->
<!--                            <button class="js-bb-show-wizard-button breadbutter-button-wizard">Show Bread & Butter WordPress Plugin Setup</button>-->
<!--                        </div>-->
<!--                        <iframe id="breadbutter-wordpress-app" class="js-bb-wizard-iframe-advance" src="--><?php //echo $app_path_mapping[$api_path];?><!--/wordpress-setup" width="100%" height="900" style="margin-top: 20px; display: none;"></iframe>-->
                        <div class="breadbutter-button-launch-wrapper">
                            <button id="breadbutter-launch-app">Launch Bread & Butter WordPress Plugin Setup</button>
                        </div>
                        <div id="breadbutter-webapp-container">
                        </div>
                    </div>
                <?php } ?>
                <form method="post" action="options.php" class="js-analytics-conversion">
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>Setting up Analytics</span>
                        </div>
                        <div class="top-row">
                            <!-- <div class="breadbutter-icon"></div> -->
                            <div class="breadbutter-how-to-header"><span>To set up Analytics for your site, please follow these steps:</span></div>
                            <a href="https://breadbutter.io/wordpress-plugin-install-analytics/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                        </div>
                        <div class="content">
                            <?php
                            settings_fields('logon_option_groups');
                            // do_settings_section('breadbutter_connect', 'logon_admin_index');
                            ?>

                            <?php
                            $cors = "<tr>
                                    <th><label>CORS Allow URL</label></th>
                                    <td><div class='url-row'><div class='target-copy'>$cors_url</div><div class='copy'></div></div></td>
                                </tr>";
                            do_settings_section('breadbutter_connect', 'logon_admin_index_organization_id', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'logon_admin_index_app_id', false, $cors, "breadbutter-app-id-table");
                            ?>

                            <?php submit_button("SAVE"); ?>
                        </div>
                    </div>

                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>Setting up Conversions</span>
                        </div>
                        <div class="top-row">
                            <!-- <div class="breadbutter-icon"></div> -->
                            <div class="breadbutter-how-to-header"><span>To set up Conversions for your site, please follow these steps:</span></div>
                            <a href="https://breadbutter.io/wordpress-guide/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                        </div>
                        <div class="content">
                            <?php
                            settings_fields('logon_option_groups');
                            // do_settings_section('breadbutter_connect', 'logon_admin_index');
                            ?>

                            <?php
                            $callback_url = get_home_url() . '/wp-json/breadbutter-connect/v1/authorize';
                            $callback = "<tr>
                                    <th><label>Callback URL</label></th>
                                    <td><div class='url-row'><div class='target-copy'>$callback_url</div><div class='copy'></div></div></td>
                                </tr>";
                            do_settings_section('breadbutter_connect', 'logon_admin_index_app_secret', $callback);
                            ?>
                            <?php do_settings_section('breadbutter_connect', 'logon_admin_index_continue_with_home_page'); ?>

                            <?php
                            do_settings_section('breadbutter_connect', 'logon_admin_index_api_path');
                            submit_button("SAVE");
                            ?>
                        </div>
                    </div>
                </form>
                <!-- <form method="post" action="options.php" class="js-analytics-conversion">
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>Google Analytics</span>
                        </div>-->
                        <!-- <div class="top-row">
                            <div class="breadbutter-icon"></div>
                            <div class="breadbutter-header"></div>
                        </div> -->
                <!--  <div class="content">
                            <?php
                            //do_settings_section('breadbutter_connect', 'breadbutter_advance_config_google_analytics');
                            //submit_button("SAVE");
                            ?>
                        </div>
                    </div>
                </form>-->

                <div class="breadbutter-tab">
                    <div class="top-row breadbutter-tab-header">
                        <span>User Event Setup</span>
                    </div>
                    <div class="top-row">
                        <!-- <div class="breadbutter-icon"></div> -->
                        <div class="breadbutter-how-to-header"><span>To set up user events for your site, please follow these steps:</span></div>
                        <a href="https://breadbutter.io/wordpress-guide-custom-api-events/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                    </div>
                    <div class="content">
                        <form method="post" action="options.php">
                            <table class="form-table " role="presentation">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label for="breadbutter_custom_event_element">Element Name/ID/Selector</label></th>
                                        <td>
                                            <input type="text" class="regular-text fixed-row" name="breadbutter_custom_event_element" value="" />
                                            <div style="font-size: 12px;">To find the element on the page we need one of: name (name="some-name") or id (id="some-unique-id") or css selector (example: .my-element-class or button.submit-button)</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="breadbutter_custom_event_code">User Event Code</label></th>
                                        <td><input type="text" class="regular-text fixed-row" name="breadbutter_custom_event_code" value="" /></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                            settings_fields('breadbutter_custom_events_group');
                            do_settings_section('breadbutter_connect', 'breadbutter_custom_events_section');
                            submit_button("SAVE", 'primary', 'submit', true, array( 'id' => 'bb-save-custom-event-btn' ));
                            ?>
                        </form>
                    </div>
                </div>

                <div class="breadbutter-tab">
                    <div class="top-row breadbutter-tab-header">
                        <span>Custom Fields for Registration</span>
                    </div>
                    <div class="top-row">
                        <!-- <div class="breadbutter-icon"></div> -->
                        <div class="breadbutter-how-to-header"><span>Create and set custom fields for questions you want your users to answer during registration.</span></div>
                        <a href="https://breadbutter.io/custom-fields-for-registration/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                    </div>
                    <div class="content">
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('breadbutter_user_custom_data_group');
                            do_settings_section('breadbutter_connect', 'breadbutter_user_custom_data_section');
                            ?>
                            <table class="form-table " role="presentation">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="breadbutter_user_custom_data_type">Type</label></th>
                                    <td>
                                        <select type="text" class="regular-text fixed-row" name="breadbutter_user_custom_data_type">
                                            <option value=""></option>
                                            <option value="textbox">Textbox</option>
                                            <option value="checkbox">Checkbox</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="breadbutter_user_custom_data_custom_key">Custom Key</label></th>
                                    <td>
                                        <input type="text" class="regular-text fixed-row" name="breadbutter_user_custom_data_custom_key" value="" />
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="breadbutter_user_custom_data_display_name">Display Name</label></th>
                                    <td><input type="text" class="regular-text fixed-row" name="breadbutter_user_custom_data_display_name" value="" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="breadbutter_user_custom_data_mandatory">Mandatory</label></th>
                                    <td><input type="checkbox" class="regular-text" name="breadbutter_user_custom_data_mandatory"/></td>
                                </tr>
                                <tr id="breadbutter-user-custom-data-default-holder" style="display: none;">
                                    <th scope="row"><label for="breadbutter_user_custom_data_default_value">Default Value</label></th>
                                    <td><input type="checkbox" class="regular-text" name="breadbutter_user_custom_data_default_value" value="" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"></th>
                                    <td><div style="font-size: 11px;color:#9a9a9a;">Type, Custom key, and Display Name are mandatory information for the Custom Fields for Registration. Spaces are not allowed in the Custom Key field.</div></td>
                                </tr>
                                </tbody>
                            </table>
                            <?php
                            //submit_button("ADD", 'primary', 'submit', true, array( 'id' => 'bb-add-user-custom-data-btn' ));
                            ?>
                            <?php
                            submit_button("SAVE", 'primary', 'submit', true, array( 'id' => 'bb-save-user-custom-data-btn' ));
                            ?>
                        </form>
                    </div>
                </div>

                <form method="post" action="options.php">
                    <div class="breadbutter-tab">
                        <div class="top-row breadbutter-tab-header">
                            <span>Additional Settings</span>
                        </div>
                        <div class="top-row">
                            <!-- <div class="breadbutter-icon"></div> -->
                            <div class="breadbutter-header">
                                <div class="breadbutter-subheader">
                                By default, Bread & Butter will use the Callback URL defined in your App Settings in Bread & Butter. However, you can use the settings below to specify different Callback URLs or Destination URLs for your WordPress site. Please note that any addresses entered below must be added to the corresponding Allow Lists on the Settings page in Bread & Butter.
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <?php
                            settings_fields('breadbutter_advance_option_groups');
                            do_settings_section('breadbutter_connect', 'breadbutter_advance_config');
                            do_settings_section('breadbutter_connect', 'breadbutter_advance_config_allow_sub_domain');
                            do_settings_section('breadbutter_connect', 'breadbutter_advance_config_show_login_buttons_on_login_page');
                            submit_button("SAVE");
                            ?>

                        </div>
                    </div>
                </form>

            </div>
            <div id="content-gating" class="breadbutter-admin-tab">
                <div class="breadbutter-tab">
                    <div class="top-row breadbutter-tab-header">
                        <span>Content Gating Setup</span>
                    </div>
                    <div class="top-row">
                        <div class="breadbutter-how-to-header"><span>Gate website content for converted/authenticated users exclusively, and protect your webforms from spam submissions.</span></div>
                        <a href="https://breadbutter.io/wordpress-guide-for-content-gating/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                    </div>
                    <div class="content preview-content">
                        <div class="bb-preview-image-box" data="content-gating"></div>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('breadbutter_gating_content_gating_group');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_gating_config_pages');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_gating_config_text');
                            submit_button("SAVE");
                            ?>
                        </form>
                    </div>
                </div>
                <div class="breadbutter-tab">
                    <div class="top-row breadbutter-tab-header">
                        <span>Blur Screen Prompt</span>
                    </div>
                    <div class="top-row">
                        <!-- <div class="breadbutter-icon"></div> -->
                        <div class="breadbutter-how-to-header"><span>Gate website content for converted/authenticated users exclusively, and protect your webforms from spam submissions.</span></div>
                        <a href="https://breadbutter.io/wordpress-guide-content-gating/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                    </div>
                    <div class="content preview-content">
                        <div class="bb-preview-image-box" data="blur-screen"></div>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('breadbutter_gating_content_groups');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_config_message');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_config_pages');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_config_blur_text');
                            submit_button("SAVE");
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div id="content-preview" class="breadbutter-admin-tab">
                <div class="breadbutter-tab">
                    <div class="top-row breadbutter-tab-header">
                        <span>Content Preview Setup</span>
                    </div>
                    <div class="top-row">
                        <div class="breadbutter-how-to-header"><span>Gate website content, but with a preview of the page content, similar to popular news websites that have a paywall.</span></div>
                        <a href="https://breadbutter.io/wordpress-content-preview/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                    </div>
                    <div class="content preview-content">
                        <div class="bb-preview-image-box" data="content-preview"></div>
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('breadbutter_gating_content_preview_group');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_preview_config_enabled', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_preview_config_pages');
                            do_settings_section('breadbutter_connect', 'breadbutter_gating_content_preview_config_text');
                            submit_button("SAVE");
                            ?>
                        </form>

                    </div>
                </div>
            </div>
            <div id="newsletter" class="breadbutter-admin-tab">
                <div class="breadbutter-tab">
                    <?php
                    include("tabs/newsletter.php");
                    ?>
                </div>
            </div>

            <div id="contactus" class="breadbutter-admin-tab">
                <div class="breadbutter-tab">
                    <?php
                    include("tabs/contactus.php");
                    ?>
                </div>
            </div>

            <div id="userprofile-tool" class="breadbutter-admin-tab">
                <div class="breadbutter-tab">
                    <div class="top-row breadbutter-tab-header">
                        <span>Profile Tool</span>
                    </div>
                    <div class="top-row">
                        <!-- <div class="breadbutter-icon"></div> -->
                        <div class="breadbutter-how-to-header"><span>Add the Profile Tool and the ability to sign out on all pages:</span></div>
                        <a href="https://breadbutter.io/wordpress-user-profile-tool/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
                    </div>
                    <div class="content">
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('breadbutter_client_option_groups');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_user_profile_tools_1');
                            do_settings_section('breadbutter_connect', 'breadbutter_user_profile_tools_pos');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_post', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_section', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_header_section', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_blur_settings', false, false, 'bb-hidden-config');
                            do_settings_section('breadbutter_connect', 'breadbutter_ui_config_theme_settings', false, false, 'bb-hidden-config');
                            submit_button("SAVE");
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="breadbutter-side-content">
            <div class="breadbutter-divider-line"></div>
            <div class="breadbutter-side-box-container">
            <div class="breadbutter-tab breadbutter-inline-box bb-dashboard-link">
                <div class="flex-col">
                    <div class="breadbutter-inline-box-title">Know your visitors</div>
                    <div class="breadbutter-inline-box-description">Go to your Bread & Butter App to see all of your customer data</div>
                    <a class="breadbutter-inline-box-content" href="<?php echo $app_path_mapping[$api_path];?>/app/#/dashboard/<?php echo $app_id;?>" target="_BLANK">Take me there</a>
                </div>
            </div>
            <div class="breadbutter-tab breadbutter-inline-box bb-dashboard-link">
                <div class="flex-col">
                    <div class="breadbutter-inline-box-title">Need help?</div>
                    <div class="breadbutter-inline-box-description">Connect with our team to get set up for free</div>
                    <a class="breadbutter-inline-box-content" href="https://breadbutter.io/talk-to-a-specialist" target="_BLANK">Book a consult</a>
                </div>
            </div>

            <div class="breadbutter-tab breadbutter-inline-box bb-dashboard-link">
                <div class="flex-col">
                    <div class="breadbutter-inline-box-title">Learn More</div>
                    <div class="breadbutter-inline-box-description">Learn more about the Bread & Butter WordPress plugin and application</div>
                    <a class="breadbutter-inline-box-content" href="https://breadbutter.io/wordpress-getting-started/" target="_BLANK">Visit Help Center</a>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script>
        //.
        const secret_input = '<input type="text" class="regular-text fixed-row js-secret-update-field" name="logon_app_secret" placeholder="App Secret"/>';
        const secret_button = '<div class="regular-text fixed-row secret-update-button js-secret-update-button">Update Secret</div>';
        const forceBackButton = function() {
            const input = this;
            if (!input.value) {
                input.insertAdjacentHTML('afterEnd', secret_button);
                input.nextSibling.onclick = forceSecretField;
                input.remove();
            }
        }
        const forceSecretField = function() {
            const button = this;
            button.insertAdjacentHTML('afterEnd', secret_input);
            button.nextSibling.focus();
            button.nextSibling.onblur = forceBackButton;
            button.remove();
        };
        if (document.querySelector('.js-secret-update-button')) {
            document.querySelectorAll('.js-secret-update-button').forEach(function(element) {
                element.onclick = forceSecretField;
            });
        }
        const wizardButton = document.querySelector('.js-bb-show-wizard-button');
        if (wizardButton) {
            wizardButton.addEventListener('click', function(event) {
                if ('Show Bread & Butter WordPress Plugin Setup' === this.textContent) {
                    document.querySelector('.js-bb-wizard-iframe-advance').style.display = 'block';
                    this.textContent = 'Hide Bread & Butter WordPress Plugin Setup';
                } else {
                    document.querySelector('.js-bb-wizard-iframe-advance').style.display = 'none';
                    this.textContent = 'Show Bread & Butter WordPress Plugin Setup';
                }
            });
        }
        const failureAuth = function(callback) {
            if (webappView) {
                webappView.addCallback(callback);
            }
            document.querySelector('#breadbutter-launch-app').style.display = '';
            let popup = window.open('<?php echo $app_path_mapping[$api_path];?>/single-sso', 'popup', 'popup=true,width=950,height=700');
            console.log(popup);
        };
        const successAuth = function(app_id_1, app_secret_1) {
            document.querySelector('#configuration [name="logon_app_id"]').value = app_id_1;
            if (document.querySelector('#configuration [name="logon_app_secret"]')) {
                document.querySelector('#configuration [name="logon_app_secret"]').value = app_secret_1;
            } else {
                document.querySelector('#configuration .js-secret-update-button').click();
                document.querySelector('#configuration [name="logon_app_secret"]').value = app_secret_1;
            }
            document.querySelector('#configuration form[name=logonApp] #submit').click();
        };
        console.log('prepare webapp view');
        const webappView = new WebappView(jQuery, '#breadbutter-webapp-container', {
            failureAuth,
            successAuth,
            urls: {
                callback_url: "<?php echo get_home_url() . '/wp-json/breadbutter-connect/v1/authorize'; ?>",
                home_url: "<?php echo get_home_url(); ?>",
                site_url: "<?php echo get_site_url(); ?>",
                site_name: "<?php echo get_bloginfo('name') ?>",
                cors_url: "<?php echo $cors_url; ?>"
            },
            gateway_id: "<?php echo $app_gateway_mapping[$api_path]; ?>"
        });
        const launchButton = document.querySelector('#breadbutter-launch-app');
        if (launchButton) {
            launchButton.addEventListener('click', function(event) {
                if (webappView.hasSession()) {
                    webappView.start();
                    launchButton.style.display = 'none';
                } else {
                    failureAuth();
                }
            });
        }
        let TARGET_OPEN = false;
        window.addEventListener("message", (event) => {
            if (event.origin === '<?php echo $app_path_mapping[$api_path]; ?>' && event.data && event.data.name) {
                console.log(event);
                switch (event.data.name) {
                    case 'completed':
                        let app_id_1 = event.data.values.app_id;
                        let app_secret_1 = event.data.values.app_secret;
                        const type = event.data.values.type;
                        if (type === 'secondary') {
                            // Create new input and add to #configuration form.
                            const typeInput = document.createElement('input');
                            typeInput.name = 'bb_wizard_type';
                            typeInput.value = type;
                            // Insert before button.
                            const submitButton = document.querySelector('#configuration #submit');
                            submitButton.parentNode.insertBefore(typeInput, submitButton);
                        }
                        //This is for the wordpress to add in the app id and app secret
                        document.querySelector('#configuration [name="logon_app_id"]').value = app_id_1;
                        if (document.querySelector('#configuration [name="logon_app_secret"]')) {
                            document.querySelector('#configuration [name="logon_app_secret"]').value = app_secret_1;
                        } else {
                            document.querySelector('#configuration .js-secret-update-button').click();
                            document.querySelector('#configuration [name="logon_app_secret"]').value = app_secret_1;
                        }
                        document.querySelector('#configuration form[name=logonApp] #submit').click();
                        // $('#configuration form[name=logonApp]').trigger('submit')
                        break;
                    case 'urls':
                        event.source.postMessage({
                            name: 'urls',
                            values: {
                                callback_url: "<?php echo get_home_url() . '/wp-json/breadbutter-connect/v1/authorize'; ?>",
                                home_url: "<?php echo get_home_url(); ?>",
                                site_url: "<?php echo get_site_url(); ?>",
                                site_name: "<?php echo get_bloginfo('name') ?>",
                                cors_url: "<?php echo $cors_url; ?>"
                            }
                        }, event.origin);
                        break;
                    case 'redirect':
                        const url = event.data.value;
                        const source = event.data.source;
                        // console.log(url);
                        if (source == 'local') {
                            document.getElementById('breadbutter-wordpress-app').src = url;
                        } else {
                            TARGET_OPEN = window.open(url, '_blank');
                            if (!TARGET_OPEN) {
                                jQuery("#bb-popups-aware-modal").dialog({
                                    modal: true,
                                    width: 600,
                                    classes: {'ui-dialog-titlebar': 'bb-popups-aware-modal-title'}
                                });
                            }
                        }
                        break;
                    case 'authorized':
                        console.log('authorized event received');
                        console.log(event.data);
                        console.log(event.source);
                        if (TARGET_OPEN) {
                            TARGET_OPEN.postMessage({
                                name: 'close'
                            }, event.origin);
                            TARGET_OPEN.close();
                        } else {
                            event.source.postMessage({
                                name: 'close'
                            }, event.origin);
                        }

                        if (event.data && event.data.device_id) {
                            if (localStorage) {
                                localStorage.setItem('bb-wordpress-device-id', event.data.device_id);
                            }
                        }

                        // getApps(event.data.x_session_token);
                        webappView.assign(event.data.session, event.data.x_session_token);
                        webappView.start();
                        let launchButton = document.querySelector('#breadbutter-launch-app');
                        if (launchButton) {
                            launchButton.style.display = 'none';
                        }
                        //manage_get_app
                        //should we same the x-seesion-token and the session in the db?

                        // let src = document.getElementById('breadbutter-wordpress-app').src;
                        // src = src.split('?')[0] + '?t=' + Date.now() + '&session=' + event.data.session;
                        // document.getElementById('breadbutter-wordpress-app').src = src;
                        break;
                    case 'thridpartycookieissue':
                        // var body = "Authentication has been blocked. If you're using an incognito browser, please close it and use the full browser. Alternatively, follow these steps to manually set up your Bread & Butter WordPress plugin: <a href='https://breadbutter.io/wordpress-guide-full/'>https://breadbutter.io/wordpress-guide-full/</a>";
                        const body = "<p>Authentication has been blocked because third-party cookies are disabled. If you're using an incognito browser, please close it and use the full browser. If you have third-party cookies disabled in your browser, please temporarily enable them.</p>" +
                        "<p>Alternatively, follow these steps to manually set up your Bread & Butter WordPress plugin: <br/><a href='https://breadbutter.io/wordpress-guide-full/'>https://breadbutter.io/wordpress-guide-full/</a></p>" +
                        "<p>Note: This is required for Admin setup only. Your website visitors won’t need to have third-party cookies enabled.</p>"
                        var notice = document.createElement('div');
                        notice.classList.add('bb-popups-aware-modal-body');
                        notice.innerHTML = body;
                        document.querySelector('#breadbutter-wordpress-app').replaceWith(notice);
                        if (document.querySelector('.js-bb-show-wizard-button')) {
                            document.querySelector('.js-bb-show-wizard-button').style.display = 'none';
                        }
                        break;
                }
            }
        }, false);
        const getApps = (session_token)=> {
            console.log(session_token);
            let postdata = {
                session_token
            };

            jQuery.post('admin-ajax.php?action=manage_get_apps', postdata).done((json) => {
                let response = JSON.parse(json);
                console.log(response.body);
                let container = document.getElementById('breadbutter-apps');
                container.innerHTML = '';
                for(let i = 0; response.body && response.body.results && i < response.body.results.length; i++) {
                    let new_item = document.createElement('div');
                    new_item.innerText = response.body.results[i].name;
                    container.appendChild(new_item);
                }
            });
        }
        const getProfile = (session_token) => {
            let postdata = {
                session_token
            };

            jQuery.post('admin-ajax.php?action=manage_get_profile', postdata).done((json) => {
                let response = JSON.parse(json);
                console.log(response.body);
            });
        }
    </script>
    <script>
        const createSamples = () => {
            document.getElementById('breadbutter-ui-sample').style.display = 'block';
            BreadButter.widgets.buttons('breadbutter-ui-sample', {
                button_theme: 'round-icons',
                pass: true
            });
        };

        const showDashboardFirst = () => {
            let dash_settings = document.getElementById('breadbutter-dashboard-tab');
            // let dash_settings = document.getElementById('configuration');
            if (dash_settings) {
                dash_settings.style.display = 'block';
            }
        };

        const addView = function(type, cls) {
            const container = document.createElement(type);
            if (cls) {
                container.classList.add(cls);
            }
            return container;
        };

        const addHoverIcon = function(tr) {
            let view = addView('div', 'breadbutter-info-icon');
            view.innerHTML = `
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 640 640" enable-background="new 0 0 640 640" xml:space="preserve" transform="rotate(180)">
                <title>info-circled</title>
                <g>
                    <circle cx="319.25" cy="319.25" r="317.125" fill="red"/>
                    <path fill="#FFFFFF" d="M319.25,226.996c18.671,0,33.771,15.65,33.771,34.595v217.457c0,19.22-15.101,34.596-33.771,34.596
                        c-18.67,0-33.771-15.649-33.771-34.596V261.865C285.479,242.646,300.58,226.996,319.25,226.996L319.25,226.996z"/>
                    <path fill="#FFFFFF" d="M319.25,101.793c18.671,0,33.771,15.65,33.771,34.595c0,19.22-15.101,34.596-33.771,34.596
                        c-18.67,0-33.771-15.65-33.771-34.596C285.753,117.169,300.58,101.793,319.25,101.793z"/>
                </g>
                </svg>
            `;
            let hint = addView('div', 'breadbutter-info-hint');
            hint.innerHTML = `
                    <div class="breadbutter-info-content">App ID not valid, or CORS Allow list not set in App.</div>
                    <div class="breadbutter-info-arrow"></div>
                    `;
            tr.appendChild(view);
            view.appendChild(hint);
        };

        const showErrorAppID = () => {
            let app_id_table_arr = document.querySelectorAll('.breadbutter-app-id-table');
            if (app_id_table_arr && app_id_table_arr.length > 0) {
                for (const app_id_table of app_id_table_arr) {
                    let trs = app_id_table.querySelectorAll('tr');
                    for (let i = 0; i < trs.length; i++) {
                        let tr = trs[i];
                        addHoverIcon(tr);
                    }
                }
            }
        }

        const updateConversion = (success) => {
            const analyticsTab = document.querySelector('.js-breadbutter-tab-conversion');
            const textBox = document.querySelector('.js-breadbutter-tab-conversion .bb-dashboard-tile-title .enable-text');
            const appSecretSet = analyticsTab.dataset.appSecretSet;
            analyticsTab.classList.remove('bb-dashboard-not-set');
            if (success && appSecretSet) {
                // set green status.
                analyticsTab.classList.add('bb-dashboard-enabled');
                textBox.textContent = 'Enabled';
                document.querySelector('.js-bb-setup-is-working').style.display = 'flex';
            } else if (success && !appSecretSet) {
                analyticsTab.classList.add('bb-dashboard-disabled');
                textBox.textContent = 'Disabled';
            } else {
                analyticsTab.classList.add('bb-dashboard-not-configured');
                textBox.textContent = 'Disabled';
            }
        };

        const updateAnalytics = (success) => {
            // success = false;
            const analyticsTab = document.querySelector('.js-breadbutter-tab-page-view-tracking');
            const textBox = document.querySelector('.js-breadbutter-tab-page-view-tracking .bb-dashboard-tile-title .enable-text');
            const analyticsButtons = document.querySelector('.js-breadbutter-tab-page-view-tracking .bb-analytics-buttons');
            const agencyCode = document.querySelector('.js-breadbutter-tab-page-view-tracking .bb-agency-code');
            analyticsTab.classList.remove('bb-dashboard-not-set');
            if (success) {
                // set green status.
                analyticsTab.classList.add('bb-dashboard-enabled');
                textBox.textContent = 'Enabled';
            } else {
                if (agencyCode) {
                    analyticsButtons.style.display = 'none';
                    agencyCode.style.display = 'flex';
                    analyticsTab.classList.add('bb-dashboard-disabled');
                    textBox.textContent = 'Disabled';
                } else {
                    analyticsTab.classList.add('bb-dashboard-not-configured');
                    textBox.textContent = 'Disabled';
                }
            }
        };

        const updateSettings = (success) => {
            // const settingsForm = document.querySelector('.js-analytics-conversion-settings');
            const analyticsTab = document.querySelector('.js-breadbutter-tab-conversion');
            const appSecretSet = analyticsTab.dataset.appSecretSet;
            if (!success) {
                // settingsForm.style.display = 'block';
                document.querySelector('.js-analytics-conversion-settings .js-analytics-setting').style.display = 'block';
                document.querySelector('.js-analytics-conversion-settings .js-conversion-setting').style.display = 'block';
                document.querySelector('#configuration-tab').textContent = 'Setup';
            } else if (success && !appSecretSet) {
                // settingsForm.style.display = 'block';
                document.querySelector('#configuration-tab').textContent = 'Setup';
                document.querySelector('.js-analytics-conversion-settings .js-conversion-setting').style.display = 'block';
                document.querySelector('.js-analytics-conversion-settings .js-analytics-setting').style.display = 'none';
            }
        };

        const updateSection = (success) => {
            updateConversion(success);
            updateSettings(success);
        };

        const callSample = () => {
            try {
                BreadButter.api.getProviders(false, function(res) {
                    console.log(res);
                    if (res && res.providers && res.providers.length) {
                        document.getElementById('breadbutter-message').innerHTML =
                            ``;
                        createSamples();
                    } else if (res && res.error) {
                        // showDashboardFirst();
                        showErrorAppID();
                        document.getElementById('breadbutter-message').innerHTML =
                            `Setup not complete. Please check your App ID in the Configuration tab, and ensure that you have set the CORS Allow List in your Bread & Butter App.`;
                        document.getElementById('breadbutter-ui-sample-holder').style.display = 'none';
                    } else {
                        // showDashboardFirst();
                        // showErrorAppID();
                        document.getElementById('breadbutter-message').innerHTML =
                            `You have not enabled any Identity Providers in Bread & Butter. Please log in to your Bread & Butter account, and go to "Default Rules" to enable Identity Providers such as Google, Microsoft, Facebook, and more.`;
                        document.getElementById('breadbutter-ui-sample').style.display = 'none';
                    }

                    updateAnalytics(res.tracking_enabled);
                });
            } catch (e) {
                showDashboardFirst();
                document.getElementById('breadbutter-message').innerHTML =
                    `No App ID or incorrect App ID. Please check your App Settings above.`;
                document.getElementById('breadbutter-ui-sample-holder').style.display = 'none';
            }
            try {
                BreadButter.api.ping(function(resp) {
                    if (resp && resp.version) {
                        updateSection(true);
                    }
                    else {
                        updateSection(false);
                    }
                });
            } catch (err) {
                updateSection(false);
            }
        };
        if (typeof BreadButter != 'undefined') {
            callSample();
            // let icon = document.getElementById('breadbutter-refresh');
            // icon.style.display = 'block';
            // icon.onclick = () => {
            //     callSample()
            // };
        } else {
            document.getElementById('breadbutter-message').innerHTML =
                `Setup not complete. Please check your App ID in the Configuration tab, and ensure that you have set the CORS Allow List in your Bread & Butter App.`;
            document.getElementById('breadbutter-ui-sample-holder').style.display = 'none';
            updateSection(false);
        }
    </script>
    <div id="bb-notification" style="display: none;"></div>
    <div id="bb-popups-aware-modal" style="display: none;"><p class="bb-popups-aware-modal-body">Please follow the instructions at the top of your browser to allow pop-ups for this page.</p></div>
</div>
<script>
    const hashChange = function () {
        let hash = window.location.hash;
        let title = "Bread & Butter < <?php echo get_bloginfo('name'); ?> — WordPress";
        switch(hash) {
            case "#content-gating":
                title += " | Content Gating";
                break;
            case "#content-preview":
                title += " | Content Preview";
                break;
            case "#newsletter":
                title += " | Opt-in Popup";
                break;
            case "#contactus":
                title += " | Contact Us";
                break;
            case "#userprofile-tool":
                title += " | User Profile Tool";
                break;
            case "#client":
                title += " | Display Settings";
                break;
            case "#advance":
                title += " | Advanced Settings";
                break;
            case "#configuration":
                title += " | Dashboard";
                break;
        }
        document.title = title;
    };

    window.addEventListener("hashchange", function () {
        hashChange();
    });

</script>