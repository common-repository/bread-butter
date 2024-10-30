<div class="top-row breadbutter-tab-header">
    <span>Opt-in Popup</span>
</div>
<div class="top-row">
    <!-- <div class="breadbutter-icon"></div> -->
    <div class="breadbutter-how-to-header"><span>Add the Opt-in Popup to any page by following these steps:</span></div>
    <a href="https://breadbutter.io/wordpress-newsletter/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
</div>

<div class="content preview-content">
    <div class="bb-preview-image-box" data="opt-in"></div>
    <form method="post" action="options.php">
        <?php
        settings_fields('breadbutter_newsletter_groups');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_homepage_enabled', false, false, 'bb-hidden-config');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_pages');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_type');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_widget');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_timer');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_image');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_url');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_event');
        submit_button("SAVE");
        ?>
    </form>

</div>