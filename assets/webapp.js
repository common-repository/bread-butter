
const locale = {
    "NOT_VIEWABLE": "Not intend to be stand alone page. please close this tab and resume.",
    "LOADING": "Loading ...",
    "WIZARD": {
        "TITLE": "Bread & Butter WordPress Plugin Setup",
        "CREATE_APP": "Create New App for your WordPress website"
    },
    "APPS": {
        "TITLE": "Bread & Butter WordPress Plugin Setup",
        "EXISTING_APP": "Existing apps:",
        "USE_APP": "Use this App",
        "OR": "or",
        "CREATE_APP": "Create New App for your WordPress website",
        "PRIMARY_HINT": "Set your WordPress site as your primary website if you have no other websites using this App.",
        "PRIMARY_WEBSITE": "Set as Primary website",
        "SECONDARY_HINT": "Set your WordPress site as your secondary website if you already have an existing website set up on this App.",
        "SECONDARY_WEBSITE": "Set as Secondary website",
        "YES": "Yes",
        "CANCEL": "Cancel",
        "APP_DESTINATION_URL": "We have detected that this App is set up for the following domain:",
        "APP_NAME_DOMAIN_SELECT": "The App %APP% with domain %DOMAIN% has been selected.",
        "APP_NAME_SELECT": "The App %APP% has been selected.",
        "UPDATE_APP": "Would you like to update this App’s settings for this WordPress site?",
        "MORE_INFORMATION": "For more information, see ",
        "MORE_INFORMATION_LINK": "Advanced Setup",
        "AUDIT": {
            "APP_NAME_DOMAIN_SELECT": "The App %APP% with domain %DOMAIN% cannot be selected because you do not have Administrator permissions. Please Cancel and select a different App or create a new App.",
            "APP_NAME_SELECT": "The App %APP% cannot be selected because you do not have Administrator permissions. Please Cancel and select a different App or create a new App."
        }
    },
    "DEFAULT_APP_NAME": "WordPress Site",
    "COMPLETED": "Completed"
}

