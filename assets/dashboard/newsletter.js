let $, dispatchNotification, ajaxDataCheck;
const assign = function(jquery, fns) {
    $ = jquery;
    dispatchNotification = fns.dispatchNotification;
    ajaxDataCheck = fns.ajaxDataCheck;
};

const refreshEvent = function() {
    let integration = $('.newsletter-event .newsletter-integration').attr('bbi-name');
    if (integration) {
        $('.newsletter-event .newsletter-integration').addClass('lock-integration');
        $('.newsletter-event .newsletter-integration .newsletter-integration-choice').hide();
        $('.newsletter-event .newsletter-integration .newsletter-integration-choice[name="' + integration + '"]').show();
        $('.newsletter-event .newsletter-integration .newsletter-integration-choice[name="' + integration + '"]').attr('bbi-selected', true);
    }
};

const setupConfigNewsletterEvent = function(id) {
    if ($(id + '.newsletter-event')) {
        $(id + '.newsletter-event').insertBefore($(id + ' .submit'));
        $(id + '.newsletter-event .vc-compact-color-item').click(function(event) {
            event.preventDefault();
            $(id + '.newsletter-event .vc-compact-color-item').removeAttr('aria-selected');
            $(this).attr('aria-selected', true);
            let color = $(this).attr('aria-label');
            $(id + '.newsletter-event .event-name').css('background-color', color);
            $(id + '.newsletter-event .event-name').attr('color', color);
        });
        $(id + '.newsletter-event input[name=event_name]').keyup(function(event) {
            let value = $(this).val();
            $(id + '.newsletter-event .event-name').text(value);
            $(id + '.newsletter-event .event-name').attr('name', value);
        });
        let color = $(id + '.newsletter-event .event-name').attr('color');
        let name = $(id + '.newsletter-event .event-name').attr('name');
        if (name) {
            $(id + '.newsletter-event input[name=event_name]').val(name);
        }
        if (color) {
            let selecter = '.newsletter-event .vc-compact-color-item[aria-label=' + color + ']';
            $(selecter).attr('aria-selected', true);
        }

        let integration = $(id + '.newsletter-event .newsletter-integration').attr('bbi-name');
        if (integration) {
            $(id + '.newsletter-event .newsletter-integration').addClass('lock-integration');
            $(id + '.newsletter-event .newsletter-integration .newsletter-integration-choice').hide();
            $(id + '.newsletter-event .newsletter-integration .newsletter-integration-choice[name="' + integration + '"]').show();
            $(id + '.newsletter-event .newsletter-integration .newsletter-integration-choice[name="' + integration + '"]').attr('bbi-selected', true);
        }
        $(id + '.newsletter-event .newsletter-integration .newsletter-integration-choice').click(function(event) {
            if ($(id + '.newsletter-event .newsletter-integration').hasClass('lock-integration')) {
                return;
            } else {
                let el = $(this);
                let name = false;
                if (el.attr('bbi-selected') == 'true') {
                    el.removeAttr('bbi-selected');
                } else {
                    $(id + '.newsletter-event .newsletter-integration .newsletter-integration-choice').removeAttr('bbi-selected');
                    el.attr('bbi-selected', true);
                    name = el.attr('name');
                }
                if (name) {
                    $(id + '.newsletter-event .newsletter-integration').attr('bbi-name', name);
                } else {
                    $(id + '.newsletter-event .newsletter-integration').removeAttr('bbi-name');
                }
            }
        });

        $(id + '.newsletter-event .clear-settings').click(function() {
            cleanIntegration();
            submitCleanForm();
        });
    }
}

