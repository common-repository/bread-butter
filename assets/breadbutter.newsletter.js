const getDataJSONForNewsletter = function() {
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
    return data;
};

const parseNewsletterJSON = function(data, params) {
    let json = JSON.parse(atob(params));
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
    return data;
}

const launchNewsletter = function() {
    let $ = jQuery;
    let widget = $('.bb-newsletter-widget');
    if (widget) {
        widget.removeClass('bb-hidden');
        let ID = "bb-newsletter-" + (Date.now() + "").slice(-5);
        widget.attr('id', ID);
        let data = getDataJSONForNewsletter();
        if (typeof bb_newsletter_attributes != 'undefined') {
            data = parseNewsletterJSON(data, bb_newsletter_attributes);
        }
        BreadButter.ui.addNewsletterWidget(ID, data);
    }

    let widget2 = $('.bb-newsletter-widget-button');
    if (widget2) {
        let rel = widget2.attr('rel');
        let data = getDataJSONForNewsletter();
        data = parseNewsletterJSON(data, rel);
        widget2.click(function(){
            BreadButter.ui.addNewsletterWidget(data);
        });
        if (data.custom_event_code) {
            let { custom_event_code } = data;
            if (localStorage) {
                let EVENT = 'breadbutter-sdk-last-success-event-code-' + btoa(custom_event_code);
                let event = localStorage.getItem(EVENT);
                if (event) {
                    BreadButter.ui.addNewsletterWidget(data);
                }
            }
        }
    }
};

launchNewsletter();