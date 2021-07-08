=== Shipmondo for WooCommerce ===
Contributors: Shipmondo
Plugin URI: https://shipmondo.com
Tags: Shipmondo, shipping, GLS, PostNord, Bring, DAO365, Pakkeshop, fragt, woocommerce, pakkelabels, fragtmodul
Requires at least: 4.5.2
Tested up to: 5.7
Stable tag: 4.0.9
License: Shipmondo
License URI: https://shipmondo.com

Shipmondo for WooCommerce - manage shipping easy and cost efficient.

== Description ==

Shipmondo is a complete freight solution that will help you in achieving a better shopping experience for your customers. You’ll be more efficient and save money on your shipping costs at the same time.

This plugin for WooCommerce will make it possible for you to offer different shipping options to your customers, for both Bring, DAO365, GLS and PostNord. An additional possibility for your customers will also be to select a specific pick­up point. A list of the closest pick­up points will be presented along with the option of seeing them marked on Google maps, based on your customer's zip code. This will make the whole ordering process both easy and transparent for your customer.

Your customer’s selected option will be saved on his/her order. By setting up a free integration with Shipmondo, you’ll have a complete and automated flow for expediting your order.

= Features =

* Multiple carriers in one plugin
* Pickup points in multiple countries: Denmark, Norway, Sweden, Finland, Netherlands,Germany, Belgium and Luxembourg
* A list of nearest pickup points
* A Google Maps overview of nearest pickup points
* Shipping details saved on order
* Combine with Shipmondo’s free integration for the complete setup
* Support multi-sites store
* Support free shipping by using Coupons
* Possibility for offering a “Free shipping for orders over X kr.” function
* Possibility to set the shipping price based on the cart’s total weight / price / number of items


= Supported carriers =

*   Bring
*   DAO365
*   GLS
*   PostNord


== Installation ==

Getting started with Shipmondo and setting up the freight module.

