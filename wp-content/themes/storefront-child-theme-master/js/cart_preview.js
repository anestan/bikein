jQuery(function($){
 var timeout;

    if (!$('body').hasClass('woocommerce-checkout') &&
        !$('body').hasClass('woocommerce-cart')) {

      //Show and hide cart preview on hover
      $(".header-cart, #cart-preview").hover(function(){
          clearTimeout(timeout);
          $('#cart-preview').slideDown(300);
      },function(){
        timeout = setTimeout(function () {
          $('#cart-preview').hide();
        }, 600);
      });
    }
});
