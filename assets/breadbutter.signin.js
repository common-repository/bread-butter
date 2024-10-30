const loadBreadButterSignIn = function() {
    let data = {};
    let id = false;
    if (typeof bb_signin_params != 'undefined') {
        for(let key in bb_signin_params) {
            if (key == 'id') {
                id = bb_signin_params[key];
            } else if (typeof bb_signin_params[key] == 'string') {
                try {
                    let json = JSON.parse(bb_signin_params[key]);
                    data[key] = json;
                } catch(e) {
                    data[key] = bb_signin_params[key];
                }
            } else {
                data[key] = bb_signin_params[key];
            }
        }
    }
    BreadButter.widgets.signIn(id, data);
};

if (typeof BB_IS_USER_LOGGED_IN == 'undefined' || !BB_IS_USER_LOGGED_IN) {
    loadBreadButterSignIn();
}