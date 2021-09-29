jQuery(function($){
  var timeout;
  //Show and hide cart preview on hover
  $(".header-cart, #cart-preview").hover(function(){
      clearTimeout(timeout);
      $('#cart-preview').slideDown(300);
  },function(){
    timeout = setTimeout(function () {
      $('#cart-preview').hide();
    }, 600);
  });

    $( document.body ).on( 'added_to_cart', function(){
      $('#cart-preview').slideDown(300).delay(2000).slideUp(300);
  });
});
