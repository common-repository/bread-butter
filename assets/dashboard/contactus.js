let $, dispatchNotification, ajaxDataCheck;
const assign = function(jquery, fns) {
    $ = jquery;
    dispatchNotification = fns.dispatchNotification;
    ajaxDataCheck = fns.ajaxDataCheck;
};

const setupConfigEvent = function() {
    const selectPosition = (value) => {
        let horizontal = 'right';
        let vertical = 'bottom';
        let values = value.split('_');
        if (values.length == 2) {
            vertical = values[0];
            horizontal = values[1];
        }
        $('select[name="breadbutter_contactus_position_vertical"]').val(vertical);
        $('select[name="breadbutter_contactus_position_horizontal"]').val(horizontal);
    };

    let select_position_options = document.querySelector('#configuration select[name=breadbutter_contactus_dashboard_position]');
    if (select_position_options) {
        select_position_options.onchange = (e) => {
            let value = e.target.value;
            selectPosition(value);
        };
    }
}

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
    setupConfigEvent();
    $('.breadbutter-wrap form#dashboard-contactus').submit(function (event) {
        event.preventDefault();
        if ($('#breadbutter_contactus_pages_enabled').prop('checked')) {
            $('select[name="breadbutter_contactus_pages[]"]').val('all');
        } else {
            $('select[name="breadbutter_contactus_pages[]"]').val('');
        }
        startSubmit(this);
        continueSubmit(this);
    });
}

export default {
    assign,
    setup
};