class Gateway {
    constructor(jQuery) {
        this.$ = jQuery;
    }
    getApp() {
        return new Promise((resolve)=> {
            this.$.post('admin-ajax.php?action=get_app').done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    updateAppOrganization(app) {
        let data = {
            ...app
        };
        return new Promise((resolve)=> {
            this.$.post('admin-ajax.php?action=update_app_organization', data).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });

    }
}
class Webapp {
    constructor(jQuery, session, session_token, gateway_id) {
        this.session = session;
        this.session_token = session_token;
        this.gateway_id = gateway_id;
        this.device_id = false;
        this.page_view_id = false;
        this.$ = jQuery;
        // this.getPageViewId();
    }

    init() {
        return this.getProfile().then((profile) => {
           this.profile = profile
        });
    }

    getEmail() {
        return this.profile.email_address;
    }

    assignUrl(urls) {
        this.home_url = urls.home_url;
        this.site_url = urls.site_url;
        this.callback_url = urls.callback_url;
        this.site_name = urls.site_name;
        this.cors_url = urls.cors_url;
    }

    getProfile() {
        return new Promise((resolve)=> {
            let postdata = {
                session: this.session
            };

            this.$.post('admin-ajax.php?action=manage_get_profile', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    registerDevice() {
        return new Promise((resolve)=> {
            this.$.post('admin-ajax.php?action=manage_register_device').done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    async getDeviceId() {
        let device_id = false;
        if (localStorage) {
            device_id = localStorage.getItem('bb-wordpress-device-id');
        }
        if (!device_id) {
            device_id = await this.registerDevice();
            if (device_id) {
                device_id = device_id.device_id;
            }
            if (localStorage) {
                localStorage.setItem('bb-wordpress-device-id', device_id);
            }
        }
        return device_id;
    }

    async getPageViewId() {
        let page_view_id = false;
        if (this.page_view_id) {
            page_view_id = this.page_view_id;
        }
        if (!page_view_id) {
            page_view_id = await this.createPageView();
            if (page_view_id) {
                page_view_id = page_view_id.event_id;
            }
            this.page_view_id = page_view_id;
        }
        return page_view_id;
    }

    createPageView() {
        return new Promise(async (resolve)=> {
            let code = 'page_view';
            let device_id = await this.getDeviceId();
            let postdata = {
                device_id,
                code,
                data: {
                    title: document.title,
                    url: document.location.href
                }
            };

            this.$.post('admin-ajax.php?action=manage_create_event', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    createEvent(code, data) {
        return new Promise(async (resolve)=> {
            let device_id = await this.getDeviceId();
            let page_view_id = await this.getPageViewId();
            let postdata = {
                device_id,
                code,
                page_view_id
            };
            if (data) {
                postdata.data = JSON.stringify(data);
            }

            this.$.post('admin-ajax.php?action=manage_create_event', postdata).done((json) => {
                let response = JSON.parse(json);
                console.log(response);
                resolve(response.body);
            });
        });
    }

    createApp(name) {
        return new Promise((resolve)=> {
            let postdata = {
                session: this.session,
                name,
                gateway_id: this.gateway_id
            };

            this.$.post('admin-ajax.php?action=manage_create_app', postdata).done(async (json) => {
                // get gateway id first
                console.log(json);
                let response = JSON.parse(json);
                console.log(response);
                await this.createEvent('create_app');
                resolve(response.body);
            });
        });
    }

    getApp(app_id) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id
            };

            this.$.post('admin-ajax.php?action=manage_get_app', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    getApps() {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token
            };

            this.$.post('admin-ajax.php?action=manage_get_apps', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }


    getWebsiteDomains(app_id) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id
            };

            this.$.post('admin-ajax.php?action=manage_get_website_domains', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    updateApp(app_id, app) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id,
                app
            };

            this.$.post('admin-ajax.php?action=manage_update_app', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    updateOrganizationID(app_id, organization_id) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id,
                organization_id
            };

            this.$.post('admin-ajax.php?action=manage_update_organization_id', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    createWebsiteDomain(app_id, domain) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id,
                domain
            };

            this.$.post('admin-ajax.php?action=manage_create_website_domain', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    getAppProviders(app_id) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id
            };

            this.$.post('admin-ajax.php?action=manage_get_app_providers', postdata).done((json) => {
                // get providers, enable providerds
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    enableProvider(app_id, identity_provider_id) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id,
                identity_provider_id
            };

            this.$.post('admin-ajax.php?action=manage_enable_provider', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    setPrimaryAppWebsiteDomain(app_id, website_domain_id) {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id,
                website_domain_id
            };

            this.$.post('admin-ajax.php?action=manage_set_primary_app_website_domain', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }

    createAppSecret(app_id, name = 'wordpress-secret') {
        return new Promise((resolve)=> {
            let postdata = {
                session_token: this.session_token,
                app_id,
                name
            };

            this.$.post('admin-ajax.php?action=manage_create_app_secret', postdata).done((json) => {
                let response = JSON.parse(json);
                resolve(response.body);
            });
        });
    }
}

class WebappView {
    constructor(jQuery, domId, {failureAuth, successAuth, urls, gateway_id}) {
        this.$ = jQuery;
        this.domId = domId;
        this.urls = urls;
        this.failureAuth = failureAuth;
        this.successAuth = successAuth;
        this.gateway_id = gateway_id;
        this.init();
    }

    hasSession() {
        return this.session && this.session_token;
    }
    assign(session, session_token) {
        this.session = session;
        this.session_token = session_token;
        this.webapp = new Webapp(this.$, this.session, this.session_token, this.gateway_id);
        if (localStorage) {
            localStorage.setItem('bb-session', this.session);
            localStorage.setItem('bb-session-token', this.session_token);
        }
        if (typeof this.callback === 'function') {
            this.callback();
        }
    }

    init() {
        if (localStorage) {
            let session = localStorage.getItem('bb-session');
            let session_token = localStorage.getItem('bb-session-token');
            if (session && session_token) {
                this.assign(session, session_token);
            }
        }
    }

    start() {
        this.webapp.init().then(() => {
            this.initView();
            this.render();
        });
    }

    initView() {
        this.dom = this.$(this.domId);
        this.dom.html(`
<div class="bb-loader-view">
    <div class="bb-loader"></div>
</div>
<div class="apps-view">
    <div class="content-component">
        <div class="content-note has-apps"> ${locale.APPS.EXISTING_APP}</div>
        <div class="apps-holder has-apps">
        </div>
        <hr class="has-apps">
        <div class="button-row">
            <div class="button-holder">
                <span class="button-or has-apps">
                ${locale.APPS.OR}
                </span>
                <button class="create-app">
                    ${locale.APPS.CREATE_APP}
                </button>
            </div>
        </div>
        <div class="bb-loader-holder">
            <div class="bb-loader"></div>
        </div>
    </div>
</div>
<div class="audit-view">
    <div class="content-component">
        <div class="choices">
            <div class="choice-hint set-primiary-hint"></div>
            <div class="choice">
                <button class="cancel">
                    ${ locale.APPS.CANCEL }
                </button>
            </div>
        </div>
    </div>
</div>
<div class="assign-view">
    <div class="content-component">
        <div class="choices">
            <div class="choice-hint set-primiary-hint"></div>
            <div class="choice-hint">${locale.APPS.UPDATE_APP}</div>
            <div class="choice">
                <button class="assign">
                    ${locale.APPS.YES}
                </button>
                <button class="cancel">
                    ${ locale.APPS.CANCEL }
                </button>
                <div class="bb-loader-holder">
                    <div class="bb-loader"></div>
                </div>
            </div>
            <div class="choice-hint">${ locale.APPS.MORE_INFORMATION}<a href="https://breadbutter.io/wordpress-plugin-advanced-setup/" target="_blank">${ locale.APPS.MORE_INFORMATION_LINK}</a></div>
        </div>
    </div>
</div>
`);
        this.appsView = this.dom.find('.apps-view');
        this.appsHolder = this.dom.find('.apps-holder');
        this.primaryHint = this.dom.find('.set-primiary-hint');
        this.cancelButton = this.dom.find('button.cancel');
        this.assignButton = this.dom.find('button.assign');
        this.createButton = this.dom.find('button.create-app');
        this.auditView = this.dom.find('.audit-view');
        this.assignView = this.dom.find('.assign-view');
        this.cancelButton.on('click', () => {
           this.toggleMode('apps');
        });
        this.assignButton.on('click', () => {
           this.assignWebapp();
        });

        this.createButton.on('click', () => {
            this.createWebapp();
        });
    }

    render() {
        this.webapp.getApps().then((apps) => {
            if (apps && ! apps.error) {
                this.renderApps(apps.results);
            } else if (apps && apps.error && this.failureAuth) {
                this.failureAuth(apps.error)
            }
        });
    }

    toggleMode(type) {
        this.dom.attr('data-view', type);
    }

    toggleLoader(target) {
        target.toggleClass('loading');
    }
    showLoader(target) {
        target.addClass('loading');
    }
    hideLoader(target) {
        target.removeClass('loading');
    }

    useApp(app_id) {
        this.showLoader(this.appsView);
        this.getAppInfo(app_id).then(() => {
            this.renderApp();
        });
    }

    renderApps(apps) {
        if (apps && apps.length) {
            this.toggleMode('apps');
            this.apps = apps;
            this.appsView.attr('has-apps', 1);
            this.appsHolder.empty();
            apps.forEach((app) => {
                let appDiv = this.$('<div>');
                appDiv.addClass('apps-row')
                appDiv.append('<div class="app-name">' + app.name + '</div>');
                appDiv.append('<div class="app-id">' + app.id + '</div>');
                let appButton = this.$('<div>');
                appButton.addClass('app-button');
                appButton.text('Use this App');
                appButton.on('click', () => {
                    this.useApp(app.id);
                });
                appDiv.append(appButton);
                this.appsHolder.append(appDiv);
            });
            if (apps.length == 1) {
                this.useApp(apps[0].id);
            }
        } else {
            // this.toggleMode('apps');
            // this.apps = [];
            // this.appsView.attr('has-apps', 0);
            this.createWebapp();
            // this.appsView.attr('has-apps', 0);
        }
    }

    getAppInfo(app_id) {
        return Promise.all([
            this.getApp(app_id),
            this.getWebsiteDomain(app_id)
        ]);
    }

    renderApp() {
        let steps = 'audit';
        this.hideLoader(this.appsView);
        if (this.app.admin_users) {
            let role = false;
            let email = this.webapp.getEmail();
            for (let i = 0; !role && i < this.app.admin_users.length; i++) {
                if (email == this.app.admin_users[i].email_address) {
                    role = this.app.admin_users[i].role;
                }
            }
            switch (role) {
                case 'auditor':
                    steps = 'audit';
                    break;
                default:
                    steps = 'assign';
                    break;
            }
        }
        let text;
        switch(steps) {
            case 'audit':
                text = locale.APPS.AUDIT.APP_NAME_SELECT.replace('%APP%', this.app.name);
                if (this.primary) {
                    text = locale.APPS.AUDIT.APP_NAME_DOMAIN_SELECT.replace('%APP%', this.app.name).replace('%DOMAIN%', this.primary);
                }
                this.primaryHint.text(text);
                this.auditView[0].scrollIntoView();
                break;
            case 'assign':
                text = locale.APPS.APP_NAME_SELECT.replace('%APP%', this.app.name);
                if (this.primary) {
                    text = locale.APPS.APP_NAME_DOMAIN_SELECT.replace('%APP%', this.app.name).replace('%DOMAIN%', this.primary);
                }
                this.primaryHint.text(text);
                this.assignView[0].scrollIntoView();
                break;
        }

        this.toggleMode(steps);
    }

    filterUrls(exist, append) {
        let new_list = exist.concat(append);
        new_list = new_list.filter((x, index) => new_list.indexOf(x) == index).filter((x) => x);
        return new_list;
    }

    async completeProcess() {
        console.log(this.app_id);
        console.log(this.secret);
        this.hideLoader(this.assignView);
        this.hideLoader(this.appsView);
        await this.webapp.createEvent('wordpress-install');
        this.successAuth(this.app_id, this.secret);
    }

    assignWebapp() {
        this.showLoader(this.assignView);
        Promise.all([
            this.updateAppSettingsPrimary(),
            this.createWebsiteDomain(),
            this.createSecret()
        ]).then((response) => {
            if (response && response.length && response[1]) {
                this.setPrimaryAppWebsiteDomain(response[1].website_domain_id).then((res) => {
                   this.completeProcess();
                });
            } else {
                this.completeProcess();
            }
        });
    }

    getApp(app_id) {
        return this.webapp.getApp(app_id).then((app)=> {
            this.app = app;
            this.app_id = app.id;
            return this.app;
        })
    }

    getWebsiteDomain(app_id) {
        return this.webapp.getWebsiteDomains(app_id).then((response) => {
            this.domains = response.results;
            this.primary = false;
            for (let i = 0; i < this.domains.length; i++) {
                if (this.domains[i].is_primary) {
                    this.primary = this.domains[i].domain;
                }
            }
            return;
        });
    }

    createWebapp() {
        this.organization_id = false;
        this.showLoader(this.appsView);
        this.createNewApp().then((res) => {
            console.log(res);
            this.app_id = res.app_id
            this.organization_id = res.organization_id;
            return this.getAppInfo(this.app_id);
        }).then(()=> {
            let promises = [];
            if (this.organization_id) {
                promises = [
                    this.setupProviders(),
                    this.updateAppSettingsNew(),
                    this.createWebsiteDomain(),
                    this.createSecret()
                ];
            } else {
                promises = [
                    this.updateAppSettingsNonOrg(),
                    this.createWebsiteDomain(),
                    this.createSecret()
                ];
            }

            Promise.all(promises).then((response) => {
                this.completeProcess();
            });
        });
    }

    createWebsiteDomain() {
        return new Promise((resolve) => {
            const { hostname } = new URL(this.urls.site_url);
            let found = false;
            for (let i = 0; i < this.domains.length; i++) {
                if (this.domains[i].domain == hostname) {
                    found = this.domains[i].id;
                }
            }
            if (!found) {
                this.webapp.createWebsiteDomain(this.app_id, hostname).then((res) => {
                    resolve(res);
                });
            } else if (hostname == this.primary) {
                resolve(false);
            } else {
                resolve({
                    website_domain_id: found,
                });
            }
        });
    }

    setPrimaryAppWebsiteDomain(website_domain_id) {
        return this.webapp.setPrimaryAppWebsiteDomain(this.app_id, website_domain_id);
    }

    createSecret() {
        return new Promise((resolve) => {
            this.webapp.createAppSecret(this.app_id).then((res)=> {
                this.secret = res.secret;
                resolve();
            });
        });
    }

    createNewApp() {
        let name = this.urls.site_name || locale.DEFAULT_APP_NAME;
        return this.webapp.createApp(name);
    }

    setupProviders() {
        return this.webapp.getAppProviders(this.app_id).then((res) => {
            let promises = [];
            if (res.results) {
                let orderArray = ['facebook', 'microsoft', 'google', 'linkedin', 'apple'];
                let providersHash = {};

                for (let i = 0; i < res.results.length; i++) {
                    let item = res.results[i];
                    if (orderArray.includes(item.identity_provider) && item.sandbox) {
                        providersHash[item.identity_provider] = item.id;
                    }
                }

                for (let i = 0; i < orderArray.length; i++) {
                    let key = orderArray[i];
                    let providerId = providersHash[key];
                    if (providerId) {
                        promises.push(
                            this.webapp.enableProvider(this.app_id, providerId)
                        );
                    }
                }
            }
            return Promise.all(promises);
        });
    }

    updateAppSettingsPrimary() {
        return this.webapp.updateApp(this.app_id, {
            callback_url: this.urls.callback_url,
            enable_callback: true,
            callback_url_allowlist: this.filterUrls(this.app.callback_url_allowlist, this.urls.callback_url),
            cors_allowlist: this.filterUrls(this.app.cors_allowlist, [this.urls.home_url, this.urls.site_url, this.urls.cors_url]),
            platform: 'wordpress'
        });
    }

    updateAppSettingsNew() {
        return this.webapp.updateApp(this.app_id, {
            callback_url: this.urls.callback_url,
            enable_callback: true,
            app_id: this.app_id,
            cors_allowlist: this.filterUrls([], [this.urls.home_url, this.urls.site_url, this.urls.cors_url]),
            callback_url_allowlist: [this.urls.callback_url],
            destination_domain_allowlist: [],
            platform: 'wordpress',
            privacy_policy_url: 'https://breadbutter.io/privacy-policy/',
            magic_link_authentication_enabled: true,
            magic_link_registration_enabled: false,
        });
    }

    updateAppSettingsNonOrg() {
        return this.webapp.updateApp(this.app_id, {
            callback_url: this.urls.callback_url,
            enable_callback: true,
            app_id: this.app_id,
            cors_allowlist: this.filterUrls([], [this.urls.home_url, this.urls.site_url, this.urls.cors_url]),
            callback_url_allowlist: [this.urls.callback_url],
            destination_domain_allowlist: [],
            platform: 'wordpress',
            privacy_policy_url: 'https://breadbutter.io/privacy-policy/',
            magic_link_authentication_enabled: true,
            magic_link_registration_enabled: true,
            passwords_enabled: false
        });
    }

    updateOrganizationID(app_id, organization_id) {
        return this.webapp.updateOrganizationID(app_id, organization_id);
    }

    addCallback(callback) {
        this.callback = callback;
    }
}