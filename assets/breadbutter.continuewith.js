const loadBreadButterContinueWith = function() {
    let data = {};
    if (typeof bb_continuewith_params != 'undefined') {
        for(let key in bb_continuewith_params) {
            if (typeof bb_continuewith_params[key] == 'string') {
                try {
                    let json = JSON.parse(bb_continuewith_params[key]);
                    data[key] = json;
                } catch(e) {
                    data[key] = bb_continuewith_params[key];
                }
            } else {
                data[key] = bb_continuewith_params[key];
            }
        }
    }
    if (typeof bb_config_params !== "undefined"  && bb_config_params.use_ui) {
        BreadButter.ui.continueWith(data);
    } else {
        BreadButter.widgets.continueWith(data);
    }
};

if (typeof BB_IS_USER_LOGGED_IN == 'undefined' || !BB_IS_USER_LOGGED_IN) {
    loadBreadButterContinueWith();
}