// Sticky header
jQuery(function($){
	var prevScrollpos = window.pageYOffset;
	window.onscroll = function() {
	var currentScrollPos = window.pageYOffset;
	  if (prevScrollpos > currentScrollPos) {
		document.getElementById("masthead").style.top = "0";
	  } else {
		document.getElementById("masthead").style.top = "-200px";
	  }
	  prevScrollpos = currentScrollPos;
	}

});