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

/***/ "./js/add_to_cart.js":
/*!***************************!*\
  !*** ./js/add_to_cart.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery(function($) {\n\n    $('form.cart').on('submit', function(e) {\n        e.preventDefault();\n\n        var form   = $(this),\n            mainId = form.find('.single_add_to_cart_button').val(),\n            fData  = form.serializeArray();\n\n        if ( mainId === '' ) {\n            mainId = form.find('input[name=\"product_id\"]').val();\n        }\n\n        if ( typeof wc_add_to_cart_params === 'undefined' )\n            return false;\n\n        $.ajax({\n            type: 'POST',\n            url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'custom_add_to_cart' ),\n            data : {\n                'product_id': mainId,\n                'form_data' : fData\n            },\n            success: function (response) {\n                var cart_url = wc_add_to_cart_params.cart_url;\n                $(document.body).trigger(\"wc_fragment_refresh\");\n                // Replace woocommerce add to cart notice\n                $('.woocommerce-notices-wrapper').replaceWith('<div class=\"woocommerce-message\" role=\"alert\">Produktet blevet tilføjet til din kurv.<a class=\"button wc-forward\" href=\"' + cart_url + '\"> Se kurv</a></div>');\n                $('input[name=\"quantity\"]').val(1);\n                console.log\n                \n                // Replace header cart dropdown\n                $('.cart-dropdown-inner').replaceWith(response);\n                $('#cart-preview').slideDown(300).delay(2000).slideUp(500);\n\n                // Update cart total items\n                var cart_total_items = $('.cart_total_items').html();\n                $('.cart-product-amount').html(cart_total_items);\n\n                form.unblock();                  \n            },\n            error: function (error) {\n                form.unblock();\n                // console.log(error);\n            }\n        });\n    });\n\n});\n\n//# sourceURL=webpack:///./js/add_to_cart.js?");

/***/ }),

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/style.scss */ \"./scss/style.scss\");\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_style_scss__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _cvrautofill_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cvrautofill.js */ \"./js/cvrautofill.js\");\n/* harmony import */ var _cvrautofill_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_cvrautofill_js__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _mmenu_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mmenu.js */ \"./js/mmenu.js\");\n/* harmony import */ var _mmenu_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_mmenu_js__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _loadmore_data_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./loadmore_data.js */ \"./js/loadmore_data.js\");\n/* harmony import */ var _loadmore_data_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_loadmore_data_js__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _loadmore_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./loadmore.js */ \"./js/loadmore.js\");\n/* harmony import */ var _loadmore_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_loadmore_js__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _cart_preview_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./cart_preview.js */ \"./js/cart_preview.js\");\n/* harmony import */ var _cart_preview_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_cart_preview_js__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _filters_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./filters.js */ \"./js/filters.js\");\n/* harmony import */ var _filters_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_filters_js__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _sticky_header_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./sticky_header.js */ \"./js/sticky_header.js\");\n/* harmony import */ var _sticky_header_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_sticky_header_js__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _stock_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./stock.js */ \"./js/stock.js\");\n/* harmony import */ var _stock_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_stock_js__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _variation_select_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./variation_select.js */ \"./js/variation_select.js\");\n/* harmony import */ var _variation_select_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_variation_select_js__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _progress_bar_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./progress_bar.js */ \"./js/progress_bar.js\");\n/* harmony import */ var _progress_bar_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_progress_bar_js__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _cart_empty_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./cart_empty.js */ \"./js/cart_empty.js\");\n/* harmony import */ var _cart_empty_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_cart_empty_js__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _add_to_cart_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./add_to_cart.js */ \"./js/add_to_cart.js\");\n/* harmony import */ var _add_to_cart_js__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_add_to_cart_js__WEBPACK_IMPORTED_MODULE_12__);\n/* harmony import */ var _lightwidget_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./lightwidget.js */ \"./js/lightwidget.js\");\n/* harmony import */ var _lightwidget_js__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_lightwidget_js__WEBPACK_IMPORTED_MODULE_13__);\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n//# sourceURL=webpack:///./js/app.js?");

