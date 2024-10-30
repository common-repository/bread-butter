<?php

use BreadButter_WP_Plugin\Base\BaseController;

function contact_us_dashboard() {
    function addDashboardOptions() {
        settings_fields('breadbutter_contactus_groups');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config_enabled');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config', false, false, 'bb-hidden-config');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config_position', false, false, 'bb-hidden-config');

        echo "<div class='bb-dashboard-submit-flex-box'>";
        submit_button("SAVE");
        echo "<div class='bb-flex-box'>";
        echo "<a class='bb-hash-button' href='#contactus'>";
        echo "<span>Advanced Settings</span>";
        echo "</a>";
        echo "</div>";
        echo "</div>";
    }
    $value = false;
    $selected_pages = get_option('breadbutter_contactus_pages', []);
    if (is_array($selected_pages) && in_array(BaseController::$allOption, $selected_pages)) {
        $value = true;
    }

    $enabled = $value ? " bb-dashboard-enabled" : "bb-dashboard-disabled" ;

    echo "<div class='breadbutter-flex-1 breadbutter-tab breadbutter-inline-box {$enabled}'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
    toggleBlock($value);
    echo "Contact us: <span class='enable-text'>" . (!$value ? "Disabled" : " Enabled") . "</span></div>";
    echo "<div class='bb-dashboard-tile-content'>
        <div>Add the contact us tool to all pages on your website</div>";

    addDashboardOptions();

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>

<?php contact_us_dashboard() ?>
<script>
</script>
