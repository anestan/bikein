<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' );
			?>

		</div><!-- .col-full -->

		<?php
				if ( is_active_sidebar( 'footer-bottom-widgets' ) ) : ?>
				<div id="footer-bottom-widgets" class="filter-wrapper">
				<?php dynamic_sidebar( 'footer-bottom-widgets' ); ?>
				</div>
			<?php endif; ?>

    <div class="btm_bar">
      <ul>
        <li><a href="#">Cookies</a></li>
        <li>Copyright - Firmanavn</li>
        <li><a href="#">Persondatapolitik</a></li>
      </ul>
    </div>
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
