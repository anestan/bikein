/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/style.scss */ \"./scss/style.scss\");\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_style_scss__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _cvrautofill_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cvrautofill.js */ \"./js/cvrautofill.js\");\n/* harmony import */ var _cvrautofill_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_cvrautofill_js__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _mmenu_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mmenu.js */ \"./js/mmenu.js\");\n/* harmony import */ var _mmenu_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_mmenu_js__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _loadmore_data_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./loadmore_data.js */ \"./js/loadmore_data.js\");\n/* harmony import */ var _loadmore_data_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_loadmore_data_js__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _loadmore_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./loadmore.js */ \"./js/loadmore.js\");\n/* harmony import */ var _loadmore_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_loadmore_js__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _sidebar_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./sidebar.js */ \"./js/sidebar.js\");\n/* harmony import */ var _sidebar_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_sidebar_js__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _filters_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./filters.js */ \"./js/filters.js\");\n/* harmony import */ var _filters_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_filters_js__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _sticky_header_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./sticky_header.js */ \"./js/sticky_header.js\");\n/* harmony import */ var _sticky_header_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_sticky_header_js__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _stock_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./stock.js */ \"./js/stock.js\");\n/* harmony import */ var _stock_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_stock_js__WEBPACK_IMPORTED_MODULE_8__);\n\n\n\n\n\n\n\n\n\n\n\n//# sourceURL=webpack:///./js/app.js?");

/***/ }),

/***/ "./js/cvrautofill.js":
/*!***************************!*\
  !*** ./js/cvrautofill.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function($){\n\t$(document).ready(function () {\n\n        // Inserts vat verification button under vat input field\n        jQuery('.append-button').each(function(){\n            var item = jQuery(this);\n            var description = item.attr('vat-button');\n            item.parent().append('<button type=\"button\" class=\"button default\" id=\"vatButton\">'+description+'</button>');\n        });\n\n        // Change button to default color if user edits vat\n        $(\"#billing_vat\").on(\"input\", function(){\n            document.getElementById(\"vatButton\").className=\"button default\";\n        });\n\n        /*\n        * OnClick event for vat verification button\n        * Gets company information from cvrapi.dk based on user vat input and country\n        * Changes vatButton style based on GET success\n        */\n        $(\"#vatButton\").click(function(){\n            var vatButton = document.getElementById(\"vatButton\");\n\n            var vat = $('#billing_vat').val();\n            var country =$('#billing_country').val();\n\n            var buttonFailure = \"CVR nr. er ugyldigt, prøv venligst igen\";\n            var buttonSuccess = \"Oplysninger er successfuldt hentet\";\n            \n            $.getJSON('//cvrapi.dk/api?search=' + vat + \"&country=\" + country, function(data) {\n                if  (vat == \"\") {\n                    vatButton.innerHTML=buttonFailure;\n                    vatButton.className=\"button failure\";\n                }\n                else if (vat != data.vat){\n                    vatButton.innerHTML=buttonFailure;\n                    vatButton.className=\"button failure\";\n                }\n                else{\n                    vatButton.innerHTML=buttonSuccess;\n                    vatButton.className=\"button success\";\n\n                    $('#billing_address_1').val(data.address);\n                    $('#billing_company').val(data.name);\n                    $('#billing_city').val(data.city);\n                    $('#billing_postcode').val(data.zipcode);\n                    $('#billing_phone').val(data.phone);\n                }\n            });\n        });\n\t});\n})(jQuery);\n\n//# sourceURL=webpack:///./js/cvrautofill.js?");

/***/ }),

/***/ "./js/filters.js":
/*!***********************!*\
  !*** ./js/filters.js ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("//Show or hide archive filters\njQuery(function($){\n\t$(\".show_filter\").click(function() {\n\t  $(\"#secondary\").slideToggle(400);\n\t});\n});\n\n\n//# sourceURL=webpack:///./js/filters.js?");

/***/ }),

/***/ "./js/loadmore.js":
/*!************************!*\
  !*** ./js/loadmore.js ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery(function($){ // use jQuery code inside this to avoid \"$ is not defined\" error\n\t$('.loadmore_button').click(function(){\n\n\t\tvar button = $(this),\n\t\t    data = {\n\t\t\t'action': 'loadmore',\n\t\t\t'query': loadmore_params.posts,\n\t\t\t'page' : loadmore_params.current_page\n\t\t};\n\n\t\t$.ajax({ // you can also use $.post here\n\t\t\turl : loadmore_params.ajaxurl, // AJAX handler\n\t\t\tdata : data,\n\t\t\ttype : 'POST',\n\t\t\tbeforeSend : function ( xhr ) {\n\t\t\t\tbutton.text('Henter...'); // change the button text, you can also add a preloader image\n\t\t\t},\n\t\t\tsuccess : function( data ){\n\t\t\t\tif( data ) {\n\t\t\t\t\tbutton.text( 'Se flere produkter' ).prev().before(data); // insert new posts\n\t\t\t\t\tloadmore_params.current_page++;\n\n\t\t\t\t\tif ( loadmore_params.current_page == loadmore_params.max_page )\n\t\t\t\t\t\tbutton.remove(); // if last page, remove the button\n\n\t\t\t\t\t// $( document.body ).trigger( 'post-load' );\n\t\t\t\t} else {\n\t\t\t\t\tbutton.remove(); // if no data, remove the button as well\n\t\t\t\t}\n\t\t\t}\n\t\t});\n\t});\n});\n\n\n//# sourceURL=webpack:///./js/loadmore.js?");

/***/ }),