You just need to create a free account at [https://shipmondo.com](https://shipmondo.com), in order to be able to use Shipmondo’s services.

(Danish installation guide: [https://help.shipmondo.com/en/articles/2032087-woocommerce-shipping-module-setup](https://help.shipmondo.com/en/articles/2032087-woocommerce-shipping-module-setup))


*   Install and activate this plugin.
*   To set up your Shipmondo-account, go to settings and then API, where you can generate your freight module key.
*   Go to [https://developers.google.com/maps/documentation/javascript/get-api-key](https://developers.google.com/maps/documentation/javascript/get-api-key) and get a free Google Maps API key.
*   Go back to your WordPress-admin, then WooCommerce and then finally to Shipmondo settings and then insert your freight module key and Google Maps API key from the step 2) and 3).
*   To set up shipping zones and methods, go to WooCommerce Settings.
        a. For WooCommerce 2.5.x > go to Settings and set up your Shipping Methods there.
        b. For WooCommerce 2.6.x > go to Settings, then Shipping and set up Shipping Zones and Shipping Methods according to your needs there.
*   Your customer's choice of shipping method and pickup point will be saved on the order under Shipping Details.


Remember to set up the integration with Shipmondo, so you can create shipping labels with all the information from each order.↵
Follow this step by step guide to set up your integration here [https://help.shipmondo.com/en/articles/2027780-woocommerce-webshop-integration-setup](https://help.shipmondo.com/en/articles/2027780-woocommerce-webshop-integration-setup)


Note! Requires at least WooCommerce 3.0.0.


== Screenshots ==

1. Costumer view of plugin
2. Plugin settings
3. Shipping settings
4. Weight based shipping price
5. Example Customer view of shipping methods

== Changelog ==

= 4.0.9 =
* Removed shop ID from pickup point selection in checkout

= 4.0.8 =
* Updated version due to translation issues

= 4.0.6 =
* Updated strings and links

= 4.0.5 =
* Solved problem with WooCommerce subscriptions where shipping packages and chosen pickup points where incorrectly missing in some cases

= 4.0.4 =
* Solved problem with migration of "Other" shipping methods from v. 3.x.x to 4.x.x
* Solved problem with error message in some cases where coupon code array is not set as an array

= 4.0.3 =
* Solved problem with price intervals on saving
* Added validation of Shipmondo API Key
* Added support for content dir placed outside the public folder
* Removed depricated jQuery function, which caused errors with jQuery 3.0.0+

= 4.0.2 =
* Solved problem with coupons not apllyable

= 4.0.1 =
* Solved problem with transient not set correctly

= 4.0.0 =
* Merged shipping methods into one method with carrier selection
* Added dynamically update of carriers from Shipmondo API
* Changed some descriptions and links to reflect Shipmondo's new setup

= 3.2.1 =
* Solved problem, which caused Google Maps to not be displayed

= 3.2.0 =
* Solved problem with WooCoomerce subscriptions, syncronized subscriptions and trial subscriptions
* Solved php 7.4 depricated function notices
* Solved WooCommerce depricated cart tax notice
* Updated tested up to tags for WooCommerce and WordPress

= 3.1.2 =
* Solved problem with company name was not required, when choosing a business shipping method

= 3.1.1 =
* Solved problem with subscriptions ordered together with other products or with af trial period

= 3.1.0 =
* Added support for WooCommerce Subscriptions
* Allow more than one of same shipping type
* Added version information in API call
* Changed how it enqueues scripts and styles so it will work with alternative WP installation methods

= 3.0.5 =
* Solved problem with pickup point and name on recipient if choosing alternative delivery address

= 3.0.4 =
* Solved currency conflict with WPML/WCML
* Solved problem with mathematical expressions in shipping prices (quantity, cost and fee shortcodes)

= 3.0.3 =
* Solved problem with pickup point drop down

= 3.0.2 =
* Solved problem with text domain caused by name change - related to missing translations from WP Plugin Repository

= 3.0.1 =
* Updated some translations

= 3.0.0 =
* Changed name from Pakkelabels.dk to Shipmondo (functions and settings names included)
* Added DB migration functionality because of the settings name change
* Removed creation of non used table for differentiated prices

= 2.2.0 =
* Added support for DIBS Eeasy for WooCommerce
* Added support for Klarna Checkout for WooCommerce
* Added saving pickup point choice in WooCommerce Session
* Added support for WooCommerce Shipping Classes
* Added custom shipping agent
* Fixed Google Translate bug
* Fixed problem with Google Maps styling

= 2.1.1 =
* Fixed problem with missing service-point ID when using dropdown instead of modal

= 2.1.0 =
* Added option to change pickup point selector to dropdown instead of modal
* Fixed CSS problem with the theme Flatsome
* Fixed error in JS if zipcode field not exists

= 2.0.11 =
* Fixed JS syntax errors
* Changed text on Pakkelabels settings page

= 2.0.10 =
* Fixed problem with modal loaded on payment page
* Fixed problem with passing country code to Pakkelabels API, if buyer is only allowed from one country

= 2.0.9 =
* Fixed problem with accepting terms after modal was open on IOS

= 2.0.8 =
* Fixed problem with some payment gateways on order-pay

= 2.0.7 =
* Fixed some problems with tagged version in the plugin repository

= 2.0.6 =
* Fixed problem with getting shipping billing country if zipcode is not added

= 2.0.5 =
* Fixed problem with finding pickup points, when only one shipping method is activated

= 2.0.4 =
* Fixed problem with array conversion in some PHP versions

= 2.0.3 =
* Fixed problem with demands pickup point on virtual product after deleting non virtual product from cart
* Fixed problem with using one shipping agent pickup point choise when choosing another pickup point

= 2.0.2 =
* Fixed problem with modal included on order confirmation page

= 2.0.1 =
* Removed javaScript from order confirmation page
* Fixed problem with chosen pickup point when WooCommerce reloads delivery options
* Added version number on javascript and css files

= 2.0.0 =
* New design and functionality for the pickup point picker
* Rewrote javaScript
* Rewrote a large part of the core functionality
* Fixed issue with zipcode validation for non danish zipcodes

= 1.1.11 =
* Fixed issue with WPML and free shipping when multi currency is disabled

= 1.1.10 =
* Changed text domain for translation
* Fixed issue with pickup point selector not displaying correct when free delivery

= 1.1.9 =
* Fixed issue with pickup point selector not displaying correct

= 1.1.8 =
* Fixed issue with hiding shipping methode when differentiated shipping price and free shipping was combined
* Added simple support for WPML multi currency, so free shipping is calculated based on the WPML currency converter
* Added .pot file for translation

= 1.1.7 =
* Fixed issue with coupon codes in WooCommerce version less than 3.0.0
* Fixed issue with loading of plugin if WooCommerce is installed as MU Plugin

= 1.1.6 =
* Fixed an issue where customers could complete checkout without selecting a pick-up point for Bring

= 1.1.5 =
* Added support for pickup points in multiple countries
* Added support for free shipping when using coupons
* Added option to hide shipping method, if conditions are not met
* Added "(free)" text on shipping method description, if shipping method is free
* Added support for multi-sites/networks
* Optimized support for multi-language sites using sub-domains to differentiate site languages
* Default zipcode for pickup points is set to the customer zipcode from billing / shipping fields

= 1.1.4 =
* Tested compatible with WooCommerce 3.0
* Updated pakkelabels.dk logo and graphic

= 1.1.3 =
* Minor structure fix

= 1.1.2 =
* Added Bring
* Updated danish translations
* Minor speed optimazation


= 1.1.1 =
* Testet compatible with WooCommerce 2.7-beta 1
* Updated danish translations
* Different speed optimization
* Different code optimization

= 1.1.0 =
* Testet compatible with WordPress 4.7 and WooCommerce 2.6.9


= 1.0.8 =
* Minor javascript optimization

= 1.0.7 =
* Various optimizations

= 1.0.61 =
* Fixed an issue that might have coursed the plugin not to work with older versions of PHP

= 1.0.6 =
* Added support for diffrentiated shipping prices based on total cart weight / Price
* Added support for shipping prices based on Quantity of items in cart

= 1.0.5 =
* Fixed a Javascript bug, resulting in the map not showing up correctly

= 1.0.4 =
* Fixed a issue with the zipcode field not getting rendered if the checkout and cart was combined to a single page
* Fixed a issue with to many ; in the legacy main class
* Fixed a couple of missing translations

= 1.0.3 =
* Fixed a issues with the Avada theme
* Fixed a issues with conflicting Javascript

= 1.0.2 =
* Added a field for a Google Mapi API key in the plugin options - and is a requirement to use the plugin from now on!

= 1.0.1 =
* Added support for free shipping
* Added support for prices with both periods and commas
* Added support for tax status

= 1.0.0 =
* First release.

== Upgrade Notice ==

= 4.0.0 =
This is a major update - Changed how setup of shipping methods is working -> Migration of shipping methods will be manipulating DB data - Be sure to backup your website before updating and test the plugin after update!

= 3.1.0 =
Changed how data is captured from the customer - Be sure to test your site after updating the plugin!

= 3.0.0 =
Namechange from Pakkelabels.dk to Shipmondo - This is a major update manipulating DB data - Be sure to backup your website before updating and test the plugin before publishing!

= 2.0.0 =
This is a major update - Be sure to backup your website before updating and test the plugin before publishing!