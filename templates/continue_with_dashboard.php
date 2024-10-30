<?php

function show_continue_with_home_page() {
    function addContinueWithOptions() {
//        settings_fields('breadbutter_client_option_groups');
//        do_settings_section('breadbutter_connect', 'breadbutter_ui_config', false, false, 'bb-hidden-config');
//        do_settings_section('breadbutter_connect', 'breadbutter_user_profile_tools_1', false, false, 'bb-hidden-config');
//        do_settings_section('breadbutter_connect', 'breadbutter_user_profile_tools_pos', false, false, 'bb-hidden-config');
//        do_settings_section('breadbutter_connect', 'breadbutter_ui_config_post', false, false, 'bb-hidden-config');
        do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_section');
//        do_settings_section('breadbutter_connect', 'breadbutter_ui_config_continue_with_header_section', false, false, 'bb-hidden-config');
//        do_settings_section('breadbutter_connect', 'breadbutter_ui_config_blur_settings', false, false, 'bb-hidden-config');
//        do_settings_section('breadbutter_connect', 'breadbutter_ui_config_theme_settings', false, false, 'bb-hidden-config');
        echo "<div class='bb-dashboard-submit-flex-box'>";
        submit_button("SAVE");
//        echo "<div class='bb-flex-box'>";
//        echo "<a class='bb-hash-button' href='#client'>";
//        echo "<span>Advanced Settings</span>";
//        echo "</a>";
//        echo "</div>";
        echo "</div>";

    }
    $value = !empty(get_option('breadbutter_continue_with_pages', []));

//    $value = get_option('breadbutter_continue_with_home_page');
//    echo $value;



    $enabled = !empty($value) ? " bb-dashboard-enabled" : "bb-dashboard-disabled" ;
//    echo "<div class='breadbutter-flex-1 breadbutter-tab breadbutter-inline-box {$enabled}'>";
//        echo "<div class='flex-row'>";
//            echo "<div class='flex-col bb-dashboard-tile-content-box'>";
//                echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
//                toggleBlock($value);
//                echo "Continue with: <span class='enable-text'>" . (empty($value)? "Disabled" : " Enabled") . "</span></div>";
//                echo "<div class='bb-dashboard-tile-content'>
//                <div>Start converting users on your homepage</div>";
//                addContinueWithOptions();
//                echo "</div>";
//            echo "</div>";
//        echo "</div>";
//    echo "</div>";

    echo "<div class='breadbutter-flex-1 breadbutter-inline-box {$enabled}'>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
    toggleBlock($value);
    echo "Continue with: <span class='enable-text'>" . (empty($value)? "Disabled" : " Enabled") . "</span></div>";
    echo "<div class='bb-dashboard-tile-content'>
                <div>Start converting users on your homepage</div>";
    addContinueWithOptions();
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>

<?php show_continue_with_home_page() ?>
<script>
    // let continue_with_enable_button = document.getElementById('enable-continue-with-home-page');
    // continue_with_enable_button.onclick = function(){
    //     let a = document.body.querySelector('input[name=breadbutter_continue_with_home_page]')
    //     a.click();
    //     let b = a.parentElement.parentElement.parentElement.parentElement.parentElement.querySelector('input[type=submit]');
    //     console.log(a.parentElement.parentElement.parentElement.parentElement.parentElement);
    //     console.log(b);
    //     setTimeout(()=> {
    //         b.click();
    //     }, 10);
    //     // b.click();
    // };
</script>
