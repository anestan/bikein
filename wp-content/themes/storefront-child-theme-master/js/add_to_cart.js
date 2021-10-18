jQuery(function($) {

    $('form.cart').on('submit', function(e) {
        e.preventDefault();

        var form   = $(this),
            mainId = form.find('.single_add_to_cart_button').val(),
            fData  = form.serializeArray();

        if ( mainId === '' ) {
            mainId = form.find('input[name="product_id"]').val();
        }

        if ( typeof wc_add_to_cart_params === 'undefined' )
            return false;

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'custom_add_to_cart' ),
            data : {
                'product_id': mainId,
                'form_data' : fData
            },
            success: function (response) {
                var cart_url = wc_add_to_cart_params.cart_url;
                $(document.body).trigger("wc_fragment_refresh");
                // Replace woocommerce add to cart notice
                $('.woocommerce-notices-wrapper').replaceWith('<div class="woocommerce-message" role="alert">Produktet blevet tilføjet til din kurv.<a class="button wc-forward" href="' + cart_url + '"> Se kurv</a></div>');
                $('input[name="quantity"]').val(1);
                console.log
                
                // Replace header cart dropdown
                $('.cart-dropdown-inner').replaceWith(response);
                $('#cart-preview').slideDown(300).delay(2000).slideUp(500);

                // Update cart total items
                var cart_total_items = $('.cart_total_items').html();
                $('.cart-product-amount').html(cart_total_items);

                form.unblock();                  
            },
            error: function (error) {
                form.unblock();
                // console.log(error);
            }
        });
    });

});