const setupConfigEvent = function() {
    $('.breadbutter-wrap #newsletter form table tr td input[type=hidden]').parent().parent().parent().parent().hide();
    setupConfigNewsletterEvent('.breadbutter-wrap #newsletter form ');
    setupConfigNewsletterEvent('.breadbutter-wrap form#dashboard-newsletter ');

    const setPlaceholder = (type) => {
        let placeholder_header, placeholder_main_message, placeholder_success_header, placeholder_success_message;
        switch (type) {
            case 'contest':
                placeholder_header =  'Enter to Win!';
                placeholder_main_message = 'Your chance to win monthly giveaways';
                placeholder_success_header = 'Perfect %NAME%, you\'re in!';
                placeholder_success_message = '';
                break;
            case 'special_offer':
                placeholder_header =  'Opt in for Special Offers';
                placeholder_main_message = 'Get exclusive offers, coupons and limited-time discounts delivered to your inbox';
                placeholder_success_header = 'Perfect %NAME%, you\'re in!';
                placeholder_success_message = '';
                break;
            case 'newsletter_signup':
                placeholder_header =  'Opt in to our Newsletter';
                placeholder_main_message = 'Keep up with the latest information and news';
                placeholder_success_header = 'Perfect %NAME%, you\'re in!';
                placeholder_success_message = '';
                break;
            case 'custom':
                placeholder_header =  'Welcome';
                placeholder_main_message = 'Keep up with the latest information and news';
                placeholder_success_header = 'Perfect %NAME%, you\'re in!';
                placeholder_success_message = '';
                break;
        }

        if (placeholder_header)
            $('input[name="breadbutter_newsletter_header"]').attr('placeholder', placeholder_header);
        if (placeholder_main_message)
            $('input[name="breadbutter_newsletter_main_message"]').attr('placeholder', placeholder_main_message);
        if (placeholder_success_header)
            $('input[name="breadbutter_newsletter_success_header"]').attr('placeholder', placeholder_success_header);
        if (placeholder_success_message)
            $('input[name="breadbutter_newsletter_success_message"]').attr('placeholder', placeholder_success_message);
    };

    const selectOptInType = (type) => {
        let header, main_message, success_header, success_message, event_name;
        switch (type) {
            case 'contest':
                header =  'Enter to Win!';
                main_message = 'Your chance to win monthly giveaways';
                success_header = 'Perfect %NAME%, you\'re in!';
                success_message = '';
                event_name = 'Contest';
                break;
            case 'special_offer':
                header =  'Opt in for Special Offers';
                main_message = 'Get exclusive offers, coupons and limited-time discounts delivered to your inbox';
                success_header = 'Perfect %NAME%, you\'re in!';
                success_message = '';
                event_name = 'Special Offer';
                break;
            case 'newsletter_signup':
                header =  'Opt in to our Newsletter';
                main_message = 'Keep up with the latest information and news';
                success_header = 'Perfect %NAME%, you\'re in!';
                success_message = '';
                event_name = 'Newsletter';
                break;
            case 'custom':
                header =  '';
                main_message = '';
                success_header = '';
                success_message = '';
                event_name = 'Opt-in';
                break;
        }

        $('input[name="breadbutter_newsletter_header"]').val(header);
        $('input[name="breadbutter_newsletter_main_message"]').val(main_message);
        $('input[name="breadbutter_newsletter_success_header"]').val(success_header);
        $('input[name="breadbutter_newsletter_success_message"]').val(success_message);
        $('.newsletter-event .event-name').text(event_name);
        $('.newsletter-event .event-name').attr('name', event_name);
        $('.newsletter-event input[name=event_name]').val(event_name);
        setPlaceholder(type);
        clean = false;
    }

    // let select_options = document.querySelector('#newsletter select[name=breadbutter_newsletter_type]');
    let select_options = document.querySelectorAll('select[name=breadbutter_newsletter_type]');
    select_options.forEach((option)=> {
        option.onchange = (e) => {
            let empty = e.target.querySelector('[name=empty_value]');
            if (empty) {
                empty.remove();
            }
            let value = e.target.value;
            selectOptInType(value);
        };
    });

    let select_option = document.querySelector('select[name=breadbutter_newsletter_type]');
    if (select_option) {
        setPlaceholder(select_option.value);
    }

    const selectCustomImageType = (type) => {
        switch (type) {
            case 'fill':
            case 'center':
                $('input[name=breadbutter_newsletter_custom_image]').removeAttr('disabled');
                $('input#newsletter-image').removeAttr('disabled');
                break;
            case 'none':
            case 'default':
            default:
                $('input[name=breadbutter_newsletter_custom_image]').attr('disabled', true);
                $('input#newsletter-image').attr('disabled', true);
                break;
        }
    };

    const addEventToCustomType = (option) => {
        if (option) {
            option.onchange = (e) => {
                let value = e.target.value;
                console.log(value);
                selectCustomImageType(value);
            };
            selectCustomImageType(option.value);
        }
    }

    // let select_image_options = document.querySelector('#newsletter select[name=breadbutter_newsletter_custom_image_type]');
    let select_image_options = document.querySelectorAll('select[name=breadbutter_newsletter_custom_image_type]');
    if (select_image_options) {
        select_image_options.forEach((option)=> {
            addEventToCustomType(option);
        });
    }

    // radio_sets.forEach((b)=> {
    //     b.addEventListener('click', (e)=> {
    //         let value = e.target.value;
    //         selectOptInType(value);
    //     });
    // });
}

