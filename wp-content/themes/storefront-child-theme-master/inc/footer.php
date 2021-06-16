<?php

/* Remove footer credit */
add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'custom_storefront_credit', 20 );
}


/* Remove Storefront Mobile Menu Links */
add_filter( 'storefront_handheld_footer_bar_links', 'jk_remove_handheld_footer_links' );
function jk_remove_handheld_footer_links( $links ) {
	unset( $links['my-account'] );

	return $links;
}

//Footer widget area
function footer_bottom_widgets() {
  register_sidebar(
    array(
      'id' => 'footer-bottom-widgets',
      'name' => esc_html__( 'Footer Bottom Widgets', 'theme-domain' ),
      'description' => esc_html__( 'Widgets til nederst i footeren', 'theme-domain' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title">',
      'after_title' => '</h3></div>'
    )
  );
  register_sidebar(
    array(
      'id' => 'btm_bar_left',
      'name' => esc_html__( 'Bottom Bar: Left Widgets', 'theme-domain' ),
      'description' => esc_html__( 'Widget område til venstre i bottom bar', 'theme-domain' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title">',
      'after_title' => '</h3></div>'
    )
  );
  register_sidebar(
    array(
      'id' => 'btm_bar_right',
      'name' => esc_html__( 'Bottom Bar: Right Widgets', 'theme-domain' ),
      'description' => esc_html__( 'Widget område til højre i bottom bar', 'theme-domain' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title">',
      'after_title' => '</h3></div>'
    )
  );
}
add_action( 'widgets_init', 'footer_bottom_widgets' );
