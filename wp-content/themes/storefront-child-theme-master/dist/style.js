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
/******/ 	return __webpack_require__(__webpack_require__.s = "./scss/style.scss");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./scss/style.scss":
/*!*************************!*\
  !*** ./scss/style.scss ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("throw new Error(\"Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\\nModuleBuildError: Module build failed (from ./node_modules/sass-loader/dist/cjs.js):\\nSassError: Invalid CSS after \\\"        right: 2%;\\\": expected \\\"}\\\", was \\\"<<<<<<< HEAD\\\"\\n        on line 72 of scss/header.scss\\n        from line 7 of scss/style.scss\\n>>         right: 2%;\\n\\n   ------------------^\\n\\n    at /Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/webpack/lib/NormalModule.js:316:20\\n    at /Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/loader-runner/lib/LoaderRunner.js:367:11\\n    at /Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/loader-runner/lib/LoaderRunner.js:233:18\\n    at context.callback (/Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/loader-runner/lib/LoaderRunner.js:111:13)\\n    at Object.callback (/Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/sass-loader/dist/index.js:62:7)\\n    at Object.done [as callback] (/Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/neo-async/async.js:8069:18)\\n    at options.error (/Users/itstackmini/Sites/www/webshop-test/wp-content/themes/storefront-child-theme-master/node_modules/node-sass/lib/index.js:293:32)\");\n\n//# sourceURL=webpack:///./scss/style.scss?");

/***/ })

/******/ });