const submitCleanForm = ()=> {
    let form = $('.breadbutter-wrap #newsletter form');
    startSubmit(form);
    continueSubmit(form);
}
let clean = false;
const cleanIntegration = ()=> {
    let form = $('.breadbutter-wrap #newsletter form');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_event_id]').val('');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_integration_id]').val('');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_trigger_id]').val('');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_action_id]').val('');

    $('input[name="breadbutter_newsletter_header"]').val('');
    $('input[name="breadbutter_newsletter_main_message"]').val('');
    $('input[name="breadbutter_newsletter_success_header"]').val('');
    $('input[name="breadbutter_newsletter_success_message"]').val('');

    let color = '#FE9200';
    $('.newsletter-event .event-name').removeAttr('name');
    $('.newsletter-event .event-name').css('background-color', color);
    $('.newsletter-event .event-name').text('');
    $('.newsletter-event input[name=event_name]').val('');
    $('.newsletter-event .event-name').attr('color', color);
    $('.newsletter-event .vc-compact-color-item').removeAttr('aria-selected');
    let selecter = '.newsletter-event .vc-compact-color-item[aria-label='+ color+']';
    $(selecter).attr('aria-selected', true);
    $('.newsletter-event .newsletter-integration').removeAttr('bbi-name');
    $('.newsletter-event .newsletter-integration').removeClass('lock-integration');
    $('.newsletter-event .newsletter-integration-choice').show();

    $('.newsletter-event .newsletter-integration input[name=api_key]').val('');
    $('.newsletter-event .newsletter-integration input[name=audience_id]').val('');
    $('.newsletter-event .newsletter-integration input[name=zapier_endpoint]').val('');

    $('.newsletter-event .newsletter-integration .newsletter-integration-choice').removeAttr('bbi-selected');

    let select = form.find('select[name=breadbutter_newsletter_type]');
    select.removeAttr('disabled');
    $('select[name=breadbutter_newsletter_type] option').removeAttr('selected');
    if (!select.find('[name=empty_value]').length) {
        let empty = document.createElement('option');
        empty.setAttribute('name', 'empty_value');
        empty.setAttribute('selected', true);
        select.prepend(empty);
    } else {
        select.find('[name=empty_value]').attr('selected', true);
    }

    let selectImageType = form.find('select[name=breadbutter_newsletter_custom_image_type]');
    if (selectImageType.length) {
        // selectImageType.removeAttr('disabled');
        let defaultOption = selectImageType[0].querySelector('[value=default]');
        if (!defaultOption) {
            defaultOption.setAttribute('selected', true);
        }
    }

    $('input[name="breadbutter_newsletter_header"]').removeAttr('placeholder');
    $('input[name="breadbutter_newsletter_main_message"]').removeAttr('placeholder');
    $('input[name="breadbutter_newsletter_success_header"]').removeAttr('placeholder');
    $('input[name="breadbutter_newsletter_success_message"]').removeAttr('placeholder');
    clean = true;
}

