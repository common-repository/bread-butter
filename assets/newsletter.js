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

const setupConfigEvent = function() {
    $('.breadbutter-wrap #newsletter form table tr td input[type=hidden]').parent().parent().parent().parent().hide();
    if ($('.newsletter-event')) {

        $('.newsletter-event').insertBefore($('.breadbutter-wrap #newsletter form .submit'));
        $('.newsletter-event .vc-compact-color-item').click(function(event){
            event.preventDefault();
            $('.newsletter-event .vc-compact-color-item').removeAttr('aria-selected');
            $(this).attr('aria-selected', true);
            let color = $(this).attr('aria-label');
            $('.newsletter-event .event-name').css('background-color', color);
            $('.newsletter-event .event-name').attr('color', color);
        });
        $('.newsletter-event input[name=event_name]').keyup(function(event){
            let value = $(this).val();
            $('.newsletter-event .event-name').text(value);
            $('.newsletter-event .event-name').attr('name', value);
        });
        let color = $('.newsletter-event .event-name').attr('color');
        let name = $('.newsletter-event .event-name').attr('name');
        if (name) {
            $('.newsletter-event input[name=event_name]').val(name);
        }
        if (color) {
            let selecter = '.newsletter-event .vc-compact-color-item[aria-label='+ color+']';
            $(selecter).attr('aria-selected', true);
        }

        let integration = $('.newsletter-event .newsletter-integration').attr('bbi-name');
        if (integration) {
            $('.newsletter-event .newsletter-integration').addClass('lock-integration');
            $('.newsletter-event .newsletter-integration .newsletter-integration-choice').hide();
            $('.newsletter-event .newsletter-integration .newsletter-integration-choice[name="' + integration + '"]').show();
            $('.newsletter-event .newsletter-integration .newsletter-integration-choice[name="' + integration + '"]').attr('bbi-selected', true);
        }
        $('.newsletter-event .newsletter-integration .newsletter-integration-choice').click(function(event) {
            if ($('.newsletter-event .newsletter-integration').hasClass('lock-integration')) {
                return;
            } else {
                let el = $(this);
                let name = false;
                if (el.attr('bbi-selected') == 'true') {
                    el.removeAttr('bbi-selected');
                } else {
                    $('.newsletter-event .newsletter-integration .newsletter-integration-choice').removeAttr('bbi-selected');
                    el.attr('bbi-selected', true);
                    name = el.attr('name');
                }
                if (name) {
                    $('.newsletter-event .newsletter-integration').attr('bbi-name', name);
                } else {
                    $('.newsletter-event .newsletter-integration').removeAttr('bbi-name');
                }
            }
        });

        $('.newsletter-event .clear-settings').click(function(event) {
            cleanIntegration();
            submitCleanForm();
        });
    }
}

const submitCleanForm = ()=> {
    let form = $('.breadbutter-wrap #newsletter form');
    startSubmit(form);
    continueSubmit(form);
}

const cleanIntegration = ()=> {
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_event_id]').val('');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_integration_id]').val('');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_trigger_id]').val('');
    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_action_id]').val('');

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
}

const getAppIntegrationData = () => {
    let event_id = $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_event_id]').val();
    let integration_id = $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_integration_id]').val();
    let trigger_id = $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_trigger_id]').val();
    let action_id = $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_action_id]').val();

    let event = $('.newsletter-event .event-name');
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

    let ai = $('.newsletter-event .newsletter-integration');
    if (ai && ai.attr('bbi-name')) {
        let name = ai.attr('bbi-name');
        switch(name) {
            case 'zapier':
                let zapier_endpoint = $('.newsletter-event .newsletter-integration input[name=zapier_endpoint]').val();
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
                let api_key = $('.newsletter-event .newsletter-integration input[name=api_key]').val();
                let audience_id = $('.newsletter-event .newsletter-integration input[name=audience_id]').val();
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
                $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_action_id]').val(action_id);
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
                    $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_integration_id]').val(integration_id);
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
    let data = getAppIntegrationData();
    if (!data) {
        // continueSubmit(form);
        errorSubmit(form, 'Event Name is required.');
        return;
    } else if (data == -1) {
        errorSubmit(form, 'Please complete all fields above before saving.');
        return;
    }
    if (data.event.id) {
        $.post('admin-ajax.php?action=update_app_event_definition', data.event).done((response) => {
            let json = JSON.parse(response);
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
    } else {
        $.post('admin-ajax.php?action=create_app_event_definition', data.event).done((response) => {
            let json = JSON.parse(response);
            if (json.body.error) {
                errorSubmit(form, json.body.error.message);
                return;
            } else {
                let event_id = json.body.event_definition_id;
                $('.breadbutter-wrap #newsletter input[name=breadbutter_newsletter_event_id]').val(event_id);
                if (!data.integration) {
                    continueSubmit(form);
                } else {
                    data.event.id = event_id;
                    handleAppInegrations(form, data);
                }
            }
        });
    }
};

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
    $('.breadbutter-wrap #newsletter form').submit(function (event) {
        event.preventDefault();
        let form = this;
        startSubmit(form);
        handleEventDefinition(form);
    });
}


export default {
    assign,
    setup
};