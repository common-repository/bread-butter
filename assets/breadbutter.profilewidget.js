const launchProfileWidget = function() {
    let $ = jQuery;
    let widget = $('.bb-profile-widget');
    if (widget.length === 0) {
        widget = $('.bb-signup-signin');
    }
    widget.removeClass('bb-hidden');
    let ID = "bb-profile-widget-" + (Date.now() + "").slice(-5);
    widget.attr('id', ID);
    BreadButter.ui.addProfileWidget(ID, {
        onLogout: bbOnLogout
    });
};
launchProfileWidget();