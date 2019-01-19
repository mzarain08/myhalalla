<?php

$extra_class = '';
$icon_type = $params['icon_type'];

$extra_class .= ' mobile-style-' . $params['style'];

if ( $icon_type == 'custom' ) {
	$extra_class .= ' woodmart-mobile-menu-custom-icon';
}

?>
<div class="woodmart-burger-icon mobile-nav-icon whb-mobile-nav-icon<?php echo esc_attr( $extra_class ); ?>">
	<?php if ( $icon_type == 'custom' ): ?>
		<span class="woodmart-custom-burger-icon"><?php echo whb_get_custom_icon( $params['custom_icon'] ); ?></span>
	<?php else: ?>
		<span class="woodmart-burger"></span>
	<?php endif; ?>
	<span class="woodmart-burger-label"><?php esc_html_e( 'Menu', 'woodmart' ); ?></span>
</div><!--END MOBILE-NAV-ICON-->