/***/ }),

/***/ "./js/cart_empty.js":
/*!**************************!*\
  !*** ./js/cart_empty.js ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// Hide cart form on Cart page\njQuery(function($){\n\n\tjQuery( document.body ).on( 'wc_cart_emptied', function(){\n\t\t$(\".woocommerce-cart .woocommerce-cart-form\").hide();\n\t});\n\n});\n\n//# sourceURL=webpack:///./js/cart_empty.js?");

/***/ }),

/***/ "./js/cart_preview.js":
/*!****************************!*\
  !*** ./js/cart_preview.js ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery(function($){\n var timeout;\n\n    if (!$('body').hasClass('woocommerce-checkout') &&\n        !$('body').hasClass('woocommerce-cart')) {\n\n      //Show and hide cart preview on hover\n      $(\".header-cart, #cart-preview\").hover(function(){\n          clearTimeout(timeout);\n          $('#cart-preview').slideDown(300);\n      },function(){\n        timeout = setTimeout(function () {\n          $('#cart-preview').hide();\n        }, 600);\n      });\n    }\n});\n\n\n//# sourceURL=webpack:///./js/cart_preview.js?");

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

eval("//Show or hide archive filters\njQuery(function($){\n\t$(\".show_filter\").click(function() {\n\t  $(\"#archive-top-filters\").slideToggle(300);\n\t\t$(\"#archive-top-filters\").css(\"display\",\"flex\");\n\t});\n\n\t$(\".show_cat\").click(function() {\n\t  $(\".widget_product_categories\").slideToggle(300);\n\t});\n});\n\n\n//# sourceURL=webpack:///./js/filters.js?");

/***/ }),

