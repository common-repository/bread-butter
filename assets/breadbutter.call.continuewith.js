let callBreadButterContinueWith = function(binary_data) {
    let data = {};
    if (typeof bb_continuewith_params != 'undefined') {
        for(let key in bb_continuewith_params) {
            if (typeof bb_continuewith_params[key] == 'string') {
                try {
                    let json = JSON.parse(bb_continuewith_params[key]);
                    for(let key in json) {
                        switch(key) {
                            case 'locale':
                                data[key] = JSON.parse(json[key]);
                                break;
                            default:
                                data[key] = json[key];
                                break;
                        }
                    }
                    data[key] = json;
                } catch(e) {
                    data[key] = bb_continuewith_params[key];
                }
            } else {
                data[key] = bb_continuewith_params[key];
            }
        }
    }
    try {
        if (typeof binary_data == 'string') {
            let json = JSON.parse(atob(binary_data));
            for(let key in json) {
                try {
                    switch (key) {
                        case 'locale':
                            data[key] = JSON.parse(json[key]);
                            break;
                        case 'expand_email_address':
                        case 'show_login_focus':
                        case 'force_reauthentication':
                            data[key] = json[key] ? true : false;
                            break;
                        default:
                            data[key] = json[key];
                            break;
                    }
                } catch(err) {
                    console.warn(err.message);
                }
            }
        }
    } catch(e) {

    }

    data['from_continue_with'] = true;

    if (typeof bb_config_params !== "undefined"  && bb_config_params.use_ui) {
        BreadButter.ui.continueWith(data);
    } else {
        BreadButter.widgets.continueWith(data);
    }
};