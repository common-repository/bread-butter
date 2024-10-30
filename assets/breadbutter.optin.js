const launchOptInWidget = function() {
    let $ = jQuery;
    let widget = $('.bb-optin-widget');
    widget.removeClass('bb-hidden');
    let ID = "bb-optin-widget-" + (Date.now() + "").slice(-5);
    widget.attr('id', ID);
    BreadButter.ui.optIn(ID, );
};
launchOptInWidget();