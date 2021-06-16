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

		<div class="col-full">
			<?php
				if ( is_active_sidebar( 'footer-bottom-widgets' ) ) : ?>
				<div id="footer-bottom-widgets" class="footer-widgets-wrapper">
				<?php dynamic_sidebar( 'footer-bottom-widgets' ); ?>
				</div>
			<?php endif; ?>
		</div>

    <div class="col-full btm_bar">
			<div class="btm_bar_textfield">
			<?php
				if ( is_active_sidebar( 'btm_bar_left' ) ) : ?>
				<div id="btm_bar_left" class="btm-bar-widget">
				<?php dynamic_sidebar( 'btm_bar_left' ); ?>
				</div>
			<?php endif; ?>
			</div>
			<div class="btm_bar_textfield">
			<?php
				if ( is_active_sidebar( 'btm_bar_right' ) ) : ?>
				<div id="btm_bar_right" class="btm-bar-widget">
				<?php dynamic_sidebar( 'btm_bar_right' ); ?>
				</div>
			<?php endif; ?>
			</div>
    </div>
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
