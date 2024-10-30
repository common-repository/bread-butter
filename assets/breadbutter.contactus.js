const parseContactUsJSON = function(data, params) {
    let json = JSON.parse(atob(params));
    for(let key in json) {
        try {
            switch (key) {
                case 'locale':
                    data[key] = JSON.parse(json[key]);
                    break;
                case 'show_phone':
                case 'show_company_name':
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

const launchContactUs = function() {
    let $ = jQuery;
    if (typeof bb_contactus_widget != 'undefined' && bb_contactus_widget) {
        let data = {
            onLogout: bbOnLogout
        };
        if (typeof bb_contactus_attributes != 'undefined') {
            data = parseContactUsJSON(data, bb_contactus_attributes);
        }
        BreadButter.ui.contactUs(data);
    }


    let widget = $('.bb-contactus-widget');
    if (widget.length) {
        for(let i = 0; i < widget.length; i++) {
            let w = widget[i];
            let element = $(w);
            let id = element.attr('id');
            let rel = element.attr('rel');

            element.removeClass('bb-hidden');

            let data = {
                onLogout: bbOnLogout
            };
            if (typeof rel != 'undefined' && typeof rel == 'string') {
                data = parseContactUsJSON(data, rel);
            }
            BreadButter.ui.contactUsForm(id, data);
        }
    }
    let widgets2 = $('.bb-contactus-widget-button');

    if (widgets2.length) {
        console.log(widgets2);
        for(let i = 0; i < widgets2.length; i++) {
            let w = widgets2[i];
            let element = $(w);
            let id = element.attr('id');
            let rel = element.attr('rel');

            let data = {
                onLogout: bbOnLogout
            };
            data = parseContactUsJSON(data, rel);
            BreadButter.ui.handleContactUs(id, data);
        }
    }

};

launchContactUs();