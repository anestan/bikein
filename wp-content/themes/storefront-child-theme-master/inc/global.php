<?php

/* Logo */
add_theme_support( 'custom-logo' );



// Remove sidebar on all Woo Pages
function iconic_remove_sidebar( $is_active_sidebar, $index ) {
    if( $index !== "sidebar-1" ) {
         return $is_active_sidebar;
     }

     if( ! is_product() ) {
        return $is_active_sidebar;
    }

     return false;
 }

add_filter( 'is_active_sidebar', 'iconic_remove_sidebar', 10, 2 );


/* Admin - WooCommerce styling */
function admin_custom_css() {
    echo '<style>
      .stock-heading {
        font-size: 1.3em;
        font-weight: 600;
        color: #23282d;
      }
    </style>';
  }

  add_action('admin_head', 'admin_custom_css');


  /*Allow SVG*/
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
   }
add_filter('upload_mimes', 'cc_mime_types');
