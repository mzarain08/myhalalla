<?php
/**
 * Single Product Images
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$is_quick_view = woodmart_loop_prop( 'is_quick_view' );

$attachment_ids = $product->get_gallery_image_ids();

$attachment_count = count( $attachment_ids );

?>
<div class="images">
	

	<div class="woocommerce-product-gallery__wrapper">
		<?php
			$attributes = array(
				'title' => esc_attr( get_the_title( get_post_thumbnail_id() ) )
			);

			if ( has_post_thumbnail() ) {

				echo '<figure class="woocommerce-product-gallery__image">' . get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), $attributes ) . '</figure>';


				if ( $attachment_count > 0 ) {
					foreach ( $attachment_ids as $attachment_id ) {
						echo '<figure class="woocommerce-product-gallery__image">' . wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) ) . '</figure>';
					}
				}

			} else {

				echo '<figure class="woocommerce-product-gallery__image--placeholder">' . apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woodmart' ) ), $post->ID ) . '</figure>';

			}

		?>
	</div>
<?php 

	if ( $attachment_count > 0 ) {
		woodmart_add_inline_script('woodmart-theme', '
			jQuery(".product-quick-view .woocommerce-product-gallery__wrapper").addClass("owl-carousel").owlCarousel({
	            rtl: jQuery("body").hasClass("rtl"),
	            items: 1, 
				dots:false,
				nav: true,
				navText: false
			});
		', 'after');
	}

 ?>
</div>