/***/ "./js/loadmore_data.js":
/*!*****************************!*\
  !*** ./js/loadmore_data.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery.noConflict($);\n/* Ajax functions */\n\njQuery(document).ready(function($) {\n    //onclick\n    $(\"#loadMore\").on('click', function(e) {\n        //init\n        var that = $(this);\n        var page = $(this).data('page');\n        var newPage = page + 1;\n        var ajaxurl = that.data('url');\n        //ajax call\n        $.ajax({\n            url: ajaxurl,\n            type: 'post',\n            data: {\n                page: page,\n                action: 'ajax_script_load_more'\n\n            },\n            error: function(response) {\n                console.log(response);\n            },\n            success: function(response) {\n                //check\n                if (response == 0) {\n                    $('#ajax-content').append('<div class=\"text-center\"><h3>You reached the end of the line!</h3><p>No more posts to load.</p></div>');\n                    $('#loadMore').hide();\n                } else {\n                    that.data('page', newPage);\n                    $('#ajax-content').append(response);\n                }\n            }\n        });\n    });\n}); \n\n//# sourceURL=webpack:///./js/loadmore_data.js?");

/***/ }),

/***/ "./js/mmenu.js":
/*!*********************!*\
  !*** ./js/mmenu.js ***!
  \*********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("!function(t){var e={};function n(o){if(e[o])return e[o].exports;var i=e[o]={i:o,l:!1,exports:{}};return t[o].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){\"undefined\"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:\"Module\"}),Object.defineProperty(t,\"__esModule\",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&\"object\"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,\"default\",{enumerable:!0,value:t}),2&e&&\"string\"!=typeof t)for(var i in t)n.d(o,i,function(e){return t[e]}.bind(null,i));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,\"a\",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p=\"\",n(n.s=0)}([function(t,e,n){\"use strict\";n.r(e);var o=function(){function t(t){var e=this;this.listener=function(t){(t.matches?e.matchFns:e.unmatchFns).forEach(function(t){t()})},this.toggler=window.matchMedia(t),this.toggler.addListener(this.listener),this.matchFns=[],this.unmatchFns=[]}return t.prototype.add=function(t,e){this.matchFns.push(t),this.unmatchFns.push(e),(this.toggler.matches?t:e)()},t}(),i=function(t){return Array.prototype.slice.call(t)},r=function(t,e){return i((e||document).querySelectorAll(t))},s=(\"ontouchstart\"in window||navigator.msMaxTouchPoints,navigator.userAgent.indexOf(\"MSIE\")>-1||navigator.appVersion.indexOf(\"Trident/\")>-1),a=\"mm-spn\",c=function(){function t(t,e,n,o,i){this.node=t,this.title=e,this.selectedClass=n,this.node.classList.add(a),s&&(o=!1),this.node.classList.add(a+\"--\"+i),this.node.classList.add(a+\"--\"+(o?\"navbar\":\"vertical\")),this._setSelectedl(),this._initAnchors()}return Object.defineProperty(t.prototype,\"prefix\",{get:function(){return a},enumerable:!0,configurable:!0}),t.prototype.openPanel=function(t){var e=t.dataset.mmSpnTitle,n=t.parentElement;n===this.node?this.node.classList.add(a+\"--main\"):(this.node.classList.remove(a+\"--main\"),e||i(n.children).forEach(function(t){t.matches(\"a, span\")&&(e=t.textContent)})),e||(e=this.title),this.node.dataset.mmSpnTitle=e,r(\".\"+a+\"--open\",this.node).forEach(function(t){t.classList.remove(a+\"--open\"),t.classList.remove(a+\"--parent\")}),t.classList.add(a+\"--open\"),t.classList.remove(a+\"--parent\");for(var o=t.parentElement.closest(\"ul\");o;)o.classList.add(a+\"--open\"),o.classList.add(a+\"--parent\"),o=o.parentElement.closest(\"ul\")},t.prototype._setSelectedl=function(){var t=r(\".\"+this.selectedClass,this.node),e=t[t.length-1],n=null;e&&(n=e.closest(\"ul\")),n||(n=this.node.querySelector(\"ul\")),this.openPanel(n)},t.prototype._initAnchors=function(){var t=this;this.node.addEventListener(\"click\",function(e){var n=!1;n=(n=(n=n||function(t){return!!t.target.matches(\"a\")&&(t.stopImmediatePropagation(),!0)}(e))||function(e){var n,o=e.target;return!!(n=o.closest(\"span\")?o.parentElement:!!o.closest(\"li\")&&o)&&(i(n.children).forEach(function(e){e.matches(\"ul\")&&t.openPanel(e)}),e.stopImmediatePropagation(),!0)}(e))||function(e){var n=e.target,o=r(\".\"+a+\"--open\",n),i=o[o.length-1];if(i){var s=i.parentElement.closest(\"ul\");if(s)return t.openPanel(s),e.stopImmediatePropagation(),!0}}(e)})},t}(),d=\"mm-ocd\",u=function(){function t(t,e){var n=this;void 0===t&&(t=null),this.wrapper=document.createElement(\"div\"),this.wrapper.classList.add(\"\"+d),this.wrapper.classList.add(d+\"--\"+e),this.content=document.createElement(\"div\"),this.content.classList.add(d+\"__content\"),this.wrapper.append(this.content),this.backdrop=document.createElement(\"div\"),this.backdrop.classList.add(d+\"__backdrop\"),this.wrapper.append(this.backdrop),document.body.append(this.wrapper),t&&this.content.append(t);var o=function(t){n.close(),t.preventDefault(),t.stopImmediatePropagation()};this.backdrop.addEventListener(\"touchstart\",o),this.backdrop.addEventListener(\"mousedown\",o)}return Object.defineProperty(t.prototype,\"prefix\",{get:function(){return d},enumerable:!0,configurable:!0}),t.prototype.open=function(){this.wrapper.classList.add(d+\"--open\"),document.body.classList.add(d+\"-opened\")},t.prototype.close=function(){this.wrapper.classList.remove(d+\"--open\"),document.body.classList.remove(d+\"-opened\")},t}(),l=function(){function t(t,e){void 0===e&&(e=\"all\"),this.menu=t,this.toggler=new o(e)}return t.prototype.navigation=function(t){var e=this;if(!this.navigator){var n=t.title,o=void 0===n?\"Menu\":n,i=t.selectedClass,r=void 0===i?\"Selected\":i,s=t.slidingSubmenus,a=void 0===s||s,d=t.theme,u=void 0===d?\"light\":d;this.navigator=new c(this.menu,o,r,a,u)}var l=this.navigator.prefix;return this.toggler.add(function(){return e.menu.classList.add(l)},function(){return e.menu.classList.remove(l)}),this.navigator},t.prototype.offcanvas=function(t){var e=this;if(!this.drawer){var n=t.position,o=void 0===n?\"left\":n;this.drawer=new u(null,o)}var i=document.createComment(\"original menu location\");return this.menu.after(i),this.toggler.add(function(){e.drawer.content.append(e.menu)},function(){e.drawer.close(),i.after(e.menu)}),this.drawer},t}();e.default=l;window.MmenuLight=l}]);\n\n//# sourceURL=webpack:///./js/mmenu.js?");

/***/ }),

