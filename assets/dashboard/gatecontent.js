let $, dispatchNotification, ajaxDataCheck;
const assign = function(jquery, fns) {
    $ = jquery;
    dispatchNotification = fns.dispatchNotification;
    ajaxDataCheck = fns.ajaxDataCheck;
};

const startSubmit = (form) => {
    let $submitter = $(form).find('[name="submit"]');
    if (event.originalEvent && event.originalEvent.submitter) {
        $submitter = $(event.originalEvent.submitter);
    }
    $submitter.attr('disabled', 'disabled');
    $submitter.closest('p.submit').addClass('bb-loader');
};

const continueSubmit = (form) => {
    const data = $(form).serialize();
    let array_data = $(this).serializeArray();
    let hash_data = {};
    for (let i = 0; i < array_data.length; i++) {
        hash_data[array_data[i].name] = array_data[i].value;
    }
    $.post('options.php', data).done((resp) => {
        $(form).find('[name="submit"]').removeAttr('disabled');
        $(form).find('.bb-loader').removeClass('bb-loader');
        dispatchNotification('Settings were saved successfully');
        if ($(form).hasClass('js-analytics-conversion') || $(form).hasClass('js-analytics-conversion-settings')) {
            location.reload();
        }
        ajaxDataCheck(hash_data);
    }).fail((error) => {
        dispatchNotification('Something went wrong. Please try again later.', 'error');
    });
};

const setup = function() {
    $('.breadbutter-wrap form#dashboard-gate-content').submit(function (event) {
        event.preventDefault();
        let select = $('select[name="breadbutter_gating_content_preview_pages[]"]').val();
        let enabled = $('input[name=breadbutter_gating_content_preview_config_enabled]').prop('checked');
        if ( enabled && !select.length ) {
            dispatchNotification('Please select at least one page', 'error');
        } else {
            startSubmit(this);
            continueSubmit(this);
        }
    });
}

export default {
    assign,
    setup
};