/***/ "./js/lightwidget.js":
/*!***************************!*\
  !*** ./js/lightwidget.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("!function(e,t){\"use strict\";Object.prototype.hasOwnProperty.call(e,\"lightwidget\")||(e.addEventListener(\"message\",function(e){if(-1===[\"lightwidget.com\",\"dev.lightwidget.com\",\"cdn.lightwidget.com\",\"instansive.com\"].indexOf(e.origin.replace(/^https?:\\/\\//i,\"\")))return!1;var i=function(e){if(-1<e.indexOf(\"{\"))return JSON.parse(e);e=e.split(\":\");return{widgetId:e[2].replace(\"instansive_\",\"\").replace(\"lightwidget_\",\"\"),size:e[1]}}(e.data);if(i.size<=0)return!1;[].forEach.call(t.querySelectorAll('iframe[src*=\"lightwidget.com/widgets/'+i.widgetId+'\"],iframe[data-src*=\"lightwidget.com/widgets/'+i.widgetId+'\"],iframe[src*=\"instansive.com/widgets/'+i.widgetId+'\"]'),function(e){e.style.height=i.size+\"px\"})},!1),e.lightwidget={})}(window,document);\n\n\n//# sourceURL=webpack:///./js/lightwidget.js?");

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

/***/ "./js/progress_bar.js":
/*!****************************!*\
  !*** ./js/progress_bar.js ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// Update progress bar on product archive\njQuery(function($){\n\t\n\t$(\".loadmore_button\").on('click', function(e) {\n\n\t    var button = $(this),\n\t\tdata = {\n\t\t'action': 'loadmore',\n\t\t'query': loadmore_params.posts\n\t\t};\n\n\t\t$.ajax({\n\t\t\turl : loadmore_params.ajaxurl, // AJAX handler\n\t\t\tdata : data,\n\t\t\ttype : 'POST',\n\t\t\tsuccess : function( data ){\n\t\t\t\tif( data ) {\n\t\t\t\n\t\t\t\t\t// replace value with post count + post count\n\t\t\t\t\tvar post_count = loadmore_params.post_count;\n\t\t\t\t\t$('#progress-bar').val(post_count + post_count);\n\n\t\t\t\t\tvar post_found = loadmore_params.post_found;\n\t\t\t\t\tvar post_count_dobble = post_count * 2;\n\n\t\t\t\t\tif ( post_count_dobble > post_found ) {\n\t\t\t\t\t\t$('.woocommerce-result-count ').replaceWith('<p class=\"woocommerce-result-count\">Viser ' + (post_count_dobble - 1 )  + ' af ' + post_found + ' resultater</p>' );\n\t\t\t\t\t}\n\t\t\t\t\telse {\n\t\t\t\t\t\t$('.woocommerce-result-count ').replaceWith('<p class=\"woocommerce-result-count\">Viser ' + post_count_dobble + ' af ' + post_found + ' resultater</p>' );\n\t\t\t\t\t}\n\n\t\t\t\t}\n\t\t\t}\n\t\t});\n       \n\n\t});\n\n});\n\n//# sourceURL=webpack:///./js/progress_bar.js?");

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

/***/ "./js/variation_select.js":
/*!********************************!*\
  !*** ./js/variation_select.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/* Select variation buttons on product page */\n\njQuery(function($){\n\n  // clones select options for each product attribute\n  var clone = $(\".single-product div.product table.variations select\").clone(true,true);\n\n  // adds a \"data-parent-id\" attribute to each select option\n  $(\".single-product div.product table.variations select option\").each(function(){\n      $(this).attr('data-parent-id',$(this).parent().attr('id'));\n  });\n\n  // converts select options to div\n  $(\".single-product div.product table.variations select option\").unwrap().each(function(){\n      if ( $(this).val() == '' ) {\n          $(this).remove();\n          return true;\n      }\n      var option = $('<div class=\"custom_option is-visible\" data-parent-id=\"'+$(this).data('parent-id')+'\" data-value=\"'+$(this).val()+'\">'+$(this).text()+'</div>');\n      $(this).replaceWith(option);\n  });\n  \n  // reinsert the clone of the select options of the attributes in the page that were removed by \"unwrap()\"\n  $(clone).insertBefore('.single-product div.product table.variations .reset_variations').hide();\n\n  // when a user clicks on a div it adds the \"selected\" attribute to the respective select option\n  $(document).on('click', '.custom_option', function(){\n      var parentID = $(this).data('parent-id');\n      if ( $(this).hasClass('on') ) {\n          $(this).removeClass('on');\n          $(\".single-product div.product table.variations select#\"+parentID).val('').trigger(\"change\");\n      } else {\n          $('.custom_option[data-parent-id='+parentID+']').removeClass('on');\n          $(this).addClass('on');\n          $(\".single-product div.product table.variations select#\"+parentID).val($(this).data(\"value\")).trigger(\"change\");\n      }\n      \n  });\n\n  // if a select option is already selected, it adds the \"on\" attribute to the respective div\n  $(\".single-product div.product table.variations select\").each(function(){\n      if ( $(this).find(\"option:selected\").val() ) {\n          var id = $(this).attr('id');\n          $('.custom_option[data-parent-id='+id+']').removeClass('on');\n          var value = $(this).find(\"option:selected\").val();\n          $('.custom_option[data-parent-id='+id+'][data-value='+value+']').addClass('on');\n      }\n  });\n\n  // when the select options change based on the ones selected, it shows or hides the respective divs\n  $('body').on('check_variations', function(){\n      $('div.custom_option').removeClass('is-visible');\n      $('.single-product div.product table.variations select').each(function(){\n          var attrID = $(this).attr(\"id\");\n          $(this).find('option').each(function(){\n              if ( $(this).val() == '' ) {\n                  return;\n              }\n              $('div[data-parent-id=\"'+attrID+'\"][data-value=\"'+$(this).val()+'\"]').addClass('is-visible');\n          });\n      });\n  });\n\n});\n\n\n//# sourceURL=webpack:///./js/variation_select.js?");

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