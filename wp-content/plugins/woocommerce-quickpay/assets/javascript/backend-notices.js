(function($, config) {
    $(function() {
        $(document.body).on('click', '.wcqp-notice .notice-dismiss', function() {
            $.post(config.flush)
        });
    });
})(jQuery, window.wcqpBackendNotices || {});