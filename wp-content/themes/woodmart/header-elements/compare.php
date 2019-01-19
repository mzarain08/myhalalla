<?php
	if ( ! woodmart_woocommerce_installed() || ! woodmart_get_opt( 'compare' ) ) return;

	$extra_class = '';
	$icon_type = $params['icon_type'];

	$extra_class .= ' whb-compare-' . $params['design'];

	if ( $params['hide_product_count'] ) {
		$extra_class .= ' without-product-count';
	}

	if ( $icon_type == 'custom' ) {
		$extra_class .= ' woodmart-compare-custom-icon';
	}
?>

<div class="woodmart-compare-info-widget<?php echo esc_attr( $extra_class ); ?>" title="<?php echo esc_attr__( 'Compare products', 'woodmart' ); ?>">
	<a href="<?php echo esc_url( woodmart_get_compare_page_url() ); ?>">
		<span class="compare-info-wrap">
			<span class="compare-icon">
				<?php 
					if ( $icon_type == 'custom' ) {
						echo whb_get_custom_icon( $params['custom_icon'] );
					}
				?>

				<?php if ( ! $params['hide_product_count'] ): ?>
					<span class="compare-count"><?php echo woodmart_get_compare_count(); ?></span>
				<?php endif; ?>
			</span>
			<span class="compare-label">
				<?php esc_html_e( 'Compare', 'woodmart' ) ?>
			</span>
		</span>
	</a>
</div>