const getAppIntegrationData = (form) => {
    let event_id = $(form).find('input[name=breadbutter_newsletter_event_id]').val();
    let integration_id = $(form).find('input[name=breadbutter_newsletter_integration_id]').val();
    let trigger_id = $(form).find('input[name=breadbutter_newsletter_trigger_id]').val();
    let action_id = $(form).find('input[name=breadbutter_newsletter_action_id]').val();

    let event = $(form).find('.newsletter-event .event-name');
    let name = event.attr('name');
    let color = event.attr('color');
    if (!event_id) {
        if (!name) {
            return false;
        }
    } else {
        if (!name) {
            return -1;
        }
    }
    let code = name.toLowerCase().replace(/ /g, '_');
    let data = {
        event: {
            name, color, code,
            id: event_id
        }
    };

    let ai = $(form).find('.newsletter-event .newsletter-integration');
    if (ai && ai.attr('bbi-name')) {
        let name = ai.attr('bbi-name');
        switch(name) {
            case 'zapier':
                let zapier_endpoint = $(form).find('.newsletter-event .newsletter-integration input[name=zapier_endpoint]').val();
                if (zapier_endpoint) {
                    data = {
                        ...data,
                        integration: {
                            id: integration_id,
                            name: 'WordPress Zapier Integration',
                            type: 'zapier',
                            zapier_endpoint
                        },
                        trigger: {
                            id: trigger_id,
                            name: 'WordPress Zapier Rule'
                        },
                        action: {
                            id: action_id,
                            type: 'zapier'
                        },
                    }
                } else {
                    data = -1;
                }
                break;
            case 'mailchimp':
                let api_key = $(form).find('.newsletter-event .newsletter-integration input[name=api_key]').val();
                let audience_id = $(form).find('.newsletter-event .newsletter-integration input[name=audience_id]').val();
                if (api_key && audience_id) {
                    data = {
                        ...data,
                        integration: {
                            id: integration_id,
                            name: 'WordPress Mailchimp Integration',
                            type: 'mailchimp',
                            api_key
                        },
                        trigger: {
                            id: trigger_id,
                            name: 'WordPress Mailchimp Rule'
                        },
                        action: {
                            id: action_id,
                            type: 'mailchimp',
                            audience_id,
                            audience_action: 'add'
                        }
                    }
                } else {
                    data = -1;
                }
                break;
        }
    }
    return data;
};

const handleAppTriggerAction = (form, data) => {
    let postdata = {
        ...data.action,
        integration_id: data.integration.id,
        trigger_id: data.trigger.id,
    };
    postdata.data = JSON.stringify(postdata.data);
    if (data.action.id) {
        $.post('admin-ajax.php?action=update_app_trigger_action', postdata).done((response) => {
            let json = JSON.parse(response);
            if (json.body && json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                continueSubmit(form);
            }
        });

    } else {
        $.post('admin-ajax.php?action=create_app_trigger_action', postdata).done((response) => {
            let json = JSON.parse(response);
            if (json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                let action_id = json.body.action_id;
                $(form).find('input[name=breadbutter_newsletter_action_id]').val(action_id);
                continueSubmit(form);
            }
        });
    }
};

const handleAppTrigger = (form, data) => {
    let postdata = {
        ...data.trigger,
        event_definition_id: data.event.id
    };
    postdata.data = JSON.stringify(postdata.data);
    $.post('admin-ajax.php?action=create_app_trigger', postdata).done((response)=> {
        let json = JSON.parse(response);
        if (json.body.error) {
            errorSubmit(form, json.body.error.message);
            return;
        } else {
            let trigger_id = json.body.trigger_id;
            $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_trigger_id]').val(trigger_id);
            data.trigger.id = trigger_id;
            handleAppTriggerAction(form, data);
        }
    });
};

const testAppIntegration = (id) => {
    return new Promise((resolve, reject)=> {
        $.post('admin-ajax.php?action=test_app_integration', {
            id
        }).done((response) => {
            // console.log(response);
            let json = JSON.parse(response);
            if (json && json.body) {
                if (json.body.error) {
                    reject(json.body.error.message);
                } else {
                    resolve();
                }
            } else {
                reject('An unknown error occurred.')
            }
        });
    });
};

const handleAppInegrations = (form, data) => {
    let postdata = {
        ...data.integration
    };
    postdata.data = JSON.stringify(postdata.data);
    if (data.integration.id) {
        $.post('admin-ajax.php?action=update_app_integration', postdata).done((response) => {
            let json = JSON.parse(response);
            if (json.body && json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                handleAppTriggerAction(form, data);
            }
        });
    } else {
        $.post('admin-ajax.php?action=create_app_integration', postdata).done((response) => {
            let json = JSON.parse(response);
            if (json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                let integration_id = json.body.integration_id;

                const afterIntegration = ()=> {
                    data.integration.id = integration_id;
                    $(form).find('input[name=breadbutter_newsletter_integration_id]').val(integration_id);
                    refreshEvent();
                    handleAppTrigger(form, data);
                };

                if (postdata.type == 'zapier') {
                    testAppIntegration(integration_id).then((response)=> {
                        afterIntegration();
                    }).catch((message)=> {
                        message = "Test App Integration: " + message;
                        errorSubmit(form, message);
                    });
                } else {
                    afterIntegration();
                }
            }
        });
    }
};