/***/ "./js/sidebar.js":
/*!***********************!*\
  !*** ./js/sidebar.js ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery(function($){\n\n    var $sidebar = $('.cart-sidebar');\n    \n      $(\".header_cart_wrapper\").on('click', function(e) {\n        e.preventDefault();\n    \n        if (!$sidebar.hasClass('cart-active')) {\n          $sidebar.addClass('cart-active');\n    \n          $(document).one('click', function closeTooltip(e) {\n              if ($sidebar.has(e.target).length === 0 && $('.header_cart_wrapper').has(e.target).length === 0) {\n                  $sidebar.removeClass('cart-active');\n              } else if ($sidebar.hasClass('cart-active')) {\n                  $(document).one('click', closeTooltip);\n              }\n          });\n          } else {\n            $menu.removeClass('cart-active');\n          }\n    \n      });\n    });\n    \n\n//# sourceURL=webpack:///./js/sidebar.js?");

/***/ }),

/***/ "./js/sticky_header.js":
/*!*****************************!*\
  !*** ./js/sticky_header.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// Sticky header\njQuery(function($){\n\tvar prevScrollpos = window.pageYOffset;\n\twindow.onscroll = function() {\n\tvar currentScrollPos = window.pageYOffset;\n\t  if (prevScrollpos > currentScrollPos) {\n\t\tdocument.getElementById(\"masthead\").style.top = \"0\";\n\t  } else {\n\t\tdocument.getElementById(\"masthead\").style.top = \"-200px\";\n\t  }\n\t  prevScrollpos = currentScrollPos;\n\t}\n\n});\n\n//# sourceURL=webpack:///./js/sticky_header.js?");

/***/ }),

/***/ "./js/stock.js":
/*!*********************!*\
  !*** ./js/stock.js ***!
  \*********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// Show or hide stock\njQuery(function($){\n\t$(\"#stockButton\").click(function() {\n\t  $(\".stock_status\").toggle(\"slide\");\n\t});\n});\n\n//# sourceURL=webpack:///./js/stock.js?");

/***/ }),

/***/ "./scss/style.scss":
/*!*************************!*\
  !*** ./scss/style.scss ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./scss/style.scss?");

/***/ })

/******/ });