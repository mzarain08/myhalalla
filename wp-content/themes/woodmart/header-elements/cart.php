<?php
$extra_class = $custom_icon = $custom_icon_width = $custom_icon_height = '';
$icon_type = $params['icon_type'];
$cart_position = $params['position'];

$extra_class .= ' woodmart-cart-design-'. $params['style']; 

if ( $icon_type == 'bag' ) {
	$extra_class .= ' woodmart-cart-alt';
}

if ( $icon_type == 'custom' ) {
	$extra_class .= ' woodmart-cart-custom-icon';
}

if ( $cart_position == 'side' ) {
	$extra_class .= ' cart-widget-opener';
}

if ( ! woodmart_woocommerce_installed() || $params['style'] == 'disable' || ( ! is_user_logged_in() && woodmart_get_opt( 'login_prices' ) ) ) return; ?>

<div class="woodmart-shopping-cart<?php echo esc_attr( $extra_class ); ?>">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
		<span class="woodmart-cart-wrapper">
			<span class="woodmart-cart-icon">
				<?php 
					if ( $icon_type == 'custom' ) {
						echo whb_get_custom_icon( $params['custom_icon'] );
					}
				?>
			</span>
			<span class="woodmart-cart-totals">
				<?php woodmart_cart_count(); ?>
				<span class="subtotal-divider">/</span> 
				<?php woodmart_cart_subtotal(); ?>
			</span>
		</span>
	</a>
	<?php if ( $cart_position != 'side' && $cart_position != 'without' ): ?>
		<div class="dropdown-cart">
			 <?php 
				// Insert cart widget placeholder - code in woocommerce.js will update this on page load
				echo '<div class="widget woocommerce widget_shopping_cart"><div class="widget_shopping_cart_content"></div></div>';
			  ?> 
		</div>
	<?php endif; ?>
</div>