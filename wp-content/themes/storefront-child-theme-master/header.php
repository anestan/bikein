<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KLX73R4');</script>
<!-- End Google Tag Manager -->

</head>

<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KLX73R4"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<header id="masthead" role="banner" style="">
	  <div class="col-full">
	    <div class="header-wrapper">
	   <!-- Logo -->
			   <div class="image-logo">
			        <?php
			        $custom_logo_id = get_theme_mod( 'custom_logo' );
			        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
			        $url = get_home_url();
			        echo "<a href='$url'><img src='$image[0]' alt='logo' /></a>";
			        ?>
			    </a>
			    </div>

					<!-- Navigation -->
					<div class="navigation-header-content">
					<!-- Menu -->
						<div class="storefront-primary-navigation">
							<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
								  <div class="menu">
								  <?php
								  wp_nav_menu(
								    array(
								      'theme_location'  => 'primary',
								      'container_class' => 'primary-navigation',
								    )
								  );
								  ?>
								</div>
							</nav><!-- #site-navigation -->
						</div>
					</div> <!-- End navigation header -->

			     <!-- Cart -->
					<div class="header-cart-wrapper">
						<a class="header-cart" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
							<div class="cart-product-amount">
								<?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
							</div>
							<i class="fas fa-shopping-cart"></i>
							<?php
								// echo do_shortcode( '[elementor-template id="10350"]' );
							?>
						</a>
					</div>
					<?php include 'inc/woocommerce/cart-preview.php';?>
	    <!-- Mobil menu -->
			    <div class="header-mobile">
						<a href="#menu"><span><i class="fas fa-bars"></i></span></a>
						  <?php
						  wp_nav_menu(
						    array(
						      'theme_location'  => 'mobile',
						      'container_id' => 'menu',
						      'menu'         => 'mobilmenu'
						    )
						  );
						  ?>
					</div>
					<script>
					var menu = new MmenuLight(
					  document.querySelector( '#menu' ),
					  'all'
					);

					var navigator = menu.navigation({
					  // selectedClass: 'Selected',
					  // slidingSubmenus: true,
					  // theme: 'dark',
					  // title: 'Menu'
					});

					var drawer = menu.offcanvas({
					  // position: 'left'
					});

					//	Open the menu.
					document.querySelector( 'a[href="#menu"]' )
					  .addEventListener( 'click', evnt => {
					    evnt.preventDefault();
					    drawer.open();
					  });

					</script>
		    </div> <!-- End Header Wrapper -->
			</div>
			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_header_container                 - 0
			 * @hooked storefront_skip_links                       - 5
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_secondary_navigation             - 30
			 * @hooked storefront_product_search                   - 40
			 * @hooked storefront_header_container_close           - 41
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_header_cart                      - 60
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			//do_action( 'storefront_header' );
			?>
	</header><!-- #masthead -->


   <!-- Navigation header -->


	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
