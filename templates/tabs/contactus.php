<div class="top-row breadbutter-tab-header">
    <span>Contact Us Tool</span>
</div>
<div class="top-row">
    <!-- <div class="breadbutter-icon"></div> -->
    <div class="breadbutter-how-to-header"><span>Add the contact us tool to any page by following these steps:</span></div>
    <a href="https://breadbutter.io/wordpress-contact-us-tool/" class="bb-howto" target="_blank">HOW TO GUIDE</a>
</div>
<div class="content preview-content">
    <div class="bb-preview-image-box" data="contact-us"></div>
    <form method="post" action="options.php">
        <?php
        settings_fields('breadbutter_contactus_groups');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config_signed_out');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config_signed_in');
        do_settings_section('breadbutter_connect', 'breadbutter_contactus_config_position');
        submit_button("SAVE");
        ?>
    </form>
</div>

<script>
    let $ = jQuery;
    $( document ).ready(function(){
        $('#contactus-image-progress').hide();
        $('#contactus-image').on('change', function () {

            let file = $('#contactus-image')[0].files[0];
            if (file) {
                let form = new FormData();
                form.append('file', $('#contactus-image')[0].files[0]);
                $('#contactus-image-progress').show();
                $('#contactus form input[type=submit]').attr('disabled', true);
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
                    $('#contactus form input[type=submit]').removeAttr('disabled');
                    console.log(response);
                    $('#contactus-image-progress').hide();
                    $('input[name=breadbutter_contactus_custom_image]').val(response);
                })
            }
        });
    });
</script>
