<?php
	if ( ! woodmart_woocommerce_installed() || ! class_exists( 'YITH_WCWL' ) ) return;

	$extra_class = '';
	$icon_type = $params['icon_type'];

	$extra_class .= ' whb-wishlist-' . $params['design'];

	if ( $params['hide_product_count'] ) {
		$extra_class .= ' without-product-count';
	}

	if ( $icon_type == 'custom' ) {
		$extra_class .= ' woodmart-wishlist-custom-icon';
	}
?>

<div class="woodmart-wishlist-info-widget<?php echo esc_attr( $extra_class ); ?>" title="<?php echo esc_attr__( 'My Wishlist', 'woodmart' ); ?>">
	<a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>">
		<span class="wishlist-info-wrap">
			<span class="wishlist-icon">
				<?php 
					if ( $icon_type == 'custom' ) {
						echo whb_get_custom_icon( $params['custom_icon'] );
					}
				?>

				<?php if ( ! $params['hide_product_count'] ): ?>
					<span class="wishlist-count">
						<?php echo YITH_WCWL()->count_products(); ?>
					</span>
				<?php endif; ?>
			</span>
			<span class="wishlist-label">
				<?php esc_html_e( 'Wishlist', 'woodmart' ) ?>
			</span>
		</span>
	</a>
</div>
