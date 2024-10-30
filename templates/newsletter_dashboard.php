<?php

function show_newsletter_home_page() {
    function addNewsletterOptions() {
        settings_fields('breadbutter_newsletter_groups');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_homepage_enabled');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_pages');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_type');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_widget');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_timer');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_image');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_url', false, false, 'bb-hidden-config');
        do_settings_section('breadbutter_connect', 'breadbutter_newsletter_config_event', false, false, 'bb-hidden-config');
        echo <<<HTML
    <input id="newsletter-image" name="file" type="file" />
    <progress id="newsletter-image-progress"></progress>
HTML;
        echo "<div class='bb-dashboard-submit-flex-box'>";
        submit_button("SAVE");
        echo "<div class='bb-flex-box'>";
        echo "<a class='bb-hash-button' href='#newsletter'>";
        echo "<span>Advanced Settings</span>";
        echo "</a>";
        echo "</div>";
        echo "</div>";
    }
    $value = get_option('breadbutter_newsletter_homepage_enabled', false);
    $values = !empty(get_option('breadbutter_newsletter_pages', []));

    $enabled = ($value || $values) ? " bb-dashboard-enabled" : "bb-dashboard-disabled" ;
    echo "<div class='breadbutter-flex-1 breadbutter-tab breadbutter-inline-box {$enabled}'>";
    echo "<div class='flex-row'>";
    echo "<div class='flex-col bb-dashboard-tile-content-box'>";
    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
    toggleBlock($value);
    echo "Opt-in Popup: <span class='enable-text'>" . (!$enabled ? "Disabled" : " Enabled") . "</span></div>";
    echo "<div class='bb-dashboard-tile-content'>
        <div>Prompt all first time visitors to join your newsletter, or sign up for your contest or special offer.</div>";
    addNewsletterOptions();


    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>

<?php show_newsletter_home_page() ?>
<script>
    let $ = jQuery;
    $( document ).ready(function(){
        $('#newsletter-image-progress').hide();
        $('#newsletter-image').on('change', function () {

            let file = $('#newsletter-image')[0].files[0];
            if (file) {
                let form = new FormData();
                form.append('file', $('#newsletter-image')[0].files[0]);
                $('#newsletter-image-progress').show();
                $('.breadbutter-wrap form#dashboard-newsletter input[type=submit]').attr('disabled', true);
                $.ajax({
                    // Your server script to process the upload
                    url: 'admin-ajax.php?action=upload_image',
                    type: 'POST',

                    // Form data
                    data: form,

                    // Tell jQuery not to process data or worry about content-type
                    // You *must* include these options!
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(response) {
                    $('.breadbutter-wrap form#dashboard-newsletter input[type=submit]').removeAttr('disabled');
                    console.log(response);
                    $('#newsletter-image-progress').hide();
                    $('input[name=breadbutter_newsletter_custom_image]').val(response);
                })
            }
        });
    });
</script>
