import NewsletterEvent from './dashboard/newsletter.js';
import ContactUsDashboard from './dashboard/contactus.js';
import GateContentDashboard from './dashboard/gatecontent.js';
import ContinueWithDashboard from './dashboard/continuewith.js';

jQuery(document).ready(function ($) {
    console.log('jquery ready');
    $(".copy").on("click", function (e) {
        var str = $(e.target).parent().children(".target-copy").text();
        const el = document.createElement("textarea");
        el.value = str;
        document.body.appendChild(el);
        el.select();
        document.execCommand("copy");
        document.body.removeChild(el);
    });

    const switchTab = function (tab) {
        $(".breadbutter-admin-tab").removeClass("active");
        $("#breadbutter-tabs .nav-tab-active").removeClass("nav-tab-active");
        $("#" + tab).addClass("active");
        $("#" + tab + "-tab").addClass("nav-tab-active");

        if (page_land_require_refresh.indexOf(tab) > -1) {
            location.reload();
        }
    };

    let page_land_require_refresh = [];
    let dashboard_changes = false;
    const hashChange = function () {
        let hash = window.location.hash;
        if (hash.length) {
            hash = hash.slice(1);
            switchTab(hash);
        } else {
            switchTab("configuration");
        }
    };
    const updateRows = () => {
        const row = document.querySelector('.bb-agency-code');
        if (row) {
            const conversion_block = document.querySelector('.js-breadbutter-tab-conversion');
            const pageview_block = document.querySelector('.js-breadbutter-tab-page-view-tracking');
            conversion_block.classList.add('agency-row');
            pageview_block.classList.add('agency-row');
        }
    };

    window.addEventListener("hashchange", function () {
        hashChange();
    });
    hashChange();
    updateRows();

    $('.js-bb-select2').select2({width: '400px',});

    if (typeof BreadButter !== "undefined" && $('[name="breadbutter_custom_events_config"]').length) {
        const ceConfig = JSON.parse($('[name="breadbutter_custom_events_config"]').val() || '[]');
        $('#bb-save-custom-event-btn').click(function() {
            const element = $('[name="breadbutter_custom_event_element"]').val();
            const code = $('[name="breadbutter_custom_event_code"]').val();
            if (element && code) {
                ceConfig.push({element, code});
                $('[name="breadbutter_custom_events_config"]').val(JSON.stringify(ceConfig));
                const tr = createTRelement([element, code], ceConfig, ceConfig.length - 1, '[name="breadbutter_custom_events_config"]', 'bb-custom-events-table');
                if($('#bb-custom-events-table tbody').length) {
                    $('#bb-custom-events-table tbody').prepend(tr);
                } else {
                    const $table = createConfigTable(['Element Name/ID/Selector', 'Event code', 'Actions'], 'bb-custom-events-table');
                    ceConfig.forEach((item, index) => {
                        const tr = createTRelement([item.element, item.code], ceConfig, index, '[name="breadbutter_custom_events_config"]', 'bb-custom-events-table');
                        $table.find('tbody').prepend(tr);
                    });
                    $('#bb-save-custom-event-btn').parent().before($table);
                }
            }
            $('[name="breadbutter_custom_event_element"]').val('');
            $('[name="breadbutter_custom_event_code"]').val('');
        });
        if (ceConfig.length > 0) {
            const $table = createConfigTable(['Element Name/ID/Selector', 'Event code', 'Actions'], 'bb-custom-events-table');
            ceConfig.forEach((item, index) => {
                const tr = createTRelement([item.element, item.code], ceConfig, index, '[name="breadbutter_custom_events_config"]', 'bb-custom-events-table');
                $table.find('tbody').prepend(tr);
            });
            $('#bb-save-custom-event-btn').parent().before($table);
        }
    }

    //User Custom Data Form
    if (typeof BreadButter !== "undefined" && $('[name="breadbutter_user_custom_data_config"]').length) {
        const ucdConfig = JSON.parse($('[name="breadbutter_user_custom_data_config"]').val() || '[]');
        const processTitle = ['Custom Key', 'Display Name', 'Type', 'Mandatory', 'Default', 'Actions'];
        const processDataArray = function(item) {
            let data = [item.custom_key, item.display_name, item.type, item.mandatory, item.default_value];
            return processArray(data);
        };
        const processArray = function(data) {
            switch(data[2]) {
                case 'textbox':
                    data[2] = 'Textbox';
                    break;
                case 'checkbox':
                    data[2] = 'Checkbox';
                    break;
            }
            data[3] = data[3] ? "✔️" : "";
            data[4] = data[4] ? "✔️" : "";
            return data;
        };
        $('[name="breadbutter_user_custom_data_type"]').change(function(e){
            let val = $(this).val();
            switch(val) {
                case 'textbox':
                    $('[name="breadbutter_user_custom_data_default_value"]').prop('checked', false);
                    $('#breadbutter-user-custom-data-default-holder').hide();
                    break;
                case 'checkbox':
                    $('[name="breadbutter_user_custom_data_default_value"]').prop('checked', false);
                    $('#breadbutter-user-custom-data-default-holder').show();
                    break;
                default:
                    $('[name="breadbutter_user_custom_data_default_value"]').prop('checked', false);
                    $('#breadbutter-user-custom-data-default-holder').hide();
                    break;
            }
        });
        $('#bb-save-user-custom-data-btn').click(function(event) {
            $('[name="breadbutter_user_custom_data_custom_key"]').removeClass('bb-error');
            $('[name="breadbutter_user_custom_data_display_name"]').removeClass('bb-error');
            $('[name="breadbutter_user_custom_data_type"]').removeClass('bb-error');
            const custom_key = $('[name="breadbutter_user_custom_data_custom_key"]').val();
            const display_name = $('[name="breadbutter_user_custom_data_display_name"]').val();
            const type = $('[name="breadbutter_user_custom_data_type"]').val();
            const mandatory = $('[name="breadbutter_user_custom_data_mandatory"]').prop('checked');
            const default_value = $('[name="breadbutter_user_custom_data_default_value"]').prop('checked');

            let validation = true;
            let error_code = 0;
            if (!custom_key || custom_key.length == 0 || custom_key.indexOf(' ') != -1) {
                $('[name="breadbutter_user_custom_data_custom_key"]').addClass('bb-error');
                validation = false;
                error_code += 1;
            }

            if (!type || type.length == 0) {
                $('[name="breadbutter_user_custom_data_type"]').addClass('bb-error');
                validation = false;
                error_code += 2;
            }


            if (!display_name || display_name.length == 0) {
                $('[name="breadbutter_user_custom_data_display_name"]').addClass('bb-error');
                validation = false;
                error_code += 4;
            }

            if (!validation) {
                if (error_code == 7) {
                    $('[name="breadbutter_user_custom_data_custom_key"]').removeClass('bb-error');
                    $('[name="breadbutter_user_custom_data_display_name"]').removeClass('bb-error');
                    $('[name="breadbutter_user_custom_data_type"]').removeClass('bb-error');
                } else {
                    event.preventDefault();
                    return;
                }
            }

            if (custom_key && display_name && type && validation) {
                ucdConfig.push({custom_key, display_name, type, mandatory, default_value});
                $('[name="breadbutter_user_custom_data_config"]').val(JSON.stringify(ucdConfig));
                let new_data = [custom_key, display_name, type, mandatory, default_value];
                const tr = createTRelement(processArray(new_data), ucdConfig, ucdConfig.length - 1, '[name="breadbutter_user_custom_data_config"]', 'bb-user-custom-data-table', ["arrange"]);
                if($('#bb-user-custom-data-table tbody').length) {
                    $('#bb-user-custom-data-table tbody').append(tr);
                } else {
                    const $table = createConfigTable(processTitle, 'bb-user-custom-data-table');
                    ucdConfig.forEach((item, index) => {
                        const tr = createTRelement(processDataArray(item), ucdConfig, index, '[name="breadbutter_user_custom_data_config"]', 'bb-user-custom-data-table', ["arrange"]);
                        $table.find('tbody').append(tr);
                    });
                    $('#bb-save-user-custom-data-btn').parent().before($table);
                }
            }

            $('[name="breadbutter_user_custom_data_custom_key"]').val('');
            $('[name="breadbutter_user_custom_data_display_name"]').val('');
            $('[name="breadbutter_user_custom_data_type"]').val('');
            $('[name="breadbutter_user_custom_data_mandatory"]').prop('checked', false);
            $('[name="breadbutter_user_custom_data_default_value"]').prop('checked', false);
            $('#breadbutter-user-custom-data-default-holder').hide();
        });
        if (ucdConfig.length > 0) {
            const $table = createConfigTable(processTitle, 'bb-user-custom-data-table');
            ucdConfig.forEach((item, index) => {
                const tr = createTRelement(processDataArray(item), ucdConfig, index, '[name="breadbutter_user_custom_data_config"]', 'bb-user-custom-data-table', ["arrange"]);
                $table.find('tbody').append(tr);
            });
            $('#bb-save-user-custom-data-btn').parent().before($table);
        }
    }

    // Secure Forms table.
    if (typeof BreadButter !== "undefined" && $('[name="breadbutter_secure_forms_config"]').length) {
        const sfConfig = JSON.parse($('[name="breadbutter_secure_forms_config"]').val() || '[]');
        $('#bb-save-secure-forms-btn').click(function() {
            const formId = $('[name="breadbutter_secure_forms_form_id"]').val();
            const submitId = $('[name="breadbutter_secure_forms_submit_id"]').val();
            const eventCode = $('[name="breadbutter_secure_forms_event_code"]').val();
            const emailId = $('[name="breadbutter_secure_forms_email_id"]').val();
            const firstNameId = $('[name="breadbutter_secure_forms_first_name_id"]').val();
            const lastNameId = $('[name="breadbutter_secure_forms_last_name_id"]').val();
            const fullNameId = $('[name="breadbutter_secure_forms_full_name_id"]').val();
            if (formId && submitId) {
                sfConfig.push({formId, submitId, eventCode: eventCode.trim(), emailId, firstNameId, lastNameId, fullNameId});
                $('[name="breadbutter_secure_forms_config"]').val(JSON.stringify(sfConfig));
                if($('#bb-secure-forms-table tbody').length) {
                    const tr = createTRelement([
                        formId,
                        submitId,
                        eventCode,
                        emailId,
                        firstNameId,
                        lastNameId,
                        fullNameId
                    ], sfConfig, sfConfig.length - 1, '[name="breadbutter_secure_forms_config"]', 'bb-secure-forms-table');
                    $('#bb-secure-forms-table tbody').prepend(tr);
                } else {
                    const $table = createConfigTable(['Form ID', 'Submit Button ID', 'Custom Event Code', 'Email ID', 'First name ID', 'Last name ID', 'Full name ID', 'Actions'], 'bb-secure-forms-table');
                    sfConfig.forEach((item, index) => {
                        const tr = createTRelement([
                            item.formId,
                            item.submitId,
                            item.eventCode,
                            item.emailId,
                            item.firstNameId,
                            item.lastNameId,
                            item.fullNameId
                        ], sfConfig, index, '[name="breadbutter_secure_forms_config"]', 'bb-secure-forms-table');
                        $table.find('tbody').prepend(tr);
                    });
                    $('#bb-save-secure-forms-btn').parent().before($table);
                }
            }
            $('[name="breadbutter_secure_forms_form_id"]').val('');
            $('[name="breadbutter_secure_forms_submit_id"]').val('');
            $('[name="breadbutter_secure_forms_event_code"]').val('');
            $('[name="breadbutter_secure_forms_email_id"]').val('');
            $('[name="breadbutter_secure_forms_first_name_id"]').val('');
            $('[name="breadbutter_secure_forms_last_name_id"]').val('');
            $('[name="breadbutter_secure_forms_full_name_id"]').val('');
        });
        if (sfConfig.length > 0) {
            const $table = createConfigTable(['Form ID', 'Submit Button ID', 'Custom Event Code', 'Email ID', 'First name ID', 'Last name ID', 'Full name ID', 'Actions'], 'bb-secure-forms-table');
            sfConfig.forEach((item, index) => {
                const tr = createTRelement([
                    item.formId,
                    item.submitId,
                    item.eventCode,
                    item.emailId,
                    item.firstNameId,
                    item.lastNameId,
                    item.fullNameId
                ], sfConfig, index, '[name="breadbutter_secure_forms_config"]', 'bb-secure-forms-table');
                $table.find('tbody').prepend(tr);
            });
            $('#bb-save-secure-forms-btn').parent().before($table);
        }
    }
    $('#userprofile-tool label[for=breadbutter_enable_user_profile_tools]').text('Enable for all pages');
    $('#client [name=breadbutter_continue_with_all_pages]').change(function() {
        if ($(this).prop('checked')) {
            $('input[name=breadbutter_enable_user_profile_tools]').prop('checked', true);
        }
    });

    $('.breadbutter-wrap #client form, ' +
        '.breadbutter-wrap #advance form, ' +
        '.breadbutter-wrap #content-gating form, ' +
        '.breadbutter-wrap #content-preview form, ' +
        '.breadbutter-wrap #contactus form, ' +
        '.breadbutter-wrap #userprofile-tool form').submit(function (event) {
        event.preventDefault();
        let data = $(this).serialize();
        let $submitter = $(this).find('[name="submit"]');
        let form_id = $submitter.closest('.breadbutter-admin-tab')[0].id;
        if (event.originalEvent && event.originalEvent.submitter) {
            $submitter = $(event.originalEvent.submitter);
        }
        $submitter.attr('disabled', 'disabled');
        $submitter.closest('p.submit').addClass('bb-loader');

        let array_data = $(this).serializeArray();
        let hash_data = {};
        for(let i = 0; i < array_data.length; i++) {
            hash_data[array_data[i].name] = array_data[i].value;
        }
        // console.log(hash_data);

        $.post('options.php', data).done((resp) => {
            $(this).find('[name="submit"]').removeAttr('disabled');
            $(this).find('.bb-loader').removeClass('bb-loader');
            dispatchNotification('Settings were saved successfully');
            if ($(this).hasClass('js-analytics-conversion') || $(this).hasClass('js-analytics-conversion-settings')) {
                location.reload();
            }
            ajaxDataCheck(hash_data);
            pageUpdateRequired(form_id);
        }).fail((error) => {
            dispatchNotification('Something went wrong. Please try again later.', 'error');
        });
    });

    function pageUpdateRequired(form_id) {
        switch(form_id) {
            case 'userprofile-tool':
                page_land_require_refresh.push('client');
                break;
            case 'client':
                page_land_require_refresh.push('userprofile-tool');
                page_land_require_refresh.push('configuration');
                break;
            case 'contactus':
                page_land_require_refresh.push('configuration');
                break;
            case 'content-gating':
                page_land_require_refresh.push('configuration');
                break;
        }
    }

    function ajaxDataCheck(hash_data) {
        switch(hash_data.option_page) {
            case "breadbutter_gating_content_groups":
                //breadbutter-dashboard-gate-content bb-dashboard-enabled-1
                if (hash_data['breadbutter_gating_content_pages[]']) {
                    $("#breadbutter-dashboard-gate-content").addClass('bb-dashboard-enabled-1');
                } else {
                    $("#breadbutter-dashboard-gate-content").removeClass('bb-dashboard-enabled-1');
                }
                break;
            case "breadbutter_gating_content_preview_group":
                //breadbutter-dashboard-gate-content bb-dashboard-enabled-2
                if (hash_data['breadbutter_gating_content_preview_pages[]']) {
                    $("#breadbutter-dashboard-gate-content").addClass('bb-dashboard-enabled-2');
                } else {
                    $("#breadbutter-dashboard-gate-content").removeClass('bb-dashboard-enabled-2');
                }
                break;
            case "breadbutter_gating_content_gating_group":
                //breadbutter-dashboard-gate-content bb-dashboard-enabled-3
                if (hash_data['breadbutter_gating_content_gating_pages[]']) {
                    $("#breadbutter-dashboard-gate-content").addClass('bb-dashboard-enabled-3');
                } else {
                    $("#breadbutter-dashboard-gate-content").removeClass('bb-dashboard-enabled-3');
                }
                break;
            case "breadbutter_newsletter_groups":
                //breadbutter-dashboard-newsletter-sign-up bb-dashboard-enabled
                if (hash_data['breadbutter_newsletter_pages[]']) {
                    $("#breadbutter-dashboard-newsletter-sign-up").addClass('bb-dashboard-enabled');
                } else {
                    $("#breadbutter-dashboard-newsletter-sign-up").removeClass('bb-dashboard-enabled');
                }
                break;
        }
    }

    function dispatchNotification(content, type = '') {
        $('#bb-notification').addClass(type).html(content).show();
        setTimeout(function() {
            $('#bb-notification').hide();
            $('#bb-notification').removeClass(type)
        }, 4000)
    }

    function createTRelement(textList, config, index, inputSelector, tableId, actions) {
        const tds = textList.map(function(text) {
            return $('<td></td>').text(text);
        });
        const removeBtn = $('<button type="button" class="bb-remove-action">Remove</button>');
        const tr = $('<tr index="'+index+'"></tr>');
        removeBtn.on('click', function() {
            config.splice(index, 1);
            $(inputSelector).val(JSON.stringify(config));
            tr.remove();
            if (config.length === 0) {
                $(`#${tableId}`).remove();
            }
        });

        let action_buttons = $('<td></td>');
        action_buttons.append(removeBtn);

        if (actions && actions.length) {
            if (actions.indexOf("arrange") != -1) {
                const arrangeUpBtn = $('<button type="button" class="bb-arrange-action bb-up-action">▲</button>');
                const arrangeDownBtn = $('<button type="button" class="bb-arrange-action bb-down-action">▼</button>');
                const arrangeFunction = function() {
                    let row = $(this).closest('tr');
                    let row_index = row[0].rowIndex - 1;
                    let item = config.splice(row_index, 1)[0];
                    if ($(this).hasClass('bb-up-action')) {
                        row.prev().before(row);
                        config.splice(row_index - 1, 0, item);
                    } else {
                        row.next().after(row);
                        config.splice(row_index + 1, 0, item);
                    }
                    $(inputSelector).val(JSON.stringify(config));
                }
                arrangeUpBtn.on('click', arrangeFunction);
                arrangeDownBtn.on('click', arrangeFunction);
                action_buttons.append(arrangeUpBtn);
                action_buttons.append(arrangeDownBtn);
            }
        }

        tr.append(tds, action_buttons);
        return tr;
    }

    function createConfigTable(theadTextList, tableID) {
        const tds = theadTextList.map(function(text) {
            return `<td>${text}</td>`;
        });
        const $table = $(`<table class="wp-list-table widefat" id="${tableID}">
            <thead>
                <tr>
                    ${tds.join('')}
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>`);
        return $table;
    }

    function updateContentGating() {
        console.log('DOMContentLoaded');
        const selectCustomImageType = (type) => {
            switch (type) {
                case 'fill':
                case 'center':
                    $('input[name=breadbutter_gating_content_gating_custom_image]').removeAttr('disabled');
                    break;
                case 'none':
                case 'default':
                default:
                    $('input[name=breadbutter_gating_content_gating_custom_image]').attr('disabled', true);
                    break;
            }
        };
        const addEventToCustomType = (option) => {
            console.log(option);
            if (option) {
                option.onchange = (e) => {
                    console.log('changing!');
                    let value = e.target.value;
                    selectCustomImageType(value);
                };
                selectCustomImageType(option.value);
            }
        }
        let select_image_option = document.querySelector('select[name=breadbutter_gating_content_gating_custom_image_type]');
        if (select_image_option) {
            addEventToCustomType(select_image_option);
        }
    }

    ContinueWithDashboard.assign($, {dispatchNotification, ajaxDataCheck});
    ContinueWithDashboard.setup();
    NewsletterEvent.assign($, {dispatchNotification, ajaxDataCheck});
    NewsletterEvent.setup();
    ContactUsDashboard.assign($, {dispatchNotification, ajaxDataCheck});
    ContactUsDashboard.setup();
    GateContentDashboard.assign($, {dispatchNotification, ajaxDataCheck});
    GateContentDashboard.setup();

    $('.bb-hash-button').on('click', function() {
        document.scrollingElement.scrollTop = 0
    })
    updateContentGating();


    let agency_code = document.querySelector('.bb-dashboard-tile-content.bb-agency-code');
    if (agency_code && agency_code.style.display != 'none') {
        let holder = document.querySelector('.bb-dashboard-tile-content.bb-agency-code');
        let button = holder.querySelector('input[type=submit]');

        const callUpdateAgencyCode = () => {
            let organization_id = holder.querySelector('input[type=text]').value;

            let gateway = new Gateway($);
            gateway.updateAppOrganization({organization_id}).then((res) => {
                console.log(res);
                //TODO, handling the error.
                if (res.error && res.error.code == "api_error") {
                    dispatchNotification(res.error.message, 'error');
                } else {
                    location.reload();
                }
            });

        }

        button.addEventListener('click', function() {
            callUpdateAgencyCode();
            // webappView.updateOrganizationID(app_id, agency_code).then((res)=> {
            //     console.log(res);
            // });
        });
    }

    const setContentClass = ()=> {
        let contents = $('.content');
        for(let i = 0; i < contents.length; i++) {
            let content = contents[i];
            if (content.clientWidth < 1050) {
                content.classList.add('narrow-view')
            } else {
                content.classList.remove('narrow-view');
            }
        }
    };

    console.log('prepare content');
    const resizingContent = (content)=> {
        function outputsize() {
            if (content.clientWidth < 1050) {
                content.classList.add('narrow-view')
            } else {
                content.classList.remove('narrow-view');
            }
        }
        outputsize()

        new ResizeObserver(outputsize).observe(content)
    };

    const assignContentBox = ()=> {
        let contents = $('.content');
        for(let i = 0; i < contents.length; i++) {
            let content = contents[i];
            resizingContent(content);
        }
    }
    assignContentBox();
});
