jQuery.noConflict($);
/* Ajax functions */
jQuery(document).ready(function($) {
    //onclick
    $("#loadMore").on('click', function(e) {
        //init
        var that = $(this);
        var page = $(this).data('page');
        var newPage = page + 1;
        var ajaxurl = that.data('url');
        //ajax call
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                page: page,
                action: 'ajax_script_load_more'

            },
            error: function(response) {
                console.log(response);
            },
            success: function(response) {
                //check
                if (response == 0) {
                    $('#ajax-content').append('<div class="text-center"><h3>You reached the end of the line!</h3><p>No more posts to load.</p></div>');
                    $('#loadMore').hide();
                } else {
                    that.data('page', newPage);
                    $('#ajax-content').append(response);
                }
            }
        });
    });
});
