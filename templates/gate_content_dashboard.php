<?php

function gate_content_dashboard() {
    function addGateContentDashboardOptions() {
        settings_fields('breadbutter_gating_content_preview_group');
        do_settings_section('breadbutter_connect', 'breadbutter_gating_content_preview_config_enabled');
        do_settings_section('breadbutter_connect', 'breadbutter_gating_content_preview_config_pages');
        do_settings_section('breadbutter_connect', 'breadbutter_gating_content_preview_config_text', false, false, 'bb-hidden-config');
        echo "<div class='bb-dashboard-submit-flex-box'>";
        submit_button("SAVE");
        echo "<div class='bb-flex-box'>";
        echo "<a class='bb-hash-button' href='#content-gating'>";
        echo "<span>Advanced Settings</span>";
        echo "</a>";
        echo "</div>";
        echo "</div>";
    }
    
    $id = 'breadbutter_gating_content_preview_config_enabled';
    $value = esc_attr(get_option($id, false));

    $enabled = $value ? " bb-dashboard-enabled" : "bb-dashboard-disabled" ;

    echo "<div class='breadbutter-flex-1 breadbutter-tab breadbutter-inline-box {$enabled}'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
    toggleBlock($value);
    echo "Gate Content: <span class='enable-text'>" . (!$value ? "Disabled" : " Enabled") . "</span></div>";
    echo "<div class='bb-dashboard-tile-content'>
        <div>Require your users to sign before viewing specific pages</div>";

    addGateContentDashboardOptions();

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>

<?php gate_content_dashboard() ?>
<script>
</script>