const handleEventDefinition = (form)=> {
    // console.log(form);
    let data = getAppIntegrationData(form);
    if (!data) {
        // continueSubmit(form);
        errorSubmit(form, 'Event Name is required.');
        return;
    } else if (data == -1) {
        errorSubmit(form, 'Please complete all fields above before saving.');
        return;
    }
    clean = false;
    // console.log(data);

    const updateEvent = () => {
        $.post('admin-ajax.php?action=update_app_event_definition', data.event).done((response) => {
            let json = JSON.parse(response);
            // console.log(json);
            if (json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                if (!data.integration) {
                    continueSubmit(form);
                } else {
                    handleAppInegrations(form, data);
                }
            }
        });
    }

    const createEvent = ()=> {
        $.post('admin-ajax.php?action=create_app_event_definition', data.event).done((response) => {
            let json = JSON.parse(response);
            if (json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                let event_id = json.body.event_definition_id;
                $(form).find('input[name=breadbutter_newsletter_event_id]').val(event_id);
                if (!data.integration) {
                    continueSubmit(form);
                } else {
                    data.event.id = event_id;
                    handleAppInegrations(form, data);
                }
            }
        });
    }

    if (!data.event.id) {
        $.post('admin-ajax.php?action=get_app_event_definitions').done((response) => {
            let json = JSON.parse(response);
            let results = json.body.results;
            // console.log(results);
            let found = false;
            for (let i = 0; !found && results && i < results.length; i++) {
                if (results[i].code == data.event.code) {
                    data.event.id = results[i].id;
                    found = true;
                    $(form).find('input[name=breadbutter_newsletter_event_id]').val(data.event.id);
                }
            }
            if (data.event.id) {
                updateEvent();
            } else {
                createEvent();
            }
        });
    } else {
        updateEvent();
    }
};
const getAppEventDefinitions = (event_name) => {
    $.post('admin-ajax.php?action=get_app_event_definitions').done((response) => {
        let json = JSON.parse(response);
        console.log(json.body);
    });
}

const startSubmit = (form) => {
    let $submitter = $(form).find('[name="submit"]');
    if (event.originalEvent && event.originalEvent.submitter) {
        $submitter = $(event.originalEvent.submitter);
    }
    $submitter.attr('disabled', 'disabled');
    $submitter.closest('p.submit').addClass('bb-loader');
};

const errorSubmit = (form, message) => {
    dispatchNotification(message, 'error');
    $(form).find('[name="submit"]').removeAttr('disabled');
    $(form).find('.bb-loader').removeClass('bb-loader');
}

const continueSubmit = (form) => {
    const data = $(form).serialize();

    let array_data = $(this).serializeArray();
    let hash_data = {};
    for (let i = 0; i < array_data.length; i++) {
        hash_data[array_data[i].name] = array_data[i].value;
    }

    // console.log(form);
    $.post('options.php', data).done((resp) => {
        if (!clean) {
            $(form).find('select[name=breadbutter_newsletter_type]').attr('disabled', true);
        }
        $(form).find('[name="submit"]').removeAttr('disabled');
        $(form).find('.bb-loader').removeClass('bb-loader');
        dispatchNotification('Settings were saved successfully');
        location.reload();
        ajaxDataCheck(hash_data);
    }).fail((error) => {
        dispatchNotification('Something went wrong. Please try again later.', 'error');
    });
};

const setup = function() {
    setupConfigEvent();
    $('.breadbutter-wrap #newsletter form').submit(function (event) {
        event.preventDefault();
        let form = this;
        startSubmit(form);
        handleEventDefinition(form);
    });
    $('.breadbutter-wrap form#dashboard-newsletter').submit(function (event) {
        event.preventDefault();
        let form = this;
        startSubmit(form);
        let type = $(form).find('select[name=breadbutter_newsletter_type]').val();
        if (!type) {
            errorSubmit(form, 'Type is required.');
            return;
        }
        handleEventDefinition(form);
    });
    $('.breadbutter-wrap form#dashboard-newsletter select[name=breadbutter_newsletter_type]').removeAttr('disabled');
}


export default {
    assign,
    setup
};