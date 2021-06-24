<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>

		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
						<td class="value">
							<?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
									)
								);
								echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>






<!-- 
	    <select onChange="variationSelected(event)">
		<option value="">Vælg størrelse</option>
		<?php //foreach ($available_variations as $i => $v) : ?>
		<option value="<?php //echo $v['variation_id'] ?>"><?php //echo implode(' / ', $v['attributes']) ?></option>
		<?php //endforeach ?>
		</select>
 -->


<?php 
/*
foreach( $product->get_variation_attributes() as $taxonomy => $terms_slug ){
    // To get the attribute label (in WooCommerce 3+)
    $taxonomy_label = wc_attribute_label( $taxonomy, $product );

    // Setting some data in an array
    $variations_attributes_and_values[$taxonomy] = array('label' => $taxonomy_label);

    foreach($terms_slug as $term){

        // Getting the term object from the slug
        $term_obj  = get_term_by('slug', $term, $taxonomy);

        $term_id   = $term_obj->term_id; // The ID  <==  <==  <==  <==  <==  <==  HERE
        $term_name = $term_obj->name; // The Name
        $term_slug = $term_obj->slug; // The Slug
        // $term_description = $term_obj->description; // The Description

        // Setting the terms ID and values in the array
        $variations_attributes_and_values[$taxonomy]['terms'][$term_id] = array(
            'name'        => $term_name,
            'slug'        => $term_slug
        );
    }
}

	//echo '<pre>'; print_r($variations_attributes_and_values);echo '</pre>';
/* 	
	foreach ($available_variations as $a => $b) {
		$var_id = $b['variation_id'];
		echo $var_id;
	}


	
 	foreach ( $variations_attributes_and_values as $i => $v ) {

		//echo '<pre>'; print_r($v);echo '</pre>';
		echo "<p>" . $v['label'] . "</p>";
		//echo '<pre>'; print_r($v['terms']);echo '</pre>';

		echo "<select class='variation-selection' onChange='variationSelected(event)'>";

				
		foreach ($v['terms'] as $j => $x) {
			$variation_name = $x['name'];

			echo "<option value='$var_id'>" .  $variation_name . "</option>";
			//echo '<pre>'; print_r($j);echo '</pre>';

		}

		echo "</select>";

	}  */

	
	 
?>


<?php
/* 
function wc_dropdown_variation_attribute_options( $args = array() ) { 
	$args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array( 
		'options' => false,  
		'attribute' => false,  
		'product' => false,  
		'selected' => false,  
		'name' => '',  
		'id' => '',  
		'class' => '',  
		'show_option_none' => __( 'Choose an option', 'woocommerce' ),  
 ) ); 
 
	$options = $args['options']; 
	$product = $args['product']; 
	$attribute = $args['attribute']; 
	$name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute ); 
	$id = $args['id'] ? $args['id'] : sanitize_title( $attribute ); 
	$class = $args['class']; 
	$show_option_none = $args['show_option_none'] ? true : false; 
	$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options. 
 
	if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) { 
		$attributes = $product->get_variation_attributes(); 
		$options = $attributes[ $attribute ]; 
	} 
 
	$html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">'; 
	$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>'; 
 
	if ( ! empty( $options ) ) { 
		if ( $product && taxonomy_exists( $attribute ) ) { 
			// Get terms if this is a taxonomy - ordered. We need the names too. 
			$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
 
			foreach ( $terms as $term ) { 
				if ( in_array( $term->slug, $options ) ) { 
					$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>'; 
				} 
			} 
		} else { 
			foreach ( $options as $option ) { 
				// This handles < 2.4.0 bw compatibility where text attributes were not sanitized. 
				$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false ); 
				$html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>'; 
			} 
		} 
	} 
 
	$html .= '</select>'; 
 
	echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args ); 
} 
*/




?>





		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
