const bbOnLogout = function() {
    BreadButter.api.resetDeviceVerification(function () {});
    window.location.assign(BB_LOGOUT_URL.replaceAll('&amp;', '&'));
}

const loadBreadButterConfiguration = function () {
    let has_continue_with = bb_config_params.has_continue_with && bb_config_params.has_continue_with != '0' ? true : false;
    let is_home_url = bb_config_params.is_home_url;
    let continue_with_home = bb_config_params.continue_with_home_page;

    let continue_with_success_seconds = bb_config_params.continue_with_success_seconds;
    let continue_with_success_header = bb_config_params.continue_with_success_header;
    let continue_with_success_text = bb_config_params.continue_with_success_text;

    let continue_with_config = {
        continue_with_success_seconds,
        continue_with_success_header,
        continue_with_success_text
    };

    let data = {
        app_id: bb_config_params.app_id,
        api_path: bb_config_params.api_path,
        page_view_tracking: bb_config_params.page_view_tracking ? true : false,
        continue_with_position: bb_config_params.continue_with_position,
        show_login_focus: bb_config_params.show_login_focus,
        allow_sub_domain: bb_config_params.allow_sub_domain,
        // expand_email_address: bb_config_params.expand_email_address,
        ga_measurement_id: bb_config_params.ga_measurement_id,
        wordpress_admin_ajax: bb_config_params.wordpress_admin_ajax,
    };

    let user_profile_tool_position = bb_config_params.user_profile_tools.position;
    let user_profile_tool_enabled = bb_config_params.user_profile_tools.enabled && bb_config_params.user_profile_tools.enabled == 'on' ? true : false;
    if (bb_config_params["app_name"]) {
        data["app_name"] = bb_config_params["app_name"];
    }
    if (bb_config_params["destination_url"]) {
        data["destination_url"] = bb_config_params["destination_url"];
    }
    if (bb_config_params["callback_url"]) {
        data["callback_url"] = bb_config_params["callback_url"];
    }
    if (bb_config_params["button_theme"]) {
        data["button_theme"] = bb_config_params["button_theme"];
    }
    if (bb_config_params["locale"]) {
        data["locale"] = bb_config_params["locale"];
    }
    if (typeof BB_POST_NEWSLETTER !== "undefined" && typeof BB_POST_NEWSLETTER_DATA !== "undefined") {
        data["newsletter"] = BB_POST_NEWSLETTER_DATA;
    }
    if (typeof BB_OVERRIDE_REG_DESTINATION_URL !== "undefined" && BB_OVERRIDE_REG_DESTINATION_URL) {
        data["registration_destination_url"] = document.location.href;
    }
    if (typeof BB_PREVIEW_OVERRIDE_REG_DESTINATION_URL != 'undefined' && BB_PREVIEW_OVERRIDE_REG_DESTINATION_URL) {
        data["registration_destination_url"] = document.location.href;
    }
    if (typeof BB_CONTACTUS_OVERRIDE_REG_DESTINATION_URL != 'undefined' && BB_CONTACTUS_OVERRIDE_REG_DESTINATION_URL) {
        data["registration_destination_url"] = document.location.href;
    }

    if (bb_config_params["custom_data"]) {
        try {
            data["custom_data"] = JSON.parse(bb_config_params["custom_data"]);
        } catch(e) {
            console.warn('CUSTOM_DATA format issue.')
        }
    }
    let has_content_gating = false;
    let content_gating_config = {};


    if (typeof BB_POST_HAS_CONTENT_GATED !== "undefined" && BB_POST_HAS_CONTENT_GATED) {
        if (typeof BB_CONTENT_GATING_POST_LOCALE !== "undefined") {
            content_gating_config.locale = BB_CONTENT_GATING_POST_LOCALE;
        }
        if (typeof BB_GATING_OVERRIDE_REG_DESTINATION_URL !== "undefined" &&
            BB_GATING_OVERRIDE_REG_DESTINATION_URL
        ) {
            content_gating_config.registration_destination_url = document.location.href;
        }


        if (typeof BB_GATING_IMAGE_TYPE !== "undefined" &&
            BB_GATING_IMAGE_TYPE
        ) {
            content_gating_config.image_type = BB_GATING_IMAGE_TYPE;
        }

        if (typeof BB_GATING_IMAGE_URL !== "undefined" &&
            BB_GATING_IMAGE_URL
        ) {
            content_gating_config.image_source = BB_GATING_IMAGE_URL;
        }

        content_gating_config.scroll_limit = BB_CONTENT_GATING_SCROLL_LIMIT;
        content_gating_config.time_limit = BB_CONTENT_GATING_TIME_LIMIT;
        has_content_gating = true;
    }

    if (typeof BB_POST_HAS_CONTENT_PREVIEW !== "undefined" && BB_POST_HAS_CONTENT_PREVIEW && typeof BB_CONTENT_PREVIEW_POST_LOCALE !== "undefined") {
        data['content_preview'] = {
            restricted_links: null,
            scroll_limit: BB_CONTENT_PREVIEW_SCROLL_LIMIT,
            time_limit: BB_CONTENT_PREVIEW_TIME_LIMIT,
            locale: BB_CONTENT_PREVIEW_POST_LOCALE,
        };
        if (typeof BB_CONTENT_PREVIEW_CLICLKABLE_CONTENT !== 'undefined') {
            data['content_preview']['clickable_content'] = BB_CONTENT_PREVIEW_CLICLKABLE_CONTENT;
        }
        if (typeof BB_CONTENT_PREVIEW_FIXED_HEIGHT !== 'undefined') {
            data['content_preview']['fixed_height'] = BB_CONTENT_PREVIEW_FIXED_HEIGHT;
        }
    }
    if (bb_config_params["continue_with_all_pages"]) {
        has_continue_with = true;
    }

    let continue_with_delay_seconds = bb_config_params['continue_with_delay_seconds'];

    let enable_page_settings = false;
    let page_has_continue_with = false;
    let page_settings = bb_config_params.page_settings || {};
    const BB_KEY = "breadbutter_post_";
    let page_config = {};
    const post_locale = {};
    let page_user_profile_tool_enabled = false;
    for (let key in page_settings) {
        if (key.indexOf(BB_KEY) == 0) {
            let value = page_settings[key][0];
            let bb_key = key.replace(BB_KEY, "");
            switch (bb_key) {
                case "continue_with":
                    page_has_continue_with = value ? true : false;
                    break;
                case "enabled":
                    enable_page_settings = value ? true : false;
                    break;
                case "app_name":
                case "callback_url":
                case "destination_url":
                case "client_data":
                case "button_theme":
                    if (value) {
                        page_config[bb_key] = value;
                    }
                    break;
                // case "expand_email_address":
                case "show_login_focus":
                case "force_reauthentication":
                    page_config[bb_key] = value ? true : false;
                    break;
                case "user_profile_tool":
                    page_user_profile_tool_enabled = value ? true : false;
                    break;
                case "as_destination_url":
                case "is_restricred":
                case "is_gated":
                    if (value) {
                        page_config["destination_url"] = document.location.href;
                    }
                    break;
                case "blur_text_1":
                    if (value) {
                        post_locale.TEXT_1 = value;
                    }
                    break;
                case "blur_text_2":
                    if (value) {
                        post_locale.TEXT_2 = value;
                    }
                    break;
                case "blur_text_3":
                    if (value) {
                        post_locale.TEXT_3 = value;
                    }
                    break;
                case "blur_text_3_2":
                    if (value) {
                        post_locale.TEXT_3_2 = value;
                    }
                    break;
                case "blur_more_text":
                    if (value) {
                        post_locale.MORE = ' ' + value;
                    }
                    break;
                case "header_1":
                    if (value) {
                        post_locale.HEADER_1 = value;
                    }
                    break;
                case "header_2":
                    if (value) {
                        post_locale.HEADER_2 = value;
                    }
                    break;
                case "header_back_1":
                    if (value) {
                        post_locale.HEADER_BACK_1 = value;
                    }
                    break;
                case "header_back_2":
                    if (value) {
                        post_locale.HEADER_BACK_2 = value;
                    }
                    break;
                case 'continue_with_popup_delay_seconds':
                    if (value) {
                        continue_with_delay_seconds = value;
                    }
                    break;
                case 'continue_with_success_seconds':
                    if (value) {
                        continue_with_config.continue_with_success_seconds = value;
                    }
                    break;
                case 'continue_with_success_header':
                    if (value) {
                        continue_with_config.continue_with_success_header = value;
                    }
                    break;
                case 'continue_with_success_text':
                    if (value) {
                        continue_with_config.continue_with_success_text = value;
                    }
                    break;
            }
        }
    }


    data = {
        ...data,
        ...continue_with_config
    };

    BreadButter.configure(data).then((up, dv)=> {
        if (!up) {
            if (jQuery && jQuery('[rel=breadbutter_connect_validation]')) {
                jQuery('[rel=breadbutter_connect_validation]').removeClass('hidden');
            }
        }

        if (typeof BB_POST_CONTACTUS !== "undefined" && BB_POST_CONTACTUS && typeof BB_POST_CONTACTUS_DATA !== "undefined") {
            let contactus_options = {
                ...BB_POST_CONTACTUS_DATA
            };
            if (typeof BB_CONTACTUS_OVERRIDE_REG_DESTINATION_URL !== "undefined" &&
                BB_CONTACTUS_OVERRIDE_REG_DESTINATION_URL
            ) {
                contactus_options.registration_destination_url = document.location.href;
            }
            BreadButter.ui.contactUs(contactus_options);
        }
    });

    let continue_with_position = false;

    if (
        page_settings["breadbutter_continue_with_position_vertical"] == "top" ||
        page_settings["breadbutter_continue_with_position_vertical"] == "bottom"
    ) {
        if (!continue_with_position) {
            continue_with_position = {};
        }
        let pos = page_settings["breadbutter_continue_with_position_vertical"];
        let pixel =
            page_settings["breadbutter_continue_with_position_vertical_px"];
        continue_with_position[pos] = pixel;
    }

    if (
        page_settings["breadbutter_continue_with_position_horizontal"] ==
            "left" ||
        page_settings["breadbutter_continue_with_position_horizontal"] ==
            "right"
    ) {
        if (!continue_with_position) {
            continue_with_position = {};
        }
        let pos =
            page_settings["breadbutter_continue_with_position_horizontal"];
        let pixel =
            page_settings["breadbutter_continue_with_position_horizontal_px"];
        continue_with_position[pos] = pixel;
    }

    if (continue_with_position) {
        page_config["continue_with_position"] = continue_with_position;
    }

    if (!enable_page_settings) {
        page_config = {};
        if (bb_config_params["continue_with_all_pages"]) {
            has_continue_with = true;
        }
    } else {
        user_profile_tool_enabled = page_user_profile_tool_enabled;
        has_continue_with = page_has_continue_with;
    }

    // if (is_home_url) {
    //     if (continue_with_home) {
    //         has_continue_with = true;
    //     } else {
    //         has_continue_with = false;
    //     }
    // }

    if (typeof BB_POST_IS_RESTRICTED !== "undefined") {
        has_continue_with = true;
        page_config["destination_url"] = document.location.href;
        page_config["show_login_focus"] = true;
        document.addEventListener("DOMContentLoaded", function () {
            document.body.classList.add('bb-content-r');
        });
        if (typeof BB_RESTRICTED_POST_LOCALE !== "undefined") {
            page_config['locale'] = BB_RESTRICTED_POST_LOCALE;
        }
    }

    if (Object.keys(post_locale).length > 0) {
        page_config['locale'] = { POPUP: post_locale };
    }

    if (typeof bb_config_params.hide_verified !== "undefined") {
        page_config['hide_verified'] = bb_config_params.hide_verified;
    }

    if (continue_with_delay_seconds) {
        page_config['delay_seconds'] = Number(continue_with_delay_seconds);
    }
    if (has_continue_with) {
        if (continue_with_config.continue_with_success_header) {
            page_config['success_header'] = continue_with_config.continue_with_success_header;
        }
        if (continue_with_config.continue_with_success_text) {
            page_config['success_text'] = continue_with_config.continue_with_success_text;
        }
        if (continue_with_config.continue_with_success_seconds) {
            page_config['success_seconds'] = continue_with_config.continue_with_success_seconds;
        }
        document.addEventListener("DOMContentLoaded", function (event) {
            if ((typeof BB_IS_USER_LOGGED_IN == 'undefined' || !BB_IS_USER_LOGGED_IN) && !bb_config_params.use_ui) {
                BreadButter.widgets.continueWith(page_config);
            }
            if (bb_config_params.use_ui) {
                page_config.onLogout = bbOnLogout;
                // page_config.show_logged_in_profile = false;
                if (bb_config_params.show_logged_in_profile && bb_config_params.show_logged_in_profile == 'on') {
                    page_config['show_logged_in_profile'] = true;
                }
                BreadButter.ui.continueWith(page_config);
            }
        });
    }
    if (has_content_gating) {
        document.addEventListener("DOMContentLoaded", function (event) {
            BreadButter.ui.contentGating(content_gating_config);
        });
    }
    if (user_profile_tool_enabled) {
        document.addEventListener("DOMContentLoaded", function (event) {
            let data = {
                onLogout: bbOnLogout,
                continue_with_position: user_profile_tool_position
            }
            BreadButter.ui.profileWidget(data);
        });
    }

    const failedCookie = document.cookie.split(";").find(function (c) {
        return c.includes("bb_auth_failed");
    });
    if (failedCookie) {
        document.cookie =
            "bb_auth_failed=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        BreadButter.api.resetDeviceVerification(function () {});
    }

    if (bb_config_params['custom_events']) {
        document.addEventListener("DOMContentLoaded", function () {
            const custom_events = JSON.parse(bb_config_params['custom_events']);
            const getElements = function (element) {
                // Check by selector.
                const bySelector = document.querySelectorAll(element);
                if (bySelector.length > 0) {
                    return bySelector;
                }
                // Check by name attribute.
                const byName = document.querySelectorAll(`[name="${element}"]`);
                if (byName.length > 0) {
                    return byName;
                }
                
                // Check by id attribute.
                const byId = /^\w/.test(element) ? document.querySelectorAll(`#${element}`) : [];
                if (byId.length > 0) {
                    return byId;
                }
            };
            custom_events.forEach(function(item) {
                let elements = [];
                if (item.element) {
                    elements = getElements(item.element);
                }
                if (elements && elements.length > 0) {
                    elements.forEach(function(element) {
                        element.addEventListener('click', function() {
                            BreadButter.events.custom(item.code, function() {console.log('Done');});
                        });
                    });
                }
            });
        });
    }

    if (bb_config_params['secure_forms']) {
        document.addEventListener("DOMContentLoaded", function () {
            const secure_forms = JSON.parse(bb_config_params['secure_forms']);
            secure_forms.forEach(function(item) {
                const form = document.getElementById(item.formId);
                const submit = document.getElementById(item.submitId);
                if (form && submit) {
                    const controlConfig = {
                        form: item.formId,
                        submit: item.submitId,
                    };
                    if (item.eventCode) {
                        controlConfig.event = item.eventCode;
                    }
                    if (item.emailId) {
                        controlConfig.email = item.emailId;
                    }
                    if (item.firstNameId) {
                        controlConfig.first_name = item.firstNameId;
                    }
                    if (item.lastNameId) {
                        controlConfig.last_name = item.lastNameId;
                    }
                    if (item.fullNameId) {
                        controlConfig.name = item.fullNameId;
                    }
                    BreadButter.ui.applyFormControl(controlConfig);
                }
            });
        });
    }
    BreadButter.config = page_config;
};

loadBreadButterConfiguration();
