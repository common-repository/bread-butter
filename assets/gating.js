(function($) {
    $(document).ready(function ($) {
        $('.js-bb-gated-sign-in-link').click(function(event) {
            event.preventDefault();
            BreadButter.widgets.continueWith(BreadButter.config);
        });
    });
})(jQuery)