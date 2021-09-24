jQuery(function($){
  var timeout;
  //Show and hide cart preview on hover
  $(".header-cart, #cart-preview").hover(function(){
      clearTimeout(timeout);
      $('#cart-preview').show();
  },function(){
    timeout = setTimeout(function () {
      $('#cart-preview').hide();
    }, 600);
  });

});
