<?php
$extra_class = '';
$menu_style = ( $params['menu_style'] ) ? $params['menu_style'] : 'default';
$location = 'main-menu';
$classes = 'menu-' . $params['menu_align'];
$icon_type = $params['icon_type'];

$classes .= ' navigation-style-' . $menu_style;

if ( $icon_type == 'custom' ) {
	$extra_class .= ' woodmart-full-screen-custom-icon';
}

if( $params['full_screen'] ) {
	?>
		<div class="woodmart-burger-icon full-screen-burger-icon<?php echo esc_attr( $extra_class ); ?>">
			<?php if ( $icon_type == 'custom' ): ?>
				<span class="woodmart-custom-burger-icon"><?php echo whb_get_custom_icon( $params['custom_icon'] ); ?></span>
			<?php else: ?>
				<span class="woodmart-burger"></span>
			<?php endif; ?>
			<span class="woodmart-burger-label"><?php esc_html_e( 'Menu', 'woodmart' ); ?></span>
		</div>
	<?php
	return;
}
?>
<div class="whb-navigation whb-primary-menu main-nav site-navigation woodmart-navigation <?php echo esc_attr( $classes ); ?>" role="navigation">
	<?php
		if( has_nav_menu( $location ) ) {
			wp_nav_menu(
				array(
					'theme_location' => $location,
					'menu_class' => 'menu',
					'walker' => new WOODMART_Mega_Menu_Walker()
				)
			);
		} else {
			$menu_link = get_admin_url( null, 'nav-menus.php' );
			?>
				<span class="create-nav-msg">
				<?php
					printf(
						wp_kses( __('Create your first <a href="%s"><strong>navigation menu here</strong></a> and add it to the "Main menu" location.', 'woodmart')
							, array(
								'a' => array(
									'href' => array()
								)
							)
						)
					, $menu_link);
				?>
				</span>
			<?php
		}
	 ?>
</div><!--END MAIN-NAV-->
