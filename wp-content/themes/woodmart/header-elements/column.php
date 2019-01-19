<?php
	if( isset( $desktop_only ) ) $class .= ' whb-visible-lg';
	if( isset( $mobile_only ) ) $class .= ' whb-hidden-lg';
	if( ! $children ) $class .= ' whb-empty-column';
 ?>
<div class="whb-column <?php echo esc_attr( $class ); ?>">
	<?php echo $children; ?>
</div>
