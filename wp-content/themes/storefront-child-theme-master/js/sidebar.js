jQuery(function($){

    var $sidebar = $('.cart-sidebar');
    
      $(".header_cart_wrapper").on('click', function(e) {
        e.preventDefault();
    
        if (!$sidebar.hasClass('cart-active')) {
          $sidebar.addClass('cart-active');
    
          $(document).one('click', function closeTooltip(e) {
              if ($sidebar.has(e.target).length === 0 && $('.header_cart_wrapper').has(e.target).length === 0) {
                  $sidebar.removeClass('cart-active');
              } else if ($sidebar.hasClass('cart-active')) {
                  $(document).one('click', closeTooltip);
              }
          });
          } else {
            $menu.removeClass('cart-active');
          }
    
      });
    });
    