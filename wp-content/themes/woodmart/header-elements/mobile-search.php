<?php

$extra_class = '';
$icon_type = $params['icon_type'];

if ( $icon_type == 'custom' ) {
	$extra_class .= ' woodmart-search-custom-icon';
}

?>

<div class="whb-search search-button mobile-search-icon<?php echo esc_attr( $extra_class ); ?>">
	<a href="#">
		<span class="search-button-icon">
			<?php 
				if ( $icon_type == 'custom' ) {
					echo whb_get_custom_icon( $params['custom_icon'] );
				}
			?>
		</span>
	</a>
</div>