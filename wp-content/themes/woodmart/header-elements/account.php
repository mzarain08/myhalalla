<?php 
if( ! woodmart_woocommerce_installed() ) return '';

$links = woodmart_get_header_links();
$my_account_style = $params['display'];
$login_side = $params['form_display'] == 'side';
$icon_type = $params['icon_type'];
$extra_class = '';

$classes = 'item-level-0';
$classes .= ( ! empty( $link['dropdown'] ) ) ? ' menu-item-has-children' : '';
$classes .= ( $params['with_username'] ) ? ' my-account-with-username' : '';
$classes .= ( $my_account_style ) ? ' my-account-with-' . $my_account_style : '';
$classes .= ( ! is_user_logged_in() && $params['login_dropdown'] && $login_side ) ? ' login-side-opener' : '';


if ( $icon_type == 'custom' && $my_account_style == 'icon' ) {
	$extra_class .= ' woodmart-account-custom-icon';
}

if( empty( $links ) ) return '';

?>
<div class="whb-header-links woodmart-navigation woodmart-header-links<?php echo esc_attr( $extra_class ); ?>">
	<ul class="menu">
		<?php foreach ($links as $key => $link):
			$classes .= ' menu-item-'. $key;
		?>
			<li class="<?php echo esc_attr( $classes ); ?> menu-simple-dropdown item-event-hover">
				<a href="<?php echo esc_url( $link['url'] ); ?>">
					<?php 
						if ( $icon_type == 'custom' && $my_account_style == 'icon' ) {
							echo whb_get_custom_icon( $params['custom_icon'] );
						}
					?>
					<span>
						<?php echo wp_kses( $link['label'], 'default' ); ?>
					</span>
				</a>
				<?php if( ! empty( $link['dropdown'] ) ) echo $link['dropdown']; ?>
			</li>

		<?php endforeach; ?>
	</ul>		
</div>